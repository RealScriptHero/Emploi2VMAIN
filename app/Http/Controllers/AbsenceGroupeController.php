<?php

namespace App\Http\Controllers;

use App\Models\AbsenceGroupe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbsenceGroupeController extends Controller
{
    /**
     * Display a listing of absences.
     */
    public function index(): JsonResponse
    {
        $absences = AbsenceGroupe::with(['groupe','module'])->get();
        return response()->json($absences);
    }

    /**
     * Store a newly created absence.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dateAbsence' => 'required|date',
            'motif' => 'nullable|string|max:255',
            'groupe_id' => 'required|exists:groupes,id',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $absence = AbsenceGroupe::create($validated);
        return response()->json($absence->load('groupe'), 201);
    }

    /**
     * Display the specified absence.
     */
    public function show(AbsenceGroupe $absenceGroupe): JsonResponse
    {
        return response()->json($absenceGroupe->load('groupe'));
    }

    /**
     * Update the specified absence.
     */
    public function update(Request $request, AbsenceGroupe $absenceGroupe): JsonResponse
    {
        if ($request->has('module_id')) {
            $request->merge([
                'module_id' => $request->input('module_id') === '' ? null : $request->input('module_id'),
            ]);
        }

        $validated = $request->validate([
            'dateAbsence' => 'sometimes|date',
            'motif' => 'nullable|string|max:255',
            'groupe_id' => 'sometimes|exists:groupes,id',
            'module_id' => 'sometimes|nullable|exists:modules,id',
        ]);

        $absenceGroupe->update($validated);
        return response()->json($absenceGroupe->load(['groupe','module']));
    }

    /**
     * Remove the specified absence.
     */
    public function destroy(AbsenceGroupe $absenceGroupe): JsonResponse
    {
        $absenceGroupe->delete();
        return response()->json(['message' => 'Absence deleted successfully']);
    }
}
