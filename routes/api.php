<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentGatewaysController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\Simulator\GatewaySimulatorController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('items', ItemsController::class);
    Route::apiResource('orders', OrdersController::class);

    Route::prefix('gateways')->group(function () {

        Route::get('/list', [PaymentGatewaysController::class, 'index']);

        Route::post('/create/{gateway}', [PaymentGatewaysController::class, 'store']);

        Route::put('/update/{gateway}', [PaymentGatewaysController::class, 'update']);

        Route::delete('/delete/{gateway}', [PaymentGatewaysController::class, 'destroy']);
    });

    Route::prefix('payments')->group(function () {
        Route::post('/pay', [PaymentsController::class, 'pay']);
    });

    Route::prefix('simulator')->group(function () {
        Route::post('/gateways', [GatewaySimulatorController::class, 'generate']);
        Route::get('/gateways', [GatewaySimulatorController::class, 'list']);
    });
});