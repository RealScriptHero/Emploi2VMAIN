<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formateur extends Model
{
    use HasFactory;

    protected $table = 'formateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'email',
    ];

    protected $appends = ['avancement', 'charge_travail', 'progress'];

    public function emploisTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function emplois()
    {
        return $this->hasMany(EmploiDuTemps::class, 'formateur_id');
    }

    public function absenceFormateurs()
    {
        return $this->hasMany(AbsenceFormateur::class);
    }

    public function avancements()
    {
        return $this->hasMany(Avancement::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'formateur_module', 'formateur_id', 'module_id')
            ->withTimestamps();
    }

    public function getHeuresTotalesRequisesAttribute(): int
    {
        // Required hours are derived from assigned modules only
        // Uses real database module hours (volumeHoraire)
        return (int) $this->modules()->sum('volumeHoraire');
    }

    public function getAvancementAttribute(): float
    {
        $totalHeuresRequises = (int) ($this->heures_totales_requises ?? 0);
        if ($totalHeuresRequises <= 0) {
            return 0.0;
        }

        $heuresRealisees = (float) $this->emplois()->sum('duree_heures');
        $progress = ($heuresRealisees / $totalHeuresRequises) * 100;

        return round(min($progress, 100), 1);
    }

    public function getChargeTravailAttribute(): float
    {
        return (float) $this->emplois()->sum('duree_heures');
    }

    public function getProgressAttribute(): float
    {
        // Backward-compatible alias used by existing frontend table
        return $this->avancement;
    }

    public function getHeuresRealisees(): float
    {
        return (float) $this->emplois()->sum('duree_heures');
    }

    public function getHeuresParModule()
    {
        return $this->emplois()
            ->selectRaw('module_id, SUM(duree_heures) as total_heures')
            ->with('module:id,codeModule,nomModule')
            ->whereNotNull('module_id')
            ->groupBy('module_id')
            ->get()
            ->map(function ($row) {
                return [
                    'module_id' => $row->module_id,
                    'code_module' => $row->module->codeModule ?? 'N/A',
                    'intitule' => $row->module->nomModule ?? 'N/A',
                    'heures' => (float) $row->total_heures,
                ];
            });
    }

    public function getHeuresParGroupe()
    {
        return $this->emplois()
            ->selectRaw('groupe_id, SUM(duree_heures) as total_heures')
            ->with('groupe:id,nomGroupe')
            ->whereNotNull('groupe_id')
            ->groupBy('groupe_id')
            ->get()
            ->map(function ($row) {
                return [
                    'groupe_id' => $row->groupe_id,
                    'nom_groupe' => $row->groupe->nomGroupe ?? 'N/A',
                    'heures' => (float) $row->total_heures,
                ];
            });
    }
}
