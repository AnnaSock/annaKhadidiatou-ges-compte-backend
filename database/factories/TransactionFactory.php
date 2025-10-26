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
        return [
            'id' => Str::uuid(),
            'montant' => $this->faker->randomFloat(2, 1000, 500000),
            'date' => $this->faker->dateTimeThisYear(),
            'type_transaction' => $this->faker->randomElement(TypeTransaction::cases())->value,
            'statut_transaction' => $this->faker->randomElement(StatutTransaction::cases())->value,
            'devise' => 'XOF',
            'compte_id' => Compte::factory(),
        ];
    }
}
