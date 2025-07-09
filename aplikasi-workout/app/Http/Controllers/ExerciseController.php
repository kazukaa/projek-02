<?php

namespace App\Http\Controllers;

use App\Models\Category; // <-- PENTING: Import model Category
use App\Models\Exercise; // <-- PENTING: Import model Exercise
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Menampilkan daftar semua exercises.
     */
    public function index()
    {
        // Ambil semua data exercise dari database
        $exercises = Exercise::latest()->get();

        // Kirim data ke view untuk ditampilkan
        return view('exercises.index', ['exercises' => $exercises]);
    }

    /**
     * Menampilkan form untuk membuat exercise baru.
     */
    public function create()
    {
        // Ambil semua kategori untuk ditampilkan di form (misalnya untuk dropdown)
        $categories = Category::all();

        return view('exercises.create', ['categories' => $categories]);
    }

    /**
     * Menyimpan exercise baru ke dalam database.
     * INILAH METHOD YANG MEMPERBAIKI ERROR ANDA.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input yang dibutuhkan dari form.
         $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|integer|exists:categories,id',
        ]);

        // 2. Kirim data yang sudah lolos validasi ke method create().
        Exercise::create($validatedData);

        // 3. Alihkan pengguna ke halaman lain dengan pesan sukses.
        return redirect()->route('exercises.index')->with('success', 'Latihan baru berhasil dibuat!');}

    /**
     * Menampilkan detail satu exercise.
     */
    public function show(Exercise $exercise)
    {
        // Logika untuk menampilkan satu exercise (misalnya halaman detail)
        return view('exercises.show', ['exercise' => $exercise]);
    }

    /**
     * Menampilkan form untuk mengedit exercise.
     */
    public function edit(Exercise $exercise)
    {
        // Logika untuk menampilkan form edit
    }

    /**
     * Memperbarui data exercise di database.
     */
    public function update(Request $request, Exercise $exercise)
    {
        // Logika untuk update data
    }

    /**
     * Menghapus exercise dari database.
     */
    public function destroy(Exercise $exercise)
    {
        // Logika untuk menghapus data
    }
}