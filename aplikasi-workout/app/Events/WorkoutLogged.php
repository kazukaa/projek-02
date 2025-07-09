<?php

namespace App\Events;

use App\Models\WorkoutLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkoutLogged
{
    use Dispatchable, SerializesModels;

    /**
     * Buat instance event baru.
     */
    public function __construct(
        public WorkoutLog $workoutLog
    ) {
        //
    }
}