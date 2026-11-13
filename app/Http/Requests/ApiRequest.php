<?php

namespace App\Http\Requests;

// Base class for the API's Form Requests: shared create/update rule handling and API-field -> DB-column renaming.

use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequest extends FormRequest
{
    /**
     * API field name => database column, for columns whose DB names would be confusing in the API
     * (e.g. the API takes `category_id`, the product table stores it in `category`).
     *
     * @var array<string, string>
     */
    protected array $columnMap = [];

    /**
     * The API has no authentication yet, so every request is allowed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * POST (create) must send the field; PUT/PATCH (update) may leave it out to keep the current value.
     */
    protected function requiredOnCreate(): string
    {
        return $this->isMethod('POST') ? 'required' : 'sometimes';
    }

    /**
     * The validated input, with API field names renamed to database columns, ready for create() / update().
     *
     * @return array<string, mixed>
     */
    public function modelData(): array
    {
        $data = $this->validated();

        foreach ($this->columnMap as $field => $column) {
            if (array_key_exists($field, $data)) {
                $data[$column] = $data[$field];
                unset($data[$field]);
            }
        }

        return $data;
    }
}
