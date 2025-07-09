<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class MyStatsOverview extends BaseWidget
{
    protected $listeners = ['stats-overview-updated' => '$refresh'];

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            // Stat::make('Latihan Selesai', 
            //     // Hitung dari tabel baru
            //      UserActivity::where('user_id', Auth::id())
            //                 ->where('activity_type', 'exercise_completed')
            //                 ->count()
            // )
            // ->description('Total sesi latihanmu'),
            Stat::make('Latihan Selesai', $user->workoutLogs()->count())
                ->description('Total sesi latihanmu')
                ->color('success'),
            Stat::make('Lencana Didapat', $user->achievements()->count())
                ->description('Koleksi penghargaanmu')
                ->color('warning'),
            Stat::make('Jadwal Pribadi', $user->schedules()->count())
                ->description('Jumlah jadwal yang kamu buat')
                ->color('info'),
        ];
    }
}