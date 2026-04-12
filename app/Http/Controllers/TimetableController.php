<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimetableController extends Controller
{
    /**
     * Retrieve a timetable record by date (optionally filtered by centre_id).
     */
    public function showByDate($date, Request $request): JsonResponse
    {
        $centre = $request->query('centre_id');
        $query = Timetable::where('date', $date);

        if (!is_null($centre)) {
            $query->where('centre_id', $centre);
        }

        $record = $query->first();
        if (!$record) {
            return response()->json(null);
        }

        return response()->json($record);
    }

    /**
     * Store a new timetable (or update if the same date/centre exists).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'centre_id' => 'nullable|exists:centres,id',
            'data' => 'required|string',
        ]);

        // use updateOrCreate keyed only by date; centre_id is stored but not used for matching
        $timetable = Timetable::updateOrCreate(
            ['date' => $validated['date']],
            ['centre_id' => $validated['centre_id'] ?? null, 'data' => $validated['data']]
        );

        return response()->json($timetable);
    }

    /**
     * Update an existing timetable by id.
     */
    public function update(Request $request, Timetable $timetable): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'sometimes|date',
            'centre_id' => 'nullable|exists:centres,id',
            'data' => 'required|string',
        ]);

        $timetable->update($validated);
        return response()->json($timetable);
    }
}
