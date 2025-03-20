<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterAPIController;
use App\Http\Controllers\Api\LoginAPIController;
use App\Http\Controllers\Api\MarketplaceAPIController;

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