<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Compte",
 *     type="object",
 *     title="Compte",
 *     description="Modèle représentant un compte bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID unique du compte"),
 *     @OA\Property(property="numero_compte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="solde_initial", type="number", format="decimal", description="Solde initial du compte"),
 *     @OA\Property(property="date_creation", type="string", format="date", description="Date de création"),
 *     @OA\Property(property="devise", type="string", default="XOF", description="Devise du compte"),
 *     @OA\Property(property="statut_compte", type="string", enum={"actif", "bloqué", "fermé"}, description="Statut du compte"),
 *     @OA\Property(property="type_compte", type="string", enum={"chèque", "épargne"}, description="Type de compte"),
 *     @OA\Property(property="version", type="integer", description="Version du compte"),
 *     @OA\Property(property="user_id", type="string", format="uuid", description="ID de l'utilisateur propriétaire"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StoreCompteRequest",
 *     type="object",
 *     title="StoreCompteRequest",
 *     description="Requête de création d'un compte",
 *     required={"numero_compte", "solde_initial", "date_creation", "type_compte"},
 *     @OA\Property(property="numero_compte", type="string", description="Numéro unique du compte"),
 *     @OA\Property(property="solde_initial", type="number", format="decimal", description="Solde initial"),
 *     @OA\Property(property="date_creation", type="string", format="date", description="Date de création"),
 *     @OA\Property(property="devise", type="string", default="XOF", description="Devise"),
 *     @OA\Property(property="statut_compte", type="string", enum={"actif", "bloqué", "fermé"}, default="actif"),
 *     @OA\Property(property="type_compte", type="string", enum={"chèque", "épargne"}, description="Type de compte")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateCompteRequest",
 *     type="object",
 *     title="UpdateCompteRequest",
 *     description="Requête de mise à jour d'un compte",
 *     @OA\Property(property="numero_compte", type="string", description="Numéro unique du compte"),
 *     @OA\Property(property="solde_initial", type="number", format="decimal", description="Solde initial"),
 *     @OA\Property(property="date_creation", type="string", format="date", description="Date de création"),
 *     @OA\Property(property="devise", type="string", description="Devise"),
 *     @OA\Property(property="statut_compte", type="string", enum={"actif", "bloqué", "fermé"}),
 *     @OA\Property(property="type_compte", type="string", enum={"chèque", "épargne"})
 * )
 */
class Schemas
{
    // Classe vide servant uniquement à regrouper les annotations Swagger
}