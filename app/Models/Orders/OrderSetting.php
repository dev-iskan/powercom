<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderSetting extends Model
{
    protected $fillable = [
        'status_created',
        'status_in_progress',
        'status_completed'
    ];

    public static function statusCreated()
    {
        $setting = self::first();
        return OrderStatus::find($setting->status_created);
    }

    public static function statusInProgress()
    {
        $setting = self::first();
        return OrderStatus::find($setting->status_in_progress);
    }

    public static function statusCompleted()
    {
        $setting = self::first();
        return OrderStatus::find($setting->status_completed);
    }
}
