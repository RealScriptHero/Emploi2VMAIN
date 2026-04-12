<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index(Request $request): JsonResponse
    {
        $modules = Module::with(['groupes', 'emplois'])->get();

        $formatted = $modules->map(function ($module) {
            return [
                'id' => $module->id,
                'codeModule' => $module->codeModule,
                'nomModule' => $module->nomModule,
                'volumeHoraire' => $module->volumeHoraire,
                'advancement' => $module->advancement,
                'groupe_ids' => $module->groupes->pluck('id')->toArray(),
                'groupes' => $module->groupes->map(function ($g) {
                    return [
                        'id' => $g->id,
                        'nomGroupe' => $g->nomGroupe ?? $g->name,
                    ];
                })->toArray(),
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Store a newly created module.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomModule' => 'required|string|max:255',
            'codeModule' => 'required|string|max:255|unique:modules,codeModule',
            'volumeHoraire' => 'required|integer|min:1',
            'groupe_ids' => 'nullable|array',
            'groupe_ids.*' => 'exists:groupes,id',
        ]);

        $module = Module::create([
            'nomModule' => $validated['nomModule'],
            'codeModule' => $validated['codeModule'],
            'volumeHoraire' => $validated['volumeHoraire'],
        ]);

        if (!empty($validated['groupe_ids'])) {
            $module->groupes()->sync($validated['groupe_ids']);
        }

        $module->load('groupes');

        return response()->json([
            'id' => $module->id,
            'codeModule' => $module->codeModule,
            'nomModule' => $module->nomModule,
            'volumeHoraire' => $module->volumeHoraire,
            'advancement' => $module->advancement,
            'groupe_ids' => $module->groupes->pluck('id')->toArray(),
            'groupes' => $module->groupes->map(fn($g) => [
                'id' => $g->id,
                'nomGroupe' => $g->nomGroupe ?? $g->name,
            ])->toArray(),
        ], 201);
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module): JsonResponse
    {
        $module->load('groupes');
        return response()->json([
            'id' => $module->id,
            'codeModule' => $module->codeModule,
            'nomModule' => $module->nomModule,
            'volumeHoraire' => $module->volumeHoraire,
            'advancement' => $module->advancement,
            'groupe_ids' => $module->groupes->pluck('id')->toArray(),
            'groupes' => $module->groupes->map(fn($g) => [
                'id' => $g->id,
                'nomGroupe' => $g->nomGroupe ?? $g->name,
            ])->toArray(),
        ]);
    }

    /**
     * Get modules for a specific group.
     */
    public function getByGroupe($groupeId): JsonResponse
    {
        try {
            $groupe = \App\Models\Groupe::findOrFail($groupeId);
            $modules = $groupe->modules()->get();

            $formatted = $modules->map(function ($module) {
                return [
                    'id' => $module->id,
                    'codeModule' => $module->codeModule,
                    'nomModule' => $module->nomModule,
                    'volumeHoraire' => $module->volumeHoraire,
                    'advancement' => $module->advancement,
                ];
            });

            return response()->json($formatted);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Group not found'], 404);
        }
    }

    /**
     * Update the specified module.
     */
    public function update(Request $request, Module $module): JsonResponse
    {
        $validated = $request->validate([
            'nomModule' => 'sometimes|string|max:255',
            'codeModule' => 'sometimes|string|max:255|unique:modules,codeModule,' . $module->id,
            'volumeHoraire' => 'sometimes|integer|min:1',
            'groupe_ids' => 'nullable|array',
            'groupe_ids.*' => 'exists:groupes,id',
        ]);

        $module->update([
            'nomModule' => $validated['nomModule'] ?? $module->nomModule,
            'codeModule' => $validated['codeModule'] ?? $module->codeModule,
            'volumeHoraire' => $validated['volumeHoraire'] ?? $module->volumeHoraire,
        ]);

        if (array_key_exists('groupe_ids', $validated)) {
            $module->groupes()->sync($validated['groupe_ids'] ?? []);
        }

        $module->load('groupes');

        return response()->json([
            'id' => $module->id,
            'codeModule' => $module->codeModule,
            'nomModule' => $module->nomModule,
            'volumeHoraire' => $module->volumeHoraire,
            'advancement' => $module->advancement,
            'groupe_ids' => $module->groupes->pluck('id')->toArray(),
            'groupes' => $module->groupes->map(fn($g) => [
                'id' => $g->id,
                'nomGroupe' => $g->nomGroupe ?? $g->name,
            ])->toArray(),
        ]);
    }

    /**
     * Remove the specified module.
     */
    public function destroy(Module $module): JsonResponse
    {
        $module->delete();
        return response()->json(['message' => 'Module deleted successfully']);
    }
}
