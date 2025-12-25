<?php

use Illuminate\Support\Facades\Route;


Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
