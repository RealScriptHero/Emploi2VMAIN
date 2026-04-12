<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Centre;
use App\Models\Departement;
use App\Models\Formateur;
use App\Models\Groupe;
use App\Models\Module;
use App\Models\Salle;

class HomeController extends Controller
{
    public function index(): View
    {
        $groupes = Groupe::count();

        $salles = Salle::count();

        // Use centre count as EFP count to match centre page expectations (2).
        $departements = Centre::count();

        if ($departements === 0) {
            // Fallback to departements table or distinct group filieres when centre table is empty
            $departements = Schema::hasTable('departements')
                ? Departement::query()->count()
                : (int) Groupe::query()
                    ->whereNotNull('filiere')
                    ->where('filiere', '!=', '')
                    ->distinct()
                    ->count('filiere');
        }

        $formateurs = Formateur::count();

        $modules = Module::query()
            ->with(['groupes', 'emplois'])
            ->get();

        $modulesList = $modules->map(function ($m) {
            $code = (string) ($m->codeModule ?: ('M' . $m->id));
            $title = (string) ($m->nomModule ?: '');
            $progress = (int) round((float) ($m->advancement ?? 0));

            // Infer "filiere" from code prefix (DEV-..., NET-..., RESEAUX-...) when possible
            $prefix = strtoupper((string) strtok($code, '-'));
            $filiere = in_array($prefix, ['DEV', 'NET', 'RESEAUX', 'RES', 'SYS', 'DB'], true) ? $prefix : 'COM';
            if ($filiere === 'RES') $filiere = 'RESEAUX';

            return [
                'code' => $code,
                'title' => $title,
                'progress' => max(0, min(100, $progress)),
                'filiere' => $filiere,
            ];
        })->values();

        $modulesList = $modulesList->sortBy('progress')->values();

        $total = max(1, $modulesList->count());
        $sum = (int) $modulesList->sum('progress');
        $overallProgress = (int) round($sum / $total);

        $modulesCompleted = (int) $modulesList->filter(fn ($m) => ($m['progress'] ?? 0) >= 75)->count();
        $modulesInProgress = (int) $modulesList->filter(fn ($m) => ($m['progress'] ?? 0) >= 40 && ($m['progress'] ?? 0) < 75)->count();
        $modulesLow = (int) $modulesList->filter(fn ($m) => ($m['progress'] ?? 0) < 40)->count();

        return view('home.index', [
            'groupes' => $groupes,
            'salles' => $salles,
            'departements' => $departements,
            'formateurs' => $formateurs,
            'modulesList' => $modulesList,
            'overallProgress' => $overallProgress,
            'modulesCompleted' => $modulesCompleted,
            'modulesInProgress' => $modulesInProgress,
            'modulesLow' => $modulesLow,
        ]);
    }
}
