<?php

namespace App\Models;

use App\Models\Traits\Favoritable;
use App\Models\Traits\RecordsActivity;

class Topic extends Model
{
    use Favoritable;
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];
    protected $appends = ['favoritesCount','isFavorited'];

    //方法
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }

    //查看是否有修改
    public function hasUpdatesFor($user)
    {
        // Look in the cache for the proper key
        // compare that carbon instance with the $thread->updated_at

        $key = $user->visitedThreadCacheKey($this);

        return cache($key);
    }



    //作用域
    public function scopeWithOrder($query,$order)
    {
        switch ($order){
            case 'recent':$query->recent();break;
            default :$query->recentReplied();break;
        }
        return $query->with('user','category');
    }

    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at','desc');
    }

    public function scopeRecent($query)
    {
        return $query->latest();
    }


    //模型关联
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function category()
    {
        return  $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
