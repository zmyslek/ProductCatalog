<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $ids = Cart::query()->orderBy('id')->pluck('id')->values();

        return response()->json([
            'data' => $ids,
            'meta' => [
                'count' => $ids->count(),
                'min' => $ids->first(),
                'max' => $ids->last(),
            ],
        ]);
    }

    public function show(Cart $cart): CartResource
    {
        return new CartResource($cart->load(['items.product']));
    }
}
