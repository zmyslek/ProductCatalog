<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->file(resource_path('html/index.html')));
Route::get('/temporary-frontend', fn () => response()->file(resource_path('html/temporary-frontend.html')));
