<?php

use Illuminate\Support\Facades\Route;


Route::middleware('set_locale')->group(function(){
    Route::post('/auth/store', [App\Http\Controllers\AuthController::class, 'store'])->name('user.store');
    Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login'])->name('user.login');
});
Route::middleware(['set_locale', 'authorized'])->group(function (){
    Route::post('/get', [App\Http\Controllers\UserController::class, 'getUser'])->name('user.get');
    Route::patch('/change/name', [App\Http\Controllers\UserController::class, 'changeName'])->name('user.change.name');
    Route::patch('/change/email', [App\Http\Controllers\UserController::class, 'changeEmail'])->name('user.change.email');
    Route::patch('/change/password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.change.password');
    Route::patch('/topup/balance', [App\Http\Controllers\UserController::class, 'topUpBalance'])->name('user.topup.balance');
    Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('user.logout');
    Route::post('/orders', [App\Http\Controllers\UserController::class, 'getOrders'])->name('user.get.orders');
});

