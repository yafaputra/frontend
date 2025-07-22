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
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('course_description')->onDelete('cascade');
            $table->timestamp('enrolled_at')->nullable();
            $table->integer('progress')->default(0); // Progress in percentage
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Ensure user can only enroll in a course once
            $table->unique(['user_id', 'course_id']);

            // Index for better query performance
            $table->index(['user_id', 'enrolled_at']);
            $table->index(['course_id', 'enrolled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_courses');
    }
};
