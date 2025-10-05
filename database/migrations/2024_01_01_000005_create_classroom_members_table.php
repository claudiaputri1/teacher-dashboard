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
        Schema::create('classroom_members', function (Blueprint $table) {
            $table->uuid('classroom_id');
            $table->uuid('student_id');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->primary(['classroom_id', 'student_id']);
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_members');
    }
};
