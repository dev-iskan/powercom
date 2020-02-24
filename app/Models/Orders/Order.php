<?php

namespace App\Models\Orders;

use App\Models\Users\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'unique_id',
        'amount',
        'paid',
        'delivery',
        'finished_at'
    ];

    protected $dates = [
        'finished_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_id = Str::upper(Str::random(10));
        });
    }

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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
