<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = ['color', 'name', 'class'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
