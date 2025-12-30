<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized', 'is_admin'])->group(function (){
    Route::post('/create/category', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.create.category');
    Route::post('/find/category', [App\Http\Controllers\AdminController::class, 'findCategory'])->name('admin.find.category');
    Route::patch('/add/subcategory', [App\Http\Controllers\AdminController::class, 'addSubcategory'])->name('admin.add.subcategory');
    Route::patch('/add/nestedsubcategory', [App\Http\Controllers\AdminController::class, 'addNestedSubcategory'])->name('admin.add.nestedsubcategory');
    Route::delete('/delete/nestedsubcategory', [App\Http\Controllers\AdminController::class, 'deleteNestedSubcategory'])->name('admin.delete.nestedsubcategory');
});

