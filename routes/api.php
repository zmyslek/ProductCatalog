<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GiftSuggestionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('carts/{cart}', [CartController::class, 'show'])->name('carts.show');
Route::get('carts/{cart}/gift-suggestions', [GiftSuggestionController::class, 'show'])->name('carts.gift-suggestions');
