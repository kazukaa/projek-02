<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MyActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Latihan Mingguan';
    protected static ?int $sort = 2; // Tampilkan di bawah statistik

    protected function getData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(6);

        $logs = $user->workoutLogs()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(duration_seconds) as total_duration'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('l'); // Nama hari dalam Bahasa Indonesia
            $data[] = isset($logs[$dateString]) ? round($logs[$dateString]->total_duration / 60) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Durasi Latihan (Menit)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
