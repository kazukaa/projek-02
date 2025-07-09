<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Menangani permintaan pendaftaran pengguna baru.
     */
    public function register(Request $request)
    {
        // 1. Validasi data yang masuk. Ini sangat penting untuk keamanan.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Jika validasi berhasil, buat pengguna baru.
        //    PENTING: Gunakan Hash::make() untuk mengenkripsi password!
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Buat token untuk pengguna yang baru dibuat.
        $token = $user->createToken('api-token')->plainTextToken;

        // 4. Kirim response sukses beserta data pengguna dan token.
        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token
        ], 201); // 201 Created
    }

    /**
     * Menangani permintaan login pengguna.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba otentikasi pengguna
        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Buat token baru untuk pengguna ini.
            $token = $user->createToken('api-token')->plainTextToken;

            // 4. Kirim response sukses
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        // 5. Jika otentikasi gagal
        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    /**
     * Menangani permintaan logout pengguna.
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan untuk otentikasi.
        // Ini memerlukan pengguna untuk mengirim token mereka saat memanggil rute ini.
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }
}
