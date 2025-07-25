<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_content', function (Blueprint $table) {
            $table->id();

            // Foreign key ke table course_description
            $table->foreignId('course_description_id')->constrained('course_description')->onDelete('cascade');

            $table->string('course_title');           // Contoh: Belajar Laravel
            $table->string('slug')->unique();         // Contoh: pengenalan-laravel
            $table->string('judul');                  // Judul materi
            $table->text('konten');                   // Konten materi (HTML)
            $table->integer('urutan')->default(0);    // Urutan materi
            $table->timestamps();                     // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_content');
    }
};
