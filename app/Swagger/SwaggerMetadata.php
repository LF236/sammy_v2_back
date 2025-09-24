<?php
namespace App\Swagger;


/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="soporte@tuapp.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor API Principal"
 * )
 * 
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Operaciones de autenticación"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 *     description="Autenticación mediante JWT Token"
 * )
 */
class SwaggerMetadata {
    // Clase vacía solo para propósitos de documentación
}