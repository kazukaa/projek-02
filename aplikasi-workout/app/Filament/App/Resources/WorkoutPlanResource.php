<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\WorkoutPlanResource\Pages;
use App\Models\WorkoutPlan;
use Filament\Forms;
use Filament\Infolists; // <-- TAMBAHKAN INI
use Filament\Infolists\Infolist; // <-- TAMBAHKAN INI
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkoutPlanResource extends Resource
{
    protected static ?string $model = WorkoutPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Latihan';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $pluralModelLabel = 'Jadwal Latihan';
    protected static ?string $navigationLabelPlural = 'Jadwal Latihan';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('exercises');
    }

    // Method ini mendefinisikan apa yang akan ditampilkan di halaman "View"
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Bagian untuk info utama
                Infolists\Components\Section::make('Informasi Jadwal')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Nama Jadwal'),
                        Infolists\Components\TextEntry::make('level')
                            ->label('Tingkat Kesulitan')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pemula' => 'success',
                                'menengah', 'intermediate' => 'warning',
                                'lanjut', 'advanced' => 'danger',
                                'semua' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('estimated_duration_minutes')
                            ->label('Estimasi Durasi')
                            ->suffix(' Menit'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->html()
                            ->placeholder('Tidak ada deskripsi')
                            ->columnSpanFull(),
                    ])->columns(3),
                
                // Bagian untuk menampilkan daftar latihan di dalamnya
                Infolists\Components\Section::make('Rangkaian Latihan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('exercises')
                            ->label('') // Kosongkan label agar tidak ada judul tambahan
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Latihan')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('pivot.reps')
                                    ->label('Repetisi')
                                    ->placeholder('N/A'),
                                Infolists\Components\TextEntry::make('pivot.duration_seconds')
                                    ->label('Durasi')
                                    ->suffix(' detik')
                                    ->placeholder('N/A'),
                            ])->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        // ... (method table() Anda tidak perlu diubah)
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Jadwal')->searchable(),
                Tables\Columns\TextColumn::make('level')->label('Tingkat Kesulitan')->badge()->color(fn (string $state): string => match ($state) { 'pemula' => 'success', 'menengah' => 'warning', 'lanjut' => 'danger', 'semua' => 'info', default => 'gray', }),
                Tables\Columns\TextColumn::make('estimated_duration_minutes')->label('Estimasi Durasi')->suffix(' Menit'),
                Tables\Columns\TextColumn::make('exercises_count')->label('Jumlah Latihan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkoutPlans::route('/'),
            // 'view' => Pages\ViewWorkoutPlan::route('/{record}'),
        ];
    }    
}
