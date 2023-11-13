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

# Auth user route
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-request-list', 'WEB\RequestListingController@getRequestsData');

# IPAD Login API
Route::post('login', 'API\UserController@ipad_Login');

# Group of Authenticated API's
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(array('namespace' => 'API'), function () {
        # Products module API's
        Route::prefix('/products')->group(function () {
            Route::get('/list', 'ProductMasterController@getList')->name('getList');
            Route::get('/pricing', 'ProductMasterController@getPricing')->name('getPricing');
        });
        # Customer management module API's
        Route::prefix('/customers')->group(function () {
            Route::get('/list', 'CustomerManagementController@getCustomers')->name('getCustomers');
            Route::post('/store', 'CustomerManagementController@storeCustomers')->name('storeCustomers');
        });
        # Master counts API's
        Route::prefix('/masters')->group(function () {
            Route::get('/count', 'MasterController@getCount')->name('getCount');
        });
        # Wishlist sync API
        Route::prefix('/wishlist')->group(function () {
            Route::post('/sync', 'WishlistController@sync_wishlist')->name('sync_wishlist');
        });
        # Cart sync API
        Route::prefix('/cart')->group(function () {
            Route::post('/sync', 'CartController@sync_cart')->name('sync_cart');
        });
        # Cloud Computing API's
        Route::prefix('/cloud')->group(function () {
            Route::get('/get-thumbnails', 'CloudComputingController@retrieve_thumbnails')->name('retrieve_thumbnails');
            Route::post('/get-original-image', 'CloudComputingController@retrieve_original_image')->name('retrieve_original_image');
            # Digital Ocean droplet snapshot [MAIL API]
            Route::post('/snapshot/success/mail', 'CloudComputingController@snapshot_success_mail')->name('snapshot_success_mail');
            # Digital Ocean droplet snapshot [MAIL API]
        });
        # Orders sync API
        Route::prefix('/orders')->group(function () {
            Route::post('/sync', 'OrderController@sync_orders')->name('sync_orders');
            Route::post('/details/sync', 'OrderController@sync_orders_details')->name('sync_orders_details');
        });
    });
});
