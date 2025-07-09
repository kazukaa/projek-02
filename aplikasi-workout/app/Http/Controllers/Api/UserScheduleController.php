<?php

namespace App\Http\Controllers\Api;

use App\Models\UserSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserScheduleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserScheduleController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $schedules = $user->schedules()->with('exercises')->get();
        return UserScheduleResource::collection($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Memastikan hari yang diinput valid
            'day_of_week' => ['nullable', 'string', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'is_rest_day' => 'sometimes|boolean',
            'exercises' => 'nullable|array',
            'exercises.*.id' => 'required_with:exercises|exists:exercises,id',
            'exercises.*.order' => 'required_with:exercises|integer',
            'exercises.*.reps' => 'nullable|integer',
            'exercises.*.duration_seconds' => 'nullable|integer',
        ]);

        // PERBAIKAN FINAL: Pisahkan data latihan dari data utama jadwal
        $exerciseData = $validated['exercises'] ?? [];
        unset($validated['exercises']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Buat jadwal utama terlebih dahulu HANYA dengan data yang relevan
        $schedule = $user->schedules()->create($validated);

        // Jika ada data latihan, baru kita lampirkan (sync) ke jadwal yang baru dibuat
        if (!empty($exerciseData)) {
            $syncData = [];
            foreach ($exerciseData as $exercise) {
                $syncData[$exercise['id']] = [
                    'order' => $exercise['order'],
                    'reps' => $exercise['reps'] ?? null,
                    'duration_seconds' => $exercise['duration_seconds'] ?? null,
                ];
            }
            $schedule->exercises()->sync($syncData);
        }

        // Kembalikan response sukses dengan data yang sudah lengkap
        return response(new UserScheduleResource($schedule->load('exercises')), 201);
    }

    public function show(UserSchedule $userSchedule)
    {
        abort_if($userSchedule->user_id !== Auth::id(), 403, 'Unauthorized');
        return new UserScheduleResource($userSchedule->load('exercises'));
    }

    // ... (fungsi update & destroy tetap sama) ...
    public function destroy(UserSchedule $userSchedule)
    {
        abort_if($userSchedule->user_id !== Auth::id(), 403, 'Unauthorized');
        $userSchedule->delete();
        return response()->noContent();
    }
}