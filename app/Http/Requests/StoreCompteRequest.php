<?php

namespace App\Http\Requests;

use App\Enums\ValidationMessages;
use App\Services\CompteService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompteRequest extends FormRequest
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
            // Règles pour le compte
            'type' => ['required', Rule::in(['cheque', 'epargne'])],
            'soldeInitial' => 'required|numeric|min:10000|max:999999999999.99',
            'devise' => 'required|string|in:XOF,FCFA',

            // Règles pour le client
            'client' => 'required|array',
            'client.id' => 'nullable|exists:users,id',

            // Champs obligatoires seulement pour un NOUVEAU client
            'client.titulaire' => 'required_if:client.id,null|string|max:255',
            'client.nci' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && !CompteService::isSenegaleseCniNumber($value)) {
                        $fail(ValidationMessages::CNI_REGEX->getMessage());
                    }
                }
            ],
            'client.email' => [
                'required_if:client.id,null',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->input('client.id'))
            ],
            'client.telephone' => [
                'required_if:client.id,null',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && !CompteService::isSenegalesePhoneNumber($value)) {
                        $fail(ValidationMessages::TELEPHONE_SENEGALAIS_INVALID->getMessage());
                    }
                },
                Rule::unique('users', 'telephone')->ignore($this->input('client.id'))
            ],
            'client.adresse' => 'required_if:client.id,null|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Messages pour le compte
            'type.required' => ValidationMessages::REQUIRED_TYPE_COMPTE->getMessage(),
            'type.in' => ValidationMessages::IN_TYPE_COMPTE->getMessage(),
            'soldeInitial.required' => ValidationMessages::SOLDE_INITIAL_REQUIRED->getMessage(),
            'soldeInitial.numeric' => ValidationMessages::SOLDE_INITIAL_NUMERIC->getMessage(),
            'soldeInitial.min' => ValidationMessages::SOLDE_INITIAL_MIN->getMessage(),
            'soldeInitial.max' => ValidationMessages::MONTANT_MAX->getMessage(),
            'devise.required' => ValidationMessages::DEVISE_REQUIRED->getMessage(),
            'devise.in' => ValidationMessages::IN_DEVISE->getMessage(),

            // Messages pour le client
            'client.required' => ValidationMessages::CLIENT_REQUIRED->getMessage(),
            'client.array' => ValidationMessages::CLIENT_ARRAY->getMessage(),
            'client.id.exists' => ValidationMessages::EXISTS_USER->getMessage(),
            'client.titulaire.required_if' => ValidationMessages::TITULAIRE_REQUIRED_IF->getMessage(),
            'client.titulaire.string' => ValidationMessages::STRING_INVALID->getMessage(),
            'client.titulaire.max' => ValidationMessages::MAX_255->getMessage(),
            'client.nci.max' => ValidationMessages::MAX_20->getMessage(),
            'client.email.required_if' => ValidationMessages::REQUIRED_EMAIL->getMessage(),
            'client.email.email' => ValidationMessages::EMAIL_INVALID->getMessage(),
            'client.email.unique' => ValidationMessages::UNIQUE_EMAIL->getMessage(),
            'client.email.max' => ValidationMessages::MAX_255->getMessage(),
            'client.telephone.required_if' => ValidationMessages::REQUIRED_TELEPHONE->getMessage(),
            'client.telephone.string' => ValidationMessages::STRING_INVALID->getMessage(),
            'client.telephone.unique' => ValidationMessages::UNIQUE_TELEPHONE->getMessage(),
            'client.telephone.max' => ValidationMessages::MAX_20->getMessage(),
            'client.adresse.required_if' => ValidationMessages::ADRESSE_REQUIRED->getMessage(),
            'client.adresse.string' => ValidationMessages::STRING_INVALID->getMessage(),
            'client.adresse.max' => ValidationMessages::MAX_500->getMessage(),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('devise') === 'FCFA') {
            $this->merge([
                'devise' => 'XOF',
            ]);
        }

        if ($this->input('client.id')) {
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validation personnalisée : si client.id est fourni, vérifier que c'est un client existant
            if ($this->input('client.id')) {
                $user = \App\Models\User::find($this->input('client.id'));
                if ($user && $user->role->value !== 'client') {
                    $validator->errors()->add('client.id', 'L\'utilisateur spécifié n\'est pas un client.');
                }
            }
        });
    }
}