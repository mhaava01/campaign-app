<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="Authenticate",
     *      description="Authenticates the user and returns the Bearer token",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             example={"email": "hello@example.com", "password": "password"}
     *         )
     *      ),
     *
     *      @OA\Response(response=204, ref="#/components/responses/204"),
     *      @OA\Response(response=422, ref="#/components/responses/422"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     *      @OA\Response(response=503, ref="#/components/responses/503"),
     * )
     *
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Authentication"},
     *      summary="Unauthenticate",
     *      description="Unauthenticate the user",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Response(response=204, ref="#/components/responses/204"),
     *      @OA\Response(response=401, ref="#/components/responses/401"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     *      @OA\Response(response=503, ref="#/components/responses/503"),
     * )
     *
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
