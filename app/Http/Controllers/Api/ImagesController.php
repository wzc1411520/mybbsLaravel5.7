<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,ImageUploadHandler $imageUpload,Image $image)
    {
        $user = $this->user();
        $size = $request->type == 'avatar'?362:1024;
        $path = $imageUpload->save($request->image,str_plural($request->type), $user->id, $size);
        $image->path = $path;
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();
        return new ImageResource($image);
    }
}
