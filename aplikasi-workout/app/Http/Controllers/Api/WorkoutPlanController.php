<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutPlanResource;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WorkoutPlanResource::collection(WorkoutPlan::all());
    }

    public function show(WorkoutPlan $workoutPlan)
    {
        // Load relasi 'exercises' agar datanya ikut terambil
        $workoutPlan->load('exercises');
        // Bungkus dengan WorkoutPlanResource
        return new WorkoutPlanResource($workoutPlan);
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
