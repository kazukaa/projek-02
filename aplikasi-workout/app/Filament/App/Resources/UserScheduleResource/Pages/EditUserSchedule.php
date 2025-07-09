<?php

namespace App\Filament\App\Resources\UserScheduleResource\Pages;

use App\Filament\App\Resources\UserScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUserSchedule extends EditRecord
{
    protected static string $resource = UserScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $scheduleExercises = $data['exercises'] ?? [];
        unset($data['exercises']);
        
        // Update data utama (name, day_of_week, dll)
        $record->update($data);

        // Siapkan data pivot
        $pivotData = [];
        foreach ($scheduleExercises as $exercise) {
            $pivotData[$exercise['exercise_id']] = [
                'order'            => $exercise['order'],
                'reps'             => $exercise['reps'],
                'duration_seconds' => $exercise['duration_seconds'],
            ];
        }

        // Sync data pivot. Ini akan menghapus yang lama dan memasukkan yang baru.
        $record->exercises()->sync($pivotData);
        
        return $record;
    }
}