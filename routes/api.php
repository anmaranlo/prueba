<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/getCities', 'OrderController@getCities');
    Route::get('/getProduct', 'OrderController@getProduct');
    Route::post('/getUsers', 'OrderController@getUsers');
    Route::post('/saveOrder', 'OrderController@saveOrder');
    Route::get('/getOrder', 'OrderController@getOrder');
    Route::post('/deleteOrder', 'OrderController@deleteOrder');
    Route::post('/editOrder', 'OrderController@editOrder');
    Route::get('/getStatuses', 'OrderController@getStatuses');
});