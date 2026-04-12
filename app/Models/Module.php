<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';

    protected $fillable = [
        'nomModule',
        'codeModule',
        'volumeHoraire',
    ];

    protected $appends = ['progression', 'advancement'];

    public function getProgressionAttribute(): float
    {
        $groupes = $this->groupes()->withPivot('heures_allouees')->get();
        $totalHeuresAllouees = 0.0;

        foreach ($groupes as $groupe) {
            $pivotHours = (float) ($groupe->pivot->heures_allouees ?? 0);
            $totalHeuresAllouees += $pivotHours > 0 ? $pivotHours : (float) $this->volumeHoraire;
        }

        if ($totalHeuresAllouees <= 0) {
            return 0.0;
        }

        $heuresRealisees = (float) $this->emplois()->sum('duree_heures');

        $progress = ($heuresRealisees / $totalHeuresAllouees) * 100;

        return round(min($progress, 100), 1);
    }

    public function getAdvancementAttribute(): float
    {
        // Backward-compatible alias used across existing UI/API
        return $this->progression;
    }

    public function emploisTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function emplois()
    {
        return $this->hasMany(EmploiDuTemps::class, 'module_id');
    }

    public function avancements()
    {
        return $this->hasMany(Avancement::class);
    }

    public function formateurs(): BelongsToMany
    {
        return $this->belongsToMany(Formateur::class, 'formateur_module', 'module_id', 'formateur_id')
            ->withTimestamps();
    }

    /**
     * The groups assigned to this module.
     */
    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(Groupe::class, 'module_groupe', 'module_id', 'groupe_id')
                    ->withPivot('heures_allouees')
                    ->withTimestamps();
    }
}
