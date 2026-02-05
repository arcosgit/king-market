<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::prefix('user')->group(function(){
    require __DIR__.'/user/web.php';
});
Route::prefix('product')->group(function(){
    require __DIR__.'/product/web.php';
});
Route::prefix('business')->group(function() {
    require __DIR__.'/business/web.php';
});


