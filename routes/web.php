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

//Route::get('/', function () {
//    return view('welcome');
//});



Auth::routes();

Route::group(['prefix'=>'admin', 'middleware'=>['admin', 'auth'], 'namespace' => 'Admin'], function() {
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'admin.home']);
    Route::get('/links', ['uses' => 'HomeController@links', 'as' => 'admin.links']);
    Route::get('/links/{id}', ['uses' => 'HomeController@link_edit', 'as' => 'admin.link.edit']);
    Route::post('/links/update', ['uses' => 'HomeController@link_update', 'as' => 'admin.link.update']);
    Route::get('/users', ['uses' => 'HomeController@users', 'as' => 'admin.users']);
    Route::delete('/users/delete', ['uses' => 'HomeController@user_destroy', 'as' => 'admin.user.delete']);
    Route::delete('/users/disable', ['uses' => 'HomeController@user_disable', 'as' => 'admin.user.disable']);
    Route::post('/users/enable', ['uses' => 'HomeController@user_enable', 'as' => 'admin.user.enable']);
    Route::delete('/delete', ['uses' => 'HomeController@destroy', 'as' => 'admin.link.destroy']);
});

Route::group(['prefix'=>'profile', 'middleware'=>['auth'], 'namespace' => 'Profile'], function() {
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'profile.home']);

    Route::post('/link_update', ['uses' => 'HomeController@link_update', 'as' => 'profile.link.update']);
    Route::post('/update', ['uses' => 'HomeController@update', 'as' => 'profile.update']);
    Route::delete('/delete', ['uses' => 'HomeController@destroy', 'as' => 'profile.link.destroy']);
    Route::get('/links/{id}', ['uses' => 'HomeController@link_edit', 'as' => 'profile.link.edit']);
});

Route::group(['namespace' => 'Site'], function() {
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'site.home']);
    Route::match(['get', 'head'], 'links/{link_id}', ['uses' => 'HomeController@show', 'as' => 'site.link']);
    Route::get('/add', ['uses' => 'HomeController@add', 'as' => 'site.add']);
    Route::post('/store', ['uses' => 'HomeController@store', 'as' => 'site.store']);
    Route::get('/edit', ['uses' => 'HomeController@edit', 'as' => 'site.edit']);
    Route::post('/update', ['uses' => 'HomeController@update', 'as' => 'site.update']);
    Route::get('/{short_link}', ['uses' => 'HomeController@visit', 'as' => 'site.visit']);
});