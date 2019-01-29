<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    public function login($type)
    {
        //安裝SocialiteProviders库
        //配置.env文件

        //从获取code直接跳转的重定向路由 完成第三方授权
        return \Socialite::with($type)->redirect();
    }

    //回调方法获取用户信息/编写业务代码
    public function callback($type)
    {
        $user = Socialite::driver('QQ')->user();
        dd($user);
    }
}
