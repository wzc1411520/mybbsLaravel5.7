<?php

namespace App\Providers;

use App\Http\Requests\Api\SocialAuthorizationRequest;
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
            'SocialiteProviders\QQ\QqExtendSocialite@handle',

        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
