<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) an order.

use Illuminate\Validation\Rule;

class OrderRequest extends ApiRequest
{
    protected array $columnMap = ['user_id' => 'user'];

    public function rules(): array
    {
        return [
            'user_id' => [$this->requiredOnCreate(), 'integer', Rule::exists('user', 'id')->where('deleted', false)],
        ];
    }
}
