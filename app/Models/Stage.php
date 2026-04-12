<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $table = 'stages';

    protected $fillable = [
        'date',
        'dateDebut',
        'dateFin',
        'groupe_id',
        'formateur_id',
        'academic_year',
    ];

    protected $casts = [
        'date' => 'date',
        'dateDebut' => 'date',
        'dateFin' => 'date',
    ];

    protected $appends = ['status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stage) {
            // Set academic year if not set
            if (!$stage->academic_year) {
                $stage->academic_year = \App\Models\Setting::get('academic_year');
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
        return $this->belongsTo(Groupe::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    public function getStatusAttribute(): string
    {
        $today = today();

        if ($this->dateDebut && $today->lt($this->dateDebut)) {
            return 'a-venir';
        }

        if ($this->dateFin && $today->gt($this->dateFin)) {
            return 'termines';
        }

        return 'en-cours';
    }
}
