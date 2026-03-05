<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
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

    // In the get() method, you can set better defaults:
    public static function get($key, $default = null)
    {
        $defaults = [
            'farm_address' => 'Houéyiho après le pont devant Volta United, Cotonou, Littoral, Bénin',
            'farm_phone' => '+2290152415241',
            'farm_email' => 'contact@anyxtech.com',
        ];

        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : ($defaults[$key] ?? $default);
    }

    public static function set($key, $value, $type = 'string', $group = 'general', $label = null)
    {
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
