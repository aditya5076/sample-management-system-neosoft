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

# Whitelisted Sutlej Connection authorised routes

use App\Models\ProductMaster;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['ip.whitelist.check']], function () {
    Route::group(['middleware' => 'revalidate.logout'], function () {
        # Auth Routes
        Auth::routes();
        Route::get('logout', 'Auth\LoginController@logout');

        # Default redirection route
        Route::get('/', function () {
            return redirect('/login');
        });

        # Default dashboard route.
        Route::get('/home', 'HomeController@index')->name('home');
    });

    # Authorised routes
    Route::group(['middleware' => ['auth', 'revalidate.logout']], function () {
        Route::group(array('namespace' => 'WEB'), function () {
            # User Management Module
            Route::prefix('/manage-users')->middleware('admin.acl')->group(function () {
                Route::get('/', 'UserManagementController@showUsers')->name('showUsers');
                Route::match(['get', 'post'], '{user_id}/delete', 'UserManagementController@inactivateUser')->name('inactivateUser');
                Route::get('{user_id}/edit', 'UserManagementController@editUser')->name('editUser');
                Route::get('/create', 'UserManagementController@createUser')->name('createUser');
                Route::post('/store', 'UserManagementController@storeUser')->name('storeUser');
                Route::post('{user_id}/update', 'UserManagementController@updateExistingUser')->name('updateExistingUser');
                Route::get('/export', 'UserManagementController@export_users')->name('export_users');
            });
            # Request Listing Module
            Route::prefix('/request-listing')->middleware('iu.restrict')->group(function () {
                Route::get('/', 'RequestListingController@showScreen')->name('showScreen');
                Route::post('/get-datatable', 'RequestListingController@getRequestsData')->name('getRequestsData');
                Route::post('/show-request', 'RequestListingController@showRequestDetails')->name('showRequestDetails');
                Route::match(['get', 'post'], '/barcode/modal-data', 'RequestListingController@getBarcodeModalData')->name('getBarcodeModalData');
                Route::post('/barcode/generate', 'RequestListingController@generateBarcodes')->name('generateBarcodes');
                Route::match(['get', 'post'], '/inward/modal-data', 'RequestListingController@getInwardModalData')->name('getInwardModalData');
                Route::post('/inward/generate', 'RequestListingController@generateInwards')->name('generateInwards');
                Route::post('/inward/edit', 'RequestListingController@showInwardDetails')->name('showInwardDetails');
                Route::post('/inward/edit/save', 'RequestListingController@editInwardData')->name('editInwardData');
                Route::match(['get', 'post'], '/outward/modal-data', 'RequestListingController@getOutwardModalData')->name('getOutwardModalData');
                Route::post('/outward/generate', 'RequestListingController@generateOutwards')->name('generateOutwards');
                Route::post('/show-product-image', 'RequestListingController@showProductImage')->name('showProductImage');
            });
            # Reports Module
            Route::prefix('/reports')->middleware('iu.restrict')->group(function () {
                Route::get('/', 'ReportsMasterController@show_reports')->name('show_reports');
                Route::match(['get', 'post'], '/generate', 'ReportsMasterController@generic_reports_generation')->name('generic_reports_generation');
            });
            # Image Compression Module
            Route::prefix('/compress')->middleware('iu.acl')->group(function () {
                Route::get('/', 'ImageCompressionController@uploadForm')->name('uploadForm');
                Route::post('/store', 'ImageCompressionController@compress_and_store')->name('compress_and_store');
            });
            # Stocks Listing Module
            Route::prefix('/stocks-listing')->middleware('iu.restrict')->group(function () {
                Route::get('/', 'StocksListingController@showStocks')->name('showStocks');
                Route::post('/get-stock-datatable', 'StocksListingController@getStocksData')->name('getStocksData');
                Route::post('/actions', 'StocksListingController@actionsProcedure')->name('actionsProcedure');
            });
            # Custom configurations module
            Route::prefix('/custom-config')->middleware('admin.acl')->group(function () {
                Route::get('/', 'CustomConfigController@showConfigScreen')->name('showConfigScreen');
                Route::post('/actions', 'CustomConfigController@eventActions')->name('eventActions');
                Route::post('/actions/add-whitelisted', 'CustomConfigController@add_whitelisted_ips')->name('add_whitelisted_ips');
            });

            Route::get('get-product-dropdown', function () {

                $data = [];
                $search = '';
                $product_masters = ProductMaster::query();

                dd(request()->all());
                if (isset(request()->quality_search)) {
                    $search = request()->quality_search;
                    $product_masters->select('quality as id', 'quality as text');
                    $product_masters->where('quality', "LIKE", "%$search%");
                }
                if (isset(request()->design_search)) {
                    $search = request()->design_search;
                    $product_masters->select('design_name as id', 'design_name as text');
                    $product_masters->where('design_name', "LIKE", "%$search%");
                }
                if (isset(request()->shade_search)) {
                    $search = request()->shade_search;
                    $product_masters->select('shade as id', 'shade as text');
                    $product_masters->where('shade', "LIKE", "%$search%");
                }

                $data['results'] = $product_masters->distinct()->get()->toArray();
                return response()->json($data);
            })->name('get-product-dropdown');
        });
        Route::group(array('namespace' => 'CRON'), function () {
            # Cron [Import procedures]
            Route::prefix('/import-data')->middleware('admin.acl')->group(function () {
                Route::get('/', 'ImportController@import_procedures')->name('import_procedures');
            });
        });
    });
});
