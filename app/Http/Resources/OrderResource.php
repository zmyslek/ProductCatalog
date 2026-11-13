<?php

namespace App\Http\Resources;

// API resource that shapes an Order into JSON: its items plus the total after discounts.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->getAttribute('user'),
            'user' => new UserResource($this->whenLoaded('customer')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'total' => $this->whenLoaded('items', fn () => $this->total()),
        ];
    }
}
