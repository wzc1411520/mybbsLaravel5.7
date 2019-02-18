<?php

namespace App\Providers;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Listeners\FavoriteEventSubscriber;
use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Weixin\WeixinExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Verified::class => [
            \App\Listeners\EmailVerified::class,
        ],
        //添加第三方登录的时间监听
        SocialiteWasCalled::class=>[
            //添加 微信登录
            'SocialiteProviders\Weixin\WeixinExtendSocialite@handle',

            //添加qq登录
//            'SocialiteProviders\QQ\QqExtendSocialite@handle',

        ],
        'eloquent.created: Illuminate\Notifications\DatabaseNotification' => [
            'App\Listeners\PushNotification',
        ],

//        'App\Events\Topic' => [
//            'App\Listeners\Favorite',
//        ],
    ];

    protected $subscribe = [
        'App\Listeners\FavoriteEventListeners'
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //观察者监听
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        Reply::observe(\App\Observers\ReplyObserver::class);
        Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);

    }
}
