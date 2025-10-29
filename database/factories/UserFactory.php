<?php

namespace Database\Factories;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'telephone' => $this->generateSenegalesePhoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'mot_de_passe' => bcrypt('password'),
            'adresse' => $this->faker->address(),
            'role' => Role::Client, // Valeur par défaut
        ];
    }

    /**
     * Génère un numéro de téléphone sénégalais valide
     */
    private function generateSenegalesePhoneNumber(): string
    {
        return \App\Services\CompteService::generateSenegalesePhoneNumber();
    }
}
