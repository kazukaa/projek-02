<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\Api\WorkoutLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('exercises', App\Http\Controllers\ExerciseController::class);

Route::post('/log-workout', [WorkoutLogController::class, 'store'])->middleware('auth');
