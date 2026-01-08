<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route for categories
Route::get('/categories', function () { return view('categories.index'); });
Route::resource('/api/categories', \App\Http\Controllers\CategoryController::class);

// Route for products
Route::get('/products', function () {
    $categories = \App\Models\Category::all();
    return view('products.index', [
        'categories' => $categories
    ]);
});
Route::resource('/api/products', \App\Http\Controllers\ProductController::class);
