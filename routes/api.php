<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingsController;

// Categories
Route::apiResource('categories', CategoryController::class);

// Products
Route::apiResource('products', ProductController::class);
Route::post('products/{id}/image', [ProductController::class, 'uploadImage']);
Route::delete('products/{id}/image', [ProductController::class, 'deleteImage']);

// Tables
Route::apiResource('tables', TableController::class);

// Orders
Route::apiResource('orders', OrderController::class);
Route::post('orders/{id}/send-to-kitchen', [OrderController::class, 'sendToKitchen']);
Route::post('orders/{id}/complete', [OrderController::class, 'complete']);

// Payments
Route::apiResource('payments', PaymentController::class);

// Settings
Route::get('settings', [SettingsController::class, 'index']);
Route::patch('settings', [SettingsController::class, 'update']);
Route::get('settings/snapshot', [SettingsController::class, 'snapshot']);

