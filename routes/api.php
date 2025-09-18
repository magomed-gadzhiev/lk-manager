<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Доступы: manager может создавать заказ и искать клиента; head — только читать заказы
//    Route::get('/customers', [OrderController::class, 'findCustomer'])->middleware('role:manager,head');
//    Route::get('/orders', [OrderController::class, 'index'])->middleware('role:head');
//    Route::post('/orders', [OrderController::class, 'store'])->middleware('role:manager');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
