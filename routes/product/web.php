<?php

use Illuminate\Support\Facades\Route;
Route::middleware(['authorized'])->group(function (){
    Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
});
