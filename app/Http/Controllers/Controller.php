<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Campaign tool API Documentation",
 *      description="",
 *
 *      @OA\Contact(
 *          email="hello@example.com"
 *      ),
 * )
 *
 *  @OA\Server(
 *        url=L5_SWAGGER_CONST_HOST,
 *        description="V1"
 *   )
 *
 * @OA\Get(
 *      path="/csrf-cookie",
 *      operationId="csrfCookie",
 *      tags={"Authentication"},
 *      summary="Get CSRF Cookie",
 *      description="To authenticate your SPA, your SPA's 'login' page should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application. https://laravel.com/docs/9.x/sanctum#spa-authentication",
 *
 *      @OA\Response(response=204, ref="#/components/responses/204"),
 *      @OA\Response(response=500, ref="#/components/responses/500"),
 *      @OA\Response(response=503, ref="#/components/responses/503"),
 * )
 *
 * @OA\Parameter(
 *      name="id",
 *      description="Resource id",
 *      required=true,
 *      in="path",
 *
 *      @OA\Schema(
 *          type="string"
 *      )
 * )
 *
 * @OA\Parameter(
 *      name="page[number]",
 *      description="Page number",
 *      in="query",
 *
 *      @OA\Schema(
 *         type="integer"
 *      )
 * )
 *
 * @OA\Parameter(
 *      name="page[size]",
 *      description="Page size",
 *      in="query",
 *
 *      @OA\Schema(
 *          type="integer"
 *      )
 * )
 *
 * @OA\Parameter(
 *      name="sort",
 *      description="Sort by",
 *      in="query",
 *
 *      @OA\Schema(
 *          type="string",
 *          example="name"
 *      )
 * )
 *
 * @OA\Parameter(
 *      name="include",
 *      description="Include relationships",
 *      in="query",
 *
 *      @OA\Schema(
 *          type="string",
 *          example="workspaces,payouts"
 *      )
 * )
 *
 * @OA\Response(
 *      response=500,
 *      description="Server Error",
 *
 *      @OA\JsonContent(example={"message": "Server Error."})
 * )
 *
 * @OA\Response(
 *      response=503,
 *      description="Service Unavailable - Maintenance Mode",
 *
 *      @OA\JsonContent(example={"message": "Service Unavailable."})
 * )
 *
 * @OA\Response(
 *      response=401,
 *      description="Unauthenticated",
 *
 *      @OA\JsonContent(example={"message": "Unauthenticated."})
 * )
 *
 * @OA\Response(
 *      response=404,
 *      description="Not Found",
 *
 *      @OA\JsonContent(example={"message": "Not Found."})
 * )
 *
 * @OA\Response(
 *      response=419,
 *      description="Session expired",
 * )
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *
 *      @OA\JsonContent(example={"message": "The phone must be a number. (and 1 more error)","errors": {"phone": {"The phone must be a number.","The phone must be between 5 and 16 digits."}}})
 * )
 *
 *  @OA\Response(
 *       response=201,
 *       description="Created",
 *
 *       @OA\JsonContent(example={})
 *  )
 *
 * @OA\Response(
 *      response=204,
 *      description="No Content",
 *
 *      @OA\JsonContent(example={})
 * )
 *
 * @OA\Response(
 *      response="resources",
 *      description="Successful operation",
 *
 *      @OA\JsonContent(example={"data": {}, "meta": {"current_page": 1, "last_page": 1, "per_page": 5, "total": 5}})
 * )
 *
 * @OA\Response(
 *      response="resource",
 *      description="Successful operation",
 *
 *      @OA\JsonContent(example={"data": {}})
 * )
 *
 * @OA\Schema(
 *      schema="meta",
 *      type="object",
 *      description="Meta data",
 *
 *      @OA\Property(property="current_page", title="Current Page", type="integer", example=1),
 *      @OA\Property(property="last_page", title="Last Page", type="integer", example=1),
 *      @OA\Property(property="per_page", title="Per Page", type="integer", example=30),
 *      @OA\Property(property="total", title="Total", type="integer", example=100),
 * )
 */
abstract class Controller
{
    //
}
