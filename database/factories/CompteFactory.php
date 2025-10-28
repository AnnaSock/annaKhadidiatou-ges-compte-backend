<?php

namespace Database\Factories;

use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'numero_compte' => 'CPT-' . strtoupper($this->faker->unique()->bothify('####??')),
            'solde_initial' => $this->faker->randomFloat(2, 0, 100000),
            'date_creation' => now(),
            'devise' => 'XOF',
            'statut_compte' => $this->faker->randomElement(StatutCompte::cases())->value,
            'type_compte' => $this->faker->randomElement(TypeCompte::cases())->value,
            'version' => 1,
            'user_id' => Client::factory()->create()->id,
        ];
    }
}
