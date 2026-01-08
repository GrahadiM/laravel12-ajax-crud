<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/categories', function () { return view('categories.index'); });
Route::resource('/api/categories', \App\Http\Controllers\CategoryController::class);
