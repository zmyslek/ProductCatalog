<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) a user.

class UserRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => [$this->requiredOnCreate(), 'string', 'max:255'],
            // Allows restoring a deleted user with {"deleted": false}.
            'deleted' => ['sometimes', 'boolean'],
        ];
    }
}
