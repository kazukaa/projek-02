<?php

namespace App\Http\Controllers\Api;

use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutLogResource;
use Illuminate\Support\Facades\Auth;
use App\Events\WorkoutLogged;

class WorkoutLogController extends Controller
{
    /**
     * Menampilkan riwayat latihan milik pengguna yang sedang login.
     */
    public function index()
    {
        // 1. Ambil ID pengguna yang sedang login dari token.
        $userId = Auth::id();

        // 2. Ambil semua log yang 'user_id'-nya cocok, urutkan dari yang terbaru.
        $logs = WorkoutLog::where('user_id', $userId)
                          ->with('exercise') // Muat data latihannya juga
                          ->latest() // Urutkan dari yang paling baru
                          ->get();

        // 3. Kembalikan data dalam bentuk koleksi resource.
        return WorkoutLogResource::collection($logs);
    }

    /**
     * Menyimpan catatan latihan baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk. Minimal harus ada 'exercise_id'.
        $validatedData = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'workout_plan_id' => 'nullable|exists:workout_plans,id',
            'reps' => 'nullable|integer',
            'duration_seconds' => 'nullable|integer',
        ]);

        // 2. Tambahkan 'user_id' dari pengguna yang sedang login.
        $validatedData['user_id'] = Auth::id();

        // 3. Buat catatan baru di database.
        $log = WorkoutLog::create($validatedData);

        WorkoutLogged::dispatch($log);

        // 4. Kembalikan data log yang baru dibuat dengan status 201 Created.
        return response(new WorkoutLogResource($log->load('exercise')), 201);
    }
}
