<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function get(Request $request) {
        $file = File::where('name', $request->name)->first();
        return Storage::disk('local')->response($file->path);
    }
}
