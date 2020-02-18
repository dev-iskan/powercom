<?php

namespace App\Models\Media;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'url',
        'size',
        'mime_type'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function uploadFile(UploadedFile $file, Product $product)
    {
        $name = $file->getClientOriginalName();
        $folderName = self::getFolderName($product->id);

        Storage::disk('local')->putFileAs(
            $folderName,
            $file,
            $name
        );

        $model = new static();
        $model->name = $name;
        $model->path = $folderName . '/' . $name;
        $model->url = config('app.url') . '/media/files?name=' . $name;
        $model->product_id = $product->id;
        $model->size = $file->getSize();
        $model->mime_type = $file->getClientMimeType();
        $model->save();

        return $model;
    }

    public function deleteFile()
    {
        Storage::disk('local')->delete($this->path);
        $this->delete();
    }

    protected static function getFolderName($product_id)
    {
        $folderName = 'products';
        $correct_id = str_pad($product_id, 8, '0', STR_PAD_LEFT);
        for ($i = 0; $i < 8; $i += 2) {
            $folderName .= '/' . substr($correct_id, $i, 2);
        }

        return $folderName;
    }
}
