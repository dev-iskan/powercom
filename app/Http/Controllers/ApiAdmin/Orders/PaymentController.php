<?php

namespace App\Http\Controllers\ApiAdmin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $paymentQuery = Payment::query();

        if ($request->query('paginate') == true) {
            return $paymentQuery->paginate($request->offset ?? 10);
        }

        return $paymentQuery->limit($request->limit)->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer',
            'payment_method' => [
                'required',
                Rule::in(Payment::getPaymentMethods()),
            ],
            'amount' => 'required|integer'
        ]);

        $order = Order::findOrFail($request->order_id);
        if (!$order->isInProgress()) {
            return response()->json(['message' => 'Заказ должен быть в процессе'], 400);
        }
        if ($order->paid) {
            return response()->json(['message' => 'Заказ уже оплачен'], 400);
        }
        if ($request->amount > $order->balance()) {
            return response()->json(['message' => 'Сумма не должна превышать стоимость заказа'], 400);
        }

        $payment = DB::transaction(function () use ($request, $order) {
            $payment = new Payment();
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->order_id = $order->id;
            $payment->paid = true;
            $payment->paid_time = now();
            $payment->save();

            if ($order->balance() == 0) {
                $order->paid = true;
                $order->save();
            }

            return $payment;
        });

        return $payment;
    }
}
