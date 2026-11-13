<?php

namespace App\Http\Resources;

// API resource that shapes a User into JSON.

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deleted' => $this->deleted,
        ];
    }
}
