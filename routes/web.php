<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ResellerPanelController;

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

Route::get('/', function () {
    return redirect()->route('register-client-form');
});

Route::get('register-client', [RegisterController::class, 'showClientRegisterForm'])
    ->name('register-client-form');

Route::get('login-client', [LoginController::class, 'showLoginForm'])->name('login-client');

Route::get('/client_marketplace', [MarketplaceController::class, 'marketplaceView'])->name('client_marketplace');

Route::get('/logout', [MarketplaceController::class, 'logout'])->name('logout');

Route::get('/cart', [MarketplaceController::class, 'cartDetailPageView'])->name('cart');

Route::get('reseller-home', [LoginController::class, 'resellerHomepage'])->name('reseller-home');

Route::get('client_orders', [MarketplaceController::class, 'clientOrdersView'])->name('client_orders');

Route::get('reseller_orders', [ResellerPanelController::class, 'resellerOrdersView'])->name('reseller_orders');