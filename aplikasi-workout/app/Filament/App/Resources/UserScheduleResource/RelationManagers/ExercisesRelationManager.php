<?php

namespace App\Filament\App\Resources\UserScheduleResource\RelationManagers;

use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ExercisesRelationManager extends RelationManager
{
    protected static string $relationship = 'exercises';
    public static string $relationshipAttribute = 'exercises';

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
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()->label('Pilih Latihan'),
                        Forms\Components\TextInput::make('urutan')->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('repetisi')->numeric()->label('Repetisi (Opsional)'),
                        Forms\Components\TextInput::make('duration_seconds')->label('Durasi (Detik)')->numeric()->required(),
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->form([
                        Forms\Components\TextInput::make('urutan')->label('Urutan')->numeric()->required(),
                        Forms\Components\TextInput::make('repetisi')->label('Repetisi')->numeric(),
                        Forms\Components\TextInput::make('duration_seconds')->label('Durasi (Detik)')->numeric()->required(),
                    ])
                    ->mutateFormDataUsing(function ($data, $record) {
                        // Set default dari pivot ke form
                        $data['urutan'] = $record->pivot->urutan;
                        $data['repetisi'] = $record->pivot->repetisi;
                        $data['duration_seconds'] = $record->pivot->duration_seconds;
                        return $data;
                    })
                    ->action(function ($record, array $data) {
                        // Ambil UserSchedule ID (parent)
                        $userSchedule = $this->getOwnerRecord(); // model UserSchedule

                        // Update data pivot secara langsung
                        DB::table('exercise_user_schedule')
                            ->where('user_schedule_id', $userSchedule->id)
                            ->where('exercise_id', $record->id)
                            ->update([
                                'urutan' => $data['urutan'],
                                'repetisi' => $data['repetisi'],
                                'duration_seconds' => $data['duration_seconds'],
                                'updated_at' => now(), // jangan lupa ini kalau pakai timestamps
                            ]);
                    })
                    ->modalHeading('Edit Latihan')
                    ->modalSubmitActionLabel('Simpan Perubahan'),


                DetachAction::make(),

                Tables\Actions\Action::make('play_sound')
                    ->label('Suara')
                    ->action(fn(Component $livewire) => $livewire->dispatch('playSound')),

                Tables\Actions\Action::make('start_timer')
                    ->label('Mulai Timer')
                    ->action(fn(Component $livewire) => $livewire->dispatch('startTimer')),
            ])
            ->filters([]);
    }

    protected function handlePivotUpdate($record, $data): void
    {
        $this->getOwnerRecord() // UserSchedule
            ->exercises()
            ->updateExistingPivot($record->id, [
                'urutan' => $data['urutan'],
                'repetisi' => $data['repetisi'],
                'duration_seconds' => $data['duration_seconds'],
            ]);
    }
}
