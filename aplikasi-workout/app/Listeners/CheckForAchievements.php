<?php

namespace App\Listeners;

use App\Events\WorkoutLogged;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CheckForAchievements
{
    public function handle(WorkoutLogged $event): void
    {
        $user = $event->workoutLog->user;

        // Ambil semua lencana yang BELUM dimiliki oleh pengguna
        $possibleAchievements = Achievement::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Loop melalui setiap lencana yang mungkin diraih
        foreach ($possibleAchievements as $achievement) {
            $this->checkAchievement($user, $achievement);
        }
    }

    private function checkAchievement(User $user, Achievement $achievement): void
    {
        $criteria = $achievement->criteria;
        $isAchieved = false;

        // Gunakan match untuk memanggil fungsi pengecekan yang sesuai
        $isAchieved = match ($achievement->type) {
            'workout_count' => $this->checkWorkoutCount($user, $criteria),
            // 'consistency' => $this->checkConsistency($user, $criteria), // Untuk nanti
            // 'exercise_milestone' => $this->checkExerciseMilestone($user, $criteria), // Untuk nanti
            default => false,
        };

        // Jika syarat terpenuhi, berikan lencana!
        if ($isAchieved) {
            $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
            // Nanti di sini kita bisa kirim notifikasi ke pengguna
            Log::info("User {$user->id} unlocked achievement: {$achievement->name}");
        }
    }

    // --- FUNGSI-FUNGSI PENGECEKAN SPESIFIK ---

    private function checkWorkoutCount(User $user, array $criteria): bool
    {
        $requiredCount = $criteria['count'] ?? 0;
        if ($requiredCount > 0) {
            return $user->workoutLogs()->count() >= $requiredCount;
        }
        return false;
    }
}