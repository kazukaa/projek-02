<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel untuk menyimpan definisi semua lencana pencapaian.
     */
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Nama lencana, misal: "Pejuang Pagi"
            $table->text('description');                 // Deskripsi syarat mendapatkannya
            $table->string('image_url')->nullable();     // Path/URL ke gambar ikon lencana
            
            // Aturan untuk mendapatkan lencana secara otomatis
            $table->string('type');                      // Tipe aksi, misal: 'total_exercises_completed'
            $table->integer('requirement');              // Jumlah yang dibutuhkan, misal: 10
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel jika migrasi dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};