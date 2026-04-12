<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $table = 'centres';

    protected $fillable = [
        'nomCentre',
        'shortName',
        'ville',
        'adresse',
    ];

    public function salles()
    {
        return $this->hasMany(Salle::class);
    }
}
