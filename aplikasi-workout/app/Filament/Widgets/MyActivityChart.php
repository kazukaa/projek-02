<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Latihan Mingguan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = Auth::user();
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(6);

        $logs = $user->workoutLogs()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as date, COALESCE(SUM(duration_seconds), 0) as total_duration")
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->toDateString();
            $labels[] = $date->translatedFormat('l');

            if (isset($logs[$dateString])) {
                $durationInSeconds = (float) $logs[$dateString]['total_duration'];
                $data[] = round($durationInSeconds / 60, 2);
            } else {
                $data[] = 0;
            }
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
