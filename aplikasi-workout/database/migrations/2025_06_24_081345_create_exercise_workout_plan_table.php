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
        Schema::create('exercise_workout_plan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
        $table->integer('order')->comment('Urutan latihan dalam plan');
        $table->integer('reps')->nullable()->comment('Jumlah repetisi');
        $table->integer('duration_seconds')->nullable()->comment('Durasi dalam detik');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_workout_plan');
    }
};
