<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Auth::routes();
Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth','verified']], function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::post('/dashboard/get', [App\Http\Controllers\DashboardController::class, 'dashboardInfo'])->name('dashboardInfo');

    Route::group(['middleware' => ['is-admin']], function () {
        /*************************** Master Products ************************************************/
        Route::get('/master_products', [App\Http\Controllers\MasterProductController::class, 'index'])->name('master_products');
        Route::get('/master_products/get', [App\Http\Controllers\MasterProductController::class, 'getMasterProduct']);
        Route::get('/master_products/add/{id?}', [App\Http\Controllers\MasterProductController::class, 'masterProductForm']);
        Route::post('/master_products/store', [App\Http\Controllers\MasterProductController::class, 'storeMasterProduct']);
        Route::get('/master_products/view/{id?}', [App\Http\Controllers\MasterProductController::class, 'masterProductVeiw']);
        Route::get('/master_products/delete/{id?}', [App\Http\Controllers\MasterProductController::class, 'destroy']);
        /***********************************************************************************************/

        /*************************** Subscription Terms ************************************************/
        Route::get('/subscription_terms', [App\Http\Controllers\SubscriptionTermController::class, 'index'])->name('subscription_terms');
        Route::get('/subscription_terms/get', [App\Http\Controllers\SubscriptionTermController::class, 'getSubscriptionTerms']);
        Route::get('/subscription_terms/add/{id?}', [App\Http\Controllers\SubscriptionTermController::class, 'SubscriptionTermForm']);
        Route::post('/subscription_terms/store', [App\Http\Controllers\SubscriptionTermController::class, 'storeSubscriptionTerm']);
        Route::get('/subscription_terms/delete/{id?}', [App\Http\Controllers\SubscriptionTermController::class, 'destroy']);
        /***********************************************************************************************/

        /*************************** Users ************************************************/
        Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users');
        Route::get('/users/get', [App\Http\Controllers\UsersController::class, 'getUsers']);
        Route::get('/users/add/{id?}', [App\Http\Controllers\UsersController::class, 'UserForm']);
        Route::post('/users/store', [App\Http\Controllers\UsersController::class, 'storeUser']);
        Route::get('/users/view/{id?}', [App\Http\Controllers\UsersController::class, 'UserView']);
        Route::get('/users/delete/{id?}', [App\Http\Controllers\UsersController::class, 'destroy']);
        /***********************************************************************************************/

    });
    /*************************** SafeLink ************************************************/
    Route::get('/create_safelink', [App\Http\Controllers\SafelinkController::class, 'index'])->name('create_safelink');
    Route::post('/safelink/getpayment', [App\Http\Controllers\SafelinkController::class, 'getPayment'])->name('getpayment');
    Route::post('/safelink/orderplaced', [App\Http\Controllers\SafelinkController::class, 'orderPlaced'])->name('orderplaced');
    Route::get('/safelink/thank-you/{rid}', [App\Http\Controllers\SafelinkController::class, 'thankYou'])->name('thankYou');
    Route::get('/safelink/cancel/{rid}', [App\Http\Controllers\SafelinkController::class, 'cancelOrder'])->name('cancelOrder');
    /***********************************************************************************************/

    /*************************** Billings Profile ************************************************/
    Route::get('/billings', [App\Http\Controllers\BillingController::class, 'index'])->name('billings');
    Route::get('/billings/get', [App\Http\Controllers\BillingController::class, 'getBilling']);
    Route::get('/billings/add/{id?}', [App\Http\Controllers\BillingController::class, 'BillingForm']);
    Route::post('/billings/store', [App\Http\Controllers\BillingController::class, 'storeBilling']);
    Route::get('/billings/view/{id?}', [App\Http\Controllers\BillingController::class, 'BillingView']);
    Route::get('/billings/delete/{id?}', [App\Http\Controllers\BillingController::class, 'destroy']);
    /***********************************************************************************************/

    /*************************** SDWAN  ************************************************/
    Route::get('/testSDWAN', [App\Http\Controllers\SafelinkController::class, 'testSDWAN'])->name('testSDWAN');
    Route::get('/testBond/{bid}', [App\Http\Controllers\SafelinkController::class, 'testBond'])->name('testBond');
    /***********************************************************************************************/
});
