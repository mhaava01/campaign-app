<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     *  @OA\Get(
     *       path="/user",
     *       operationId="getUser",
     *       tags={"User"},
     *       summary="Display authenticated user",
     *       description="Display authenticated user",
     *       security={ {"sanctum": {} }},
     *
     *       @OA\Parameter(
     *           name="include",
     *           description="Include relationships",
     *           in="query",
     *
     *           @OA\Schema(
     *               type="string",
     *               example="workspaces"
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(
     *
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/UserResource",
     *               ),
     *           )
     *       ),
     *
     *       @OA\Response(response=401, ref="#/components/responses/401"),
     *       @OA\Response(response=500, ref="#/components/responses/500"),
     *       @OA\Response(response=503, ref="#/components/responses/503"),
     *  )
     *
     *  Display the specified resource.
     */
    public function show(): UserResource
    {
        $user = QueryBuilder::for(User::class)
            ->allowedFields('id', 'name', 'email')
            ->allowedIncludes('workspaces')
            ->findOrFail(Auth::id());

        return new UserResource($user);
    }
}
