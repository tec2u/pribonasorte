<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NetworkController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Admin\CorporateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\EcommController;
use App\Http\Controllers\ShippingController;
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

Route::controller(PaymentController::class)->group(function () {
    Route::post('/notity', 'notity')->name('notity');//autentica login de usuarios
});

Route::controller(EcommController::class)->group(function () {
    Route::post('/finalize-ecomm', 'finalizeEcomm')->name('api.finalizeEcomm');
});

Route::controller(ShippingController::class)->group(function () {
    Route::post('/calculate/{userID}', 'calculateShippingPrice')->name('calculate');
});

Route::get('/verify-corporation-authenticity/{idCorporate}', [CorporateController::class, "verify"])->name("verify_corporation_authenticity");


Route::prefix('/app')->name('api.app')->group(function () {
    // sem token
    Route::controller(UserController::class)->group(function () {
        Route::post('/get/user', 'returnUser')->name('.returnUser');
        Route::post('/login', 'login')->name('.login');
    });

    // com token
    Route::middleware('token.auth')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('/register', 'register')->name('.register');
            Route::post('/update/user', 'updateUser')->name('.updateUser');
        });

        Route::controller(HomeController::class)->group(function () {
            Route::post('/home', 'home')->name('.home');
        });

        Route::controller(WithdrawController::class)->group(function () {
            Route::post('/withdraw', 'withdraw')->name('.withdraw');
        });

        Route::controller(NetworkController::class)->group(function () {
            Route::post('/networks/mycareer', 'myCareer')->name('.myCareer');
            Route::post('/networks', 'networks')->name('.networks');
            Route::post('/networks/mytree', 'mytree')->name('.mytree');
        });

        Route::controller(ReportsController::class)->group(function () {
            Route::post('/report', 'report')->name('.report');
            Route::post('/report/orderhistory', 'orderhistory')->name('.orderhistory');
            Route::post('/report/mysmartshipping', 'mysmartshipping')->name('.mysmartshipping');
            Route::post('/report/mysmartshipping/cancel', 'mySmartshippingCancel')->name('.mySmartshippingCancel');
            Route::post('/report/home', 'reportsHome')->name('.reportsHome');
            Route::post('/report/comissions', 'myComission')->name('.myComission');
            Route::post('/report/comissions/permonth', 'myComissionPerMonth')->name('.myComissionPerMonth');
            Route::post('/report/enrollments', 'enrollments')->name('.enrollments');
            Route::post('/report/myDirects', 'mydirects')->name('.mydirects');
            Route::post('/report/myorders/smartshipping', 'myOrdersSmartshipping')->name('.my_orders_smartshipping');
            Route::post('/newsmartshipping', 'newsmartshipping')->name('.newsmartshipping');
            Route::post('/report/newrank/Advancement', 'newRankAdvancement')->name('.new_rank_Advancement');
            Route::post('/report/myreferrals', 'myreferrals')->name('.myreferrals');
            Route::post('/report/enrolledcustomers', 'enrolledcustomers')->name('.enrolledcustomers');
            Route::post('/report/newrecruits', 'newrecruits')->name('.newrecruits');
            Route::post('/report/costumerrecruits', 'costumerrecruits')->name('.costumerrecruits');
            Route::post('/report/smartshippeople', 'smartshippeople')->name('.smartshippeople');
            Route::post('/report/volumereport', 'volumereport')->name('.volumereport');
            Route::post('/report/volumereportfilter', 'volumereportfilter')->name('.volumereportfilter');
            Route::post('/report/bonuslist', 'bonuslist')->name('.bonuslist');
            Route::post('/report/teamorders', 'teamorders')->name('.teamorders');
        });

        Route::controller(PurchaseController::class)->group(function () {
            Route::post('/packages', 'packages')->name('.packages');
            Route::post('/package', 'package')->name('.package');
            Route::post('/package/buy', 'buyPackage')->name('.buyPackage');

            Route::post('/products/new/smartshiping', 'newSmartshiping')->name('.newSmartshiping');
            Route::post('/products/new/smartshiping/add', 'newSmartshipingAdd')->name('.newSmartshipingAdd');

            Route::post('/products', 'products')->name('.products');
            Route::post('/product', 'product')->name('.product');
            Route::post('/cart/add', 'cartAdd')->name('.cartAdd');

            Route::post('/cart', 'cart')->name('.cart');

            Route::post('/cart/payment', 'payment')->name('.payment');
        });
    });
});
