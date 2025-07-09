<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserActivity;

class AchievementService
{
    /**
     * Metode utama untuk memeriksa dan memberikan semua pencapaian yang relevan kepada pengguna.
     */
    public function checkAndAwardAchievements(User $user)
    {
        $this->checkTotalExercisesCompleted($user);
        // Anda bisa menambahkan pengecekan lain di sini di masa depan
        // $this->checkWorkoutsInARow($user);
        // $this->checkDifferentCategories($user);
    }

    /**
     * Memeriksa pencapaian berdasarkan total latihan yang diselesaikan.
     */
    private function checkTotalExercisesCompleted(User $user)
    {
        // 1. Dapatkan semua lencana tipe ini yang BELUM dimiliki user
        $unearnedAchievements = Achievement::where('type', 'total_exercises_completed')
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        // Jika tidak ada lencana baru untuk dikejar, hentikan
        if ($unearnedAchievements->isEmpty()) {
            return;
        }

        // 2. Hitung progres pengguna saat ini
        $progress = UserActivity::where('user_id', $user->id)
            ->where('activity_type', 'exercise_completed')
            ->count();

        // 3. Periksa setiap lencana yang belum didapat
        foreach ($unearnedAchievements as $achievement) {
            if ($progress >= $achievement->requirement) {
                // 4. Berikan lencana jika syarat terpenuhi!
                $user->achievements()->attach($achievement->id);

                // Kirim notifikasi ke pengguna (opsional tapi bagus)
                \Filament\Notifications\Notification::make()
                    ->title('Pencapaian Baru Terbuka!')
                    ->body("Selamat, Anda mendapatkan lencana: {$achievement->name}")
                    ->success()
                    ->sendToDatabase($user);
            }
        }
    }
}