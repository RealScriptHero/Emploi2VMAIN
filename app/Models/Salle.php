<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $table = 'salles';

    protected $fillable = [
        'nomSalle',
        'centre_id',
    ];

    protected $appends = ['display_name'];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function emploisTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->centre ? strtoupper($this->centre->shortName) . ' / ' . $this->nomSalle : $this->nomSalle;
    }
}
