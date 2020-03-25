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
        $ordersQuery = Order::with('status', 'client')->latest();

        if ($q = $request->query('q')) {
            $ordersQuery->where(function ($query) use ($q) {
                $query->whereHas('client', function ($clientQuery) use ($q) {
                    $clientQuery->where('name', 'ilike', "%{$q}%")
                        ->orWhere('surname', 'ilike', "%{$q}%")
                        ->orWhere('patronymic', 'ilike', "%{$q}%")
                        ->orWhere('phone', 'ilike', "%{$q}%")
                        ->orWhere('email', 'ilike', "%{$q}%");
                })
                    ->orWhere('unique_id', 'ilike', "%{$q}%");
            });
        }

        if ($paid = $request->query('paid')) {
            $ordersQuery->where('paid', $paid);
        }

        if ($delivery = $request->query('delivery')) {
            $ordersQuery->where('delivery', $delivery);
        }

        if ($status_id = $request->query('status_id')) {
            $ordersQuery->where('order_status_id', $status_id);
        }

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
        $order->note = $request->note;
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
        /** @var Order $order */
        $order = Order::with('items.product')->findOrFail($id);

        if (!$order->isCreated()) {
            return response()->json(['message' => 'Заказ должен быть в новым'], 400);
        }

        if (!$order->isValid()) {
            return response()->json(['message' => 'Заказ должен иметь товары'], 400);
        }

        foreach ($order->items as $item) {
            $product = $item->product;

            if ($item->quantity > $product->quantity) {
                return response()->json(['message' => 'Превышено количество продукта ' . $product->name], 400);
            }

            $product->quantity = $product->quantity - $item->quantity;
            $product->save();
        }

        $order->setInProgressStatus();
        $order->save();

        return $order;
    }

    public function setCompleted($id)
    {
        /** @var Order $order */
        $order = Order::findOrFail($id);

        if (!$order->isInProgress()) {
            return response()->json(['message' => 'Заказ должен быть в процессе'], 400);
        }

        if (!$order->paid) {
            return response()->json(['message' => 'Заказ должен быть оплачен'], 400);
        }

        if ($order->delivery && !$order->isDelivered()) {
            return response()->json(['message' => 'Заказ должен быть доставлен'], 400);
        }

        $order->finished_at = now();
        $order->setCompletedStatus();
        $order->save();

        return $order;
    }

    public function completeDelivery($id)
    {
        /** @var Order $order */
        $order = Order::with('order_delivery')->forDelivery()->findOrFail($id);
        $delivery = $order->order_delivery;

        if ($delivery->delivered) {
            return response()->json(['message' => 'Уже доставлено'], 400);
        }

        $delivery->delivered = true;
        $delivery->delivered_at = now();
        $delivery->save();

        return $order;
    }

    public function cancelDelivery($id)
    {
        /** @var Order $order */
        $order = Order::with('order_delivery')->findOrFail($id);
        if (!($order->isInProgress() || $order->isCreated())) {
            return response()->json(['message' => 'Невозможно отменить заявку'], 400);
        }
        if ($order->isDelivered() || $order->paid) {
            return response()->json(['message' => 'Невозможно отменить заявку'], 400);
        }
        $order->cancel();

        return $order;
    }

    public function destroy($id)
    {

    }
}
