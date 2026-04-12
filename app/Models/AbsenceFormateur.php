<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceFormateur extends Model
{
    use HasFactory;

    protected $table = 'absence_formateurs';

    protected $fillable = [
        'dateAbsence',
        'motif',
        'formateur_id',
        'module_id',
        'groupe_id',
        'academic_year',
    ];

    protected $casts = [
        'dateAbsence' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($absence) {
            // Set academic year if not set
            if (!$absence->academic_year) {
                $absence->academic_year = \App\Models\Setting::get('academic_year');
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

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
}
