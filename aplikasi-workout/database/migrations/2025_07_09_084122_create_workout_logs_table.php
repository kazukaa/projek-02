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
        // Tabel ini akan mencatat setiap kali seorang pengguna menyelesaikan sebuah latihan.
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id();

            // Kolom 'user_id' untuk tahu siapa yang melakukan latihan.
            // Jika user dihapus, semua catatannya juga akan terhapus (cascadeOnDelete).
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Kolom 'exercise_id' untuk tahu latihan apa yang diselesaikan.
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();

            // Kolom 'workout_plan_id' (opsional) jika latihan ini adalah bagian dari sebuah rencana.
            // $table->foreignId('workout_plan_id')->nullable()->constrained()->nullOnDelete();

            // (Opsional) Data spesifik saat latihan diselesaikan.
            // $table->integer('reps')->nullable(); // Jumlah repetisi yang dilakukan.
            // $table->integer('duration_seconds')->nullable(); // Durasi yang dicatat.

            $table->timestamps(); // Mencatat kapan log ini dibuat.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
