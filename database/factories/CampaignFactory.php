<?php

namespace Database\Factories;

use App\Enums\ActivityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'landing_page_url' => fake()->url(),
            'activity_status' => fake()->randomElement([ActivityStatus::ACTIVE->value, ActivityStatus::PAUSED->value]),
        ];
    }
}
