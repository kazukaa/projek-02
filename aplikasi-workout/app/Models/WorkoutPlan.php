<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'level',
        'estimated_duration_minutes',
    ];

    /**
     * Mendefinisikan relasi many-to-many ke model Exercise.
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_workout_plan')
            ->withPivot('order', 'reps', 'duration_seconds')
            // PERBAIKAN: Gunakan orderByPivot untuk mengurutkan berdasarkan kolom pivot
            ->orderByPivot('order', 'asc')
            // PERBAIKAN: Secara eksplisit pilih semua kolom dari tabel exercises
            // untuk menyelesaikan masalah "ambiguous id".
            ->select('exercises.*');
    }
}
