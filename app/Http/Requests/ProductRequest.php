<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) a product.

class ProductRequest extends ApiRequest
{
    protected array $columnMap = ['category_id' => 'category'];

    public function rules(): array
    {
        $required = $this->requiredOnCreate();

        return [
            'title' => [$required, 'string', 'max:255'],
            'description' => [$required, 'string', 'max:255'],
            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:category,id'],
            'price' => [$required, 'numeric', 'min:0'],
            'discount_percentage' => ['sometimes', 'nullable', 'numeric', 'between:0,100'],
            'rating' => ['sometimes', 'numeric', 'between:0,5'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'brand' => ['sometimes', 'nullable', 'string', 'max:255'],
            'sku' => ['sometimes', 'nullable', 'string', 'max:255'],
            'weight' => ['sometimes', 'integer', 'min:0'],
            'warrantyInformation' => ['sometimes', 'nullable', 'string', 'max:255'],
            'shippingInformation' => ['sometimes', 'nullable', 'string', 'max:255'],
            'returnPolicy' => ['sometimes', 'nullable', 'string', 'max:255'],
            'minimumOrderQuantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'image' => [$required, 'url', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
