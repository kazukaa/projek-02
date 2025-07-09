<?php

namespace App\Filament\App\Resources\WorkoutPlanResource\Pages;

use App\Filament\App\Resources\WorkoutPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkoutPlan extends ViewRecord
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
