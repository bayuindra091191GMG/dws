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

Route::get('/', 'Frontend\HomeController@index')->name('home');
Route::get('/test-email', 'Frontend\HomeController@testEmail')->name('testEmail');
Route::get('/test-location', 'Frontend\HomeController@getLocation')->name('getLocation');
Route::get('/test-province', 'Frontend\HomeController@getProvince')->name('getProvince');
// ADMIN ROUTE
// ====================================================================================================================

Route::prefix('admin')->group(function(){
    Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

    // Setting
    Route::get('/setting', 'Admin\AdminController@showSetting')->name('admin.setting');
    Route::post('/setting-update', 'Admin\AdminController@saveSetting')->name('admin.setting.update');

    // Admin User
    Route::get('/admin-users', 'Admin\AdminUserController@index')->name('admin.admin-users.index');
    Route::get('/admin-users/create', 'Admin\AdminUserController@create')->name('admin.admin-users.create');
    Route::post('/admin-users/store', 'Admin\AdminUserController@store')->name('admin.admin-users.store');
    Route::get('/admin-users/edit/{item}', 'Admin\AdminUserController@edit')->name('admin.admin-users.edit');
    Route::post('/admin-users/update', 'Admin\AdminUserController@update')->name('admin.admin-users.update');
    Route::post('/admin-users/delete', 'Admin\AdminUserController@destroy')->name('admin.admin-users.destroy');

    // User
    Route::get('/users', 'Admin\UserController@index')->name('admin.users.index');
    Route::get('/users/create', 'Admin\UserController@create')->name('admin.users.create');
    Route::post('/users/store', 'Admin\UserController@store')->name('admin.users.store');
    Route::get('/users/edit/{item}', 'Admin\UserController@edit')->name('admin.users.edit');
    Route::post('/users/update', 'Admin\UserController@update')->name('admin.users.update');
    Route::post('/users/delete', 'Admin\UserController@destroy')->name('admin.users.destroy');

    // Category
    Route::get('/categories', 'Admin\CategoryController@index')->name('admin.categories.index');
    Route::get('/categories/create', 'Admin\CategoryController@create')->name('admin.categories.create');
    Route::post('/categories/store', 'Admin\CategoryController@store')->name('admin.categories.store');
    Route::get('/categories/edit/{item}', 'Admin\CategoryController@edit')->name('admin.categories.edit');
    Route::post('/categories/update', 'Admin\CategoryController@update')->name('admin.categories.update');
    Route::post('/categories/delete', 'Admin\CategoryController@destroy')->name('admin.categories.destroy');

    // Contact Message
    Route::get('/contact-messages', 'Admin\ContactMessageController@index')->name('admin.contact-messages.index');

    // Subscribes
    Route::get('/subscribes', 'Admin\SubscribeController@index')->name('admin.subscribes.index');

    // FAQ
    Route::get('/faqs', 'Admin\FaqController@index')->name('admin.faqs.index');
    Route::get('/faqs/create', 'Admin\FaqController@create')->name('admin.faqs.create');
    Route::post('/faqs/store', 'Admin\FaqController@store')->name('admin.faqs.store');
    Route::get('/faqs/edit/{item}', 'Admin\FaqController@edit')->name('admin.faqs.edit');
    Route::post('/faqs/update', 'Admin\FaqController@update')->name('admin.faqs.update');
    Route::post('/faqs/delete', 'Admin\FaqController@destroy')->name('admin.faqs.destroy');

    // Product
    Route::get('/product/', 'Admin\ProductController@index')->name('admin.product.index');
    Route::get('/product/show/{item}', 'Admin\ProductController@show')->name('admin.product.show');
    Route::get('/product/create', 'Admin\ProductController@create')->name('admin.product.create');
    Route::post('/product/store', 'Admin\ProductController@store')->name('admin.product.store');

    Route::get('/product/create-customize/{item}', 'Admin\ProductController@createCustomize')->name('admin.product.create.customize');
    Route::post('/product/store-customize/{item}', 'Admin\ProductController@storeCustomize')->name('admin.product.store.customize');
    Route::get('/product/edit-customize/{item}', 'Admin\ProductController@editCustomize')->name('admin.product.edit.customize');
    Route::post('/product/update-customize/{item}', 'Admin\ProductController@updateCustomize')->name('admin.product.update.customize');
    Route::get('/product/edit/{item}', 'Admin\ProductController@edit')->name('admin.product.edit');

    // Wastebanks
    Route::get('/waste-banks', 'Admin\WasteBankController@index')->name('admin.waste-banks.index');
    Route::get('/waste-banks/create', 'Admin\WasteBankController@create')->name('admin.waste-banks.create');
    Route::get('/waste-banks/create/masaro', 'Admin\WasteBankController@createMasaro')->name('admin.waste-banks.create.masaro');
    Route::post('/waste-banks/store', 'Admin\WasteBankController@store')->name('admin.waste-banks.store');
    Route::get('/waste-banks/edit/{item}', 'Admin\WasteBankController@edit')->name('admin.waste-banks.edit');
    Route::post('/waste-banks/update', 'Admin\WasteBankController@update')->name('admin.waste-banks.update');
    Route::post('/waste-banks/delete', 'Admin\WasteBankController@destroy')->name('admin.waste-banks.destroy');

    // Dws Waste
    Route::get('/dws-wastes', 'Admin\DwsWasteController@index')->name('admin.dws-wastes.index');
    Route::get('/dws-wastes/create', 'Admin\DwsWasteController@create')->name('admin.dws-wastes.create');
    Route::post('/dws-wastes/store', 'Admin\DwsWasteController@store')->name('admin.dws-wastes.store');
    Route::get('/dws-wastes/edit/{item}', 'Admin\DwsWasteController@edit')->name('admin.dws-wastes.edit');
    Route::post('/dws-wastes/update', 'Admin\DwsWasteController@update')->name('admin.dws-wastes.update');
    Route::post('/dws-wastes/delete', 'Admin\DwsWasteController@destroy')->name('admin.dws-wastes.destroy');

    // Dws Waste Items
    Route::get('/dws-waste-items', 'Admin\DwsWasteItemController@index')->name('admin.dws-waste-items.index');
    Route::get('/dws-waste-items/create', 'Admin\DwsWasteItemController@create')->name('admin.dws-waste-items.create');
    Route::post('/dws-waste-items/store', 'Admin\DwsWasteItemController@store')->name('admin.dws-waste-items.store');
    Route::get('/dws-waste-items/edit/{item}', 'Admin\DwsWasteItemController@edit')->name('admin.dws-waste-items.edit');
    Route::post('/dws-waste-items/update', 'Admin\DwsWasteItemController@update')->name('admin.dws-waste-items.update');
    Route::post('/dws-waste-items/delete', 'Admin\DwsWasteItemController@destroy')->name('admin.dws-waste-items.destroy');

    // Masaro Waste
    Route::get('/masaro-wastes', 'Admin\MasaroWasteController@index')->name('admin.masaro-wastes.index');
    Route::get('/masaro-wastes/create', 'Admin\MasaroWasteController@create')->name('admin.masaro-wastes.create');
    Route::post('/masaro-wastes/store', 'Admin\MasaroWasteController@store')->name('admin.masaro-wastes.store');
    Route::get('/masaro-wastes/edit/{item}', 'Admin\MasaroWasteController@edit')->name('admin.masaro-wastes.edit');
    Route::post('/masaro-wastes/update', 'Admin\MasaroWasteController@update')->name('admin.masaro-wastes.update');
    Route::post('/masaro-wastes/delete', 'Admin\MasaroWasteController@destroy')->name('admin.masaro-wastes.destroy');

    // Dws Waste Items
    Route::get('/masaro-waste-items', 'Admin\MasaroWasteItemController@index')->name('admin.masaro-waste-items.index');
    Route::get('/masaro-waste-items/create', 'Admin\MasaroWasteItemController@create')->name('admin.masaro-waste-items.create');
    Route::post('/masaro-waste-items/store', 'Admin\MasaroWasteItemController@store')->name('admin.masaro-waste-items.store');
    Route::get('/masaro-waste-items/edit/{item}', 'Admin\MasaroWasteItemController@edit')->name('admin.masaro-waste-items.edit');
    Route::post('/masaro-waste-items/update', 'Admin\MasaroWasteItemController@update')->name('admin.masaro-waste-items.update');
    Route::post('/masaro-waste-items/delete', 'Admin\MasaroWasteItemController@destroy')->name('admin.masaro-waste-items.destroy');

    // Antar Sendiri Transactions
    Route::get('/transactions/antar_sendiri', 'Admin\TransactionHeaderController@index')->name('admin.transactions.antar_sendiri.index');
    Route::get('/transactions/antar_sendiri/show/{id}', 'Admin\TransactionHeaderController@show')->name('admin.transactions.antar_sendiri.show');
    Route::get('/transactions/antar_sendiri/dws/create', 'Admin\TransactionHeaderController@createDws')->name('admin.transactions.antar_sendiri.dws.create');
    Route::get('/transactions/antar_sendiri/masaro/create', 'Admin\TransactionHeaderController@createMasaro')->name('admin.transactions.antar_sendiri.masaro.create');
    Route::post('/transactions/antar_sendiri/store', 'Admin\TransactionHeaderController@store')->name('admin.transactions.antar_sendiri.store');
    Route::get('/transactions/antar_sendiri/dws/edit/{id}', 'Admin\TransactionHeaderController@editDws')->name('admin.transactions.antar_sendiri.dws.edit');
    Route::get('/transactions/antar_sendiri/masaro/edit/{id}', 'Admin\TransactionHeaderController@editMasaro')->name('admin.transactions.antar_sendiri.masaro.edit');
    Route::post('/transactions/antar_sendiri/update/{id}', 'Admin\TransactionHeaderController@update')->name('admin.transactions.antar_sendiri.update');

    // On Demand Transactions
    Route::get('/transactions/on_demand', 'Admin\TransactionHeaderOnDemandController@index')->name('admin.transactions.on_demand.index');
    Route::get('/transactions/on_demand/show/{id}', 'Admin\TransactionHeaderOnDemandController@show')->name('admin.transactions.on_demand.show');
    Route::get('/transactions/on_demand/dws/create', 'Admin\TransactionHeaderOnDemandController@createDws')->name('admin.transactions.on_demand.dws.create');
    Route::get('/transactions/on_demand/masaro/create', 'Admin\TransactionHeaderOnDemandController@createMasaro')->name('admin.transactions.on_demand.masaro.create');
    Route::post('/transactions/on_demand/store', 'Admin\TransactionHeaderOnDemandController@store')->name('admin.transactions.on_demand.store');
    Route::get('/transactions/on_demand/dws/edit/{id}', 'Admin\TransactionHeaderOnDemandController@editDws')->name('admin.transactions.dws.on_demand.edit');
    Route::get('/transactions/on_demand/masaro/edit/{id}', 'Admin\TransactionHeaderOnDemandController@editMasaro')->name('admin.transactions.on_demand.masaro.edit');
    Route::post('/transactions/on_demand/update/{id}', 'Admin\TransactionHeaderOnDemandController@update')->name('admin.transactions.on_demand.update');

    // Rutin Transactions
    Route::get('/transactions/penjemputan_rutin', 'Admin\TransactionHeaderOnDemandController@index')->name('admin.transactions.penjemputan_rutin.index');
    Route::get('/transactions/penjemputan_rutin/show/{id}', 'Admin\TransactionHeaderOnDemandController@show')->name('admin.transactions.penjemputan_rutin.show');
    Route::get('/transactions/penjemputan_rutin/dws/create', 'Admin\TransactionHeaderOnDemandController@createDws')->name('admin.transactions.penjemputan_rutin.dws.create');
    Route::get('/transactions/penjemputan_rutin/masaro/create', 'Admin\TransactionHeaderOnDemandController@createMasaro')->name('admin.transactions.penjemputan_rutin.masaro.create');
    Route::post('/transactions/penjemputan_rutin/store', 'Admin\TransactionHeaderOnDemandController@store')->name('admin.transactions.penjemputan_rutin.store');
    Route::get('/transactions/penjemputan_rutin/dws/edit/{id}', 'Admin\TransactionHeaderOnDemandController@editDws')->name('admin.transactions.dws.penjemputan_rutin.edit');
    Route::get('/transactions/penjemputan_rutin/masaro/edit/{id}', 'Admin\TransactionHeaderOnDemandController@editMasaro')->name('admin.transactions.penjemputan_rutin.masaro.edit');
    Route::post('/transactions/penjemputan_rutin/update/{id}', 'Admin\TransactionHeaderOnDemandController@update')->name('admin.transactions.penjemputan_rutin.update');

    // Points
    Route::get('/points', 'Admin\PointController@index')->name('admin.points.index');
    Route::get('/points/show/{id}', 'Admin\PointController@show')->name('admin.points.show');
});

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

Route::view('/send-email', 'auth.send-email');

// Datatables
Route::get('/datatables-admin-users', 'Admin\AdminUserController@getIndex')->name('datatables.admin_users');
Route::get('/datatables-admin-products', 'Admin\ProductController@getIndex')->name('datatables.admin_products');
Route::get('/datatables-users', 'Admin\UserController@getIndex')->name('datatables.users');
Route::get('/datatables-categories', 'Admin\CategoryController@getIndex')->name('datatables.categories');
Route::get('/datatables-currencies', 'Admin\CurrencyController@getIndex')->name('datatables.currencies');
Route::get('/datatables-store-addresses', 'Admin\StoreAddressController@getIndex')->name('datatables.store-addresses');
Route::get('/datatables-contact-message', 'Admin\ContactMessageController@getIndex')->name('datatables.contact-message');
Route::get('/datatables-subscribes', 'Admin\SubscribeController@getIndex')->name('datatables.subscribes');
Route::get('/datatables-vouchers', 'Admin\VoucherController@getIndex')->name('datatables.vouchers');
Route::get('/datatables-faqs', 'Admin\FaqController@getIndex')->name('datatables.faqs');
Route::get('/datatables-waste-banks', 'Admin\WasteBankController@getIndex')->name('datatables.waste-banks');
Route::get('/datatables-dws-wastes', 'Admin\DwsWasteController@getIndex')->name('datatables.dws-wastes');
Route::get('/datatables-masaro-wastes', 'Admin\MasaroWasteController@getIndex')->name('datatables.masaro-wastes');
Route::get('/datatables-transactions-antar-sendiri', 'Admin\TransactionHeaderController@getIndex')->name('datatables.antar_sendiri.transactions');
Route::get('/datatables-transactions-on-demand', 'Admin\TransactionHeaderOnDemandController@getIndex')->name('datatables.on_demand.transactions');
Route::get('/datatables-dws-waste-items', 'Admin\DwsWasteItemController@getIndex')->name('datatables.dws-waste-items');
Route::get('/datatables-masaro-waste-items', 'Admin\MasaroWasteItemController@getIndex')->name('datatables.masaro-waste-items');
Route::get('/datatables-points', 'Admin\PointController@getIndex')->name('datatables.points');

// Select2
Route::get('/select-roles', 'Admin\RoleController@getRoles')->name('select.roles');
Route::get('/select-categories', 'Admin\CategoryController@getCategories')->name('select.categories');
Route::get('/select-products', 'Admin\ProductController@getProducts')->name('select.products');
Route::get('/select-admin-users', 'Admin\AdminUserController@getAdminUsers')->name('select.admin-users');
Route::get('/select-dws-categories', 'Admin\DwsWasteController@getDwsCategories')->name('select.dws-categories');
Route::get('/select-masaro-categories', 'Admin\MasaroWasteController@getMasaroCategories')->name('select.masaro-categories');

// Third Party API
Route::get('/update-currency', 'Admin\CurrencyController@getCurrenciesUpdate')->name('update-currencies');

// Email Aauth
Route::get('/request-verification/{email}', 'Auth\RegisterController@RequestVerification')->name('request-verification');