<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
// prefix admin


Route::group(
[
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

Route::group(['namespace'=>'Dashboard' , 'middleware' => 'guest:admin' ,  'prefix' => 'admin'] , function(){
    Route::get('login', 'LoginController@login')->name('admin.login');
    Route::post('login', 'LoginController@postlogin')->name('admin.post.login');  

});

Route::group(['namespace'=>'Dashboard'  , 'middleware' => 'auth:admin' ,   'prefix' => 'admin'] , function(){
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    Route::get('logout', 'LogoutController@logout')->name('admin.logout');  

    Route::group(['prefix'=>"settings"] , function(){
        Route::get('shipping-methods/{type}' ,'SettingController@editShippingMethods') ->name('edit.shippings.methods');
        Route::put('shipping-methods/{id}' ,'SettingController@updateShippingMethods') ->name('update.shippings.methods');
    });

    Route::group(['prefix'=>"profile"] , function(){
        Route::get('edit' ,'ProfileController@edit') ->name('edit.profile');
        Route::put('update' ,'ProfileController@update') ->name('update.profile');
    });
});
});

