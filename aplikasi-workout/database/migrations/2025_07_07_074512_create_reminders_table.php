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
    Schema::create('reminders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title')->default('Waktunya Latihan!');
        $table->text('message')->default('Jangan lupa jadwal latihanmu hari ini.');
        $table->time('remind_at'); // Waktu pengingat, misal: 08:00
        $table->json('days_of_week'); // Hari apa saja, misal: ['Monday', 'Wednesday']
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
