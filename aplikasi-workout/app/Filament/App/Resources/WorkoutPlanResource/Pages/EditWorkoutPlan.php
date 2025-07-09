<?php

namespace App\Filament\App\Resources\WorkoutPlanResource\Pages;

use App\Filament\App\Resources\WorkoutPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutPlan extends EditRecord
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
