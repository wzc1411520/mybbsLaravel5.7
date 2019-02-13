<?php

namespace App\Http\Controllers\Api;

use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //回复点赞
    public function storeReply (Reply $reply)
    {
        //需要自定义事件监听
        //修改点赞数
        $attributes = ['user_id' => $this->user()->id];
        if( ! $reply->favorites()->where($attributes)->exists()){
            return $reply->favorites()->create($attributes);
        }else{
            return $this->response->noContent();
        }
    }

    //文章点赞
    public function storeTopic(Topic $topic)
    {
        $attributes = ['user_id' => $this->user()->id];
        if( ! $topic->favorites()->where($attributes)->exists()){
            return $topic->favorites()->create($attributes);
        }else{
            return $this->response->noContent();
        }
    }
}