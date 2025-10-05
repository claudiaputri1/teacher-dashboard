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
        // Create modules table
        if (!Schema::hasTable('modules')) {
            Schema::create('modules', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('slug')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('icon_url')->nullable();
                $table->integer('order_index');
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        // Create lessons table
        if (!Schema::hasTable('lessons')) {
            Schema::create('lessons', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('module_id');
                $table->string('slug')->unique();
                $table->string('title');
                $table->string('lesson_type');
                $table->integer('order_index');
                $table->text('content_markdown')->nullable();
                $table->string('video_url')->nullable();
                $table->json('scene_config')->nullable();
                $table->integer('estimated_duration_minutes')->nullable();
                $table->integer('xp_reward')->default(10);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
                
                $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
    }
};
