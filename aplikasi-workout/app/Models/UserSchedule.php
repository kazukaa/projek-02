<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'day_of_week',
        'is_rest_day',
    ];

    /**
     * Mendefinisikan bahwa jadwal ini dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan bahwa jadwal ini bisa memiliki banyak Exercise.
     */
    // public function exercises(): BelongsToMany
    // {
    //     return $this->belongsToMany(Exercise::class, 'exercise_user_schedule')
    //         ->withPivot('urutan', 'repetisi', 'duration_seconds') // Pastikan ini juga 'urutan'
    //         ->orderByPivot('urutan', 'asc'); // <-- Diubah menjadi 'urutan'
    // }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_user_schedule')
            ->using(\App\Models\ExerciseUserSchedule::class) // gunakan model pivot
            ->withPivot(['urutan', 'repetisi', 'duration_seconds'])
            ->orderByPivot('urutan', 'asc') // <-- Diubah menjadi 'urutan'
            ->withTimestamps();
    }
}
