<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Exercise;
use App\Models\WorkoutLog;
use App\Models\WorkoutPlan;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        Stat::make('Total Pengguna', User::count())
            ->description('Jumlah pengguna terdaftar')
            ->color('success'),
        Stat::make('Total Latihan', Exercise::count())
            ->description('Jumlah latihan di katalog')
            ->color('info'),
        Stat::make('Total Jadwal Latihan', WorkoutPlan::count())
            ->description('Jumlah jadwal preset')
            ->color('warning'),
        Stat::make('Total Aktivitas Selesai', WorkoutLog::count())
            ->description('Jumlah latihan yang diselesaikan pengguna')
            ->color('primary'),
        ];
    }
}
