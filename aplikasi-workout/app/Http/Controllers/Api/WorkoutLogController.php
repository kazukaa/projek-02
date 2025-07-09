<?php

namespace App\Http\Controllers\Api;

use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutLogResource;
use Illuminate\Support\Facades\Auth;
use App\Events\WorkoutLogged;
use Illuminate\Support\Facades\Log;

class WorkoutLogController extends Controller
{
    /**
     * Menampilkan riwayat latihan milik pengguna yang sedang login.
     */
    public function index()
    {
        // 1. Ambil ID pengguna yang sedang login dari token.
        $userId = Auth::id();

        // 2. Ambil semua log yang 'user_id'-nya cocok, urutkan dari yang terbaru.
        $logs = WorkoutLog::where('user_id', $userId)
            ->with('exercise') // Muat data latihannya juga
            ->latest() // Urutkan dari yang paling baru
            ->get();

        // 3. Kembalikan data dalam bentuk koleksi resource.
        return WorkoutLogResource::collection($logs);
    }

    /**
     * Menyimpan catatan latihan baru.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $exerciseId = $request->input('exercise_id');
        $duration = $request->input('duration_seconds', 0);

        WorkoutLog::create([
            'user_id' => $userId,
            'exercise_id' => $exerciseId,
            'duration_seconds' => $duration,
        ]);

        $logCount = WorkoutLog::where('user_id', $userId)->count();

        $eligibleAchievements = \App\Models\Achievement::where('requirement', '<=', $logCount)->get();


        $existingAchievementIds = \App\Models\UserAchievement::where('user_id', $userId)
            ->pluck('achievement_id')
            ->toArray();

        Log::info('Jumlah workout log user: ' . $logCount);
        Log::info('Eligible achievements: ', $eligibleAchievements->pluck('id')->toArray());
        Log::info('Existing achievements: ', $existingAchievementIds);

        foreach ($eligibleAchievements as $achievement) {
            \App\Models\UserAchievement::firstOrCreate(
                [
                    'user_id' => $userId,
                    'achievement_id' => $achievement->id,
                ],
                [
                    'unlocked_at' => now(),
                ]
            );
        }

        return response()->json([
            'message' => 'Workout log created successfully',
            'unlocked_achievements' => $eligibleAchievements->pluck('name'),
        ], 201);
    }
}
