<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/users', 'Api\UserController@index');
Route::get('/noauth/users', 'Api\UserController@index');

//User Management
Route::middleware('auth:api')->group(function(){
    Route::get('/get-users', 'Api\UserController@index');
    Route::post('/get-user-data', 'Api\UserController@show');
    Route::get('/registration', 'Api\RegisterController@registrationData');
    Route::get('/waste-banks', 'Api\WasteBankController@getData');
    Route::get('/check-category', 'Api\GeneralController@checkCategory');
    Route::get('/dws-category', 'Api\DwsWasteController@getData');
    Route::get('/masaro-category', 'Api\MasaroWasteController@getData');
});

Route::post('/register', 'Api\RegisterController@register');
