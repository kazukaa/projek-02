<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            // 'exercise' adalah nama fungsi relasi di model WorkoutLog
            'exercise' => new ExerciseResource($this->whenLoaded('exercise')), 
            'reps' => $this->reps,
            'duration_seconds' => $this->duration_seconds,
            'completed_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
