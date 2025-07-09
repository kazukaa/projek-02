<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }

    public function index(Request $request)
    {
        $query = Exercise::query();

        // Cek apakah ada parameter 'category' di URL (misal: /api/exercises?category=perut)
        if ($request->has('category')) {
            $categorySlug = $request->query('category');
            // Filter latihan berdasarkan slug kategori
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $exercises = $query->with('category')->get();

        return ExerciseResource::collection($exercises);
    }

    public function show(Exercise $exercise)
    {
        // Menampilkan detail satu latihan spesifik
        return new ExerciseResource($exercise);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
