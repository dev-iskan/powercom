<?php

namespace App\Http\Controllers\Front\Orders;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramNotification;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Services\Cart;
use App\Services\TelegramMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function show($id) {
        $order = Order::with('status', 'items.product', 'order_delivery')->findOrFail($id);
        return view('user.order', compact('order'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'delivery' => 'nullable|boolean',
            'full_name' => 'required_if:delivery,1|max:255',
            'phone' => 'required_if:delivery,1|digits:12',
            'address' => 'required_if:delivery,1|max:255',
            'price' => 'nullable'
        ]);
        // check cart
        $cart = session()->get('cart');

        if (!$cart) {
            return redirect()->route('cart.index');
        }

        $client = auth()->user()->client;

        $order = DB::transaction(function () use ($client, $request, $cart) {
            $order = new Order();
            $order->client_id = $client->id;
            $order->setCreatedStatus();
            $order->delivery = $request->delivery;
            $order->save();

            if ($request->delivery) {
                $order->order_delivery()->create($request->all());
            }
            foreach ($cart->items as $item) {
                $order_item = new OrderItem();
                $order_item->quantity = $item['quantity'];
                $order_item->price = $item['price'];
                $order_item->product_id = $item['data']->id;
                $order_item->order_id = $order->id;
                $order_item->save();
            }

            $order->setInProgressStatus();
            $order->updateAmount();

            return $order;
        });

        if (config('app.env') == 'production') {
            $message = TelegramMessages::notifyNewOrder(
                $request->full_name,
                $request->phone,
                $request->address,
                'https://powercom.uz/admin/' . $order->id
            );
            SendTelegramNotification::dispatch($message);
        }

        session()->put('cart', new Cart(null));

        return redirect()->back()->with('message', 'Заказ успешно оформлен');
    }
}
