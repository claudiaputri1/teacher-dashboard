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
        // Create assignments table
        if (!Schema::hasTable('assignments')) {
            Schema::create('assignments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('classroom_id');
                $table->uuid('teacher_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->uuid('module_id')->nullable();
                $table->uuid('lesson_id')->nullable();
                $table->string('assignment_type');
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

        // Create assignment_submissions table
        if (!Schema::hasTable('assignment_submissions')) {
            Schema::create('assignment_submissions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('assignment_id');
                $table->uuid('student_id');
                $table->text('submission_text')->nullable();
                $table->json('submission_files')->nullable();
                $table->timestamp('submitted_at')->useCurrent();
                $table->string('status')->default('draft');
                $table->integer('score')->nullable();
                $table->json('ai_feedback')->nullable();
                $table->text('teacher_feedback')->nullable();
                $table->integer('teacher_override_score')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->uuid('graded_by')->nullable();
                $table->timestamp('updated_at')->useCurrent();
                
                $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('profiles')->onDelete('cascade');
                $table->foreign('graded_by')->references('id')->on('profiles')->onDelete('set null');
                $table->unique(['assignment_id', 'student_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
};
