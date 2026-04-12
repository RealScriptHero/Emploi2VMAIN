<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of utilisateurs.
     */
    public function index(): JsonResponse
    {
        $utilisateurs = Utilisateur::all();
        return response()->json($utilisateurs);
    }

    /**
     * Store a newly created utilisateur.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'motDePasse' => 'required|string|min:6',
            'role' => 'required|string|max:255',
        ]);

        $validated['motDePasse'] = bcrypt($validated['motDePasse']);
        $utilisateur = Utilisateur::create($validated);
        return response()->json($utilisateur, 201);
    }

    /**
     * Display the specified utilisateur.
     */
    public function show(Utilisateur $utilisateur): JsonResponse
    {
        return response()->json($utilisateur);
    }

    /**
     * Update the specified utilisateur.
     */
    public function update(Request $request, Utilisateur $utilisateur): JsonResponse
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:utilisateurs,email,' . $utilisateur->id,
            'motDePasse' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|max:255',
        ]);

        if (isset($validated['motDePasse'])) {
            $validated['motDePasse'] = bcrypt($validated['motDePasse']);
        }

        $utilisateur->update($validated);
        return response()->json($utilisateur);
    }

    /**
     * Remove the specified utilisateur.
     */
    public function destroy(Utilisateur $utilisateur): JsonResponse
    {
        $utilisateur->delete();
        return response()->json(['message' => 'Utilisateur deleted successfully']);
    }
}
