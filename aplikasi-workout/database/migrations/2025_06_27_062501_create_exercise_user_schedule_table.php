<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // .../database/migrations/..._create_exercise_user_schedule_table.php

public function up(): void
{
    Schema::create('exercise_user_schedule', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_schedule_id')->constrained()->cascadeOnDelete();
        $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();

        // PASTIKAN SEMUA KOLOM PIVOT ADA DI SINI
        $table->integer('urutan')->default(1);
        $table->integer('repetisi')->nullable();
        $table->integer('duration_seconds');

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('exercise_user_schedule');
    }
};