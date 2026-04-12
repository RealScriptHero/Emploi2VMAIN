<?php

namespace App\Http\Controllers;

use App\Models\Avancement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvancementController extends Controller
{
    /**
     * Display a listing of avancements.
     */
    public function index(): JsonResponse
    {
        $avancements = Avancement::with('modifie', 'formateur')->get();
        return response()->json($avancements);
    }

    /**
     * Store a newly created avancement.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pourcentage' => 'required|integer|min:0|max:100',
            'dateLastUpdate' => 'nullable|datetime',
            'modifie_id' => 'required|exists:utilisateurs,id',
            'formateur_id' => 'required|exists:formateurs,id',
        ]);

        $avancement = Avancement::create($validated);
        return response()->json($avancement->load('modifie', 'formateur'), 201);
    }

    /**
     * Display the specified avancement.
     */
    public function show(Avancement $avancement): JsonResponse
    {
        return response()->json($avancement->load('modifie', 'formateur'));
    }

    /**
     * Update the specified avancement.
     */
    public function update(Request $request, Avancement $avancement): JsonResponse
    {
        $validated = $request->validate([
            'pourcentage' => 'sometimes|integer|min:0|max:100',
            'dateLastUpdate' => 'nullable|datetime',
            'modifie_id' => 'sometimes|exists:utilisateurs,id',
            'formateur_id' => 'sometimes|exists:formateurs,id',
        ]);

        $avancement->update($validated);
        return response()->json($avancement->load('modifie', 'formateur'));
    }

    /**
     * Remove the specified avancement.
     */
    public function destroy(Avancement $avancement): JsonResponse
    {
        $avancement->delete();
        return response()->json(['message' => 'Avancement deleted successfully']);
    }
}
