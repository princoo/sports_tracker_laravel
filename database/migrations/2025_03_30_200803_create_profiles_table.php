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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nationality');
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER']);
            $table->string('phone')->nullable()->unique();
            $table->text('bio')->nullable();
            $table->uuid('avatar_id')->nullable();
            $table->timestamps();
                    
            // Index on user_id
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
