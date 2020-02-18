<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    protected $fillable = [
        'uuid',
        'key',
        'url',
        'path',
        'size',
        'mime_type'
    ];

    protected $hidden = ['key', 'uuid'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public static function upload(UploadedFile $image, $imageable_type, $imageable_id)
    {
        // create name from uuid
        $uuid = (string)Str::uuid();
        $key = hash('sha256', $uuid);
        $folderName = self::getFolderName($imageable_type, $imageable_id);

        $imageName = $uuid . '.' . $image->getClientOriginalExtension();

        Storage::disk('local')->putFileAs(
            $folderName,
            $image,
            $imageName
        );

        $model = new static();
        $model->uuid = $uuid;
        $model->key = $key;
        $model->path = $folderName.'/'.$imageName;
        $model->url = config('app.url').'/media/images?uuid='.$uuid.'&key='.$key;
        $model->imageable_type = $imageable_type;
        $model->imageable_id = $imageable_id;
        $model->size = $image->getSize();
        $model->mime_type = $image->getClientMimeType();
        $model->save();

        return $model;
    }

    public function deleteImage()
    {
        Storage::disk('local')->delete($this->path);
        $this->delete();
    }

    protected static function getFolderName($type, $id)
    {
        $folderName = $type;
        $correct_id = str_pad($id, 8, '0', STR_PAD_LEFT);
        for ($i = 0; $i<8; $i+=2)
        {
            $folderName .= '/'.substr($correct_id, $i, 2);
        }

        return $folderName;
    }
}
