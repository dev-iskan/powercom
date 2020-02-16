<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'short_description'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
