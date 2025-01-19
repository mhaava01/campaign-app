<?php

use App\Http\Controllers\Api\Campaign\CampaignController;
use App\Http\Controllers\Api\Country\CountryController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Workspace\WorkspaceCampaignController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group([
        'prefix' => 'user',
        'as' => 'user.',
    ], function () {
        Route::get('/', [UserController::class, 'show'])
            ->name('show');
    });

    Route::group([
        'prefix' => 'workspaces',
        'as' => 'workspaces.',
    ], function () {
        Route::get('/{workspaceId}/campaigns', [WorkspaceCampaignController::class, 'index'])
            ->name('campaigns.index');

        Route::post('/{workspaceId}/campaigns', [WorkspaceCampaignController::class, 'store'])
            ->name('campaigns.store');
    });

    Route::group([
        'prefix' => 'campaigns',
        'as' => 'campaigns.',
    ], function () {

        Route::patch('/{campaignId}', [CampaignController::class, 'update'])
            ->name('update');
    });

    Route::get('/countries', CountryController::class)
        ->name('countries.index');
});
