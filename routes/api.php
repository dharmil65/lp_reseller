<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterAPIController;
use App\Http\Controllers\Api\LoginAPIController;
use App\Http\Controllers\Api\MarketplaceAPIController;
use App\Http\Controllers\Api\ResellerPanelAPIController;

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

Route::middleware('auth.token')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('reg-client', [RegisterAPIController::class, 'clientRegister']);

Route::post('loginClient', [LoginAPIController::class, 'apiLogin']);

Route::get('/fetch-marketplace-data', [MarketplaceAPIController::class, 'fetchMarketplaceData']);

Route::post('/cart/store', [MarketplaceAPIController::class, 'cartStore'])->name('cart.store');

Route::get('/client-cart-data', [MarketplaceAPIController::class, 'cartShowEndClient'])->name('client-cart-data');

Route::post('/cart/fetch-cart-data', [MarketplaceAPIController::class, 'fetchEndClientCartData'])->name('cart.fetch-cart-data');

Route::post('/cart/provide-cart-data-end-client', [MarketplaceAPIController::class, 'provideCartDataEndClient'])->name('cart.provide-cart-data-end-client');

Route::post('/cart/add-quantity', [MarketplaceAPIController::class, 'addQuantityDataEndClient'])->name('cart.add-quantity');

Route::get('/cart/order-summary', [MarketplaceAPIController::class, 'endClientOrderSummary'])->name('cart.order-summary');

Route::get('/cart/place-order', [MarketplaceAPIController::class, 'endClientOrderPlace'])->name('cart.place-order');

Route::get('/fetch-client-orders', [MarketplaceAPIController::class, 'fetchClientOrders'])->name('client-orders');

Route::get('/reseller-order-data', [ResellerPanelAPIController::class, 'getResellerOrders'])->name('reseller-order-data');

Route::POST('/client-approval-to-complete', [MarketplaceAPIController::class, 'clientApprovalToComplete'])->name('client-approval-to-complete');