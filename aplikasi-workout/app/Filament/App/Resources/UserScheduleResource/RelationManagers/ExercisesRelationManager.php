<?php

namespace App\Filament\App\Resources\UserScheduleResource\RelationManagers;

use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Livewire\Component;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action as TablesAction;

// ... (tambahkan use statement lain jika perlu)

class ExercisesRelationManager extends RelationManager
{
    protected static string $relationship = 'exercises';

    // Form ini untuk mengedit data pivot (urutan, repetisi, dll)
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('pivot.urutan')->label('Urutan')->required()->numeric(),
            Forms\Components\TextInput::make('pivot.repetisi')->label('Repetisi')->numeric(),
            Forms\Components\TextInput::make('pivot.duration_seconds')->label('Durasi (Detik)')->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('name')
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('Nama Latihan'),
            Tables\Columns\TextColumn::make('pivot.urutan')->label('Urutan')->sortable(),
            Tables\Columns\TextColumn::make('pivot.repetisi')->label('Repetisi'),
            Tables\Columns\TextColumn::make('pivot.duration_seconds')->label('Durasi (Detik)'),
        ])
            // Tambahkan preloadRecordSelect untuk mengisi data latihan yang akan ditambahkan
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()->label('Pilih Latihan'),
                        Forms\Components\TextInput::make('urutan')->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('repetisi')->numeric()->label('Repetisi (Opsional)'),
                        Forms\Components\TextInput::make('duration_seconds')->label('Durasi (Detik)')->numeric()->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Anda bisa menambahkan action Suara dan Timer di sini jika mau
                Tables\Actions\Action::make('play_sound')
                    ->label('Suara')
                    ->action(fn (Component $livewire) => $livewire->dispatch('playSound')),
                Tables\Actions\Action::make('start_timer')
                    ->label('Mulai Timer')
                    ->action(fn (Component $livewire) => $livewire->dispatch('startTimer')),
            ])->filters([
                // Anda bisa menambahkan filter di sini jika diperlukan
            ]);
    }
}