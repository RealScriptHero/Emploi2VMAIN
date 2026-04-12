<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CentreController extends Controller
{
    /**
     * Display a listing of centres.
     */
    public function index(): JsonResponse
    {
        // List centres only once per id (no eager salles needed for dropdowns; keeps payload small)
        $centres = Centre::query()
            ->orderBy('nomCentre')
            ->get()
            ->unique('id')
            ->values();

        return response()->json($centres);
    }

    /**
     * Store a newly created centre.
     */
    public function store(Request $request): JsonResponse
    {
        // Accept multiple field name variants
        $name = $request->input('nomCentre') ?? $request->input('name') ?? $request->input('nom');
        $short = $request->input('shortName') ?? $request->input('short') ?? $request->input('nomCourt') ?? $request->input('abbreviation');
        $ville = $request->input('ville');
        $adresse = $request->input('adresse');

        if (!$name) {
            return response()->json(['message' => 'Center name is required.'], 422);
        }

        $centre = Centre::create([
            'nomCentre' => $name,
            'shortName' => $short,
            'ville' => $ville,
            'adresse' => $adresse,
        ]);

        return response()->json($centre, 201);
    }

    /**
     * Display the specified centre.
     */
    public function show(Centre $centre): JsonResponse
    {
        return response()->json($centre->load('salles'));
    }

    /**
     * Update the specified centre.
     */
    public function update(Request $request, Centre $centre): JsonResponse
    {
        // Accept multiple field name variants
        $data = [];
        
        $name = $request->input('nomCentre') ?? $request->input('name') ?? $request->input('nom');
        if ($name) {
            $data['nomCentre'] = $name;
        }
        
        $short = $request->input('shortName') ?? $request->input('short') ?? $request->input('nomCourt') ?? $request->input('abbreviation');
        if ($short !== null) {
            $data['shortName'] = $short;
        }
        
        if ($request->has('ville')) {
            $data['ville'] = $request->input('ville');
        }
        
        if ($request->has('adresse')) {
            $data['adresse'] = $request->input('adresse');
        }

        $centre->update($data);
        return response()->json($centre);
    }

    /**
     * Remove the specified centre.
     */
    public function destroy(Centre $centre): JsonResponse
    {
        $centre->delete();
        return response()->json(['message' => 'Centre deleted successfully']);
    }
}
