<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable  implements JWTSubject
{
    use HasRoles;
    use Notifiable {
        notify as protected laravelNotify;
    }
    use MustVerifyEmailTrait;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == \Auth::id()) {
            return;
        }
        if($instance->type == 'reply_favorite'){
        $this->increment('reply_notification_count');
        }else if($instance->type == 'topic_favorite'){
        $this->increment('topic_notification_count');
        }else{
        $this->increment('notification_count');
        }


        $this->laravelNotify($instance);
    }
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->reply_notification_count = 0;
        $this->topic_notification_count = 0;
        $this->save();

//        $this->unreadNotifications->markAsRead();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar','weixin_openid', 'weixin_unionid','registration_id',   'weixin_session_key', 'weapp_openid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }


    public function read($topic)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($topic),
            \Carbon\Carbon::now()
        );
    }

    public function visitedThreadCacheKey($topic)
    {
        return $key = sprintf("users.%s.visits.%s",$this->id,$topic->id);
    }

    // Rest omitted for brevity

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
