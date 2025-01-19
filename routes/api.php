<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Api\User' , 'prefix' => 'user'], function () {
    Route::post('register', 'AuthController@register'); 
    Route::post('login', 'AuthController@login');
});


Route::group(['middleware' => ['user'], 'namespace' => 'Api\User' , 'prefix' => 'user'], function () {

    Route::get('products', 'ProductController@list'); 
    

    Route::prefix('orders')->group(function () {
        Route::get('/', 'OrderController@list'); 
        Route::get('payments', 'OrderController@list_of_payments'); 
        Route::post('store', 'OrderController@store'); 
        Route::patch('{order}/update', 'OrderController@update');
        Route::post('{order}/pay', 'OrderController@pay')->name('api.user.pay'); 
        Route::delete('{order}/delete', 'OrderController@destroy');

    });

    Route::post('/logout', 'AuthController@logout'); 
});

Route::group(['namespace' => 'Api\Admin' , 'prefix' => 'admin'], function () {
    Route::post('login', 'AuthController@login');
});

Route::group(['middleware' => ['admin'], 'namespace' => 'Api\Admin' , 'prefix' => 'admin'], function () {
    Route::get('orders', 'OrderController@list'); 
    Route::post('orders/{order}/update/status', 'OrderController@update_status');

    Route::get('payments', 'OrderController@list_of_payments'); 

    Route::post('/logout', 'AuthController@logout'); 

});