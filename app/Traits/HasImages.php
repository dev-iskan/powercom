<?php

namespace App\Traits;

use App\Models\Media\Image;

trait HasImages
{
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getFirstImage()
    {
        return $this->images()->first();
    }
}
