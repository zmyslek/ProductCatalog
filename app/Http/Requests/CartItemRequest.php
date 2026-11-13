<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) a cart line.
// On create, unit_price and discount_percentage may be omitted: the controller copies them from the product.

class CartItemRequest extends ApiRequest
{
    public function rules(): array
    {
        $required = $this->requiredOnCreate();

        return [
            'cart_id' => [$required, 'integer', 'exists:cart,id'],
            'product_id' => [$required, 'integer', 'exists:product,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            // The column is NOT NULL: null is only accepted on create, where it means "use the product's price".
            'unit_price' => [$this->isMethod('POST') ? 'nullable' : 'sometimes', 'numeric', 'min:0'],
            'discount_percentage' => ['sometimes', 'nullable', 'numeric', 'between:0,100'],
        ];
    }
}
