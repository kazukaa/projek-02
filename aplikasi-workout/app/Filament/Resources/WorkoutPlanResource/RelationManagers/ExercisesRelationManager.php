<?php

namespace App\Filament\Resources\WorkoutPlanResource\RelationManagers;

use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Livewire\Component;

class ExercisesRelationManager extends RelationManager
{
    protected static string $relationship = 'exercises';

    // Form ini digunakan saat Anda mengklik tombol "Edit" pada sebuah latihan
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order') // Menggunakan 'urutan' agar konsisten dengan tabel
                    ->label('Urutan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('reps') // Menggunakan 'repetisi'
                    ->label('Repetisi')
                    ->numeric(),
                Forms\Components\TextInput::make('duration_seconds')
                    ->label('Durasi (Detik)')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Latihan'),
                Tables\Columns\TextColumn::make('pivot.order')->label('Urutan')->sortable(),
                Tables\Columns\TextColumn::make('pivot.reps')->label('Repetisi'),
                Tables\Columns\TextColumn::make('pivot.duration_seconds')->label('Durasi (Detik)'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()->label('Pilih Latihan'),
                        Forms\Components\TextInput::make('order')->label('Urutan')->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('reps')->label('Repetisi (Opsional)')->numeric(),
                        Forms\Components\TextInput::make('duration_seconds')->label('Durasi (Detik)')->numeric()->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                
            //     Action::make('speak')
            //         ->label('Suara')
            //         ->icon('heroicon-o-speaker-wave')
            //         ->color('success')
            //         ->action(function ($record, Component $livewire) {
            //             $livewire->dispatch('speak-text', text: $record->name);
            //         })
            //         ->requiresConfirmation(false),

            //     Action::make('startTimer')
            //         ->label('Timer')
            //         ->icon('heroicon-o-clock')
            //         ->color('info')
            //         ->action(function ($record, Component $livewire) {
            //             $duration = $record->pivot->duration_seconds ?? $record->duration_seconds;
            //             $livewire->dispatch('start-timer', duration: $duration, exerciseId: $record->id);
            //         })
            //         ->requiresConfirmation(false),
            ]);
    }
}