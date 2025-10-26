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

// Routes pour les comptes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('comptes', CompteController::class);
});

// Routes pour les utilisateurs (Admin/Client)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('admins', AdminController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('users', UserController::class);
});

// Routes pour les transactions
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class);
});
