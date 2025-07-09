<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutPlanResource\Pages;
use App\Filament\Resources\WorkoutPlanResource\RelationManagers;
use App\Models\WorkoutPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class WorkoutPlanResource extends Resource
{
    protected static ?string $model = WorkoutPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Latihan';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $pluralModelLabel = 'Jadwal Latihan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Rencana Latihan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Rencana Latihan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('level')
                            ->label('Tingkat Kesulitan')
                            ->options([
                                'pemula' => 'Pemula',   
                                'menengah' => 'Menengah',
                                'lanjut' => 'Lanjut',
                                'semua' => 'Semua Level',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('estimated_duration_minutes')
                            ->label('Estimasi Durasi (Menit)')
                            ->numeric()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                // Placeholder ini hanya akan muncul di halaman 'Create'
                Forms\Components\Placeholder::make('exercises_relation_manager_hint')
                    ->label('Daftar Latihan')
                    ->content('Anda dapat menambahkan dan mengatur latihan untuk rencana ini setelah menyimpannya, yaitu di halaman Edit.')
                    ->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Rencana')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pemula' => 'success',
                        'menengah', 'intermediate' => 'warning',
                        'lanjut', 'advanced' => 'danger',
                        'semua' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('estimated_duration_minutes')
                    ->label('Durasi')
                    ->suffix(' Menit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('exercises_count')
                    ->counts('exercises') // Menggunakan 'counts' untuk performa lebih baik
                    ->label('Jumlah Latihan')
                    ->icon('heroicon-o-bolt')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->label('Filter Level')
                    ->options([
                        'pemula' => 'Pemula',
                        'menengah' => 'Menengah',
                        'lanjut' => 'Lanjut',
                        'semua' => 'Semua Level',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ExercisesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkoutPlans::route('/'),
            'create' => Pages\CreateWorkoutPlan::route('/create'),
            'edit' => Pages\EditWorkoutPlan::route('/{record}/edit'),
        ];
    }    
}