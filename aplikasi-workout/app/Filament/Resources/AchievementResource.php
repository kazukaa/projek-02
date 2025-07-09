<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Lencana Pencapaian';
    protected static ?string $navigationGroup = 'Manajemen Aplikasi';
    protected static ?string $pluralModelLabel = 'Lencana Pencapaian';
    protected static ?string $navigationLabelPlural = 'Lencana Pencapaian';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Lencana')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lencana')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Ikon Lencana')
                            ->image()
                            ->directory('achievement-icons')
                            ->imageEditor()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Syarat')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Aturan & Kriteria')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipe Pencapaian')
                            ->options([
                                'total_exercises_completed' => 'Total Latihan Selesai',
                                'daily_streak' => 'Konsistensi Login Harian',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('requirement')
                            ->label('Kriteria (Angka)')
                            ->helperText('Contoh: Isi "10" jika tipe-nya Total Latihan Selesai.')
                            ->numeric()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Ikon')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lencana')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label('Kriteria'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }    
}