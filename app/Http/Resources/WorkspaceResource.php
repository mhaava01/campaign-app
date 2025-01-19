<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="WorkspaceResource",
 *     description="Workspace resource",
 *
 *     @OA\Xml(
 *         name="WorkspaceResource"
 *     )
 * )
 */
class WorkspaceResource extends JsonResource
{
    /**
     * @OA\Property(property="id", title="ID", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     * @OA\Property(property="name", title="Name", type="string", example="My Workspace"),
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
            'name' => $this->whenHas('name'),
        ];
    }
}
