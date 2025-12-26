<?php

use Illuminate\Support\Facades\Route;


Route::post('/change/lang', [App\Http\Controllers\UserController::class, 'changeLang'])->name('user.change.lang');
Route::post('/auth/store', [App\Http\Controllers\AuthController::class, 'store'])->name('user.store');
Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login'])->name('user.login');
Route::middleware('not_authorized')->group(function (){
    Route::post('/get', [App\Http\Controllers\UserController::class, 'getUser'])->name('user.get');
    Route::patch('/change/name', [App\Http\Controllers\UserController::class, 'changeName'])->name('user.change.name');
});

