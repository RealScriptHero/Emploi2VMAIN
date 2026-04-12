<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackfillAcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = \App\Models\Setting::get('academic_year', '2024/2025');

        // Backfill existing data with current academic year
        \App\Models\EmploiDuTemps::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\AbsenceFormateur::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\AbsenceGroupe::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\Avancement::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\Stage::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\Groupe::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\Module::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        \App\Models\Formateur::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        $this->command->info("Backfilled existing data with academic year: {$currentYear}");
    }
}
