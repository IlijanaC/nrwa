<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Bank Project API Documentation",
 *     description="API endpoints for managing departments, products, and product types in the Bank Project.",
 *     @OA\Contact(email="tvoj.email@example.com"),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Bank Project API Server"
 * )
 *
 * @OA\Tag(
 *     name="Departments",
 *     description="API Endpoints of Departments"
 * )
 * @OA\Tag(
 *     name="Product Types",
 *     description="API Endpoints of Product Types"
 * )
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints of Products"
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="basicAuth",
 *         type="http",
 *         scheme="basic",
 *         description="HTTP Basic Authentication required to access these endpoints."
 *     ),
 * )
 */
class OpenApiSpec
{
    // Prazna klasa koja služi samo za dokumentaciju
}