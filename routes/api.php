<?php

use Illuminate\Support\Facades\Route;

Route::post('/categories', [App\Http\Controllers\HomeController::class, 'categories'])->name('categories');
Route::prefix('user')->group(function(){
    require __DIR__.'/user/api.php';
});
Route::prefix('business')->group(function() {
    require __DIR__.'/business/api.php';
});
Route::prefix('admin')->group(function(){
    require __DIR__.'/admin/api.php';
});
Route::prefix('product')->group(function(){
    require __DIR__.'/product/api.php';
});
