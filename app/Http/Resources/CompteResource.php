<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'numeroCompte' => $this->numero_compte,
            'titulaire' => $this->titulaire,
            'type' => $this->type_compte->value,
            'solde' => $this->solde,
            'devise' => $this->devise,
            'dateCreation' => $this->date_creation,
            'statut' => $this->statut_compte->value,
            'metadata' => [
                'derniereModification' => $this->updated_at->toIso8601String(),
                'version' => $this->version ?? 1
            ]
        ];
    }
}

