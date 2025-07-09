<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Mengambil data dasar
        $workoutLogs = $user->workoutLogs()->get();
        $achievementsCount = $user->achievements()->count();

        // 2. Menghitung statistik utama
        $totalWorkouts = $workoutLogs->count();
        $totalDurationMinutes = $workoutLogs->sum('duration_seconds') / 60;

        // 3. Menghitung data untuk grafik (aktivitas 7 hari terakhir)
        $chartData = $this->getWeeklyActivityChartData($user);

        // 4. Mengambil riwayat 5 latihan terakhir
        $recentHistory = $user->workoutLogs()
                              ->with('exercise')
                              ->latest()
                              ->limit(5)
                              ->get();

        // 5. Menggabungkan semua data ke dalam satu response
        return response()->json([
            'data' => [
                'stats' => [
                    'total_workouts' => $totalWorkouts,
                    'total_duration_minutes' => round($totalDurationMinutes),
                    'streak_days' => 0, // Logika streak bisa ditambahkan nanti
                    'achievements_count' => $achievementsCount,
                ],
                'chart' => $chartData,
                'history' => $recentHistory->map(fn ($log) => [
                    'exercise' => ['name' => $log->exercise->name],
                    'completed_at' => $log->created_at,
                ]),
            ]
        ]);
    }

    private function getWeeklyActivityChartData($user)
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(6);

        // Mengambil data log dalam 7 hari terakhir dan mengelompokkannya per hari
        $logs = $user->workoutLogs()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(duration_seconds) as total_duration'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        // Loop untuk 7 hari terakhir untuk memastikan semua hari ada di grafik
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            $dayName = $date->translatedFormat('l'); // Format hari dalam Bahasa Indonesia

            $labels[] = $dayName;
            $data[] = isset($logs[$dateString]) ? round($logs[$dateString]->total_duration / 60) : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
