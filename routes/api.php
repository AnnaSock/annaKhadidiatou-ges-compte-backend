<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function () {
    // Route GET pour lister les comptes actifs et valides
    Route::get('/comptes', [CompteController::class, 'index']);

    // Route POST pour créer un nouveau compte
    Route::post('/comptes', [CompteController::class, 'store']);

    // Route GET pour récupérer un compte spécifique
    Route::get('/comptes/{compte}', [CompteController::class, 'show']);
});
