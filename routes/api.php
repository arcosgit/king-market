<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::prefix('user')->group(function(){
    require __DIR__.'/user/api.php';
});
Route::prefix('business')->group(function() {
    require __DIR__.'/business/api.php';
});
Route::prefix('admin')->group(function(){
    require __DIR__.'/admin/api.php';
});
