<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('exercises', App\Http\Controllers\ExerciseController::class);
