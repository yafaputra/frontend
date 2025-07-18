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
        Schema::create('courses__description', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Judul course
            $table->string('tag')->nullable(); // Tag kategori course
            $table->text('overview')->nullable(); // Deskripsi/overview
            $table->string('image_url')->nullable(); // Gambar utama course
            $table->string('thumbnail')->nullable(); // Thumbnail alternatif
            $table->unsignedBigInteger('price')->default(0); // Harga normal
            $table->unsignedBigInteger('price_discount')->nullable(); // Harga diskon (jika ada)
            $table->string('instructor_name')->nullable(); // Nama instruktur
            $table->string('instructor_position')->nullable(); // Posisi instruktur
            $table->string('instructor_image_url')->nullable(); // Foto instruktur
            $table->integer('video_count')->default(0); // Jumlah video
            $table->integer('duration')->default(0); // Durasi total jam
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses__description');
    }
};
