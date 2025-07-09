<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AchievementResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    /**
     * Menampilkan semua lencana yang telah dimiliki oleh pengguna yang sedang login.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Mengambil data lencana melalui relasi yang sudah kita buat di Model User
        $achievements = $user->achievements()->get();
        
        return AchievementResource::collection($achievements);
    }
}
