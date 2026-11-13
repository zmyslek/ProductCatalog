<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for cart lines: list, show, add a product to a cart, change a line and remove it.

use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CartItemController extends Controller
{
    /**
     * GET /api/cart-items
     *
     * ?cart_id=N lists the lines of one cart.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = CartItem::query()
            ->with('product')
            ->when($request->filled('cart_id'), fn ($query) => $query->where('cart_id', $request->integer('cart_id')))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return CartItemResource::collection($items);
    }

    /**
     * POST /api/cart-items
     *
     * If unit_price is not sent, the line takes the product's current price and discount.
     * The price is stored on the line so later product price changes don't change this cart.
     */
    public function store(CartItemRequest $request): JsonResponse
    {
        $data = $request->modelData();

        if (! isset($data['unit_price'])) {
            $product = Product::findOrFail($data['product_id']);
            $data['unit_price'] = $product->price;
            if (! array_key_exists('discount_percentage', $data)) {
                $data['discount_percentage'] = $product->discount_percentage;
            }
        }

        $item = CartItem::create($data)->refresh()->load('product');

        return (new CartItemResource($item))->response()->setStatusCode(201);
    }

    /**
     * GET /api/cart-items/{cart_item}
     */
    public function show(CartItem $cartItem): CartItemResource
    {
        return new CartItemResource($cartItem->load('product'));
    }

    /**
     * PUT/PATCH /api/cart-items/{cart_item}
     */
    public function update(CartItemRequest $request, CartItem $cartItem): CartItemResource
    {
        $cartItem->update($request->modelData());

        return new CartItemResource($cartItem->load('product'));
    }

    /**
     * DELETE /api/cart-items/{cart_item}
     */
    public function destroy(CartItem $cartItem): Response
    {
        $cartItem->delete();

        return response()->noContent();
    }
}
