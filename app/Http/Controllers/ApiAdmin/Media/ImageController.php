<?php

namespace App\Http\Controllers\ApiAdmin\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index($imageable_type, $imageable_id)
    {
        $images = Image::where('imageable_type', $imageable_type)->where('imageable_id', $imageable_id)->get();
        return $images;
    }

    public function store($imageable_type, $imageable_id, Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,bmp,png,webp'
        ]);

        $image = Image::upload($request->file('image'), $imageable_type, $imageable_id);
        return $image;
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        $image->deleteFile();

        return response()->json(['message' => __('response.deleted')]);
    }
}
