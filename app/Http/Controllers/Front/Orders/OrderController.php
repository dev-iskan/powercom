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
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function show($id)
    {
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

        DB::beginTransaction();
        try {
            $order = new Order();
            $order->client_id = $client->id;
            $order->setCreatedStatus();
            $order->delivery = $request->delivery;
            $order->save();

            if ($request->delivery) {
                $order->order_delivery()->create($request->all());
            }
            foreach ($cart->items as $item) {
                $product = $item['data']->fresh();
                if ($item['quantity'] > $product->quantity) {
                    throw new \Exception('not enough quantity');
                }

                $order_item = new OrderItem();
                $order_item->quantity = $item['quantity'];
                $order_item->price = $item['price'];
                $order_item->product_id = $product->id;
                $order_item->order_id = $order->id;
                $order_item->save();

                $product->quantity = $product->quantity - $item['quantity'];
                $product->save();
            }

            $order->setInProgressStatus();
            $order->updateAmount();

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();
            return back()->with('message', 'Произошла ошибка');
        }
        DB::commit();


        if (config('app.env') == 'production') {
            $message = TelegramMessages::notifyNewOrder(
                $request->full_name,
                $request->phone,
                $request->address,
                'http://admin.powercom.uz/orders-edit/' . $order->id
            );
            SendTelegramNotification::dispatch($message);
        }

        session()->put('cart', new Cart(null));

        return redirect()->route('order.show', ['id' => $order->id])->with('message', 'Заказ успешно оформлен');
    }
}
