<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    // Route::put('/{id}', [ProductController::class, 'update']);
    // Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::post('/', [ContactController::class, 'store']);
    // Route::get('/{id}', [ContactController::class, 'show']);
    // Route::put('/{id}', [ContactController::class, 'update']);
    // Route::delete('/{id}', [ContactController::class, 'destroy']);
});

Route::prefix('collections')->group(function () {
    Route::get('/', [CollectionController::class, 'index']);
    Route::post('/', [CollectionController::class, 'store']);
    Route::get('/{id}', [CollectionController::class, 'show']);
    // Route::put('/{id}', [CollectionController::class, 'update']);
    // Route::delete('/{id}', [CollectionController::class, 'destroy']);
});
Route::prefix('orders')->group(function () {
    // Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    // Route::get('/{id}', [OrderController::class, 'show']);
    // Route::put('/{id}', [OrderController::class, 'update']);
    // Route::delete('/{id}', [OrderController::class, 'destroy']);
});
