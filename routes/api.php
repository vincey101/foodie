<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add/{foodItem}', [CartController::class, 'add']);
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove']);
}); 