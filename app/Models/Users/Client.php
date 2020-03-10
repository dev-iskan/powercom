<?php

namespace App\Models\Users;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'phone',
        'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->surname.' '.$this->patronymic;
    }
}
