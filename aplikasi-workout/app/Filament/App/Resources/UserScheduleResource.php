<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserScheduleResource\Pages;
use App\Models\UserSchedule;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use App\Filament\App\Resources\UserScheduleResource\RelationManagers;

class UserScheduleResource extends Resource
{
    protected static ?string $model = UserSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Saya';
    protected static ?string $pluralModelLabel = 'Jadwal Saya';
    protected static ?string $navigationGroup = 'Akun Saya';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jadwal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Jadwal')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('day_of_week')
                            ->label('Hari')
                            ->options([
                                'Monday'    => 'Senin',
                                'Tuesday'   => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday'  => 'Kamis',
                                'Friday'    => 'Jumat',
                                'Saturday'  => 'Sabtu',
                                'Sunday'    => 'Minggu',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_rest_day')
                            ->label('Tandai sebagai Hari Istirahat')
                            ->reactive(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Rangkaian Latihan')
                    ->schema([
                        Forms\Components\Placeholder::make('exercises_relation_manager_hint')
                ->label('Rangkaian Latihan')
                ->content('Anda dapat menambahkan dan mengatur latihan untuk jadwal ini di halaman Edit.')
                ->hiddenOn('create'),
            ])
            ->collapsible() // Membuat section bisa dilipat
            ->hiddenOn('create'), // Sembunyikan di halaman Create
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Jadwal')->searchable(),
                Tables\Columns\TextColumn::make('day_of_week')->label('Hari'),
                Tables\Columns\IconColumn::make('is_rest_day')->label('Hari Istirahat')->boolean(),
                Tables\Columns\TextColumn::make('exercises_count')->counts('exercises')->label('Jumlah Latihan'),
            ])
            ->actions([
                Action::make('startSession')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->modalContent(fn ($record) => view(
                        'filament.tables.actions.start-workout-session',
                        ['exercises' => $record->exercises]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    // Relation Manager tidak lagi diperlukan karena kita menggunakan Repeater
    public static function getRelations(): array
    {
        return [
            RelationManagers\ExercisesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserSchedules::route('/'),
            'create' => Pages\CreateUserSchedule::route('/create'),
            'edit' => Pages\EditUserSchedule::route('/{record}/edit'),
        ];
    }    
}