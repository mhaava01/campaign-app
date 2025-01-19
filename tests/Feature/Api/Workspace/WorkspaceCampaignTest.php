<?php

use App\Models\Campaign;
use App\Models\User;
use App\Models\Workspace;

describe('workspace campaign', function () {
    beforeEach(function () {
        $user = User::factory()
            ->hasAttached(Workspace::factory()
                ->has(Campaign::factory(3)))
            ->create();

        $this->actingAs($user);

        $this->workspaceId = $user->workspaces->first()->id;
    });

    test('can read campaigns', function () {
        $response = $this->getJson(route('workspaces.campaigns.index', ['workspaceId' => $this->workspaceId]));

        $response->assertJsonCount(3, 'data');
    });

    test('can create campaign', function () {
        $response = $this->postJson(route('workspaces.campaigns.store', ['workspaceId' => $this->workspaceId]), [
            'title' => fake()->sentence,
            'landing_page_url' => fake()->url,
            'activity_status' => \App\Enums\ActivityStatus::ACTIVE,
            'payouts' => [
                [
                    'country_id' => \App\Models\Country::first()->id,
                    'amount_per_interaction' => 2.99,
                ],
            ],
        ]);

        $response->assertCreated();
    });

    test('cannot create campaign without payouts', function () {
        $response = $this->postJson(route('workspaces.campaigns.store', ['workspaceId' => $this->workspaceId]), [
            'title' => fake()->sentence,
            'landing_page_url' => fake()->url,
            'activity_status' => \App\Enums\ActivityStatus::ACTIVE,
            'payouts' => [],
        ]);

        $response->assertUnprocessable();
    });
});
