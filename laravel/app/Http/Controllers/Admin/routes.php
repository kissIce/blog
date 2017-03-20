<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resources([
        'auth/member'       => 'MemberController',
    ]);
    $router->resources([
        'auth/article_cate'       => 'ArticleCateController',
    ]);
});
