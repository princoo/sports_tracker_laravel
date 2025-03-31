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
        Schema::create('players', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->float('age');
            $table->float('height');
            $table->float('weight');
            $table->string('foot');
            $table->string('nationality');
            $table->string('acad_status');
            $table->timestamp('dob');
            $table->string('gender');
            $table->json('positions'); // Using JSON type for array of positions
            $table->foreignUuid('site_id')->constrained('sites')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('site_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
