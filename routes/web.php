<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->file(resource_path('html/index.html')));
Route::get('/products', fn () => response()->file(resource_path('html/products.html')));
Route::get('/products/{product}', fn () => response()->file(resource_path('html/product.html')));
Route::get('/catalog.css', fn () => response()->file(resource_path('html/catalog.css'), [
    'Content-Type' => 'text/css; charset=UTF-8',
    'Cache-Control' => 'public, max-age=3600',
]));
