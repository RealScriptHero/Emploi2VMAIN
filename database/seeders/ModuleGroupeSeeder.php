<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Groupe;

class ModuleGroupeSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::all();
        $groupes = Groupe::all();

        if ($modules->isEmpty() || $groupes->isEmpty()) {
            echo "⚠️ Create modules and groups first\n";
            return;
        }

        foreach ($groupes as $groupe) {
            foreach ($modules->take(3) as $module) {
                $heures = rand(30, 60);

                $groupe->modules()->syncWithoutDetaching([
                    $module->id => ['heures_allouees' => $heures],
                ]);
            }
        }

        echo "✅ Module assignments created\n";

        $groupe = Groupe::first();
        if ($groupe) {
            echo "\nExample for {$groupe->nomGroupe}:\n";
            echo "Total required: {$groupe->modules()->sum('module_groupe.heures_allouees')}h\n";
            echo "Completed: {$groupe->emplois()->sum('duree_heures')}h\n";
            echo "Progress: {$groupe->avancement}%\n";
        }
    }
}

