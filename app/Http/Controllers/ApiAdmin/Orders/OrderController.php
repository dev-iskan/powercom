<?php

namespace App\Http\Controllers\ApiAdmin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Models\Orders\Order;
use App\Models\Users\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $ordersQuery = Order::with('status', 'client');
        if ($request->query('paginate') == true) {
            return $ordersQuery->paginate($request->offset ?? 10);
        }

        return $ordersQuery->limit($request->limit)->get();
    }

    public function store(StoreOrderRequest $request)
    {
        $client = Client::findOrFail($request->client_id);

        $order = new Order();
        $order->client_id = $client->id;
        $order->setCreatedStatus();
        $order->delivery = $request->delivery;
        $order->save();

        if ($request->delivery) {
            $order->order_delivery()->create($request->all());
        }

        return $order;
    }

    public function show($id)
    {
        $order = Order::with('items', 'status', 'order_delivery', 'client')->findOrFail($id);
        return $order;
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::with('status', 'order_delivery')->findOrFail($id);

        if (!$request->delivery && $order->order_delivery) {
            $order->order_delivery()->delete();
        } elseif ($request->delivery && $order->order_delivery) {
            $order->order_delivery->update($request->all());
        } elseif ($request->delivery) {
            $order->order_delivery()->create($request->all());
        }
        $order->update($request->all());

        return $order->fresh('order_delivery');
    }

    public function setInProgress($id)
    {
        $order = Order::findOrFail($id);

        $order->setInProgressStatus();
        $order->save();

        return $order;
    }

    public function setCompleted($id)
    {
        $order = Order::findOrFail($id);

        $order->setCompletedStatus();
        $order->save();

        return $order;
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->setCancelledStatus();
        $order->save();

        return $order;
    }
}
