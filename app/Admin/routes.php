 <?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('user', 'UsersController');
    $router->resource('topic', 'TopicsController');
    $router->resource('category', 'CategoriesController');
    $router->resource('reply', 'RepliesController');
    $router->resource('links', 'LinksController');

});
