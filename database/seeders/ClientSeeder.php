<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Compte;
use App\Models\Transaction;
use App\Enums\StatutCompte;
use App\Enums\StatutTransaction;
use App\Enums\TypeCompte;
use App\Enums\TypeTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        Client::factory(10)->create()->each(function ($client) {
            // Créer 2 comptes épargne par client (actifs)
            $comptesEpargne = Compte::factory(2)->create([
                'user_id' => $client->id,
                'type_compte' => TypeCompte::Epargne->value,
                'statut_compte' => StatutCompte::Actif->value,
            ]);

            $comptesEpargne->each(function ($compte) {
                // Créer 2 dépôts valides
                Transaction::factory(2)->create([
                    'compte_id' => $compte->id,
                    'type_transaction' => TypeTransaction::Depot->value,
                    'statut_transaction' => StatutTransaction::Valide->value,
                ]);

                // Créer 2 retraits valides
                Transaction::factory(2)->retrait()->create([
                    'compte_id' => $compte->id,
                    'statut_transaction' => StatutTransaction::Valide->value,
                ]);

                // Créer 1 dépôt annulé
                Transaction::factory(1)->create([
                    'compte_id' => $compte->id,
                    'type_transaction' => TypeTransaction::Depot->value,
                    'statut_transaction' => StatutTransaction::Annule->value,
                ]);

                // Créer 1 retrait annulé
                Transaction::factory(1)->retrait()->create([
                    'compte_id' => $compte->id,
                    'statut_transaction' => StatutTransaction::Annule->value,
                ]);

                // Créer 1 dépôt en attente
                Transaction::factory(1)->create([
                    'compte_id' => $compte->id,
                    'type_transaction' => TypeTransaction::Depot->value,
                    'statut_transaction' => StatutTransaction::EnAttente->value,
                ]);

                // Créer 1 retrait en attente
                Transaction::factory(1)->retrait()->create([
                    'compte_id' => $compte->id,
                    'statut_transaction' => StatutTransaction::EnAttente->value,
                ]);
            });
        });
    }
}
