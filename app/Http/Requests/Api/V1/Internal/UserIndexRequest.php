<?php

namespace App\Http\Requests\Api\V1\Internal;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
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
            'email_verified' => ['sometimes', 'boolean'],
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
            'email_verified.boolean' => 'The email_verified filter must be true or false.',
            'per_page.integer' => 'The per_page value must be a whole number.',
            'per_page.max' => 'The per_page value cannot be greater than 100.',
            'page.min' => 'The page value must be at least 1.',
        ];
    }
}
