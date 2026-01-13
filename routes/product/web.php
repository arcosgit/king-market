<?php

use Illuminate\Support\Facades\Route;
// Route::middleware(['authorized'])->group(function (){
// });

Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create')->middleware(['is_user_auth_and_have_business']);
Route::get('/show/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit')->middleware(['is_user_auth_and_have_business', 'is_user_owner_product']);
Route::get('/catalog', [App\Http\Controllers\ProductController::class, 'catalogShow'])->name('catalog.show');


