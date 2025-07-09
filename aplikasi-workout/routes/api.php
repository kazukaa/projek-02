<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\WorkoutPlanController;
use App\Http\Controllers\Api\WorkoutLogController;
use App\Http\Controllers\Api\UserScheduleController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\DashboardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini kita mendaftarkan semua rute untuk API kita.
|
*/

// Rute Publik (Bisa diakses siapa saja tanpa perlu login)
//==============================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('categories', CategoryController::class)->only(['index']);
Route::apiResource('exercises', ExerciseController::class)->only(['index', 'show']);
Route::apiResource('workout-plans', WorkoutPlanController::class)->only(['index', 'show']);


// Rute Terlindungi (Hanya untuk pengguna yang sudah login dengan token)
//========================================================================
Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk data pengguna & otentikasi
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute untuk riwayat latihan (log)
    // Route::apiResource('workout-logs', WorkoutLogController::class)->only(['index', 'store']);

    // Rute untuk jadwal kustom pengguna
    Route::apiResource('my-schedules', UserScheduleController::class);

    // Rute untuk melihat pencapaian (lencana)
    Route::get('/my-achievements', [AchievementController::class, 'index']);

    Route::get('/dashboard', DashboardController::class);

    // TAMBAHKAN BARIS INI untuk mendaftarkan semua pintu layanan jadwal kustom
    Route::apiResource('my-schedules', UserScheduleController::class);
});


// Route::middleware('auth:sanctum')->post('/log-workout', [WorkoutLogController::class, 'store']);
