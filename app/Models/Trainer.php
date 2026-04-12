<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trainer extends Model
{
    protected $table = 'formateurs';
    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'specialite', 'progress'];
    
    protected $casts = [
        'modules' => 'json',
    ];

    public function assignedModules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'trainer_module');
    }
}

