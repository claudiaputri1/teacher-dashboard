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
        // Create profiles table (users) - simple version first
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('email')->nullable();
                $table->string('full_name')->nullable();
                $table->string('avatar_url')->nullable();
                $table->string('role')->default('student');
                $table->string('school_name')->nullable();
                $table->string('grade_level')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
