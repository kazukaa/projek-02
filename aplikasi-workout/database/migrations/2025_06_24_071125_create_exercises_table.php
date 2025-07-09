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
        Schema::create('exercises', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->string('video_url')->nullable();
        $table->enum('difficulty', ['pemula', 'menengah', 'lanjut']);
        $table->integer('duration_seconds')->default(30);
        // Nanti kita akan tambahkan relasi ke Category di sini
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
