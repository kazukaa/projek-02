<?php

namespace App\Filament\App\Resources\ExerciseResource\Pages;

use App\Filament\App\Resources\ExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExercise extends EditRecord
{
    protected static string $resource = ExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
