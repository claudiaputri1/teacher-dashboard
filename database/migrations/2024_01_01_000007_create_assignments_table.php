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
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('classroom_id');
            $table->uuid('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->uuid('module_id')->nullable();
            $table->uuid('lesson_id')->nullable();
            $table->string('assignment_type')->default('essay'); // essay, quiz, project, etc.
            $table->timestamp('due_date')->nullable();
            $table->integer('max_score')->default(100);
            $table->json('rubric')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('set null');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
