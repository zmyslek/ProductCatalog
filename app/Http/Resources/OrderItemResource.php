<?php

namespace App\Http\Resources;

// API resource that shapes an OrderItem into JSON: product info, quantity, unit price, discount and line total.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->whenLoaded('product', fn () => $this->product->title),
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount_percentage' => $this->discount_percentage,
            'final_price' => $this->finalPrice(),
        ];
    }
}
