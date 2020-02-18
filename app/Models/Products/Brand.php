<?php

namespace App\Models\Products;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasImages;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
