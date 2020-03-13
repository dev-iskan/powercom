<?php

namespace App\Models\Blog;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasImages;

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    protected $fillable = ['name', 'short_description', 'description', 'active'];
}
