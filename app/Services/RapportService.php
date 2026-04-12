<?php

namespace App\Services;

use App\Models\AbsenceFormateur;
use App\Models\AbsenceGroupe;
use App\Models\EmploiDuTemps;
use App\Models\Formateur;
use App\Models\Groupe;

class RapportService
{
    public static function avancement(Groupe $groupe): int
    {
        $totalHeuresRequises = $groupe->getRequiredHours();
        if ($totalHeuresRequises <= 0) {
            return 0;
        }

        $heuresRealisees = (float) EmploiDuTemps::query()
            ->where('groupe_id', $groupe->id)
            ->whereDate('date', '<=', now()->toDateString())
            ->sum('duree_heures');

        $progress = ($heuresRealisees / $totalHeuresRequises) * 100;

        return (int) round(min($progress, 100));
    }

    public static function tauxAbsence(Groupe $groupe): int
    {
        $totalSeances = EmploiDuTemps::query()
            ->where('groupe_id', $groupe->id)
            ->count();

        if ($totalSeances === 0) {
            return 0;
        }

        $absences = AbsenceGroupe::query()
            ->where('groupe_id', $groupe->id)
            ->count();

        return (int) round(($absences / $totalSeances) * 100);
    }

    public static function chargeFormateur(Formateur $formateur): int
    {
        $heures = (float) EmploiDuTemps::query()
            ->where('formateur_id', $formateur->id)
            ->sum('duree_heures');

        return (int) round($heures);
    }

    public static function absenceDetailsForGroupe(Groupe $groupe)
    {
        return AbsenceFormateur::query()
            ->with('formateur:id,nom,prenom')
            ->where('groupe_id', $groupe->id)
            ->orderByDesc('dateAbsence')
            ->get(['id', 'formateur_id', 'dateAbsence', 'motif']);
    }
}

