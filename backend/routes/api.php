<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\outletController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\transactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [authController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/outlet', [outletController::class, 'index']);
    Route::get('/category',[outletController::class, 'getCategory']);
    Route::get('/products/{id?}', [outletController::class, 'showProductByOutlet']);
    Route::post('/payment/qris', [paymentController::class, 'qris']);
    Route::post('/sales',[transactionController::class ,'transaction']);
});

Route::get('/test-speed', function () {
    return response()->json('Hello World');
});