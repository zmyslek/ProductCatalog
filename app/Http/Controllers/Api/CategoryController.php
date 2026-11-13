<?php

namespace App\Http\Controllers\Api;

// REST API endpoints for categories: list all categories, show one category with its products, and create/update/delete categories.

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     *
     * Categories ordered by name, each with the number of active products in it.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->where('active', true)])
            ->orderBy('name')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return CategoryResource::collection($categories);
    }

    /**
     * POST /api/categories
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->modelData());

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * GET /api/categories/{category}
     *
     * One category with its active products.
     */
    public function show(Category $category): CategoryResource
    {
        $category->load(['products' => fn ($query) => $query->where('active', true)->orderBy('id')]);

        return new CategoryResource($category);
    }

    /**
     * PUT/PATCH /api/categories/{category}
     */
    public function update(CategoryRequest $request, Category $category): CategoryResource
    {
        $category->update($request->modelData());

        return new CategoryResource($category);
    }

    /**
     * DELETE /api/categories/{category}
     *
     * Products are kept and become uncategorised (category = NULL), matching the table's ON DELETE SET DEFAULT.
     * Done explicitly in a transaction so it doesn't depend on SQLite's foreign key setting.
     */
    public function destroy(Category $category): Response
    {
        DB::transaction(function () use ($category) {
            $category->products()->update(['category' => null]);
            $category->delete();
        });

        return response()->noContent();
    }
}
