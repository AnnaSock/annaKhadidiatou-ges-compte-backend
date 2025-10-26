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
        // Préfixes téléphoniques sénégalais
        $prefixes = ['77', '78', '76', '70', '75', '33'];

        // Choisir un préfixe aléatoire
        $prefix = $this->faker->randomElement($prefixes);

        // Générer 7 chiffres aléatoires
        $number = $this->faker->unique()->numberBetween(1000000, 9999999);

        // Retourner le numéro au format +221 XX XXX XX XX
        return '+221 ' . substr($prefix, 0, 1) . substr($prefix, 1, 1) . ' ' . substr($number, 0, 3) . ' ' . substr($number, 3, 2) . ' ' . substr($number, 5, 2);
    }
}
