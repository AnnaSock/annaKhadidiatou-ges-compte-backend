<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use App\Exceptions\CompteNotFoundException;
use App\Http\Requests\StoreCompteRequest;
use App\Http\Requests\UpdateCompteRequest;
use App\Http\Resources\CompteResource;
use App\Models\Client;
use App\Models\Compte;
use App\Services\CompteService;
use App\Services\PaginationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="Gestion des comptes bancaires"
 * )
 */
class CompteController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/annaSock/v1/comptes",
     *     summary="Lister tous les comptes actifs et valides",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de la page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes avec pagination",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="message", type="string", example="Comptes récupérés avec succès"),
     *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
     *             @OA\Property(property="links", ref="#/components/schemas/Links")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Rôle non autorisé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Rôle non autorisé.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // $role = $request->header('X-User-Role'); // admin ou client
        // $userId = $request->header('X-User-Id'); // utile pour filtrer les comptes client
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        $query = Compte::query()
            ->actifs()
            ->typeValide();
            // ->parRole($role, $userId);

        $result = PaginationService::paginate($query, $page, $limit, $request->url());

        if (empty($result['items']) || count($result['items']) === 0) {
            throw new CompteNotFoundException();
        }

        return $this->successResponse(
            CompteResource::collection($result['items']),
            ResponseMessages::COMPTES_RECUPERES->value,
            $result['pagination'],
            $result['links']
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/annaSock/v1/comptes",
     *     summary="Créer un nouveau compte bancaire",
     *     description="Permet de créer un compte bancaire pour un nouveau client ou un client existant avec validation complète des données.",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données pour créer un compte. Deux cas possibles : avec un client existant (id) ou en créant un nouveau client.",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"type", "soldeInitial", "devise", "client"},
     *             @OA\Property(property="type", type="string", enum={"cheque", "epargne"}, example="cheque"),
     *             @OA\Property(property="soldeInitial", type="number", minimum=10000, example=500000),
     *             @OA\Property(property="devise", type="string", enum={"XOF", "FCFA"}, example="XOF"),
     *             @OA\Property(property="client", type="object",
     *                 description="Informations du client. Utilisez 'id' pour un client existant ou les autres champs pour créer un nouveau client.",
     *                 oneOf={
     *                     @OA\Schema(
     *                         title="Nouveau client",
     *                         type="object",
     *                         required={"titulaire", "email", "telephone", "adresse"},
     *                         @OA\Property(property="titulaire", type="string", example="Cheikh Sy", description="Nom du titulaire"),
     *                         @OA\Property(property="nci", type="string", example="1234567890123A", description="Numéro de carte d'identité nationale"),
     *                         @OA\Property(property="email", type="string", format="email", example="cheikh.sy@example.com", description="Adresse email"),
     *                         @OA\Property(property="telephone", type="string", example="+221771234567", description="Numéro de téléphone"),
     *                         @OA\Property(property="adresse", type="string", example="Dakar, Sénégal", description="Adresse")
     *                     ),
     *                     @OA\Schema(
     *                         title="Client existant",
     *                         type="object",
     *                         required={"id"},
     *                         @OA\Property(property="id", type="integer", example=5, description="ID du client existant")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compte créé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Compte créé avec succès"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Données invalides",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Les données fournies sont invalides"),
     *             @OA\Property(property="errors", type="object",
     *                 example={
     *                     "titulaire": "Le nom du titulaire est requis",
     *                     "soldeInitial": "Le solde initial doit être supérieur à 0"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation métier",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation")
     *         )
     *     )
     * )
     */
    public function store(StoreCompteRequest $request)
    {
        // Récupérer les données validées
        $validated = $request->validated();

        // Créer le compte via le service
        $compte = CompteService::creerCompte($validated);

        // Retourner la réponse avec le code 201 (Created)
        return $this->successResponse(
            new CompteResource($compte),
            ResponseMessages::COMPTE_CREE->value,
            null,
            null,
            HttpStatusCodes::CREATED
        );
    }

    /**
     * @OA\Get(
     *     path="/annaSock/v1/comptes/{compte}",
     *     summary="Récupérer un compte spécifique",
     *     description="Permet de récupérer un compte spécifique (chèque ou épargne) quel que soit son statut (actif, bloqué, fermé). Utilise le Route Model Binding et la validation.",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="compte",
     *         in="path",
     *         required=true,
     *         description="UUID du compte",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du compte récupéré avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Compte récupéré avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Compte non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Compte non trouvé.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Accès non autorisé.")
     *         )
     *     )
     * )
     */
    public function show(Compte $compte)
    {
        if (!in_array($compte->type_compte, [TypeCompte::Cheque, TypeCompte::Epargne])) {
            throw new CompteNotFoundException(ResponseMessages::TYPE_COMPTE_INVALIDE->value);
        }

        // Retourner la réponse formatée avec le trait ApiResponse
        return $this->successResponse(
            new CompteResource($compte),
            ResponseMessages::COMPTE_RECUPERE->value
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compte $compte)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/comptes/{compte}",
     *     summary="Mettre à jour un compte",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="compte",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compte mis à jour",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function update(UpdateCompteRequest $request, Compte $compte)
    {
        //
    }

    /**
     * @OA\Delete(
     *     path="/api/comptes/{compte}",
     *     summary="Supprimer un compte",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="compte",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Compte supprimé"
     *     )
     * )
     */
    public function destroy(Compte $compte)
    {
        //
    }
}
