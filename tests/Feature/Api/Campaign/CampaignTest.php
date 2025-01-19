<?php

use App\Enums\ActivityStatus;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Workspace;

describe('campaign', function () {
    beforeEach(function () {
        $user = User::factory()
            ->hasAttached(Workspace::factory()
                ->has(Campaign::factory()))
            ->create();

        $this->actingAs($user);
    });

    test('can update activity_status', function () {
        $campaign = Campaign::first();

        $newStatus = $campaign->activity_status === ActivityStatus::ACTIVE
            ? ActivityStatus::PAUSED
            : ActivityStatus::ACTIVE;

        $response = $this->patchJson(route('campaigns.update', ['campaignId' => $campaign->id]), [
            'activity_status' => $newStatus,
        ]);

        $response->assertNoContent();
        $this->assertEquals($newStatus, $campaign->fresh()->activity_status);
    });
});
