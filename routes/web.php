<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () {

    $topic = new  \App\Models\Topic();
    $user = new \App\Models\User();
//    $topic->find(1);

//    $favo = $topic->storeTopicFavorite();
    event(new \App\Events\TopicFavoriteEvents($topic->find(57),$user->find(1)));
//    $favorite = new \App\Models\Favorite();
//    $topic = $favorite->topic;
//    dd($favo);
});

//Auth::routes();
// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::group(['middleware'=>'auth'],function ($route){
    $route->get('/', 'TopicsController@index')->name('root');
    $route->resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
    $route->post('/replies/{reply}/favorites','FavoritesController@storeReply');
    $route->delete('/replies/{reply}/favorites','FavoritesController@deleteReply');
    $route->post('/topics/{topic}/favorites','FavoritesController@storeTopic');
    $route->delete('/topics/{topic}/favorites','FavoritesController@deleteTopic');
});

//web端第三方登錄
Route::get('socials/{social_type}/authorizations', 'AuthorizationsController@login')->name('weixin');

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);