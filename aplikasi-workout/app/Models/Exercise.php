<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\UserSchedule;


/**
 * Model untuk merepresentasikan sebuah latihan (exercise).
 */
class Exercise extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
   protected $fillable = [
    'name',
    'description',
    'kategori_latihan_id',
    'tingkat_kesulitan',
    'url_video_tutorial',
    'duration_seconds',
];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Category.
     * Satu Exercise dimiliki oleh satu Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function workoutPlans(): BelongsToMany
    {
        return $this->belongsToMany(WorkoutPlan::class, 'exercise_workout_plan');
    }

    // public function userSchedules(): BelongsToMany
    // {
    //     // Pastikan nama tabel pivot 'exercise_user_schedule' sudah benar
    //     return $this->belongsToMany(UserSchedule::class, 'exercise_user_schedule');
    // }

    public function userSchedules()
{
    return $this->belongsToMany(UserSchedule::class, 'exercise_user_schedule')
        ->withPivot(['urutan', 'repetisi', 'duration_seconds'])
        ->withTimestamps();
}
}
