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
        'middleware' => ['api.throttle','bindings'],
    ], function($api) {
        //登录/注册
        $api->group([
            'limit' => config('api.rate_limits.sign.limit'),
            'expires' => config('api.rate_limits.sign.expires'),
        ],function($api){


            // 短信验证码
            $api->post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');
            // 用户注册
            $api->post('users', 'UsersController@store')->name('api.users.store');
            // 图片验证码
            $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');
            // 第三方登录
            $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')->name('api.socials.authorizations.store');

            // 登录
            $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');

            // 刷新token
            $api->put('authorizations/current', 'AuthorizationsController@update')->name('api.authorizations.update');
            // 删除token
            $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');

            //微信小程序
            //登录
            $api->post('wechat/authorizations','AuthorizationsController@weChatStore')->name('api.wechat.authorizations.store');

            // 小程序注册
            $api->post('weapp/users', 'UsersController@weChatStore')
                ->name('api.weapp.users.store');
        });

        //访问
        $api->group([
            'limit' => config('api.rate_limits.access.limit'),
            'expires' => config('api.rate_limits.access.expires'),
        ],function($api){
            //登录访问的数据
            $api->group(['middleware' => 'api.auth'],function ($api){
                // 当前登录用户信息
                $api->get('user', 'UsersController@me')->name('api.user.show');
                // 编辑登录用户信息
                $api->patch('user', 'UsersController@update')->name('api.user.update');
                $api->put('user', 'UsersController@update')
                    ->name('api.user.update');
                // 图片资源
                $api->post('images', 'ImagesController@store')->name('api.images.store');
                // 发布话题
                $api->post('topics', 'TopicsController@store')->name('api.topics.store');
                //修改话题
                $api->patch('topics/{topic}', 'TopicsController@update')->name('api.topics.update');
                //删除
                $api->delete('topics/{topic}', 'TopicsController@destroy')->name('api.topics.destroy');

                //回复
                $api->post('topics/{topic}/replies', 'RepliesController@store')
                    ->name('api.topics.replies.store');

                // 删除回复
                $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                    ->name('api.topics.replies.destroy');

                // 通知统计
                $api->get('user/notifications/stats', 'NotificationsController@stats')
                    ->name('api.user.notifications.stats');
                // 通知列表
                $api->get('user/notifications', 'NotificationsController@index')
                    ->name('api.user.notifications.index');
                // 标记消息通知为已读
                $api->patch('user/read/notifications', 'NotificationsController@read')
                    ->name('api.user.notifications.read');
                $api->put('user/read/notifications', 'NotificationsController@read')
                    ->name('api.user.notifications.read.put');
                // 当前登录用户权限
                $api->get('user/permissions', 'PermissionsController@index')
                    ->name('api.user.permissions.index');
                // 用户详情
                $api->get('users/{user}', 'UsersController@show')
                    ->name('api.users.show');
                //添加回复点赞
                $api->get('reply/{reply}/favorite', 'FavoritesController@storeReply')
                    ->name('api.reply.favorite');
                //添加文章点赞
                $api->get('topic/{topic}/favorite', 'FavoritesController@storeTopic')
                    ->name('api.topic.favorite');
                //取消点赞

                //关注
                //取消关注

                //我的点赞
                //获取用户的点赞记录
                $api->post('users/{user}/favorite', 'FavoritesController@favoriteIndex')
                    ->name('api.favorite.users.index');

                //我的关注


            });
            //访客访问的数据
            // 游客可以访问的接口
            $api->get('categories', 'CategoriesController@index')
                ->name('api.categories.index');
            $api->get('topics', 'TopicsController@index')
                ->name('api.topics.index');
            $api->get('users/{user}/topics', 'TopicsController@userIndex')
                ->name('api.users.topics.index');
            $api->get('topics/{topic}', 'TopicsController@show')
                ->name('api.topics.show');
            // 话题回复列表
            $api->get('topics/{topic}/replies', 'RepliesController@index')
                ->name('api.topics.replies.index');
            // 某个用户的回复列表
            $api->get('users/{user}/replies', 'RepliesController@userIndex')
                ->name('api.users.replies.index');

            // 资源推荐
            $api->get('links', 'LinksController@index')
                ->name('api.links.index');

            // 活跃用户
            $api->get('actived/users', 'UsersController@activedIndex')
                ->name('api.actived.users.index');




        });

    });




});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
