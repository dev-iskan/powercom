<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function get(Request $request) {
        $image = Image::where('uuid', $request->uuid)->where('key', $request->key)->first();
        return Storage::disk('local')->response($image->path);
    }
}
