<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Ganti 'password' jika perlu
        ]);

        // Panggil seeder Anda yang lain
        $this->call([
            AchievementSeeder::class,
        ]);
    }
}