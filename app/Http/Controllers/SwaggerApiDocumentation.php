<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestion Comptes Bancaires",
 *      description="API REST pour la gestion des comptes bancaires, utilisateurs et transactions",
 *      @OA\Contact(
 *          email="contact@banque.com"
 *      )
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur API"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     name="Authorization",
 *     in="header",
 *     description="Token Bearer pour l'authentification"
 * )
 */
class SwaggerApiDocumentation
{
    // Cette classe ne contient que des annotations Swagger
}
