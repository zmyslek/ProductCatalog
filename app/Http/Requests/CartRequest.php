<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) a cart.

use Illuminate\Validation\Rule;

class CartRequest extends ApiRequest
{
    protected array $columnMap = ['user_id' => 'user'];

    public function rules(): array
    {
        return [
            // A cart can only belong to a user that exists and is not deleted.
            'user_id' => [$this->requiredOnCreate(), 'integer', Rule::exists('user', 'id')->where('deleted', false)],
        ];
    }
}
