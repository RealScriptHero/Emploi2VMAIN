<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $table = 'groupes';

    protected $fillable = [
        'nomGroupe',
        'filiere',
        'niveau',
        'effectif',
        'notes',
        'active',
        'centre_id',
    ];

    protected $appends = ['avancement', 'avancement_presentiel', 'avancement_distanciel', 'advancement'];

    public function getRequiredHours(): float
    {
        $modules = $this->modules()->withPivot('heures_allouees')->get();
        $total = 0.0;

        foreach ($modules as $module) {
            $pivotHours = (float) ($module->pivot->heures_allouees ?? 0);
            $total += $pivotHours > 0 ? $pivotHours : (float) $module->volumeHoraire;
        }

        return $total;
    }

    public function getAvancementAttribute(): float
    {
        $totalHeuresRequises = $this->getRequiredHours();

        if ($totalHeuresRequises <= 0) {
            return 0.0;
        }

        $heuresRealisees = (float) $this->emplois()->sum('duree_heures');
        $progress = ($heuresRealisees / $totalHeuresRequises) * 100;

        return round(min($progress, 100), 1);
    }

    public function getAdvancementAttribute(): float
    {
        // Backward-compatible alias used across existing UI/API
        return $this->avancement;
    }

    // Get presentiel hours only (legacy rows with null type_session count as présentiel)
    public function getHeuresPresentiel()
    {
        return (float) $this->emplois()
            ->where(function ($q) {
                $q->where('type_session', 'presentiel')
                    ->orWhereNull('type_session')
                    ->orWhere('type_session', '');
            })
            ->sum('duree_heures');
    }

    // Get distance hours only
    public function getHeuresDistanciel()
    {
        return $this->emplois()
            ->where('type_session', 'distance')
            ->sum('duree_heures');
    }

    // Presentiel progress
    public function getAvancementPresentielAttribute()
    {
        $total = $this->getRequiredHours();
        if ($total == 0) return 0;
        
        $heures = $this->getHeuresPresentiel();
        return round(min(($heures / $total) * 100, 100), 1);
    }

    // Distance progress
    public function getAvancementDistancielAttribute()
    {
        $total = $this->getRequiredHours();
        if ($total == 0) return 0;
        
        $heures = $this->getHeuresDistanciel();
        return round(min(($heures / $total) * 100, 100), 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Groupe belongs to one Centre
    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    // One Groupe has many Stages
    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    // One Groupe has many Absences
    public function absenceGroupes()
    {
        return $this->hasMany(AbsenceGroupe::class);
    }

    /**
     * The modules assigned to this group.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_groupe', 'groupe_id', 'module_id')
                    ->withPivot('heures_allouees')
                    ->withTimestamps();
    }

    public function emplois()
    {
        return $this->hasMany(EmploiDuTemps::class, 'groupe_id');
    }
}
