<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for carts: look up a cart by ID with its items, discounted prices and total, and create/update/delete carts.

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * GET /api/carts
     *
     * Paginated carts with their items and totals. ?user_id=N filters by owner.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $carts = Cart::query()
            ->with(['items.product', 'owner'])
            ->when($request->filled('user_id'), fn ($query) => $query->where('user', $request->integer('user_id')))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return CartResource::collection($carts);
    }

    /**
     * POST /api/carts
     *
     * Creates an empty cart; add products with POST /api/cart-items.
     */
    public function store(CartRequest $request): JsonResponse
    {
        $cart = Cart::create($request->modelData())->load(['items.product', 'owner']);

        return (new CartResource($cart))->response()->setStatusCode(201);
    }

    /**
     * GET /api/carts/{cart}
     *
     * Route model binding loads the cart by ID; an unknown ID returns a 404 JSON response automatically.
     * Each item includes the product name, quantity and final price after its discount, plus the cart total.
     */
    public function show(Cart $cart): CartResource
    {
        // Eager-load items and their products in 2 queries instead of 1 query per item (avoids the N+1 problem).
        return new CartResource($cart->load(['items.product', 'owner']));
    }

    /**
     * PUT/PATCH /api/carts/{cart}
     *
     * The only editable field is the owner; items are managed through /api/cart-items.
     */
    public function update(CartRequest $request, Cart $cart): CartResource
    {
        $cart->update($request->modelData());

        return new CartResource($cart->load(['items.product', 'owner']));
    }

    /**
     * DELETE /api/carts/{cart}
     *
     * Deletes the cart and its items together.
     */
    public function destroy(Cart $cart): Response
    {
        DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->delete();
        });

        return response()->noContent();
    }
}
