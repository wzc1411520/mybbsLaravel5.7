<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FavoriteIndexResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\TopicResource;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function favoriteIndex(Request $request,User $user)
    {
        $include = strtolower($request->include);
        $favorites = $user->favorites()->where(function ($query)use($include){
            if('topic'==$include){
                $query->whereFavoritedType('App\Models\Topic');
            }
            if('reply'==$include){
                $query->whereFavoritedType('App\Models\Reply');
            }

        })->latest()->with('favorited')->limit(30)->get();
//        return $favorites;
        return FavoriteIndexResource::collection($favorites);
    }
    //回复点赞
    public function storeReply (Reply $reply)
    {
        //需要自定义事件监听
        //修改点赞数
        $attributes = ['user_id' => $this->user()->id];
        if( ! $reply->favorites()->where($attributes)->exists()){
            $reply->favorites()->create($attributes);
            //调用信息通知事件
            event(new \App\Events\ReplyFavoriteEvents($reply,$this->user()));
            return new ReplyResource($reply);
        }else{
            return $this->response->noContent();
        }
    }

    //文章点赞
    public function storeTopic(Topic $topic)
    {
        $attributes = ['user_id' => $this->user()->id];
        if( ! $topic->favorites()->where($attributes)->exists()){
            $topic->favorites()->create($attributes);
            //调用信息通知事件
            event(new \App\Events\TopicFavoriteEvents($topic,$this->user()));
            return new TopicResource($topic);
        }else{
            return $this->response->noContent();
        }
    }
}
