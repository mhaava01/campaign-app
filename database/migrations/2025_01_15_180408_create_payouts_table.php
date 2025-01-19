<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('country_id')
                ->constrained()
                ->references('id')
                ->on('countries')
                ->cascadeOnDelete();
            $table->foreignUuid('campaign_id')
                ->constrained()
                ->references('id')
                ->on('campaigns')
                ->cascadeOnDelete();
            $table->decimal('amount_per_interaction');
            $table->timestamps();

            $table->unique(['country_id', 'campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
