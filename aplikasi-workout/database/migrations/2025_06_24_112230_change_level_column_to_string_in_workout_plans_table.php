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
        Schema::table('workout_plans', function (Blueprint $table) {
            // Mengubah tipe kolom 'level' menjadi string (VARCHAR)
            // Ini akan mengizinkan kita menyimpan 'beginner', 'intermediate', dll.
            $table->string('level')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_plans', function (Blueprint $table) {
            // Jika kita rollback, kembalikan ke tipe semula (asumsi sebelumnya integer)
            // Ini adalah praktik yang baik, meskipun mungkin tidak akan Anda gunakan.
            $table->integer('level')->change();
        });
    }
};