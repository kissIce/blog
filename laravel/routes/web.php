<?php
use Illuminate\Routing\Router;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace'=>'Admin\Controllers','prefix'=>'admin'],function (Router $router) {
    $router->get('code/{id?}','PublicController@code',function ($id) {
    })->where('id','[0-9]+');

});
