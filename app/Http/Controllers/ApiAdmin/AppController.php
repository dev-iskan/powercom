<?php

namespace App\Http\Controllers\ApiAdmin;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderSetting;
use App\Models\Orders\OrderStatus;
use App\Models\Orders\Payment;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $payment_methods = Payment::getPaymentMethods();
        $order_setting = OrderSetting::first();
        $order_statuses = OrderStatus::all();
        $total = Order::justCreated()->count();
        return response()->json(compact('payment_methods', 'order_statuses', 'order_setting', 'total'));
    }
}
