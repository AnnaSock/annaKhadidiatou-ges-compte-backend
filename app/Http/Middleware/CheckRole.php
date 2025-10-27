<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Exemple : rôle envoyé depuis un header (Authorization) ou paramètre
        $role = $request->header('X-User-Role') ?? $request->query('role');

        if (!in_array($role, [Role::Admin->value, Role::Client->value])) {
            return response()->json(['message' => 'Rôle non autorisé.'], 403);
        }

        // On stocke le rôle dans la requête pour le contrôleur
        $request->merge(['role' => $role]);

        return $next($request);
    }
}
