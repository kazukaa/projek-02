<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseUserSchedule extends Pivot
{
    protected $table = 'exercise_user_schedule';

    protected $fillable = [
        'user_schedule_id',
        'exercise_id',
        'urutan',
        'repetisi',
        'duration_seconds',
    ];

    // public function exercises()
    // {
    //     return $this->belongsToMany(Exercise::class, 'exercise_user_schedule')
    //         ->withPivot(['urutan', 'repetisi', 'duration_seconds'])
    //         ->using(\App\Models\ExerciseUserSchedule::class) // model pivot
    //         ->withTimestamps();
    // }
}
