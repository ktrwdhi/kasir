<?php

use App\Http\Controllers\admin\authController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\outletController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\transactionController;
use App\Http\Controllers\admin\userController;
use Illuminate\Support\Facades\Route;

Route::get('/',[authController::class,'isLoggedIn']);
Route::get('/login',[authController::class,'showLogin'])->name('login');
Route::post('/login',[authController::class,'login'])->name('login.store');
Route::get('/user-edit/{uuid}',[userController::class,'edit'])->name('user.edit');
Route::put('/user-update',[userController::class,'update'])->name('user.update');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::get('/products',[productController::class,'index'])->name('products.index');
    Route::get('/outlets',[outletController::class,'index'])->name('outlet.index');
    Route::post('/outlet',[outletController::class,'store'])->name('outlet.store');
    Route::get('/outlet/{uuid}',[outletController::class,'show'])->name('outlet.show');
    Route::post('/logout',[authController::class,'logout'])->name('logout');
    Route::post('/product',[productController::class,'store'])->name('products.store');
    Route::get('/product/{id}',[outletController::class,'showOutletByProduct'])->name('product.show');
    Route::patch('/products/{id}', [productController::class, 'update'])->name('products.update');
    Route::post('/admin/products/attach', [productController::class, 'attach'])->name('products.attach');
    Route::get('/transactions', [transactionController::class,'index'])->name('transactions.index');
    Route::get('/users', [userController::class,'index'])->name('users.index');
    Route::post('/varian/store',[productController::class,'storeVarian'])->name('varian.store');
    Route::post('/users',[userController::class,'store'])->name('users.store');
});
