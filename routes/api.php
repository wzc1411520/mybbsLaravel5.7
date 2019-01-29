<?php

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
],function($api) {



    $api->group([
        'middleware' => 'api.throttle',
    ], function($api) {
        //登录/注册
        $api->group([
            'limit' => config('api.rate_limits.sign.limit'),
            'expires' => config('api.rate_limits.sign.expires'),
        ],function($api){


            // 短信验证码
            $api->post('verificationCodes', 'VerificationCodesController@store')
                ->name('api.verificationCodes.store');
            // 用户注册
            $api->post('users', 'UsersController@store')
                ->name('api.users.store');
            // 图片验证码
            $api->post('captchas', 'CaptchasController@store')
                ->name('api.captchas.store');
        });

        //访问
        $api->group([
            'limit' => config('api.rate_limits.access.limit'),
            'expires' => config('api.rate_limits.access.expires'),
        ],function($api){

        });
    });




});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
