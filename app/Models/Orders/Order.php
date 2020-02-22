<?php

namespace App\Models\Orders;

use App\Models\Users\Client;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'amount',
        'paid',
        'delivery',
        'finished_at'
    ];

    protected $dates = [
        'finished_at'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function order_delivery()
    {
        return $this->hasOne(OrderDelivery::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
