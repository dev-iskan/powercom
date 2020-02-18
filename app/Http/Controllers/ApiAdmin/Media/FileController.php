<?php

namespace App\Http\Controllers\ApiAdmin\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\File;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request,[
            'product_id' => 'required|numeric'
        ]);

        $files = File::where('product_id', $request->product_id)->get();
        return $files;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file' =>  'required|file|mimes:doc,docx,pdf,xls,xlsx',
            'product_id' => 'required|numeric'
        ]);

        $product = Product::findOrFail($request->product_id);

        $file = File::uploadFile($request->file('file'), $product);
        return $file;
    }

    public function destroy($id)
    {
        /** @var File $file */
        $file = File::findOrFail($id);
        $file->deleteFile();

        return response()->json(['message' => __('response.deleted')]);
    }
}
