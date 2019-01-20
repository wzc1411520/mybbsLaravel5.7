<?php
/**
 * Created by PhpStorm.
 * User: wzc
 * Date: 2019/1/20
 * Time: 21:23
 */
namespace App\Handlers;

use Illuminate\Support\Facades\Storage;

class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function save($file, $folder, $file_prefix,$max_width = false)
    {
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }
        $path = Storage::putFileAs(
            'public/'.$folder, $file, $filename
        );
        // 如果限制了图片宽度，就进行裁剪
        if ($max_width ) {
            $filepath = str_replace('/', '\\', storage_path('app\\'.$path));
            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($filepath, $max_width);
        }

        return str_replace('public/','/storage/',$path);
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = \Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}