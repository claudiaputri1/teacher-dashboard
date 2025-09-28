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
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id');
            $table->datetime('deadline');
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->json('resources')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('class_siswas')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
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
