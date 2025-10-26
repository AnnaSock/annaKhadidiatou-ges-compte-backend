<?php

namespace App\Http\Requests;

class StoreAdminRequest extends StoreUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Forcer le rôle à admin pour StoreAdminRequest
        $rules['role'] = 'required|in:admin';

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => 'admin',
        ]);
    }
}
