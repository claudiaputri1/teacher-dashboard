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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('lesson_id');
            $table->string('status')->default('not_started'); // not_started, in_progress, completed
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->decimal('quiz_score', 5, 2)->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
