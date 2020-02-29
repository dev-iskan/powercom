<?php

namespace App\Services\Payme;

use App\Models\Orders\Order;
use App\Models\Orders\Payment;
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
        $existed_payment = Payment::where('order_id', $order_or_error->id)
            ->whereIn('payme_state', [Payment::STATE_CREATED, Payment::STATE_COMPLETED])
            ->where('payme_receipt_id', '!=', $request->params['id'])
            ->first();
        if ($existed_payment) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'time', [
                'ru' => 'Заказ в ожидании оплаты',
                'uz' => 'To`lovni kutish uchun buyurtma',
                'en' => 'Order pending payment'
            ]);
        }

        // get transaction by payme_id
        $payment = Payment::byPaymeId($request->params['id'])->first();
        // if no transaction create new
        if (!$payment) {
            if (now()->timestamp * 1000 - $request->params['time'] > Payment::TIMEOUT) {
                return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
            }
            $payme_time = Carbon::createFromTimestamp($request->params['time'] / 1000);

            $payment = new Payment();
            $payment->amount = $request->params['amount'];

            $payment->payme_time = $payme_time;
            $payment->payment_method = 'payme';
            $payment->payme_receipt_id = $request->params['id'];
            $payment->payme_state = Payment::STATE_CREATED;
            $payment->order()->associate($order_or_error);
            $payment->save();

            return [
                'result' => [
                    'create_time' => $payment->payme_time->timestamp * 1000,
                    'transaction' => (string)$payment->id,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        }

        // check state of transaction
        if ($payment->payme_state !== Payment::STATE_CREATED) {
            return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM);
        } elseif ($payment->isPaymeExpired()) {
            $payment->cancelPayme(Payment::STATE_CANCELLED, Payment::REASON_CANCELLED_BY_TIMEOUT);
            return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
        } else {
            return [
                'result' => [
                    'create_time' => $payment->payme_time->timestamp * 1000,
                    'transaction' => (string)$payment->id,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        }
    }

    public static function PerformTransaction(Request $request)
    {
        $payment = Payment::byPaymeId($request->params['id'])->first();
        $perform_time = $payment->payme_perform_time;
        if (!$payment) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }

        if ($payment->payme_state === Payment::STATE_CREATED) {
            if ($payment->isPaymeExpired()) {
                $payment->cancelPayme(Payment::STATE_CANCELLED, Payment::REASON_CANCELLED_BY_TIMEOUT);
                $payment->order->makeAvailable();
                return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM, 'time');
            }

            $payment->paid = true;
            $payment->performPayme();
            $perform_time = $payment->payme_perform_time;

            $order = $payment->order;
            if ($order->balance() == 0) {
                $order->paid = true;
                $order->save();
            }

            return [
                'result' => [
                    'transaction' => (string)$payment->id,
                    'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        }
        if ($payment->payme_state === Payment::STATE_COMPLETED) {
            return [
                'result' => [
                    'transaction' => (string)$payment->id,
                    'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        }

        return self::getErrorResponse(self::ERROR_COULD_NOT_PERFORM);
    }

    public static function CheckTransaction(Request $request)
    {
        $payment = Payment::byPaymeId($request->params['id'])->first();
        if (!$payment) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }
        $perform_time = $payment->payme_perform_time;
        $cancel_time = $payment->payme_cancel_time;
        return [
            'result' => [
                'create_time' => $payment->payme_time->timestamp * 1000,
                'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                'transaction' => (string)$payment->id,
                'state' => $payment->payme_state,
                'reason' => $payment->payme_cancel_reason
            ],
            'id' => $request->id
        ];
    }

    public static function CancelTransaction(Request $request)
    {
        $payment = Payment::byPaymeId($request->params['id'])->with('order')->first();
        if (!$payment) {
            return self::getErrorResponse(self::ERROR_TRANSACTION_NOT_FOUND);
        }

        // if transaction already cancelled
        if ($payment->payme_state == Payment::STATE_CANCELLED ||
            $payment->payme_state == Payment::STATE_CANCELLED_AFTER_COMPLETE) {

            $cancel_time = $payment->payme_cancel_time;
            return [
                'result' => [
                    'transaction' => (string)$payment->id,
                    'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        } // if transaction created but not completed cancel it
        elseif ($payment->payme_state == Payment::STATE_CREATED) {
            $payment->cancelPayme(Payment::STATE_CANCELLED, (int)$request->params['reason']);
            $cancel_time = $payment->payme_cancel_time;

            $payment->order->makeAvailable();
            return [
                'result' => [
                    'transaction' => (string)$payment->id,
                    'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                    'state' => $payment->payme_state
                ],
                'id' => $request->id
            ];
        } elseif ($payment->payme_state == Payment::STATE_COMPLETED) {
            if ($payment->order->isInProcess()) {

                $payment->paid = false;
                $payment->cancelPayme(Payment::STATE_CANCELLED_AFTER_COMPLETE, (int)$request->params['reason']);
                $cancel_time = $payment->payme_cancel_time;


                return [
                    'result' => [
                        'transaction' => (string)$payment->id,
                        'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                        'state' => $payment->payme_state
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

        $payments = Payment::paymeApplication()->where('payme_time', '>=', $from)->where('payme_time', '<=', $to)->get();

        $formattedTransactions = $payments->map(function ($payment) {
            $time = $payment->payme_time;
            $create_time = $payment->payme_time;
            $perform_time = $payment->payme_perform_time;
            $cancel_time = $payment->payme_cancel_time;

            return [
                'id' => $payment->payme_receipt_id,
                'time' => $time ? $time->timestamp * 1000 : 0,
                'amount' => $payment->amount,
                'account' => [
                    'order_id' => (string)$payment->order_id
                ],
                'create_time' => $create_time ? $create_time->timestamp * 1000 : 0,
                'perform_time' => $perform_time ? $perform_time->timestamp * 1000 : 0,
                'cancel_time' => $cancel_time ? $cancel_time->timestamp * 1000 : 0,
                'transaction' => (string)$payment->id,
                'state' => $payment->payme_state,
                'reason' => $payment->payme_cancel_reason
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

        $order = Order::byUniqueId($request->params['account']['order_id'])->unpaid()->inProgress()->first();
        if (!$order) {
            return self::getErrorResponse(self::ERROR_INVALID_ACCOUNT, 'order');
        }

        if ($request->params['amount'] > $order->balance()) {
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
