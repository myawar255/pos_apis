<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseCartController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::get('docs/openapi', function () {
        $path = public_path('api-docs/openapi.yaml');
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/yaml',
        ]);
    });

    Route::middleware('auth.api')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('products', ProductController::class);
        Route::patch('products/{product}/quantity', [ProductController::class, 'updateQuantity']);

        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('suppliers', SupplierController::class);

        Route::get('settings', [SettingController::class, 'index']);
        Route::put('settings', [SettingController::class, 'update']);

        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart', [CartController::class, 'store']);
        Route::patch('cart/{product}', [CartController::class, 'update']);
        Route::delete('cart/{product}', [CartController::class, 'destroy']);
        Route::delete('cart', [CartController::class, 'empty']);

        Route::get('purchase-cart', [PurchaseCartController::class, 'index']);
        Route::post('purchase-cart', [PurchaseCartController::class, 'store']);
        Route::patch('purchase-cart/{product}', [PurchaseCartController::class, 'update']);
        Route::delete('purchase-cart/{product}', [PurchaseCartController::class, 'destroy']);
        Route::delete('purchase-cart', [PurchaseCartController::class, 'empty']);

        Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
        Route::post('orders/{order}/payments', [OrderPaymentController::class, 'store']);
    });
});
