<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Pengguna';
    protected static string $color = 'success';
    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        // Query untuk mengambil jumlah user yang dibuat per hari
        $data = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pengguna Terdaftar',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Tipe grafik adalah garis
    }
}
