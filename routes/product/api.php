<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized'])->group(function (){
    Route::post('/store', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
    Route::post('/temporary/save/img', [App\Http\Controllers\ProductController::class, 'temporarySaveImg'])->name('product.temporary.save.img');
    Route::delete('/delete/temporary/img', [App\Http\Controllers\ProductController::class, 'deleteTemporaryImg'])->name('product.delete.temporary.img');
});

