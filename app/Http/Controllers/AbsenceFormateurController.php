<?php

namespace App\Http\Controllers;

use App\Models\AbsenceFormateur;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbsenceFormateurController extends Controller
{
    /**
     * Display a listing of absences.
     */
    public function index(): JsonResponse
    {
        $absences = AbsenceFormateur::with(['formateur','module','groupe'])->get();
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
            'formateur_id' => 'required|exists:formateurs,id',
            'module_id' => 'nullable|exists:modules,id',
            'groupe_id' => 'nullable|exists:groupes,id',
        ]);

        $absence = AbsenceFormateur::create($validated);
        return response()->json($absence->load(['formateur','module','groupe']), 201);
    }

    /**
     * Display the specified absence.
     */
    public function show(AbsenceFormateur $absenceFormateur): JsonResponse
    {
        return response()->json($absenceFormateur->load(['formateur','module','groupe']));
    }

    /**
     * Update the specified absence.
     */
    public function update(Request $request, AbsenceFormateur $absenceFormateur): JsonResponse
    {
        if ($request->has('groupe_id')) {
            $request->merge([
                'groupe_id' => $request->input('groupe_id') === '' ? null : $request->input('groupe_id'),
            ]);
        }
        if ($request->has('module_id')) {
            $request->merge([
                'module_id' => $request->input('module_id') === '' ? null : $request->input('module_id'),
            ]);
        }

        $validated = $request->validate([
            'dateAbsence' => 'sometimes|date',
            'motif' => 'nullable|string|max:255',
            'formateur_id' => 'sometimes|exists:formateurs,id',
            'module_id' => 'sometimes|nullable|exists:modules,id',
            'groupe_id' => 'sometimes|nullable|exists:groupes,id',
        ]);

        $absenceFormateur->update($validated);
        return response()->json($absenceFormateur->load(['formateur', 'module', 'groupe']));
    }

    /**
     * Remove the specified absence.
     */
    public function destroy(AbsenceFormateur $absenceFormateur): JsonResponse
    {
        $absenceFormateur->delete();
        return response()->json(['message' => 'Absence deleted successfully']);
    }
}
