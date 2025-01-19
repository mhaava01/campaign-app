<?php

namespace App\Http\Controllers\Api\Country;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use OpenApi\Annotations as OA;

class CountryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/countries",
     *      operationId="getCountriesList",
     *      tags={"Country"},
     *      summary="Display a listing of the resource.",
     *      description="Display a listing of the resource.",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(ref="#/components/schemas/CountryResource"),
     *              ),
     *          )
     *      ),
     *
     *      @OA\Response(response=401, ref="#/components/responses/401"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     *      @OA\Response(response=503, ref="#/components/responses/503"),
     * )
     *
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        return CountryResource::collection(Country::all());
    }
}
