<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'payment_method',
        'paid',
        'cancelled',
        'paid_time',
        'cancelled_time'
    ];

    protected $dates = [
        'paid_time',
        'cancelled_time'
    ];

    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Наличные',
            'terminal' => 'Терминал',
            'transfer' => 'Перечисление',
            'payme' => 'Payme',
            'click' => 'Click'
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
