<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="PayoutResource",
 *     description="Payout resource",
 *
 *     @OA\Xml(
 *         name="PayoutResource"
 *     )
 * )
 */
class PayoutResource extends JsonResource
{
    /**
     * @OA\Property(property="id", title="ID", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     * @OA\Property(property="amount_per_interaction", title="Amount per interaction", type="number", example="2.99"),
     * @OA\Property(property="country", ref="#/components/schemas/CountryResource"),
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
            'amount_per_interaction' => $this->whenHas('amount_per_interaction'),
            'country' => new CountryResource($this->whenLoaded('country')),
        ];
    }
}
