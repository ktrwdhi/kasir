<?php

use App\Http\Controllers\admin\authController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\outletController;
use App\Http\Controllers\admin\productController;
use Illuminate\Support\Facades\Route;

Route::get('/',[authController::class,'isLoggedIn']);
Route::get('/login',[authController::class,'showLogin'])->name('login');
Route::post('/login',[authController::class,'login'])->name('login.store');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::get('/products',[productController::class,'index'])->name('products.index');
    Route::get('/outlets',[outletController::class,'index'])->name('outlet.index');
    Route::get('/outlet/{uuid}',[outletController::class,'show'])->name('outlet.show');
    Route::post('/logout',[authController::class,'logout'])->name('logout');
    Route::post('/product',[productController::class,'store'])->name('products.store');
});