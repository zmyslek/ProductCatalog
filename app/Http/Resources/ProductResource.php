<?php

namespace App\Http\Resources;

// API resource that shapes a Product into JSON, including the original and discounted price.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->getAttribute('category'),
            // Only included when the controller eager-loaded it, so list endpoints don't trigger extra queries.
            'category' => new CategoryResource($this->whenLoaded('productCategory')),
            'price' => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'discounted_price' => $this->discountedPrice(),
            'rating' => $this->rating,
            'stock' => $this->stock,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'weight' => $this->weight,
            'warrantyInformation' => $this->warrantyInformation,
            'shippingInformation' => $this->shippingInformation,
            'returnPolicy' => $this->returnPolicy,
            'minimumOrderQuantity' => $this->minimumOrderQuantity,
            'image' => $this->image,
            'active' => $this->active,
        ];
    }
}
