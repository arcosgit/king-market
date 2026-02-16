<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized'])->group(function (){
    Route::post('/get', [App\Http\Controllers\BusinessController::class, 'getBusiness'])->name('business.get');
    Route::post('/create', [App\Http\Controllers\BusinessController::class, 'create'])->name('business.create');
    Route::patch('/change/name', [App\Http\Controllers\BusinessController::class, 'changeName'])->name('business.change.name');
    Route::post('/products', [App\Http\Controllers\BusinessController::class, 'getProducts'])->name('business.products');
});

Route::middleware('set_locale')->group(function (){
    Route::post('/products/{id}', [App\Http\Controllers\BusinessController::class, 'getProductsFromName'])->name('business.products.from.name');
});

