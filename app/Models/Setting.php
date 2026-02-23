<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public static function get($key, $default = null) {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'string', $group = 'general', $label = null) {
        $setting = self::firstOrCreate(['key' => $key]);
        $setting->update([
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'label' => $label ?? $key,
        ]);
        return $setting;
    }
}