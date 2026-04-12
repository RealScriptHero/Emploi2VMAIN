<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Services\RapportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PDO;

class GroupeController extends Controller
{
    /**
     * Display a listing of groupes.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $filiere = $request->query('filiere');
        $niveau = $request->query('niveau');
        $centre_id = $request->query('centre_id');

        $perPage = (int) $request->query('perPage', 6);
        $page = (int) $request->query('page', 1);

        $query = Groupe::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomGroupe', 'like', "%{$search}%")
                  ->orWhere('filiere', 'like', "%{$search}%")
                  ->orWhere('niveau', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($filiere) {
            $query->where('filiere', $filiere);
        }

        if ($niveau) {
            $query->where('niveau', $niveau);
        }

        if ($centre_id) {
            $query->where('centre_id', $centre_id);
        }

        // If caller requested all groups (for selects), return full list without pagination
        if ($request->query('all')) {
            // Load full group data for timetable display
            $all = $query->with(['centre:id,shortName'])
                ->get();

            // Reverse groups only for CFIFJ centre
            $all = $all->groupBy(function ($groupe) {
                return $groupe->centre ? $groupe->centre->shortName : '';
            })->map(function ($groups, $shortName) {
                if ($shortName === 'CFIFJ') {
                    return $groups->reverse();
                }
                return $groups;
            })->flatten();

            return response()->json(['data' => $all]);
        }

        $paginator = $query->with(['centre','modules','emplois'])->paginate($perPage, ['*'], 'page', $page);

        $items = collect($paginator->items())->map(function (Groupe $groupe) {
            // Ensure relationships are loaded and progress is calculated
            $groupe->load(['modules', 'emplois']);
            // Force refresh of appended attributes
            $groupe->avancement;
            $groupe->advancement;
            return $groupe;
        })->values();

        return response()->json([
            'data' => $items,
            'total' => $paginator->total(),
            'perPage' => $paginator->perPage(),
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
        ]);
    }

    /**
     * Store a newly created groupe.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomGroupe' => 'required|string|max:255',
            'centre_id' => 'required|exists:centres,id',
            'filiere' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'effectif' => 'required|integer|min:0',
            'notes' => 'sometimes|nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        $groupe = Groupe::create($validated);
        // Load centre relation before returning
        $groupe->load('centre');
        return response()->json($groupe, 201);
    }

    /**
     * Display the specified groupe.
     */
    public function show(Groupe $groupe): JsonResponse
    {
        return response()->json($groupe->load('centre'));
    }

    /**
     * Update the specified groupe.
     */
    public function update(Request $request, Groupe $groupe): JsonResponse
    {
        $validated = $request->validate([
            'nomGroupe' => 'sometimes|string|max:255',
            'centre_id' => 'sometimes|exists:centres,id',
            'filiere' => 'sometimes|string|max:255',
            'niveau' => 'sometimes|string|max:255',
            'effectif' => 'sometimes|integer|min:0',
            'notes' => 'sometimes|nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        $groupe->update($validated);
        // Load centre relation before returning
        $groupe->load('centre');
        return response()->json($groupe);
    }

    /**
     * Remove the specified groupe.
     */
    public function destroy(Groupe $groupe): JsonResponse
    {
        $groupe->delete();

        // Attempt to reset auto-increment/sequence so IDs are compacted.
        try {
            $driver = DB::getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
            $max = DB::table('groupes')->max('id') ?? 0;

            if ($driver === 'mysql') {
                $next = $max + 1;
                DB::statement("ALTER TABLE groupes AUTO_INCREMENT = {$next}");
            } elseif ($driver === 'sqlite') {
                // sqlite maintains sqlite_sequence table
                DB::statement("UPDATE sqlite_sequence SET seq = {$max} WHERE name = 'groupes'");
            } elseif ($driver === 'pgsql') {
                DB::statement("SELECT setval(pg_get_serial_sequence('groupes','id'), {$max}, true)");
            }
        } catch (\Throwable $e) {
            // Not critical — ignore if database doesn't support resetting sequences this way
            logger()->warning('Failed to reset groupes auto-increment: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Groupe deleted successfully']);
    }

    /**
     * Get unique filières for dropdowns
     */
    public function getFilieres(): JsonResponse
    {
        $filieres = Groupe::query()
            ->whereNotNull('filiere')
            ->where('filiere', '!=', '')
            ->distinct()
            ->pluck('filiere')
            ->sort()
            ->values();

        return response()->json($filieres);
    }
}
