<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'timetables';

    protected $fillable = [
        'date',
        'centre_id',
        'data',
    ];

    protected $casts = [
        'date' => 'date',
        'data' => 'array',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}
