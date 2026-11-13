<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for order lines: list, show, add a product to an order, change a line and remove it.

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * GET /api/order-items
     *
     * ?order_id=N lists the lines of one order.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = OrderItem::query()
            ->with('product')
            ->when($request->filled('order_id'), fn ($query) => $query->where('order_id', $request->integer('order_id')))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return OrderItemResource::collection($items);
    }

    /**
     * POST /api/order-items
     *
     * If unit_price is not sent, the line takes the product's current price and discount.
     */
    public function store(OrderItemRequest $request): JsonResponse
    {
        $data = $request->modelData();

        if (! isset($data['unit_price'])) {
            $product = Product::findOrFail($data['product_id']);
            $data['unit_price'] = $product->price;
            if (! array_key_exists('discount_percentage', $data)) {
                $data['discount_percentage'] = $product->discount_percentage;
            }
        }

        $item = OrderItem::create($data)->refresh()->load('product');

        return (new OrderItemResource($item))->response()->setStatusCode(201);
    }

    /**
     * GET /api/order-items/{order_item}
     */
    public function show(OrderItem $orderItem): OrderItemResource
    {
        return new OrderItemResource($orderItem->load('product'));
    }

    /**
     * PUT/PATCH /api/order-items/{order_item}
     */
    public function update(OrderItemRequest $request, OrderItem $orderItem): OrderItemResource
    {
        $orderItem->update($request->modelData());

        return new OrderItemResource($orderItem->load('product'));
    }

    /**
     * DELETE /api/order-items/{order_item}
     */
    public function destroy(OrderItem $orderItem): Response
    {
        $orderItem->delete();

        return response()->noContent();
    }
}
