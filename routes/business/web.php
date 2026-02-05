<?php

use Illuminate\Support\Facades\Route;
Route::middleware('set_locale')->group(function (){
    Route::get('/products/{name}', [App\Http\Controllers\BusinessController::class, 'showProducts'])->name('business.products.show');
});
