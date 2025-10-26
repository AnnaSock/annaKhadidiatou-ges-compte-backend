<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompteRequest;
use App\Http\Requests\UpdateCompteRequest;
use App\Models\Compte;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="Gestion des comptes bancaires"
 * )
 */
class CompteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/comptes",
     *     summary="Lister tous les comptes",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Compte")
     *         )
     *     )
     * )
     */
    public function index()
    {
        //
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
     *     path="/api/comptes/{compte}",
     *     summary="Afficher un compte spécifique",
     *     tags={"Comptes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="compte",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du compte",
     *         @OA\JsonContent(ref="#/components/schemas/Compte")
     *     )
     * )
     */
    public function show(Compte $compte)
    {
        //
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
