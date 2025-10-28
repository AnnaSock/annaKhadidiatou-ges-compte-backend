<?php

namespace App\Http\Controllers;

use App\Enums\StatutCompte;
use App\Enums\TypeCompte;
use App\Exceptions\CompteNotFoundException;
use App\Http\Requests\StoreCompteRequest;
use App\Http\Requests\UpdateCompteRequest;
use App\Http\Resources\CompteResource;
use App\Models\Compte;
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
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Compte")),
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
            'Comptes récupérés avec succès',
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
     *     path="/api/comptes",
     *     summary="Créer un nouveau compte",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCompteRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compte créé",
     *         @OA\JsonContent(ref="#/components/schemas/Compte")
     *     )
     * )
     */
    public function store(StoreCompteRequest $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/annaSock/v1/comptes/{compte}",
     *     summary="Récupérer un compte spécifique",
     *     description="Par défaut, la recherche se fait sur la base locale lorsque le compte est chèque ou épargne actif. Utilise le Route Model Binding et la validation.",
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
     *             @OA\Property(property="data", ref="#/components/schemas/Compte"),
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
        // Validation automatique via Route Model Binding
        // Le modèle Compte est automatiquement résolu par Laravel
        // avec les scopes globaux appliqués (not_deleted)

        // Vérification supplémentaire : compte doit être actif et de type valide
        if ($compte->statut_compte !== StatutCompte::Actif) {
            throw new CompteNotFoundException('Le compte n\'est pas actif.');
        }

        if (!in_array($compte->type_compte, [TypeCompte::Cheque, TypeCompte::Epargne])) {
            throw new CompteNotFoundException('Type de compte non valide pour cette opération.');
        }

        // Retourner la réponse formatée avec le trait ApiResponse
        return $this->successResponse(
            new CompteResource($compte),
            'Compte récupéré avec succès'
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
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCompteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compte mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Compte")
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
