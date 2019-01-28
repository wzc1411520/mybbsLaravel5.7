<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Encore\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
        if (class_exists(Config::class)) {
            Config::load();
        }
        //观察者监听
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		Reply::observe(\App\Observers\ReplyObserver::class);
		Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);

        \Carbon\Carbon::setLocale('zh');

        //共享数据

        $user = new User();
        $link = new Link();
        $category = \Cache::rememberForever('channels',function (){
            return Category::all();
        });
        \View::composer('*',function ($view)use($user,$link,$category){
            $view->with('activeUsers',$user->getActiveUsers());
            $view->with('links',$link->getAllCached());
            $view->with('categories',$category);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }
}
