<?php

namespace App\Models\Blog;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasImages;

    protected $fillable = ['name', 'short_description', 'description', 'active'];
}
