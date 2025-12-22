<?php

use Illuminate\Support\Facades\Route;


Route::post('/change/lang', [App\Http\Controllers\UserController::class, 'changeLang'])->name('user.change.lang');
Route::post('/auth/store', [App\Http\Controllers\AuthController::class, 'store'])->name('user.store');
