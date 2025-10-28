<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transaction;
use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        Client::factory(5)->create()->each(function ($client) {
            // Créer 2 comptes épargne par client (peuvent être actifs, bloqués ou fermés)
            $comptesEpargne = Compte::factory(2)->create([
                'user_id' => $client->id,
                'type_compte' => TypeCompte::Epargne->value,
                'statut_compte' => collect([StatutCompte::Actif->value, StatutCompte::Bloque->value, StatutCompte::Ferme->value])->random(),
            ]);

            $comptesEpargne->each(function ($compte) {
                // Créer des transactions pour chaque compte (d'abord des dépôts, puis des retraits)
                Transaction::factory(5)->create([
                    'compte_id' => $compte->id,
                    'type_transaction' => 'dépôt', // Forcer des dépôts d'abord
                ]);

                Transaction::factory(5)->retrait()->create([
                    'compte_id' => $compte->id,
                ]);
            });

            // Créer 1 compte chèque par client (peuvent être actifs ou fermés)
            $compteCheque = Compte::factory(1)->create([
                'user_id' => $client->id,
                'type_compte' => TypeCompte::Cheque->value,
                'statut_compte' => collect([StatutCompte::Actif->value, StatutCompte::Ferme->value])->random(),
            ]);

            $compteCheque->each(function ($compte) {
                // Créer des transactions pour chaque compte (d'abord des dépôts, puis des retraits)
                Transaction::factory(5)->create([
                    'compte_id' => $compte->id,
                    'type_transaction' => 'dépôt', // Forcer des dépôts d'abord
                ]);

                Transaction::factory(5)->retrait()->create([
                    'compte_id' => $compte->id,
                ]);
            });
        });
    }
}
