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
        Schema::create('session_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('session_id')->references('id')->on('test_sessions')->onDelete('cascade');
            $table->foreignUuid('test_id')->references('id')->on('tests')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate entries
            $table->unique(['session_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_tests');
    }
};
