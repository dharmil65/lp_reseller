<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\AdvertiserController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:reseller'])->group(function () {
    Route::get('/reseller/dashboard', [ResellerController::class, 'index']);
});

Route::middleware(['auth', 'role:advertiser'])->group(function () {
    Route::get('/advertiser/dashboard', [AdvertiserController::class, 'index']);
});