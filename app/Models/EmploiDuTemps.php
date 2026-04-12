<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    protected $table = 'emploi_du_temps';
    
    protected $fillable = [
        'groupe_id',
        'formateur_id',
        'module_id',
        'salle_id',
        'jour',
        'creneau',
        'duree_heures',
        'date',
        'type_session',
        'academic_year',
    ];
    
    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($emploi) {
            if (!$emploi->duree_heures) {
                $emploi->duree_heures = 2.00;
            }
            // Set academic year if not set
            if (!$emploi->academic_year) {
                $emploi->academic_year = \App\Models\Setting::get('academic_year');
            }
        });

        // Global scope to filter by current academic year
        static::addGlobalScope('academic_year', function ($builder) {
            $currentYear = \App\Models\Setting::get('academic_year');
            if ($currentYear) {
                $builder->where('academic_year', $currentYear);
            }
        });
    }
    
    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }
    
    public function formateur()
    {
        return $this->belongsTo(Formateur::class, 'formateur_id');
    }
    
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
    
    public function salle()
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}
