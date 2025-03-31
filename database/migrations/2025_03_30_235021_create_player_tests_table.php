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
        Schema::create('player_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Primary key for the table
            $table->foreignUuid('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreignUuid('test_id')->references('id')->on('tests')->onDelete('cascade');
            $table->foreignUuid('recorder_by')->references('id')->on('users');
            $table->foreignUuid('session_id')->references('id')->on('test_sessions')->onDelete('cascade');
            $table->timestamp('recorded_at');
            $table->float('results');
            $table->timestamps();

            // Index for the combination of player and test
            $table->index(['player_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_tests');
    }
};
