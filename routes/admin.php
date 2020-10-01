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
//group 1
Route::group(['namespace'=>'Dashboard' , 'middleware' => 'guest:admin' ,  'prefix' => 'admin'] , function(){
    Route::get('login', 'LoginController@login')->name('admin.login');
    Route::post('login', 'LoginController@postlogin')->name('admin.post.login');  

});
//end group 1

///group 2
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
 ///////////// categories routes///////
    Route::group(['prefix'=>"MainCategories"] , function(){
        Route::get('index' ,'CategoryController@index') ->name('index.category');
        Route::get('create' ,'CategoryController@create') ->name('create.category');
        Route::post('store' ,'CategoryController@store') ->name('store.category');
        Route::get('edit/{id}' ,'CategoryController@edit') ->name('edit.category');
        Route::post('update/{id}' ,'CategoryController@update') ->name('update.category');
        Route::get('delete/{id}' ,'CategoryController@delete') ->name('delete.category');
    });
 /////////// end categories routes///////

 /* ///////////// sub-categories routes///////
  Route::group(['prefix'=>"SubCategories"] , function(){
    Route::get('index' ,'SubCategoryController@index') ->name('index.subcategory');
    Route::get('create' ,'SubCategoryController@create') ->name('create.subcategory');
    Route::post('store' ,'SubCategoryController@store') ->name('store.subcategory');
    Route::get('edit/{id}' ,'SubCategoryController@edit') ->name('edit.subcategory');
    Route::post('update/{id}' ,'SubCategoryController@update') ->name('update.subcategory');
    Route::get('delete/{id}' ,'SubCategoryController@delete') ->name('delete.subcategory');
});*/
/////////// end sub-categories routes///////

  ///////////// Brands routes///////
  Route::group(['prefix'=>"Brands"] , function(){
    Route::get('index' ,'BrandController@index') ->name('index.brand');
    Route::get('create' ,'BrandController@create') ->name('create.brand');
    Route::post('store' ,'BrandController@store') ->name('store.brand');
    Route::get('edit/{id}' ,'BrandController@edit') ->name('edit.brand');
    Route::post('update/{id}' ,'BrandController@update') ->name('update.brand');
    Route::get('delete/{id}' ,'BrandController@delete') ->name('delete.brand');
});
/////////// end Brands routes///////

  ///////////// tags routes///////
  Route::group(['prefix'=>"Tags"] , function(){
    Route::get('index' ,'TagsController@index') ->name('index.tag');
    Route::get('create' ,'TagsController@create') ->name('create.tag');
    Route::post('store' ,'TagsController@store') ->name('store.tag');
    Route::get('edit/{id}' ,'TagsController@edit') ->name('edit.tag');
    Route::post('update/{id}' ,'TagsController@update') ->name('update.tag');
    Route::get('delete/{id}' ,'TagsController@delete') ->name('delete.tag');
});
/////////// end tags routes///////

  ///////////// product routes///////
  Route::group(['prefix'=>"Product"] , function(){
    Route::get('index' ,'ProductController@index') ->name('index.product');
    Route::get('create' ,'ProductController@create') ->name('create.product');
    Route::post('store' ,'ProductController@store') ->name('store.product');
    Route::get('edit/{id}' ,'ProductController@edit') ->name('edit.product');
    Route::post('update/{id}' ,'ProductController@update') ->name('update.product');
    Route::get('delete/{id}' ,'ProductController@delete') ->name('delete.product');
});
/////////// end tags routes///////

});
//end group2

});

