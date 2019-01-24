<?php

namespace App\Http\Controllers;

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
        $reply->storeFavorite();
        return back();
    }

    //文章点赞
    public function storeTopic(Topic $topic)
    {
        $topic->storeFavorite();
        return back();
    }
}
