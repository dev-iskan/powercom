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
        $ordersQuery = Order::query();
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
        $order->save();

        if ($request->delivery) {
            $delivery = new OrderDelivery($request->all());
            $delivery->order_id = $order->id;
            $delivery->save();
        }

        return $order;
    }

    public function show()
    {

    }
}