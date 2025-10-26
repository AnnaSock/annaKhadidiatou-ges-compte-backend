<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'numero_compte' => 'required|string|unique:comptes,numero_compte',
            'solde_initial' => 'required|numeric|min:0',
            'date_creation' => 'required|date',
            'devise' => 'string|default:XOF',
            'statut_compte' => 'string|in:actif,bloqué,fermé',
            'type_compte' => 'required|string|in:chèque,épargne',
        ];
    }
}
