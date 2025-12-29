<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized', 'is_admin'])->group(function (){
    Route::post('/create/category', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.create.category');
    Route::post('/find/category', [App\Http\Controllers\AdminController::class, 'findCategory'])->name('admin.find.category');
});

