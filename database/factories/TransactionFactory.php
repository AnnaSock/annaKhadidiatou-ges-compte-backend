<?php

namespace Database\Factories;

use App\Enums\TypeTransaction;
use App\Enums\StatutTransaction;
use App\Models\Compte;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(TypeTransaction::cases());
        $montant = $this->faker->randomFloat(2, 1000, 500000);

        return [
            'id' => Str::uuid(),
            'montant' => $montant,
            'date' => $this->faker->dateTimeThisYear(),
            'type_transaction' => $type->value,
            'statut_transaction' => $this->faker->randomElement(StatutTransaction::cases())->value,
            'devise' => 'XOF',
            'compte_id' => Compte::factory()->create()->id,
        ];
    }

    /**
     * Créer une transaction de retrait avec vérification du solde
     */
   public function retrait(): Factory
{
    return $this->state(function (array $attributes) {
        $compte = Compte::find($attributes['compte_id']);

        if ($compte) {
            $soldeDisponible = $compte->solde_initial +
                $compte->transactions()->where('type_transaction', TypeTransaction::Depot->value)->sum('montant') -
                $compte->transactions()->where('type_transaction', TypeTransaction::Retrait->value)->sum('montant');

            $montant = min($attributes['montant'] ?? 0, $soldeDisponible);
            $montant = max($montant, 100); // Montant minimum pour éviter 0

            return [
                'type_transaction' => TypeTransaction::Retrait->value,
                'montant' => $montant,
            ];
        }

        return $attributes;
    });
}

}
