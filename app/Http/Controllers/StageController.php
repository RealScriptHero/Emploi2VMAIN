<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StageController extends Controller
{
    /**
     * Display a listing of stages.
     */
    public function index(): JsonResponse
    {
        $stages = Stage::with('groupe', 'formateur')->get();
        return response()->json($stages);
    }

    /**
     * Store a newly created stage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'groupe_id' => 'required|exists:groupes,id',
            'formateur_id' => 'required|exists:formateurs,id',
        ]);

        $stage = Stage::create($validated);
        return response()->json($stage->load('groupe', 'formateur'), 201);
    }

    /**
     * Display the specified stage.
     */
    public function show(Stage $stage): JsonResponse
    {
        return response()->json($stage->load('groupe', 'formateur'));
    }

    /**
     * Update the specified stage.
     */
    public function update(Request $request, Stage $stage): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'dateDebut' => 'sometimes|date',
            'dateFin' => 'sometimes|date|after_or_equal:dateDebut',
            'groupe_id' => 'sometimes|exists:groupes,id',
            'formateur_id' => 'sometimes|exists:formateurs,id',
        ]);

        $stage->update($validated);
        return response()->json($stage->load('groupe', 'formateur'));
    }

    /**
     * Remove the specified stage.
     */
    public function destroy(Stage $stage): JsonResponse
    {
        $stage->delete();
        return response()->json(['message' => 'Stage deleted successfully']);
    }
}
