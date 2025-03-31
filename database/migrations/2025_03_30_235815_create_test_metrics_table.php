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
        Schema::create('test_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('player_test_id')->references('id')->on('player_tests')->onDelete('cascade');
            $table->float('accuracy')->nullable();
            $table->float('body_position')->nullable();
            $table->float('total_time')->nullable();
            $table->integer('attempts')->nullable();
            $table->integer('successes')->nullable();
            $table->float('power')->nullable();
            $table->integer('cones_touched')->nullable();
            $table->enum('foot', ['LEFT', 'RIGHT', 'BOTH'])->nullable();
            $table->integer('errors')->nullable();
            $table->float('distance')->nullable();
            $table->float('ball_control')->nullable();
            $table->timestamps();

            $table->index('player_test_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_metrics');
    }
};
