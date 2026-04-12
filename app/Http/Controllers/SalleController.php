<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SalleController extends Controller
{
    /**
     * Display a listing of salles.
     */
    public function index(): JsonResponse
    {
        $salles = Salle::whereRaw('LOWER(nomSalle) != ?', ['teams'])
            ->with('centre')
            ->get();

        return response()->json($salles);
    }

    /**
     * Get salles by centre.
     */
    public function byCentre($centreId): JsonResponse
    {
        $salles = Salle::where('centre_id', $centreId)
            ->whereRaw('LOWER(nomSalle) != ?', ['teams'])
            ->get();

        return response()->json($salles);
    }

    /**
     * Get available salles for a specific day and time slot.
     * GET /api/salles/available?jour={jour}&creneau={creneau}&exclude_id={current_emploi_id}
     */
    public function getAvailableSalles(Request $request): JsonResponse
    {
        $jour = $request->input('jour');
        $date = $request->input('date');
        $creneau = $request->input('creneau');
        $excludeId = $request->input('exclude_id'); // When editing existing entry

        if (!$creneau || (!$jour && !$date)) {
            // If jour/date or creneau not specified, return all salles
            $salles = Salle::orderBy('nomSalle')->get(['id', 'nomSalle', 'centre_id']);
            return response()->json(['data' => $salles]);
        }

        $query = EmploiDuTemps::where('creneau', $creneau)
            ->whereNotNull('salle_id')
            ->when($excludeId, function($q, $excludeId) {
                return $q->where('id', '!=', $excludeId);
            });

        if ($date) {
            $query->where('date', $date);
        } elseif ($jour) {
            $query->where('jour', ucfirst(strtolower($jour)));
        }

        $occupiedSalleIds = $query->pluck('salle_id')->toArray();

        $availableSalles = Salle::whereNotIn('id', $occupiedSalleIds)
            ->orderBy('nomSalle')
            ->get(['id', 'nomSalle', 'centre_id']);

        return response()->json(['data' => $availableSalles]);
    }

    /**
     * Store a newly created salle.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomSalle' => 'required|string|max:255',
            'centre_id' => 'required|exists:centres,id',
        ]);

        $salle = Salle::create($validated);
        return response()->json($salle->load('centre'), 201);
    }

    /**
     * Display the specified salle.
     */
    public function show(Salle $salle): JsonResponse
    {
        return response()->json($salle->load('centre'));
    }

    /**
     * Update the specified salle.
     */
    public function update(Request $request, Salle $salle): JsonResponse
    {
        $validated = $request->validate([
            'nomSalle' => 'sometimes|string|max:255',
            'centre_id' => 'sometimes|exists:centres,id',
        ]);

        $salle->update($validated);
        return response()->json($salle->load('centre'));
    }

    /**
     * Remove the specified salle.
     */
    public function destroy(Salle $salle): JsonResponse
    {
        $salle->delete();
        return response()->json(['message' => 'Salle deleted successfully']);
    }
}
