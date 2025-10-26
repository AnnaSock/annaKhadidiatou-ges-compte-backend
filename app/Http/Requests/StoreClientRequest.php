<?php

namespace App\Http\Requests;

class StoreClientRequest extends StoreUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Forcer le rôle à client pour StoreClientRequest
        $rules['role'] = 'required|in:client';

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => 'client',
        ]);
    }
}
