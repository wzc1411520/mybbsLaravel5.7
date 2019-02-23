<?php

namespace App\Http\Middleware;

use App\Models\Link;
use App\Models\User;
use Closure;

class categoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        \Cache::forget('category');
//        $category = \Cache::rememberForever('category',function (){
//            return \Auth::user()->categories->all();
//        });
        $user = new User();
        $link = new Link();
        view()->share('activeUsers',$user->getActiveUsers());
        view()->share('links',$link->getAllCached());
        if(\Auth::user())view()->share('categories',\Auth::user()->categories->all());

        return $next($request);
    }
}
