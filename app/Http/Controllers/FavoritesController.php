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
        event(new \App\Events\ReplyFavoriteEvents($reply,\Auth::user()));
//        $reply->topic->user->notify(new ReplyFavorite($reply));
        return back();
    }

    //文章点赞
    public function storeTopic(Topic $topic)
    {
        $topic->storeFavorite();
        event(new \App\Events\TopicFavoriteEvents($topic,\Auth::user()));
//        $topic->user->notify(new TopicFavorite($topic));
        return back();
    }
}
