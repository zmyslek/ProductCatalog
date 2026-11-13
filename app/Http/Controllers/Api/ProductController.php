<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for products: list or filter active products, show one product, and create/update/delete products.

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * GET /api/products
     *
     * Paginated catalog of active products. Optional query parameters:
     * ?category_id=N (filter by category), ?include_inactive=1 (also list inactive products), ?per_page=N.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with('productCategory')
            ->unless($request->boolean('include_inactive'), fn ($query) => $query->where('active', true))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category', $request->integer('category_id')))
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * POST /api/products
     */
    public function store(ProductRequest $request): JsonResponse
    {
        // refresh() reloads the row so columns filled by database defaults (stock, rating, ...) are in the response.
        $product = Product::create($request->modelData())->refresh()->load('productCategory');

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * GET /api/products/{product}
     *
     * Any product, active or not, with its category.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('productCategory'));
    }

    /**
     * PUT/PATCH /api/products/{product}
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->modelData());

        return new ProductResource($product->load('productCategory'));
    }

    /**
     * DELETE /api/products/{product}
     *
     * The database would cascade-delete every order line for this product, erasing order history.
     * That is refused; deactivating the product ("active": false) hides it from the catalog instead.
     * Cart lines for the product are removed by the database's ON DELETE CASCADE.
     */
    public function destroy(Product $product): Response|JsonResponse
    {
        if ($product->orderItems()->exists()) {
            return response()->json([
                'message' => 'This product appears in existing orders and cannot be deleted. Set "active" to false instead.',
            ], 409);
        }

        $product->delete();

        return response()->noContent();
    }
}
