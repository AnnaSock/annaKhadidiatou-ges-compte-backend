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
 *     required={"type", "soldeInitial", "devise", "client"},
 *     @OA\Property(property="type", type="string", enum={"cheque", "epargne"}, maxLength=10, example="cheque"),
 *     @OA\Property(property="soldeInitial", type="number", format="float", minimum=10000, maximum=999999999999.99, example=500000),
 *     @OA\Property(property="devise", type="string", enum={"XOF", "FCFA"}, maxLength=3, example="XOF"),
 *     @OA\Property(property="client", type="object",
 *         oneOf={
 *             @OA\Schema(
 *                 type="object",
 *                 required={"id"},
 *                 @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
 *             ),
 *             @OA\Schema(
 *                 type="object",
 *                 required={"titulaire", "email", "telephone", "adresse"},
 *                 @OA\Property(property="titulaire", type="string", maxLength=255, example="Cheikh Sy"),
 *                 @OA\Property(property="nci", type="string", maxLength=20, nullable=true, example="1234567890123A"),
 *                 @OA\Property(property="email", type="string", format="email", maxLength=255, example="cheikh.sy@example.com"),
 *                 @OA\Property(property="telephone", type="string", maxLength=20, example="+221 77 123 45 67"),
 *                 @OA\Property(property="adresse", type="string", maxLength=500, example="Dakar, Sénégal")
 *             )
 *         }
 *     )
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


/**
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="per_page", type="integer", example=10),
 *     @OA\Property(property="total", type="integer", example=100),
 *     @OA\Property(property="last_page", type="integer", example=10)
 * )
 *
 * @OA\Schema(
 *     schema="Links",
 *     type="object",
 *     @OA\Property(property="first", type="string", example="http://example.com/api/comptes?page=1"),
 *     @OA\Property(property="last", type="string", example="http://example.com/api/comptes?page=10"),
 *     @OA\Property(property="prev", type="string", nullable=true, example="http://example.com/api/comptes?page=1"),
 *     @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/comptes?page=3")
 * )
 */
class Schemas
{
    // Classe vide servant uniquement à regrouper les annotations Swagger
}
