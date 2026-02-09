<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        // Charger les paramètres depuis la base de données ou config
        $settings = $this->getSettings();
        
        // Calculer la progression
        $filledSettings = $this->calculateFilledSettings($settings);
        $totalSettings = 24; // Nombre total de paramètres
        
        $progress = $totalSettings > 0 ? round(($filledSettings / $totalSettings) * 100) : 0;
        
        return view('settings.index', [
            'settings' => $settings,
            'progress' => $progress,
            'filledSettings' => $filledSettings,
            'totalSettings' => $totalSettings,
        ]);
    }
    
    /**
     * Sauvegarder tous les paramètres
     */
    public function save(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'general.farm_name' => 'nullable|string|max:255',
            'general.address' => 'nullable|string|max:500',
            'general.phone' => 'nullable|string|max:20',
            'general.email' => 'nullable|email|max:255',
            'general.currency' => 'nullable|string|in:EUR,USD,XOF',
            'general.timezone' => 'nullable|string',
            'general.language' => 'nullable|string|in:fr,en,es',
            
            'elevage.rabbit_types' => 'nullable|array',
            'elevage.gestation_days' => 'nullable|integer|min:28|max:35',
            'elevage.min_mating_age' => 'nullable|integer|min:90|max:180',
            'elevage.temperature_alert' => 'nullable|numeric|min:0|max:50',
            'elevage.measurement_unit' => 'nullable|string|in:metric,imperial',
            
            'appearance.theme' => 'nullable|string|in:dark,light,auto',
            'appearance.accent_color' => 'nullable|string|max:7',
            'appearance.density' => 'nullable|string|in:compact,normal,comfort',
        ]);
        
        // Sauvegarder dans la base de données ou cache
        foreach ($validated as $category => $categoryData) {
            foreach ($categoryData as $key => $value) {
                // Utiliser le cache pour les paramètres
                Cache::put("settings.{$category}.{$key}", $value);
                
                // Ou sauvegarder dans la base de données si vous avez une table settings
                // Setting::updateOrCreate(
                //     ['key' => "{$category}.{$key}"],
                //     ['value' => $value]
                // );
            }
        }
        
        // Appliquer immédiatement le thème
        if (isset($validated['appearance']['theme'])) {
            session(['theme' => $validated['appearance']['theme']]);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Paramètres sauvegardés avec succès'
            ]);
        }
        
        return back()->with('success', 'Paramètres sauvegardés avec succès');
    }
    
    /**
     * Exporter les données
     */
    public function exportData(Request $request)
    {
        // Générer un fichier CSV avec toutes les données
        $fileName = 'anyxtech-export-' . date('Y-m-d') . '.csv';
        
        // Récupérer toutes les données de votre application
        $data = [
            // Vos données d'export ici
        ];
        
        // Générer le CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        return response()->streamDownload(function () use ($data) {
            $output = fopen('php://output', 'w');
            // Écrire les données
            fclose($output);
        }, $fileName, $headers);
    }
    
    /**
     * Réinitialiser les données de test
     */
    public function resetData(Request $request)
    {
        // Vérifier la confirmation
        if (!$request->has('confirmed')) {
            return back()->with('error', 'Confirmation requise');
        }
        
        // Supprimer les données de test
        // Attention: Cette action est irréversible!
        
        return back()->with('success', 'Données de test réinitialisées');
    }
    
    /**
     * Récupérer les paramètres
     */
    private function getSettings()
    {
        // Charger depuis le cache ou la base de données
        $settings = [
            'general' => [
                'farm_name' => Cache::get('settings.general.farm_name', 'AnyxTech Élevage'),
                'address' => Cache::get('settings.general.address', ''),
                'phone' => Cache::get('settings.general.phone', ''),
                'email' => Cache::get('settings.general.email', ''),
                'currency' => Cache::get('settings.general.currency', 'EUR'),
                'timezone' => Cache::get('settings.general.timezone', 'Europe/Paris'),
                'language' => Cache::get('settings.general.language', 'fr'),
            ],
            'elevage' => [
                'rabbit_types' => Cache::get('settings.elevage.rabbit_types', ['viande', 'fourrure']),
                'gestation_days' => Cache::get('settings.elevage.gestation_days', 31),
                'min_mating_age' => Cache::get('settings.elevage.min_mating_age', 120),
                'temperature_alert' => Cache::get('settings.elevage.temperature_alert', 30),
                'measurement_unit' => Cache::get('settings.elevage.measurement_unit', 'metric'),
            ],
            'appearance' => [
                'theme' => Cache::get('settings.appearance.theme', 'dark'),
                'accent_color' => Cache::get('settings.appearance.accent_color', '#00D9FF'),
                'density' => Cache::get('settings.appearance.density', 'normal'),
            ],
        ];
        
        return $settings;
    }
    
    /**
     * Calculer le nombre de paramètres remplis
     */
    private function calculateFilledSettings($settings)
    {
        $count = 0;
        
        foreach ($settings as $category) {
            foreach ($category as $value) {
                if (is_array($value)) {
                    if (!empty($value)) $count++;
                } else {
                    if (!empty($value)) $count++;
                }
            }
        }
        
        return $count;
    }
}