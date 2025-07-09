<?php

namespace App\Filament\Resources\WorkoutPlanResource\Pages;

use App\Filament\Resources\WorkoutPlanResource;
use App\Models\Exercise;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkoutPlan;

class AttachExercise extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = WorkoutPlanResource::class;
    protected static string $view = 'filament.resources.workout-plan-resource.pages.attach-exercise';

    public ?array $data = [];
    public WorkoutPlan $record;
    public function mount(int | string $record): void
    {
        // Gunakan model dari resource untuk mencari record
        $this->record = static::getResource()::getModel()::findOrFail($record);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        // Ambil ID latihan yang sudah terpasang
        $attachedIds = $this->record->exercises()->pluck('id')->toArray();

        return $form
            ->schema([
                Select::make('exercise_id')
                    ->label('Pilih Latihan')
                    // Ambil latihan yang BELUM terpasang
                    ->options(Exercise::whereNotIn('id', $attachedIds)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('urutan')->numeric()->required()->default(1),
                TextInput::make('repetisi')->numeric()->label('Repetisi'),
                TextInput::make('duration_seconds')->numeric()->required()->label('Durasi (Detik)'),
            ])
            ->statePath('data');
    }

    public function attach(): void
    {
        $data = $this->form->getState();

        $this->record->exercises()->attach($data['exercise_id'], [
            'urutan' => $data['urutan'],
            'repetisi' => $data['repetisi'],
            'duration_seconds' => $data['duration_seconds'],
        ]);

        Notification::make()
            ->title('Latihan berhasil ditambahkan')
            ->success()
            ->send();

        // Kosongkan form setelah berhasil
        $this->form->fill();
    }

    // Tombol simpan
    protected function getFormActions(): array
    {
        return [
            Action::make('attach')
                ->label('Tambahkan Latihan')
                ->submit('attach'),
        ];
    }
}
