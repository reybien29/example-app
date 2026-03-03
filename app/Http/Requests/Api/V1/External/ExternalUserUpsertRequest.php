<?php

namespace App\Http\Requests\Api\V1\External;

use Illuminate\Foundation\Http\FormRequest;

class ExternalUserUpsertRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['sometimes', 'string', 'min:8', 'max:255'],
            'email_verified_at' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A user name is required.',
            'email.required' => 'An email address is required.',
            'email.email' => 'The email address format is invalid.',
            'password.min' => 'The password must contain at least 8 characters.',
        ];
    }
}
