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

        // $validatedData = $request->validate([
        //     'exercise_id' => 'required|exists:exercises,id',
        //     'user_id'
        // ]);

        WorkoutLog::create([
            'user_id' => Auth::id(), // Ambil ID pengguna yang sedang login
            'exercise_id' => $request->input('exercise_id'), // Ambil ID latihan dari request
        ]);


        return response()->json(['message' => 'Workout log created successfully'], 201);
    }
}
