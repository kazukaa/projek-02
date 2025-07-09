<?php

namespace App\Filament\Widgets;

use App\Models\WorkoutLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\ExerciseResource;

class TopExercises extends BaseWidget
{
    protected static ?string $heading = 'Latihan Terpopuler';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Query untuk menghitung latihan mana yang paling sering muncul
                WorkoutLog::query()
                    ->join('exercises', 'workout_logs.exercise_id', '=', 'exercises.id')
                    // PERBAIKAN: Tambahkan exercises.id di sini
                    ->select('exercises.id', 'exercises.name', DB::raw('count(workout_logs.exercise_id) as count'))
                    // PERBAIKAN: Tambahkan exercises.id di sini juga
                    ->groupBy('exercises.id', 'exercises.name')
                    ->orderBy('count', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Latihan'),
                Tables\Columns\TextColumn::make('count')
                    ->label('Jumlah Diselesaikan')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
