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
        // Add school_name to teachers table if not exists
        if (!Schema::hasColumn('teachers', 'school_name')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('school_name')->nullable()->after('email');
            });
        }

        // Create classes table
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('teacher_id');
                $table->string('academic_year');
                $table->integer('max_capacity')->default(30);
                $table->timestamps();
                
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
                $table->index(['teacher_id']);
            });
        }

        // Create students table
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('nis')->nullable();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('teacher_id');
                $table->string('status')->default('active');
                $table->timestamps();
                
                $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
                $table->index(['teacher_id', 'class_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        
        // Remove school_name column from teachers table
        if (Schema::hasColumn('teachers', 'school_name')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('school_name');
            });
        }
    }
};
