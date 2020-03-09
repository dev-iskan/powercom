<?php

namespace App\Http\Controllers\Front\Cart;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
//        session()->forget('cart');
        $previous_cart = session()->get('cart');
        $cart = new Cart($previous_cart);
//        dd($cart);

        return view('cart', compact('cart'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric'
        ]);

        $product = Product::with('images')->findOrFail($request->product_id);

        $previous_cart = session()->get('cart');

        $cart = new Cart($previous_cart);
        $cart->add($request->product_id, $product);
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric'
        ]);

        $previous_cart = session()->get('cart');

        $cart = new Cart($previous_cart);
        $cart->remove($request->product_id);

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}
