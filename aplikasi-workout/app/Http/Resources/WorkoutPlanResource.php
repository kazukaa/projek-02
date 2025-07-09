<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutPlanResource extends JsonResource
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
        'level' => $this->level,
        'estimated_duration_minutes' => $this->estimated_duration_minutes,
        // Ini akan memuat koleksi latihan menggunakan ExerciseResource
        'exercises' => ExerciseResource::collection($this->whenLoaded('exercises')),
    ];
}
}
