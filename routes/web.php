<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [FoodController::class, 'index'])->name('home');
Route::get('/menu', [FoodController::class, 'menu'])->name('menu');
Route::get('/category/{category}', [FoodController::class, 'category'])->name('category');
Route::get('/cart', [CartController::class, 'history'])->name('cart.history');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/area/{area}', [FoodController::class, 'area'])->name('area');
Route::get('/search', [RestaurantSearchController::class, 'search'])->name('search.restaurants');

Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/debug', function() {
    dd(Auth::user());  // This will show if a user is logged in
});

require __DIR__.'/auth.php';
