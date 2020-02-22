<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'price',
        'delivered_at',
    ];

    protected $dates = ['delivered_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
