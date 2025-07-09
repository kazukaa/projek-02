<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            
            // PERBAIKAN: Menggunakan 'image_url' agar konsisten dengan database
            'image_url'   => $this->when($this->image_url, url('storage/' . $this->image_url)),
            
            // Mengambil tanggal kapan lencana didapat dari tabel pivot
            'unlocked_at' => $this->whenPivotLoaded('user_achievements', function () {
                // Menggunakan 'created_at' dari tabel pivot sebagai tanggal didapatkannya lencana.
                // Ini asumsi umum dan cara yang baik.
                return $this->pivot->created_at;
            }),
        ];
    }
}