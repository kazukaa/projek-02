<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use App\Filament\Resources\ExerciseResource;

class Dashboard extends BaseDashboard
{
    // Override method getHeaderActions untuk menambahkan tombol di header
    protected function getHeaderActions(): array
    {
        return [
            Action::make('new_exercise')
                ->label('Tambah Latihan Baru')
                ->url(ExerciseResource::getUrl('create'))
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }
}