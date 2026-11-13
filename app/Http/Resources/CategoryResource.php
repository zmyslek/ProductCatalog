<?php

namespace App\Http\Resources;

// API resource that shapes a Category model into the JSON returned by the REST API.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'url' => $this->url,
            'products_count' => $this->whenCounted('products'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
