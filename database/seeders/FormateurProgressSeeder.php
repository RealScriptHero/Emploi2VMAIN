<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formateur;
use App\Models\Module;

class FormateurProgressSeeder extends Seeder
{
    public function run(): void
    {
        $formateurs = Formateur::all();

        foreach ($formateurs as $formateur) {
            $formateur->update([
                'heures_totales_requises' => rand(100, 200),
            ]);
        }

        $modules = Module::all();

        foreach ($formateurs as $formateur) {
            if ($modules->isEmpty()) {
                break;
            }

            $randomModules = $modules->random(min(3, $modules->count()));
            foreach ($randomModules as $module) {
                $formateur->modules()->syncWithoutDetaching($module->id);
            }
        }

        echo "✅ Formateur progress tracking initialized\n";

        $formateur = Formateur::with('modules')->first();
        if ($formateur) {
            echo "\nExample: {$formateur->nom} {$formateur->prenom}\n";
            echo "Required hours: {$formateur->heures_totales_requises}h\n";
            echo "Taught hours: {$formateur->getHeuresRealisees()}h\n";
            echo "Progress: {$formateur->avancement}%\n";
            echo "Modules: " . $formateur->modules->pluck('codeModule')->implode(', ') . "\n";
        }
    }
}

