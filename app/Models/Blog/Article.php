<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['name', 'short_description', 'description', 'active'];
}
