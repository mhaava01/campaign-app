<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="CampaignResource",
 *     description="Campaign resource",
 *
 *     @OA\Xml(
 *         name="CampaignResource"
 *     )
 * )
 */
class CampaignResource extends JsonResource
{
    /**
     * @OA\Property(property="id", title="ID", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     * @OA\Property(property="title", title="Title", type="string", example="Example Campaign"),
     * @OA\Property(property="landing_page_url", title="Landing Page Url", type="string", example="https://example.com/"),
     * @OA\Property(property="activity_status", title="Activity Status", type="string", enum={"active", "paused"}, example="active"),
     * @OA\Property(
     *     property="payouts",
     *     type="array",
     *
     *     @OA\Items(ref="#/components/schemas/PayoutResource"),
     * ),
     */
    private $data;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'title' => $this->whenHas('title'),
            'landing_page_url' => $this->whenHas('landing_page_url'),
            'activity_status' => $this->whenHas('activity_status'),
            'payouts' => PayoutResource::collection($this->whenLoaded('payouts')),
        ];
    }
}
