<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'group', 'type', 'description',
    ];

    /**
     * Récupérer une valeur de paramètre
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? self::castValue($setting->value, $setting->type) : $default;
        });
    }

    /**
     * Définir une valeur de paramètre
     */
    public static function set($key, $value, $group = 'general', $type = 'string', $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("setting.{$key}");
        return $setting;
    }

    /**
     * Caster la valeur selon le type
     */
    private static function castValue($value, $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Récupérer tous les paramètres d'un groupe
     */
    public static function getGroup($group)
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return self::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => self::castValue($setting->value, $setting->type)];
                });
        });
    }
}
