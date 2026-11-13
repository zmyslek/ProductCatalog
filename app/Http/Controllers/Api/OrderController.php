<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for orders: list, show with items and total, create, update and delete.

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     *
     * Paginated orders with their items and totals. ?user_id=N filters by customer.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->with(['items.product', 'customer'])
            ->when($request->filled('user_id'), fn ($query) => $query->where('user', $request->integer('user_id')))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return OrderResource::collection($orders);
    }

    /**
     * POST /api/orders
     *
     * Creates an empty order; add lines with POST /api/order-items.
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $order = Order::create($request->modelData())->load(['items.product', 'customer']);

        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    /**
     * GET /api/orders/{order}
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load(['items.product', 'customer']));
    }

    /**
     * PUT/PATCH /api/orders/{order}
     */
    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->modelData());

        return new OrderResource($order->load(['items.product', 'customer']));
    }

    /**
     * DELETE /api/orders/{order}
     *
     * The items are deleted first: order_items.order_id is ON DELETE SET DEFAULT on a NOT NULL column
     * with no default, so deleting the order alone would fail on the foreign key.
     */
    public function destroy(Order $order): Response
    {
        DB::transaction(function () use ($order) {
            $order->items()->delete();
            $order->delete();
        });

        return response()->noContent();
    }
}
