<?php

namespace App\Services;

class Cart
{
    public $items; // ['id' => ['quantity' => 2, 'data' => eloquent]
    public $total_quantity; // 10
    public $total_price; // 400000

    public function __construct($previous_cart)
    {
        if ($previous_cart != null) {
            $this->items = $previous_cart->items;
            $this->total_quantity = $previous_cart->total_quantity;
            $this->total_price = $previous_cart->total_price;
        } else {
            $this->items = [];
            $this->total_quantity = 0;
            $this->total_price = 0;
        }
    }

    public function add($id, $product)
    {
        if (array_key_exists($id, $this->items)) {
            $product_to_add = $this->items[$id];

            if ($product->quantity < $product_to_add['quantity'] + 1) {
                return;
            }

            $product_to_add['quantity']++;
        } else {
            $product_to_add = ['quantity' => 1, 'data' => $product, 'price' => $product->price];
        }

        $this->items[$id] = $product_to_add;
        $this->total_quantity += 1;
        $this->total_price += $product_to_add['price'];
    }

    public function decrement($id)
    {
        if (!array_key_exists($id, $this->items)) return;

        $product_to_decrement = $this->items[$id];
        if ($product_to_decrement['quantity'] == 1) {
            $this->remove($id);
        } else {
            $product_to_decrement['quantity']--;
            $this->items[$id] = $product_to_decrement;
            $this->total_price -= $product_to_decrement['price'];
            $this->total_quantity--;
        }
    }

    public function remove($id)
    {
        if (!array_key_exists($id, $this->items)) return;

        $product_to_remove = $this->items[$id];

        unset($this->items[$id]);
        $this->total_price -= ($product_to_remove['quantity'] * $product_to_remove['price']);
        $this->total_quantity -= $product_to_remove['quantity'];
    }
}
