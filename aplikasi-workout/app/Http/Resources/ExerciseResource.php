<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{ 
    protected static ?string $navigationGroup = 'Manajemen Konten';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'description' => $this->description,
        'video_url' => $this->video_url,
        'difficulty' => $this->difficulty,
        // 'whenLoaded' digunakan agar data pivot hanya muncul saat di-load melalui relasi WorkoutPlan
        'order' => $this->whenPivotLoaded('exercise_workout_plan', fn () => $this->pivot->order),
        'reps' => $this->whenPivotLoaded('exercise_workout_plan', fn () => $this->pivot->reps),
        'duration_seconds' => $this->whenPivotLoaded('exercise_workout_plan', fn () => $this->pivot->duration_seconds),
    ];
}
}
