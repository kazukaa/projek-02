<?php

namespace App\Filament\Widgets;

use App\Models\WorkoutLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Layout\Stack; 
use Filament\Tables\Columns\ImageColumn;

class LatestWorkoutLogs extends BaseWidget
{
    protected static ?string $heading = 'Catatan Latihan Terbaru';
    protected static ?int $sort = 3; // Mengatur urutan widget di dashboard
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Mengambil 5 catatan latihan terbaru, beserta data user dan latihannya
                WorkoutLog::with(['user', 'exercise'])->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exercise.name')
                    ->label('Latihan yang Diselesaikan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Selesai')
                    ->since() // Menampilkan format "x menit yang lalu"
                    ->sortable(),
            ]);
    }
}