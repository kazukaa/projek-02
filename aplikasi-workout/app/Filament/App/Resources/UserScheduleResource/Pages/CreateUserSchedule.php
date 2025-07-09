<?php

namespace App\Filament\App\Resources\UserScheduleResource\Pages;

use App\Filament\App\Resources\UserScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateUserSchedule extends CreateRecord
{
    protected static string $resource = UserScheduleResource::class;
    
    // Properti untuk menyimpan data latihan sementara
    protected array $scheduleExercises = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tetapkan user_id secara otomatis
        $data['user_id'] = Auth::id();
        
        // Ambil data exercises dari form dan simpan ke properti sementara
        if (isset($data['exercises'])) {
            $this->scheduleExercises = $data['exercises'];
        }
        
        // Hapus data exercises dari array data utama agar tidak error saat create UserSchedule
        unset($data['exercises']);
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        
        // Siapkan data untuk tabel pivot
        $pivotData = [];
        foreach ($this->scheduleExercises as $exercise) {
            $pivotData[$exercise['exercise_id']] = [
                'order'            => $exercise['order'],
                'reps'             => $exercise['reps'],
                'duration_seconds' => $exercise['duration_seconds'],
            ];
        }

        // Gunakan sync untuk menyimpan relasi BelongsToMany dengan data pivot
        if (!empty($pivotData)) {
            $record->exercises()->sync($pivotData);
        }

        return $record;
    }
}