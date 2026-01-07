<?php

use Illuminate\Support\Facades\Route;
// Route::middleware(['authorized'])->group(function (){
// });

Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create')->middleware(['authorized', 'is_user_auth_and_have_business']);
Route::get('/show/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');


