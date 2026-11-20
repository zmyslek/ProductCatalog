<?php

use Illuminate\Support\Facades\Route;

$pageHeaders = ['Cache-Control' => 'public, max-age=300'];

Route::get('/', fn () => response()->file(resource_path('html/home.html'), $pageHeaders));
Route::get('/cart', fn () => response()->file(resource_path('html/index.html'), $pageHeaders));
Route::get('/products', fn () => response()->file(resource_path('html/products.html'), $pageHeaders));
Route::get('/products/{product}', fn () => response()->file(resource_path('html/product.html'), $pageHeaders));
Route::get('/catalog.css', fn () => response()->file(resource_path('html/catalog.css'), [
    'Content-Type' => 'text/css; charset=UTF-8',
    'Cache-Control' => 'no-cache, no-store, must-revalidate',
]));
