<?php

namespace App\Models\Orders;

use App\Models\Users\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Order extends Model
{
    use OrderStatusTrait;
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

    // =relations
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

    // =helpers
    public function updateAmount()
    {
        $this->amount = $this->items()->sum(DB::raw('order_items.price * order_items.quantity'));
        $this->save();
    }

    public function isValid()
    {
        return $this->items()->exists();
    }

    public function isDelivered()
    {
        return $this->order_delivery && $this->order_delivery->delivered;
    }

    public function balance()
    {
        $paid_amount =  $this->payments()->sum('amount');
        return $this->amount - $paid_amount;
    }
}
