<?php

namespace App\Http\Resources;

// API resource that shapes a Cart into JSON: its items plus the total after discounts.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'total' => $this->whenLoaded('items', fn () => $this->total()),
        ];
    }
}
