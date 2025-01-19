<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Country;
use App\Models\Payout;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->state([
                'name' => 'Demo User',
                'email' => 'user@example.com',
            ])
            ->hasAttached(Workspace::factory()
                ->state([
                    'name' => 'My Workspace',
                ])
                ->has(Campaign::factory(50)
                    ->has(Payout::factory(1)
                        ->state(new Sequence(
                            fn (Sequence $sequence) => [
                                'country_id' => Country::inRandomOrder()->first('id')->id,
                            ],
                        ))
                    )
                )
            )
            ->create();
    }
}
