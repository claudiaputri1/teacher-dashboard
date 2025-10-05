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
        Schema::create('lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('module_id');
            $table->string('slug');
            $table->string('title');
            $table->string('lesson_type')->nullable();
            $table->integer('order_index')->default(0);
            $table->text('content_markdown')->nullable();
            $table->string('video_url')->nullable();
            $table->json('scene_config')->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->integer('xp_reward')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->unique(['module_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
