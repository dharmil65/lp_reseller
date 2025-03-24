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
    return redirect()->route('register');
});

Route::get('register', [RegisterController::class, 'showClientRegisterForm'])
    ->name('register');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('/marketplace', [MarketplaceController::class, 'marketplaceView'])->name('marketplace');

Route::get('/logout', [MarketplaceController::class, 'logout'])->name('logout');

Route::get('/cart', [MarketplaceController::class, 'cartDetailPageView'])->name('cart');

Route::get('reseller-home', [LoginController::class, 'resellerHomepage'])->name('reseller-home');

Route::get('orders', [MarketplaceController::class, 'clientOrdersView'])->name('orders');

Route::get('reseller_orders', [ResellerPanelController::class, 'resellerOrdersView'])->name('reseller_orders');