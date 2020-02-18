<?php

namespace App\Models\Products;

use App\Models\Media\File;
use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Product extends Model implements Sortable
{
    use SortableTrait, HasImages;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'quantity',
        'price',
        'active',
        'order'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
