<?php

namespace App\Traits;

use App\Enums\HttpStatusCodes;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Réponse JSON formatée pour les succès
     */
    public function successResponse($data = [], $message = '', $pagination = null, $links = null, $code = HttpStatusCodes::OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'pagination' => $pagination,
            'links' => $links,
        ], $code instanceof HttpStatusCodes ? $code->value : $code);
    }

    /**
     * Réponse JSON formatée pour les erreurs
     */
    public function errorResponse($message = '', $code = HttpStatusCodes::BAD_REQUEST, $data = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        // Pour les erreurs de validation (400), on utilise le format avec 'error'
        if ($code instanceof HttpStatusCodes && $code === HttpStatusCodes::BAD_REQUEST) {
            $response['error'] = [
                'code' => HttpStatusCodes::BAD_REQUEST,
                'message' => $message,
                'details' => $data
            ];
            unset($response['message']);
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $code instanceof HttpStatusCodes ? $code->value : $code);
    }
}
