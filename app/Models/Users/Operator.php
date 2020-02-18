<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
