<?php

use Illuminate\Support\Facades\Route;


Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
Route::get('/orders', [App\Http\Controllers\UserController::class, 'orders'])->name('user.orders');
Route::get('/favorite', [App\Http\Controllers\UserController::class, 'favoriteProductsShow'])->name('user.favorite.product');
