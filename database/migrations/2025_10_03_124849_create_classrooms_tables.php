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
        // Create classrooms table
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('teacher_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('class_code')->unique();
                $table->string('school_name')->nullable();
                $table->string('grade_level')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->foreign('teacher_id')->references('id')->on('profiles')->onDelete('cascade');
            });
        }

        // Create classroom_members table
        if (!Schema::hasTable('classroom_members')) {
            Schema::create('classroom_members', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('classroom_id');
                $table->uuid('student_id');
                $table->timestamp('joined_at')->useCurrent();
                
                $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('profiles')->onDelete('cascade');
                $table->unique(['classroom_id', 'student_id']);
            });
        }

        // Create student_progress table
        if (!Schema::hasTable('student_progress')) {
            Schema::create('student_progress', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->uuid('lesson_id');
                $table->string('status')->default('not_started');
                $table->integer('completion_percentage')->nullable();
                $table->integer('quiz_score')->nullable();
                $table->integer('time_spent_seconds')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('profiles')->onDelete('cascade');
                $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
                $table->unique(['user_id', 'lesson_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('classroom_members');
        Schema::dropIfExists('classrooms');
    }
};
