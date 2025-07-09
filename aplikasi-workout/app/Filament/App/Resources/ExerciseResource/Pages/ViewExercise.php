<?php

namespace App\Filament\App\Resources\ExerciseResource\Pages;

use App\Filament\App\Resources\ExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExercise extends ViewRecord
{
    protected static string $resource = ExerciseResource::class;

    // PERBAIKAN: Override method ini untuk menghilangkan semua tombol di header
    protected function getHeaderActions(): array
    {
        // Kembalikan array kosong agar tidak ada tombol yang muncul
        return [];
    }
}
