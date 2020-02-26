<?php

namespace App\Http\Controllers\ApiAdmin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Models\Orders\Order;
use App\Models\Orders\OrderDelivery;
use App\Models\Users\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $ordersQuery = Order::with('status');
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
            $delivery = new OrderDelivery($request->all());
            $delivery->order_id = $order->id;
            $delivery->save();
        }

        return $order;
    }

    public function show($id)
    {
        $order = Order::with('items', 'status', 'order_delivery')->findOrFail($id);
        return $order;
    }

    public function setInProgress($id) {
        $order = Order::findOrFail($id);

        $order->setInProgressStatus();
        $order->save();

        return $order;
    }

    public function setCompleted($id) {
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
