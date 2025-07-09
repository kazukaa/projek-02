<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();

            // Menghubungkan ke pengguna dan pencapaian
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            
            // Mencatat kapan lencana ini didapatkan
            $table->timestamp('unlocked_at');

            // Memastikan seorang pengguna tidak bisa mendapatkan lencana yang sama dua kali
            $table->unique(['user_id', 'achievement_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
