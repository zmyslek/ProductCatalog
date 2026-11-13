<?php

namespace App\Http\Requests;

// Validation for creating (POST) and updating (PUT/PATCH) a category.

use Illuminate\Validation\Rule;

class CategoryRequest extends ApiRequest
{
    public function rules(): array
    {
        $required = $this->requiredOnCreate();

        return [
            // On update, the category's own current slug must not count as a duplicate.
            'slug' => [$required, 'string', 'max:255', 'alpha_dash', Rule::unique('category', 'slug')->ignore($this->route('category'))],
            'name' => [$required, 'string', 'max:255'],
            'url' => [$required, 'url', 'max:255'],
        ];
    }
}
