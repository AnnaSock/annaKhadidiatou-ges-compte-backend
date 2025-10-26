<?php

namespace App\Http\Requests;

class UpdateClientRequest extends UpdateUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Empêcher la modification du rôle pour les clients
        unset($rules['role']);

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // S'assurer que le rôle reste client
        $this->merge([
            'role' => 'client',
        ]);
    }
}
