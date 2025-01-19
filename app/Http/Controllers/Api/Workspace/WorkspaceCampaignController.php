<?php

namespace App\Http\Controllers\Api\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorkspaceCampaignController extends Controller
{
    protected function gate(string $workspaceId): void
    {
        Gate::allowIf(fn (User $user): bool => $user
            ->workspaces()
            ->where('workspace_id', $workspaceId)
            ->exists());
    }

    /**
     * @OA\Get(
     *      path="/workspaces/{id}/campaigns",
     *      operationId="getWorkspaceCampaignsList",
     *      tags={"Workspace"},
     *      summary="Display a listing of the resource.",
     *      description="Display a listing of the resource.",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(ref="#/components/parameters/id"),
     *      @OA\Parameter(ref="#/components/parameters/page[number]"),
     *      @OA\Parameter(ref="#/components/parameters/page[size]"),
     *      @OA\Parameter(
     *        name="include",
     *        description="Include relationships",
     *        in="query",
     *
     *        @OA\Schema(
     *            type="string",
     *            example=""
     *        )
     *      ),
     *
     *     @OA\Parameter(
     *         name="filter[search]",
     *         description="Search by title",
     *         in="query",
     *
     *         @OA\Schema(
     *             type="string",
     *             example=""
     *         )
     *       ),
     *
     *      @OA\Parameter(
     *          name="filter[activity_status]",
     *          description="Filter by activity status",
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string",
     *              enum={
     *                  "active",
     *                  "paused",
     *              },
     *              example="paused"
     *          )
     *        ),
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
     *                  @OA\Items(ref="#/components/schemas/CampaignResource"),
     *              ),
     *
     *              @OA\Property(property="meta", ref="#/components/schemas/meta")
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
    public function index(string $workspaceId): ResourceCollection
    {
        $this->gate($workspaceId);

        $customers = QueryBuilder::for(Campaign::class)
            ->allowedIncludes(['payouts.country'])
            ->allowedFilters([
                AllowedFilter::callback('search', fn (Builder $q, string $value): Builder => $q
                    ->where('title', 'ILIKE', '%'.$value.'%')),
                AllowedFilter::exact('activity_status'),
            ])
            ->defaultSort('-created_at')
            ->where('workspace_id', $workspaceId)
            ->jsonPaginate();

        return CampaignResource::collection($customers);
    }

    /**
     * @OA\Post(
     *       path="/workspaces/{id}/campaigns",
     *       operationId="storeWorkspaceCampaign",
     *       tags={"Workspace"},
     *       summary="Create campaign",
     *       description="Create campaign",
     *       security={ {"sanctum": {} }},
     *
     *       @OA\Parameter(ref="#/components/parameters/id"),
     *
     *       @OA\RequestBody(
     *           required=true,
     *
     *           @OA\JsonContent(
     *              type="object",
     *
     *               @OA\Property(property="title", type="string"),
     *               @OA\Property(property="landing_page_url", type="string"),
     *               @OA\Property(property="activity_status", type="string", enum={"active", "paused"}),
     *               @OA\Property(
     *                  property="payouts",
     *                  type="array",
     *
     *                  @OA\Items(
     *
     *                      @OA\Property(property="country_id", type="string"),
     *                      @OA\Property(property="amount_per_interaction", type="number"),
     *                  )),
     *               example={"title": "Example Campaign", "landing_page_url": "https://google.com/", "activity_status": "paused", "payouts": {{"country_id": "550e8400-e29b-41d4-a716-446655440000", "amount_per_interaction": 2.99}}}
     *          )
     *       ),
     *
     *       @OA\Response(response=201, ref="#/components/responses/201"),
     *       @OA\Response(response=204, ref="#/components/responses/204"),
     *       @OA\Response(response=401, ref="#/components/responses/401"),
     *       @OA\Response(response=500, ref="#/components/responses/500"),
     *       @OA\Response(response=503, ref="#/components/responses/503"),
     * )
     *
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request, string $workspaceId): CampaignResource
    {
        $this->gate($workspaceId);

        $consumer = Campaign::create([
            ...$request->validated(),
            'workspace_id' => $workspaceId,
        ]);

        $consumer->payouts()->createMany($request->validated('payouts'));

        return new CampaignResource($consumer);
    }
}
