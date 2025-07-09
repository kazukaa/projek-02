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
        $user = Auth::user();
        $endDate = now();
        $startDate = $endDate->copy()->subDays(6);

        // Ambil semua workout log selama 7 hari terakhir, termasuk relasi exercise
        $logs = $user->workoutLogs()
            ->with('exercise')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // dd($logs); // Debugging: Periksa data yang diambil

        // Kelompokkan berdasarkan tanggal
        $durasiPerTanggal = [];

        foreach ($logs as $log) {
            $tanggal = $log->created_at->format('Y-m-d');
            $durasi = $log->exercise->duration_seconds ?? 0;

            if (!isset($durasiPerTanggal[$tanggal])) {
                $durasiPerTanggal[$tanggal] = 0;
            }

            $durasiPerTanggal[$tanggal] += $durasi;
        }

        // Format label dan data
        $labels = [];
        $data = [];

        foreach ($logs as $log) {
            $tanggal = Carbon::parse($log->created_at)->toDateString();
            $durasi = $log->exercise->duration_seconds ?? 0;

            if (!isset($durasiPerTanggal[$tanggal])) {
                $durasiPerTanggal[$tanggal] = 0;
            }

            $durasiPerTanggal[$tanggal] += $durasi;
        }

        return [
            'datasets' => [[
                'label' => 'Durasi Latihan (Menit)',
                'data' => $data,
                'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
            ]],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
