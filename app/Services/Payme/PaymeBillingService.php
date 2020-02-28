<?php

namespace App\Services\Payme;

use App\Models\Orders\Order;
use App\Models\Orders\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymeBillingService
{
    const ERROR_INTERNAL_SYSTEM = -32400;
    const ERROR_INSUFFICIENT_PRIVILEGE = -32504;
    const ERROR_INVALID_JSON_RPC_OBJECT = -32600;
    const ERROR_METHOD_NOT_FOUND = -32601;
    const ERROR_INVALID_AMOUNT = -31001;
    const ERROR_TRANSACTION_NOT_FOUND = -31003;
    const ERROR_INVALID_ACCOUNT = -31050;
    const ERROR_COULD_NOT_CANCEL = -31007;
    const ERROR_COULD_NOT_PERFORM = -31008;

    protected static $error_messages = [
        self::ERROR_INSUFFICIENT_PRIVILEGE => [
            'ru' => 'Недостаточно привилегий для выполнения метода',
            'uz' => 'Usulni bajarishda yetarli huquqlar yo\'q',
            'en' => 'Not enough privileges to execute method'
        ],
        self::ERROR_METHOD_NOT_FOUND => [
            'ru' => 'Запрашиваемый метод не найден',
            'uz' => 'So‘ralgan usul topilmadi',
            'en' => 'The requested method was not found'
        ],
        self::ERROR_INVALID_AMOUNT => [
            'ru' => 'Неверная сумма',
            'uz' => 'Noto‘g‘ri miqdor',
            'en' => 'Invalid amount'
        ],
        self::ERROR_TRANSACTION_NOT_FOUND => [
            'ru' => 'Транзакция не найдена',
            'uz' => 'Transaksiya topilmadi',
            'en' => 'Transaction not found'
        ],
        self::ERROR_COULD_NOT_CANCEL => [
            'ru' => 'Оплата уже проведена',
            'uz' => 'To\'lov amalga oshirildi',
            'en' => 'Payment already done'
        ],
        self::ERROR_INVALID_ACCOUNT => [
            'ru' => 'Неверный номер заказа',
            'uz' => 'Noto‘g‘ri zakaz raqami',
            'en' => 'Invalid order number'
        ],
        self::ERROR_COULD_NOT_PERFORM => [
            'ru' => 'Не удалось выполнить операцию',
            'uz' => 'Ushbu amalni bajarib bo\'lmadi',
            'en' => 'Could not perform operation'
        ],
        self::ERROR_INVALID_JSON_RPC_OBJECT => [
            'ru' => 'Отсутствуют обязательные поля в RPC-запросе',
            'uz' => 'RPC so\'rovida majburiy maydonlar yo\'q',
            'en' => 'Missing required fields in RPC request'
        ]
    ];

    public static function getMessageArray($error)
    {
        return self::$error_messages[$error];
    }

    public static function CheckPerformTransaction(Request $request)
    {
        $order_or_error = self::validateAndGetOrder($request);
        if (array_key_exists('error', $order_or_error)) {
            return $order_or_error;
        }

        return [
            'result' => [
                'allow' => true
            ],
            'id' => $request->id
        ];
    }

    public static function CreateTransaction(Request $request)
    {
        $order_or_error = self::validateAndGetOrder($request);
        if (array_key_exists('error', $order_or_error)) {
            return $order_or_error;
        }

        // if order created or paid transaction already exists
        $existed_transaction = Transaction::where('order_id', $order_or_error->id)
            ->whereIn('payme_state', [Transaction::STATE_CREATED, Transaction::STATE_COMPLETED])
            ->where('payme_receipt_id', '!=', $request->params['id'])
            ->first();
        if ($existed_transaction) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'time', [
                'ru' => 'Заказ в ожидании оплаты',
                'uz' => 'To`lovni kutish uchun buyurtma',
                'en' => 'Order pending payment'
            ]);
        }

        // get transaction by payme_id
        $transaction = Transaction::byPaymeId($request->params['id'])->first();
        // if no transaction create new
        if (!$transaction) {
            if (now()->timestamp * 1000 - $request->params['time'] > Transaction::TIMEOUT) {
                return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
            }
            $payme_time = Carbon::createFromTimestamp($request->params['time'] / 1000);

            $transaction = new Transaction();
            $transaction->amount = $request->params['amount'];

            $transaction->payme_time = $payme_time;
            $transaction->payment_method = 'payme';
            $transaction->payme_receipt_id = $request->params['id'];
            $transaction->payme_state = Transaction::STATE_CREATED;
            $transaction->order()->associate($order_or_error);
            $transaction->save();

            $order_or_error->status = Order::STATUS_WAITING_PAY;
            $order_or_error->save();

            return [
                'result' => [
                    'create_time' => $transaction->payme_time->timestamp * 1000,
                    'transaction' => (string)$transaction->id,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        }

        // check state of transaction
        if ($transaction->payme_state !== Transaction::STATE_CREATED) {
            return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM);
        } elseif ($transaction->isPaymeExpired()) {
            $transaction->cancelPayme(Transaction::STATE_CANCELLED, Transaction::REASON_CANCELLED_BY_TIMEOUT);
            return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
        } else {
            return [
                'result' => [
                    'create_time' => $transaction->payme_time->timestamp * 1000,
                    'transaction' => (string)$transaction->id,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        }
    }

    public static function PerformTransaction(Request $request)
    {
        $transaction = Transaction::byPaymeId($request->params['id'])->first();
        $perform_time = $transaction->payme_perform_time;
        if (!$transaction) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }

        if ($transaction->payme_state === Transaction::STATE_CREATED) {
            if ($transaction->isPaymeExpired()) {
                $transaction->cancelPayme(Transaction::STATE_CANCELLED, Transaction::REASON_CANCELLED_BY_TIMEOUT);
                $transaction->order->makeAvailable();
                return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
            }

            $transaction->paid = true;
            $transaction->performPayme();

            $transaction->order->acceptPayment($transaction);

            $perform_time = $transaction->payme_perform_time;
            return [
                'result' => [
                    'transaction' => (string)$transaction->id,
                    'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        }
        if ($transaction->payme_state === Transaction::STATE_COMPLETED) {
            return [
                'result' => [
                    'transaction' => (string)$transaction->id,
                    'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        }

        return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM);
    }

    public static function CheckTransaction(Request $request)
    {
        $transaction = Transaction::byPaymeId($request->params['id'])->first();
        if (!$transaction) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }
        $perform_time = $transaction->payme_perform_time;
        $cancel_time = $transaction->payme_cancel_time;
        return [
            'result' => [
                'create_time' => $transaction->payme_time->timestamp * 1000,
                'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                'transaction' => (string)$transaction->id,
                'state' => $transaction->payme_state,
                'reason' => $transaction->payme_cancel_reason
            ],
            'id' => $request->id
        ];
    }

    public static function CancelTransaction(Request $request)
    {
        $transaction = Transaction::byPaymeId($request->params['id'])->with('order')->first();
        if (!$transaction) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }

        // if transaction already cancelled
        if ($transaction->payme_state == Transaction::STATE_CANCELLED ||
            $transaction->payme_state == Transaction::STATE_CANCELLED_AFTER_COMPLETE) {

            $cancel_time = $transaction->payme_cancel_time;
            return [
                'result' => [
                    'transaction' => (string)$transaction->id,
                    'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        } // if transaction created but not completed cancel it
        elseif ($transaction->payme_state == Transaction::STATE_CREATED) {
            $transaction->cancelPayme(Transaction::STATE_CANCELLED, (int)$request->params['reason']);
            $cancel_time = $transaction->payme_cancel_time;

            $transaction->order->makeAvailable();
            return [
                'result' => [
                    'transaction' => (string)$transaction->id,
                    'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                    'state' => $transaction->payme_state
                ],
                'id' => $request->id
            ];
        } elseif ($transaction->payme_state == Transaction::STATE_COMPLETED) {
            if ($transaction->order->canBeCancelled()) {

                $transaction->paid = false;
                $transaction->cancelPayme(Transaction::STATE_CANCELLED_AFTER_COMPLETE, (int)$request->params['reason']);

                $transaction->order->cancelPayment($transaction);

                $cancel_time = $transaction->payme_cancel_time;
                return [
                    'result' => [
                        'transaction' => (string)$transaction->id,
                        'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                        'state' => $transaction->payme_state
                    ],
                    'id' => $request->id
                ];
            }
            return self::getErrorResponse(self::ERROR_COULD_NOT_CANCEL);
        }
    }

    public static function GetStatement(Request $request)
    {
        if (!isset($request->params['from'])) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'from', [
                'ru' => 'Неверная дата',
                'uz' => 'Sana noto‘g‘ri',
                'en' => 'Incorrect date'
            ]);
        }

        if (!isset($request->params['to'])) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'to', [
                'ru' => 'Неверная дата',
                'uz' => 'Sana noto‘g‘ri',
                'en' => 'Incorrect date'
            ]);
        }

        if (1 * $request->params['from'] >= 1 * $request->params['to']) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'to', [
                'ru' => 'Неверная дата (from >= to)',
                'uz' => 'Sana noto‘g‘ri (from >= to)',
                'en' => 'Incorrect date (from >= to)'
            ]);
        }

        $from = Carbon::createFromTimestamp($request->params['from'] / 1000);
        $to = Carbon::createFromTimestamp($request->params['to'] / 1000);

        $transactions = Transaction::paymeApplication()->where('payme_time', '>=', $from)->where('payme_time', '<=', $to)->get();

        $formattedTransactions = $transactions->map(function ($transaction) {
            $time = $transaction->payme_time;
            $create_time = $transaction->payme_time;
            $perform_time = $transaction->payme_perform_time;
            $cancel_time = $transaction->payme_cancel_time;

            return [
                'id' => $transaction->payme_receipt_id,
                'time' => $time ? $time->timestamp * 1000 : 0,
                'amount' => $transaction->amount,
                'account' => [
                    'order_id' => (string)$transaction->order_id
                ],
                'create_time' => $create_time ? $create_time->timestamp * 1000 : 0,
                'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                'transaction' => (string)$transaction->id,
                'state' => $transaction->payme_state,
                'reason' => $transaction->payme_cancel_reason
            ];
        });

        return [
            'result' => [
                'transactions' => $formattedTransactions
            ],
            'id' => $request->id
        ];
    }

    protected static function validateAndGetOrder($request)
    {
        $validator = Validator::make($request->params, [
            'amount' => 'required|numeric',
            'account' => 'required|array',
            'account.order_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'order');
        }

        // TODO pay only for unpaid and not completed orders
        $order = Order::byUniqueId($request->params['account']['order_id'])->first();

        if (!$order) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'order');
        }

        if ($order->status != Order::STATUS_CREATED && $order->status != Order::STATUS_WAITING_PAY) {
            return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'order');
        }

        // TODO fix check
        if ($request->params['amount'] != $order->amount) {
            return self::getErrorResponse(self::ERROR_INVALID_AMOUNT, 'amount');
        }

        return $order;
    }

    public static function getErrorResponse($code, $data = null, $messageArray = null)
    {
        return [
            'error' => [
                'code' => $code,
                'message' => $messageArray ?? self::getMessageArray($code),
                'data' => $data
            ],
            'id' => request()->id
        ];
    }
}
