// app/Models/Setting.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key', 'value', 'type', 'category', 'group'];
    public $timestamps = true;
    
    /**
     * Récupérer un paramètre avec conversion de type
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_' . str_replace('.', '_', $key);
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            // Convertir selon le type
            return self::convertValue($setting->value, $setting->type);
        });
    }
    
    /**
     * Sauvegarder plusieurs paramètres
     */
    public static function saveMany(array $data)
    {
        foreach ($data as $category => $settings) {
            foreach ($settings as $key => $value) {
                $fullKey = $category . '.' . $key;
                
                // Déterminer le type
                $type = self::determineType($value);
                
                // Préparer la valeur pour stockage
                $storedValue = self::prepareValueForStorage($value, $type);
                
                // Mettre à jour ou créer
                self::updateOrCreate(
                    ['key' => $fullKey],
                    [
                        'value' => $storedValue,
                        'type' => $type,
                        'category' => $category
                    ]
                );
            }
        }
        
        // Effacer le cache
        Cache::flush();
        
        return true;
    }
    
    /**
     * Récupérer tous les paramètres par catégorie
     */
    public static function getAllByCategory()
    {
        return Cache::remember('all_settings', 3600, function () {
            $allSettings = self::all();
            $categorized = [];
            
            foreach ($allSettings as $setting) {
                [$category, $key] = explode('.', $setting->key, 2);
                
                // Convertir la valeur selon son type
                $value = self::convertValue($setting->value, $setting->type);
                
                $categorized[$category][$key] = $value;
            }
            
            return $categorized;
        });
    }
    
    /**
     * Convertir la valeur selon le type
     */
    private static function convertValue($value, $type)
    {
        if ($value === null) {
            return null;
        }
        
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            case 'array':
                return json_decode($value, true) ?? [];
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
    
    /**
     * Déterminer le type d'une valeur
     */
    private static function determineType($value)
    {
        if (is_array($value)) {
            return 'array';
        } elseif (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_float($value)) {
            return 'float';
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'string';
        }
    }
    
    /**
     * Préparer la valeur pour le stockage
     */
    private static function prepareValueForStorage($value, $type)
    {
        switch ($type) {
            case 'array':
            case 'json':
                return json_encode($value);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string) $value;
        }
    }
    
    /**
     * Exporter les paramètres
     */
    public static function export()
    {
        return self::getAllByCategory();
    }
    
    /**
     * Réinitialiser aux valeurs par défaut
     */
    public static function resetToDefaults()
    {
        // Supprimer tous les paramètres
        self::truncate();
        
        // Réinsérer les valeurs par défaut
        $migration = new \CreateSettingsTable();
        $migration->seedDefaultSettings();
        
        // Effacer le cache
        Cache::flush();
        
        return true;
    }
    
    /**
     * Obtenir un paramètre de type spécifique
     */
    public static function getByType($key, $default = null, $type = null)
    {
        $value = self::get($key, $default);
        
        if ($type) {
            return self::convertValue($value, $type);
        }
        
        return $value;
    }
}