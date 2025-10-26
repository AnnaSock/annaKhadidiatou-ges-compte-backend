<?php

namespace App\Http\Requests;

class UpdateAdminRequest extends UpdateUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Empêcher la modification du rôle pour les admins
        unset($rules['role']);

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // S'assurer que le rôle reste admin
        $this->merge([
            'role' => 'admin',
        ]);
    }
}
