<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['set_locale', 'authorized'])->group(function (){

});

