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
Route::post('/closest-waste-banks', 'Api\WasteBankController@getClosestWasteBanks');
//User Management
Route::middleware('auth:api')->group(function(){
    Route::get('/get-users', 'Api\UserController@index');
    Route::post('/get-user-data', 'Api\UserController@show');
    Route::post('/save-user-device', 'Api\UserController@saveUserToken');
    Route::get('/waste-banks', 'Api\WasteBankController@getData');
    Route::get('/check-category', 'Api\GeneralController@checkCategory');
    Route::get('/dws-category', 'Api\DwsWasteController@getData');
    Route::get('/masaro-category', 'Api\MasaroWasteController@getData');
    Route::post('/address', 'Api\UserController@getAddress');
    Route::post('/set-address', 'Api\UserController@setAddress');

    //Transactions
    Route::post('/get-transactions', 'Api\TransactionHeaderController@getTransactions');
    Route::post('/get-transaction-details', 'Api\TransactionHeaderController@getTransactionDetails');
    Route::post('/get-transaction-data', 'Api\TransactionHeaderController@getTransactionData');

    //Antar Sendiri
    Route::post('/antar-sendiri/admin/set-transaction', 'Api\TransactionHeaderController@setTransactionToUser');
    Route::post('/antar-sendiri/user/confirm', 'Api\TransactionHeaderController@confirmTransactionByUserAntarSendiri');
    Route::post('/antar-sendiri/user/cancel', 'Api\TransactionHeaderController@cancelTransactionByUserAntarSendiri');

    //On Demand
    Route::post('/on-demand/create', 'Api\TransactionHeaderController@createTransaction');
    Route::post('/on-demand/driver/confirm', 'Api\TransactionHeaderController@confirmTransactionByDriver');
    Route::post('/on-demand/user/confirm', 'Api\TransactionHeaderController@confirmTransactionByUser');

    //Pickup Routine
    Route::post('/pickup/user/confirm', 'Api\TransactionHeaderController@confirmTransactionByUserRoutinePickup');
    Route::post('/pickup/user/cancel', 'Api\TransactionHeaderController@cancelTransactionByUserRoutinePickup');

    //Voucher
    Route::get('/voucher-categories', 'Api\VoucherController@getCategories');
    Route::post('/vouchers', 'Api\VoucherController@get');

    //Routine Pickup
    Route::post('/routine-pickup', 'Api\UserController@changeRoutinePickup');
    Route::post('/waste-banks/get-schedules', 'Api\WasteBankController@getWasteBankSchedules');

    //Point
    Route::post('/redeem-poin', 'Api\PoinController@redeem');
});
//Route::post('/waste-banks/get-schedules', 'Api\WasteBankController@getWasteBankSchedules');
//Route::post('/on-demand/create', 'Api\TransactionHeaderController@createTransaction');
//Route::post('/routine-pickup', 'Api\UserController@changeRoutinePickup');

Route::post('/register', 'Api\RegisterController@register');
Route::get('/verifyemail/{token}', 'Api\RegisterController@verify');
Route::post('/fb-register', 'Api\RegisterController@facebookAuth');

//Waste Collector Transaction
Route::middleware('auth:waste_collector')->group(function(){
    Route::post('/waste-collector/get-data', 'Api\WasteCollectorController@show');
    Route::post('/user-list-pickup', 'Api\WasteCollectorController@getUserListRoutinePickUp');
    Route::post('/pickup/create', 'Api\WasteCollectorController@createTransactionRoutinePickup');
    Route::post('/waste-collector/transactions', 'Api\WasteCollectorController@getAllTransactions');

    Route::post('/waste-collector/on-demand/confirm', 'Api\WasteCollector@confirmOnDemandTransaction');

});

//Forgot Password
Route::post('/checkEmail', 'Api\ForgotPasswordController@checkEmail');
Route::post('/sendResetLinkEmail', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/setNewPassword', 'Api\ForgotPasswordController@setNewPassword');
Route::get('/registration', 'Api\RegisterController@registrationData');

Route::group(['namespace' => 'Api', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::post('forgotpassword', 'ForgotPasswordController@forgotPassword');
    Route::get('find/{token}', 'ForgotPasswordController@find');
    Route::post('reset', 'ForgotPasswordController@reset');
});

//Beta
Route::post('/subscribe', 'Api\SubscribeController@save');

//Coba2
Route::post('/test', 'Api\TransactionHeaderController@test');
