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
        Schema::create('user_workspace', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->constrained()
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreignUuid('workspace_id')
                ->constrained()
                ->references('id')
                ->on('workspaces')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'workspace_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_workspace');
    }
};
