<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avancement extends Model
{
    use HasFactory;

    protected $table = 'avancements';

    protected $fillable = [
        'pourcentage',
        'dateLastUpdate',
        'modifie_id',
        'formateur_id',
        'academic_year',
    ];

    protected $casts = [
        'dateLastUpdate' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($avancement) {
            // Set academic year if not set
            if (!$avancement->academic_year) {
                $avancement->academic_year = \App\Models\Setting::get('academic_year');
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

    public function modifie()
    {
        return $this->belongsTo(Utilisateur::class, 'modifie_id');
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
}
