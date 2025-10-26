<?php

namespace App\Http\Requests;

use App\Enums\ValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:users,telephone|max:20',
            'email' => 'required|email|unique:users,email|max:255',
            'mot_de_passe' => 'required|string|min:8|max:255',
            'adresse' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['admin', 'client'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => ValidationMessages::REQUIRED_NOM->getMessage(),
            'prenom.required' => ValidationMessages::REQUIRED_PRENOM->getMessage(),
            'telephone.required' => ValidationMessages::REQUIRED_TELEPHONE->getMessage(),
            'telephone.unique' => ValidationMessages::UNIQUE_TELEPHONE->getMessage(),
            'email.required' => ValidationMessages::REQUIRED_EMAIL->getMessage(),
            'email.email' => ValidationMessages::EMAIL_INVALID->getMessage(),
            'email.unique' => ValidationMessages::UNIQUE_EMAIL->getMessage(),
            'mot_de_passe.required' => ValidationMessages::REQUIRED_MOT_DE_PASSE->getMessage(),
            'mot_de_passe.min' => ValidationMessages::MIN_8->getMessage(),
            'role.required' => ValidationMessages::REQUIRED_ROLE->getMessage(),
            'role.in' => ValidationMessages::IN_ROLE->getMessage(),
        ];
    }
}
