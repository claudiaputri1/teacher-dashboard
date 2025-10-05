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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->uuid('student_id');
            $table->text('submission_text')->nullable();
            $table->json('submission_files')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, graded
            $table->decimal('score', 5, 2)->nullable();
            $table->json('ai_feedback')->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->decimal('teacher_override_score', 5, 2)->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->uuid('graded_by')->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('graded_by')->references('id')->on('profiles')->onDelete('set null');
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
