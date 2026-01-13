<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized'])->group(function (){
    Route::post('/store', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
    Route::post('/temporary/save/img', [App\Http\Controllers\ProductController::class, 'temporarySaveImg'])->name('product.temporary.save.img');
    Route::delete('/delete/temporary/img', [App\Http\Controllers\ProductController::class, 'deleteTemporaryImg'])->name('product.delete.temporary.img');
    Route::post('/buy', [App\Http\Controllers\ProductController::class, 'buy'])->name('product.buy');
    Route::post('/create/update/review', [App\Http\Controllers\ProductController::class, 'createReview'])->name('product.create.update.review');
});

Route::middleware('set_locale')->group(function (){
    Route::post('/home', [App\Http\Controllers\HomeController::class, 'products'])->name('product.home');
    Route::post('/show', [App\Http\Controllers\ProductController::class, 'showProduct'])->name('product.get');
    Route::post('/reviews', [App\Http\Controllers\ProductController::class, 'reviews'])->name('product.get.reviews');
    Route::post('/find', [App\Http\Controllers\ProductController::class, 'find'])->name('product.find');
    Route::post('/find/filter', [App\Http\Controllers\ProductController::class, 'findFilter'])->name('product.find.filter');
    Route::post('/catalog', [App\Http\Controllers\ProductController::class, 'catalog'])->name('product.catalog');
    Route::post('/save/img/{id}', [App\Http\Controllers\ProductController::class, 'saveImg'])->name('product.save.img')->middleware(['is_user_auth_and_have_business', 'is_user_owner_product']);
    Route::post('/edit/{id}', [App\Http\Controllers\ProductController::class, 'editSave'])->name('product.edit.save')->middleware(['is_user_auth_and_have_business', 'is_user_owner_product']);
    Route::delete('/delete/{id}', [App\Http\Controllers\ProductController::class, 'deleteProduct'])->name('product.delete')->middleware(['is_user_auth_and_have_business', 'is_user_owner_product']);
});



