<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use OpenApi\Annotations as OA;

class CampaignController extends Controller
{
    protected function gate(string $campaignId): void
    {
        Gate::allowIf(fn (User $user): bool => $user
            ->workspaces()
            ->whereRelation('campaigns', 'id', $campaignId)
            ->exists());
    }

    /**
     * @OA\Patch(
     *      path="/campaigns/{id}",
     *      operationId="updateCampaign",
     *      tags={"Campaign"},
     *      summary="Update campaign",
     *      description="Update campaign",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(ref="#/components/parameters/id"),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *             type="object",
     *
     *              @OA\Property(
     *                  property="activity_status",
     *                  type="string",
     *                  enum={"active", "paused"}
     *              ),
     *              example={"activity_status": "active"}
     *         )
     *      ),
     *
     *      @OA\Response(response=204, ref="#/components/responses/204"),
     *      @OA\Response(response=401, ref="#/components/responses/401"),
     *      @OA\Response(response=500, ref="#/components/responses/500"),
     *      @OA\Response(response=503, ref="#/components/responses/503"),
     * )
     *
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, string $campaignId): \Illuminate\Http\Response
    {
        $this->gate($campaignId);

        Campaign::where('id', $campaignId)->update($request->validated());

        return Response::noContent();
    }
}
