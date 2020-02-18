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

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // scopes

    public function scopeParents($query)
    {
        $query->whereNull('parent_id');
    }

    public function scopeChildren($query)
    {
        $query->whereNotNull('parent_id');
    }

    // helpers

    public function isParent()
    {
        return $this->children()->exists();
    }

    public function setParent($parent)
    {
        if ($parent == $this->id) {
            return;
        }

        if (!$parent) {
            $this->parent()->dissociate();
            $this->save();
            return;
        }

        $this->parent()->associate($parent);
        $this->save();
    }
}
