<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('v1.')->group(function () {
    Route::prefix('auth')->as('auth.')->controller(AuthController::class)->group(function () {
        Route::middleware('throttle:auth')->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
        });

        Route::post('refresh', 'refresh')->name('refresh');

        Route::middleware('auth:api')->group(function () {
            Route::get('me', 'me')->name('me');
            Route::post('logout', 'logout')->name('logout');
        });
    });

    Route::middleware(['auth:api', 'throttle:api'])->group(function () {
        Route::apiResource('orders', OrderController::class);

        Route::get('orders/{order}/payments', [PaymentController::class, 'orderPayments'])->name('orders.payments.index');
        Route::post('orders/{order}/payments', [PaymentController::class, 'store'])->name('orders.payments.store');

        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
});
