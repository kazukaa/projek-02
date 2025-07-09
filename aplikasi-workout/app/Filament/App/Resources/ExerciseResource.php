<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Component;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'Latihan';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Latihan';
    protected static ?string $navigationLabelPlural = 'Latihan'; 

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Latihan')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Nama Latihan'),
                        Infolists\Components\TextEntry::make('category.name')->label('Kategori'),
                        Infolists\Components\TextEntry::make('difficulty')
                            ->label('Tingkat Kesulitan')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pemula' => 'success',
                                'menengah' => 'warning',
                                'lanjut' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('duration_seconds')->label('Durasi')->suffix(' detik'),
                    ])->columns(2),
                Infolists\Components\Section::make('Detail Gerakan')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\ViewEntry::make('video_url')
                            ->label('Video Tutorial')
                            ->view('infolists.components.video-player')
                            ->visible(fn ($record) => $record->video_url),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Latihan')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->badge()->searchable(),
                Tables\Columns\TextColumn::make('difficulty')->label('Tingkat Kesulitan')->badge()->color(fn (string $state): string => match ($state) {
                    'pemula' => 'success',
                    'menengah' => 'warning',
                    'lanjut' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('duration_seconds')->label('Durasi (detik)')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name')->label('Filter Kategori'),
            ])
            ->actions([
                ViewAction::make(),
                // EditAction saat ini tidak aktif. Hapus tanda '//' jika Anda ingin menggunakannya.
                // EditAction::make(), 
                Action::make('speak')
                    ->label('Panduan Suara')
                    ->icon('heroicon-o-speaker-wave')
                    ->color('success')
                    ->action(function ($record, Component $livewire) {
                        $textToSpeak = $record->name . ". " . ($record->description ?? '');
                        $livewire->dispatch('speak-text', text: $textToSpeak);
                    }),
                
                // ==> ACTION BARU DITAMBAHKAN DI SINI <==
                Action::make('startTimer')
                    ->label('Mulai Timer')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->action(function ($record, Component $livewire) {
                        // Kirim event 'start-timer' dengan data durasi ke browser
                        // $livewire->dispatch('start-timer', duration: $record->duration_seconds);
                        $livewire->dispatch('start-timer', duration: $record->duration_seconds, exerciseId: $record->id);
                    }),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExercises::route('/'),
            'view' => Pages\ViewExercise::route('/{record}'),
        ];
    }    
}