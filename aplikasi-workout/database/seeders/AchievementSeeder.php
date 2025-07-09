<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan DB::table() untuk memasukkan data awal
        DB::table('achievements')->insert([
            [
                'name'          => 'Latihan Pertama',
                'description'   => 'Menyelesaikan latihan pertamamu',
                'image_url'     => 'achievement-icons/first.png', // Path diperbaiki
                'type'          => 'total_exercises_completed',
                'requirement'   => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Pemanasan',
                'description'   => 'Menyelesaikan 5 total latihan',
                'image_url'     => 'achievement-icons/warming-up.png', // Path diperbaiki
                'type'          => 'total_exercises_completed',
                'requirement'   => 5,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Gigih',
                'description'   => 'Menyelesaikan 10 total latihan',
                'image_url'     => 'achievement-icons/persistent.png', // Path diperbaiki
                'type'          => 'total_exercises_completed',
                'requirement'   => 10,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}