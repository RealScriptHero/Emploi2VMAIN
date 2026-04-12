<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        if (!Schema::hasTable((new static)->getTable())) {
            return $default;
        }

        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        if (!Schema::hasTable((new static)->getTable())) {
            return null;
        }

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getAll()
    {
        if (!Schema::hasTable((new static)->getTable())) {
            return [];
        }

        return static::pluck('value', 'key')->toArray();
    }
}