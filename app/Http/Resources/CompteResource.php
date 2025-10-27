<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'numero_compte' => $this->numero_compte,
            'solde_initial' => $this->solde_initial,
            'titulaire' => $this->titulaire,
            'type_compte' => $this->type_compte,
            'devise' => $this->devise,
            'date_creation' => $this->date_creation,
            'statut_compte' => $this->statut_compte,
            'solde' => $this->solde,
            'motif_blocage' => $this->motif_blocage,
            'metadata' => [
                'derniere_modification' => $this->updated_at->toIso8601String(),
                'version' => 1
            ]
        ];
    }
}

