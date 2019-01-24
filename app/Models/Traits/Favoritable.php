<?php
/**
 * Created by PhpStorm.
 * User: wzc
 * Date: 2019/1/23
 * Time: 16:34
 */

namespace App\Models\Traits;


use App\Models\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class,'favorited');
    }

    public function storeFavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        session()->flash('message', '已经赞过了!!!');
        if( ! $this->favorites()->where($attributes)->exists()){
            return $this->favorites()->create($attributes);
        }else{
            session()->flash('message', '已经赞过了!!!');
        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id',auth()->id())->count();
    }
}