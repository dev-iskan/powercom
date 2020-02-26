<?php

namespace App\Http\Controllers\ApiAdmin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderItemRequest;
use App\Http\Requests\Orders\UpdateOrderItemRequest;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index(Request $request)
    {
        $itemsQuery = OrderItem::with('product.categories');

        if ($order_id = $request->query('order_id')) {
            $itemsQuery->where('order_id', $order_id);
        }

        if ($request->query('paginate') == true) {
            return $itemsQuery->paginate($request->offset ?? 10);
        }
        return $itemsQuery->limit($request->limit ?? null)->get();
    }

    public function store(StoreOrderItemRequest $request)
    {
        $order = Order::findOrFail($request->order_id);
        $product = Product::findOrFail($request->product_id);

        $item = new OrderItem($request->all());
        $item->product_id = $product->id;
        $item->order_id = $order->id;
        $item->save();

        $order->updateAmount();
        return $item;
    }

    public function update(UpdateOrderItemRequest $request, $id)
    {
        $product = Product::findOrFail($request->product_id);

        $item = OrderItem::with('order')->findOrFail($id);
        $item->fill($request->all());
        $item->product_id = $product->id;
        $item->save();

        $item->order->updateAmount();
        return $item;
    }

    public function destroy($id)
    {
        $item = OrderItem::with('order')->findOrFail($id);
        $item->delete();

        $item->order->updateAmount();
        return response()->json(['message' => __('response.deleted')]);

    }
}
