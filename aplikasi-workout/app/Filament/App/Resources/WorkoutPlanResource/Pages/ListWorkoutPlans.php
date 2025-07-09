<?php

namespace App\Filament\App\Resources\WorkoutPlanResource\Pages;

use App\Filament\App\Resources\WorkoutPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutPlans extends ListRecords
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        // Kembalikan array kosong agar tidak ada tombol yang muncul
        return [];
    }
}
