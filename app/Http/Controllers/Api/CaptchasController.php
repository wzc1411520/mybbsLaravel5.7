<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use SocialiteProviders\Weixin\Provider;
use SocialiteProviders\Weixin\WeixinExtendSocialite;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request,CaptchaBuilder $captchaBuilder)
    {
        //缓存key
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        //创建验证码
        $captcha = $captchaBuilder->build();
        //设置有效时间
        $expiredAt = now()->addMinutes(2);

        //加入缓存
        \Cache::put($key,['phone'=>$phone,'code'=>$captcha->getPhrase()],$expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result);

    }
}
