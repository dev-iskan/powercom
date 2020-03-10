<?php

namespace App\Http\Controllers\Front\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;
        $orders = $client->orders()->with('status', 'items.product', 'order_delivery', 'payments')->get();
        dd($client, $orders);
        return view('user.index', compact('client', 'orders'));
    }
}
