<?php

namespace App\Services\Click;

use App\Models\Orders\Order;
use App\Models\Orders\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ClickBillingService
{
    const ERROR_SIGN_CHECK_FAILED = -1;
    const ERROR_INVALID_AMOUNT = -2;
    const ERROR_ACTION_NOT_FOUND = -3;
    const ERROR_ALREADY_PAID = -4;
    const ERROR_USER_NOT_FOUND = -5;
    const ERROR_TRANSACTION_NOT_FOUND = -6;
    const ERROR_FAILED_UPDATE_USER = -7;
    const ERROR_IN_REQUEST_FROM_CLICK = -8;
    const ERROR_TRANSACTION_CANCELLED = -9;

    protected static $error_notes = [
        self::ERROR_SIGN_CHECK_FAILED => 'SIGN CHECK FAILED!',
        self::ERROR_INVALID_AMOUNT => 'Incorrect parameter amount',
        self::ERROR_ACTION_NOT_FOUND => 'Action not found',
        self::ERROR_ALREADY_PAID => 'Already paid',
        self::ERROR_USER_NOT_FOUND => 'User does not exist',
        self::ERROR_TRANSACTION_NOT_FOUND => 'Transaction does not exist',
        self::ERROR_FAILED_UPDATE_USER => 'Failed to update user',
        self::ERROR_IN_REQUEST_FROM_CLICK => 'Error in request from click',
        self::ERROR_TRANSACTION_CANCELLED => 'Transaction cancelled'
    ];

    public static function prepare(Request $request)
    {
        $result = self::validateRequest($request);
        if ($result['error'] === 0) {
            $order = Order::byUniqueId($request->merchant_trans_id)->first();

            $transaction = new Transaction;
            $transaction->amount = (int) ($request->amount * 100);

            $transaction->payment_method = 'click';
            $transaction->click_time = now();
            $transaction->click_trans_id = $request->click_trans_id;
            $transaction->click_status = Transaction::STATUS_CREATED;
            $transaction->order()->associate($order);
            $confirm = $transaction->save();

            if ($confirm) {
                $result = array_merge($result, [
                    'click_trans_id' => $request->click_trans_id,
                    'merchant_trans_id' => $request->merchant_trans_id,
                    'merchant_prepare_id' => (string) $transaction->id
                ]);
            } else {
                $result = self::getErrorArray(self::ERROR_FAILED_UPDATE_USER);
            }
        }
        return $result;
    }

    public static function complete(Request $request)
    {
        $transaction = Transaction::find($request->merchant_prepare_id);
        $result = self::validateRequest($request);
        if ($request->error < 0 && !in_array($result['error'], [-4, -9])) {
            $transaction->cancelClick();
            $transaction->order->makeAvailable();
            $result = self::getErrorArray(self::ERROR_TRANSACTION_CANCELLED);
        } elseif ($result['error'] === 0) {

            $transaction->paid = true;
            $confirm = $transaction->performClick();

            $transaction->order->acceptPayment($transaction);

            if ($confirm) {
                $result = array_merge($result, [
                    'click_trans_id' => $request->click_trans_id,
                    'merchant_trans_id' => $request->merchant_trans_id,
                    'merchant_confirm_id' => (string) $transaction->id
                ]);
            } else {
                $result = self::getErrorArray(self::ERROR_FAILED_UPDATE_USER);
            }
        }
        return $result;
    }

    protected static function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'click_trans_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'click_paydoc_id' => 'required|numeric',
            'merchant_trans_id' => 'required|string',
            'amount' => 'required|numeric',
            'action' => 'required|numeric',
            'error' => 'nullable|numeric',
            'error_note' => 'nullable',
            'sign_time' => 'required',
            'sign_string' => 'required',
            'merchant_prepare_id' => Rule::requiredIf($request->action === 1)
        ]);
        if ($validator->fails()) {
            return self::getErrorArray(self::ERROR_IN_REQUEST_FROM_CLICK);
        }

        $sign_time = urldecode($request->sign_time);

        $sign_string = md5(
            $request->click_trans_id.
            $request->service_id.
            config('local.click_billing.key').
            $request->merchant_trans_id.
            ((int) $request->action === 1 ? $request->merchant_prepare_id : '').
            $request->amount.
            $request->action.
            $sign_time
        );
//        dd($sign_string);

        if ($sign_string !== $request->sign_string) {
            return self::getErrorArray(self::ERROR_SIGN_CHECK_FAILED);
        }

        if (! ((int) $request->action === 0 || (int) $request->action === 1)) {
            return self::getErrorArray(self::ERROR_ACTION_NOT_FOUND);
        }

        $order = Order::byUniqueId($request->merchant_trans_id)->first();
        if (!$order) {
            return self::getErrorArray(self::ERROR_USER_NOT_FOUND);
        }

        $amount = (int) ($request->amount * 100);

        if ($amount != $order->amount) {
            return self::getErrorArray(self::ERROR_INVALID_AMOUNT);
        }

        // check duplicating of transaction with click_trans_id
        $transaction = Transaction::byClickId($request->click_trans_id)->first();
        if ((int) $request->action === 0 && $transaction) {
            return self::getErrorArray(self::ERROR_IN_REQUEST_FROM_CLICK);
        }

        // find transaction by merchant_prepare_id
        if ((int) $request->action === 1) {
            $transaction = Transaction::find($request->merchant_prepare_id);
            if (!$transaction) {
                return self::getErrorArray(self::ERROR_TRANSACTION_NOT_FOUND);
            }

            if ($transaction && $transaction->click_status === Transaction::STATUS_COMPLETED) {
                return self::getErrorArray(self::ERROR_ALREADY_PAID);
            }

            if ($transaction && $transaction->click_status === Transaction::STATUS_CANCELLED) {
                return self::getErrorArray(self::ERROR_TRANSACTION_CANCELLED);
            }
            if (abs((float)($transaction->amount / 100) - (float)$request->amount) > 0.01) {
                return self::getErrorArray(self::ERROR_INVALID_AMOUNT);
            }
        }

        return [
            'error' => 0,
            'error_note' => 'Success'
        ];
    }

    public static function getErrorArray($error)
    {
        return [
            'error' => $error,
            'error_note' => self::$error_notes[$error]
        ];
    }
}
