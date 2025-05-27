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
        Schema::table('player_tests', function (Blueprint $table) {
            $table->float('results')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('player_tests', function (Blueprint $table) {
            $table->float('results')->nullable(false)->change(); // revert if needed
        });
    }
};
