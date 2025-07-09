<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Nama jadwal, misal: "Fokus Perut Jumat"
            $table->text('description')->nullable(); // Deskripsi singkat dari pengguna
            $table->string('day_of_week')->nullable(); // Opsional: untuk hari apa jadwal ini (Senin, Selasa, dll.)
            $table->boolean('is_rest_day')->default(false); // Penanda untuk Fitur "Rest Day"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_schedules');
    }
};