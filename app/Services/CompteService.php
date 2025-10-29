<?php

namespace App\Services;

use Illuminate\Support\Str;

class CompteService
{
    /**
     * Vérifie si un numéro de téléphone est au format sénégalais valide
     *
     * @param string $phoneNumber
     * @return bool
     */
    public static function isSenegalesePhoneNumber(string $phoneNumber): bool
    {
        $pattern = '/^\+221\s(77|78|76|70|75|33)\s\d{3}\s\d{2}\s\d{2}$/';

        return preg_match($pattern, $phoneNumber) === 1;
    }

    /**
     * Vérifie si un numéro CNI est au format sénégalais valide
     *
     * @param string $cniNumber
     * @return bool
     */
    public static function isSenegaleseCniNumber(string $cniNumber): bool
    {
        $pattern = '/^[0-9]{13}[A-Z]{1}$/';

        return preg_match($pattern, $cniNumber) === 1;
    }

    /**
     * Génère un numéro de téléphone sénégalais valide (pour les tests/factory)
      *
      * @return string
      */
     public static function generateSenegalesePhoneNumber(): string
     {
         $prefixes = ['77', '78', '76', '70', '75', '33'];

         $prefix = $prefixes[array_rand($prefixes)];

         $number = str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

         return '+221 ' . substr($prefix, 0, 1) . substr($prefix, 1, 1) . ' ' . substr($number, 0, 3) . ' ' . substr($number, 3, 2) . ' ' . substr($number, 5, 2);
     }

     /**
      * Génère un numéro CNI sénégalais valide (pour les tests/factory)
      *
      * @return string
      */
     public static function generateSenegaleseCniNumber(): string
     {
         $numbers = str_pad(rand(10000000000, 99999999999), 13, '0', STR_PAD_LEFT);
         $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
         $letter = $letters[array_rand($letters)];

         return $numbers . $letter;
     }

    /**
     * Génère un numéro de compte unique
     *
     * @return string
     */
    public static function generateNumeroCompte(): string
    {
        do {
            $numero = 'CPT-' . strtoupper(Str::random(7));
        } while (\App\Models\Compte::where('numero_compte', $numero)->exists());

        return $numero;
    }

    /**
     * Calcule le solde actuel d'un compte
     *
     * @param \App\Models\Compte $compte
     * @return float
     */
    public static function calculerSolde(\App\Models\Compte $compte): float
    {
        $depots = $compte->transactions()
            ->where('type_transaction', \App\Enums\TypeTransaction::Depot->value)
            ->sum('montant');

        $retraits = $compte->transactions()
            ->where('type_transaction', \App\Enums\TypeTransaction::Retrait->value)
            ->sum('montant');

        return $compte->solde_initial + $depots - $retraits;
    }

    /**
     * Vérifie si un compte peut effectuer un retrait
     *
     * @param \App\Models\Compte $compte
     * @param float $montant
     * @return bool
     */
    public static function peutRetirer(\App\Models\Compte $compte, float $montant): bool
    {
        $soldeDisponible = self::calculerSolde($compte);
        return $soldeDisponible >= $montant;
    }

    /**
     * Crée un nouveau compte bancaire
     *
     * @param array $data
     * @return \App\Models\Compte
     * @throws \Exception
     */
    public static function creerCompte(array $data): \App\Models\Compte
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // Gestion du client (nouveau ou existant)
            $client = self::getOrCreateClient($data['client']);

            // Créer le compte
            return \App\Models\Compte::create([
                'id' => Str::uuid(),
                'numero_compte' => self::generateNumeroCompte(),
                'solde_initial' => $data['soldeInitial'],
                'date_creation' => now(),
                'devise' => $data['devise'],
                'statut_compte' => \App\Enums\StatutCompte::Actif->value,
                'type_compte' => $data['type'],
                'version' => 1,
                'user_id' => is_string($client) ? $client : $client->id,
            ]);
        });
    }

    /**
     * Récupère un client existant ou en crée un nouveau
     *
     * @param array $clientData
     * @return \App\Models\Client
     */
    private static function getOrCreateClient(array $clientData): string
    {
        if (isset($clientData['nci']) && $clientData['nci']) {
            // Vérifier d'abord que le NCI existe et est un client
            $user = \Illuminate\Support\Facades\DB::table('users')
                ->where('nci', $clientData['nci'])
                ->where('role', 'client')
                ->first();

            if (!$user) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Client non trouvé');
            }

            // Retourner l'ID du client trouvé
            return $user->id;
        } else {
            // Nouveau client
            $client = \App\Models\Client::create([
                'id' => Str::uuid(),
                'nom' => explode(' ', $clientData['titulaire'])[0] ?? '',
                'prenom' => explode(' ', $clientData['titulaire'])[1] ?? '',
                'telephone' => $clientData['telephone'],
                'email' => $clientData['email'],
                'mot_de_passe' => bcrypt('password'), // Mot de passe par défaut
                'adresse' => $clientData['adresse'],
                'nci' => $clientData['nci'] ?? null,
            ]);

            return $client->id;
        }
    }
}