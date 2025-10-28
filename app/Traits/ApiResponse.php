<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Réponse JSON formatée
     */
    public function successResponse($data = [], $message = '', $pagination = null, $links = null, $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'pagination' => $pagination,
            'links' => $links,
        ], $code);
    }

    public function errorResponse($message = '', $code = 400, $data = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
