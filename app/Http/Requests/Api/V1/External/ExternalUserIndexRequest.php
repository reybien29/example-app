<?php

namespace App\Http\Requests\Api\V1\External;

use Illuminate\Foundation\Http\FormRequest;

class ExternalUserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:120'],
            'updated_after' => ['sometimes', 'date'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'search.max' => 'The search term must not exceed 120 characters.',
            'updated_after.date' => 'The updated_after value must be a valid date.',
            'per_page.integer' => 'The per_page value must be a whole number.',
            'per_page.max' => 'The per_page value cannot be greater than 100.',
            'page.min' => 'The page value must be at least 1.',
        ];
    }
}
