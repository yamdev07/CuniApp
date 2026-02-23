<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        $settings = Setting::getAllByCategory();
        
        // Calculer la progression
        $filledSettings = $this->calculateFilledSettings($settings);
        $totalSettings = 24; // Nombre total de paramètres
        $progress = $totalSettings > 0 ? round(($filledSettings / $totalSettings) * 100) : 0;
        
        // Données pour les options de couleur
        $accentColors = ['#00D9FF', '#0066FF', '#8B5CF6', '#10B981', '#F59E0B'];
        
        return view('settings.index', [
            'settings' => $settings,
            'progress' => $progress,
            'filledSettings' => $filledSettings,
            'totalSettings' => $totalSettings,
            'accentColors' => $accentColors,
        ]);
    }
    
    /**
     * Sauvegarder tous les paramètres
     */
    public function save(Request $request)
    {
        return $this->saveSettings($request, 'all');
    }
    
    /**
     * Sauvegarder les paramètres généraux
     */
    public function saveGeneral(Request $request)
    {
        return $this->saveSettings($request, 'general');
    }
    
    /**
     * Sauvegarder les paramètres d'élevage
     */
    public function saveElevage(Request $request)
    {
        return $this->saveSettings($request, 'elevage');
    }
    
    /**
     * Sauvegarder les paramètres d'apparence
     */
    public function saveAppearance(Request $request)
    {
        return $this->saveSettings($request, 'appearance');
    }
    
    /**
     * Sauvegarder les paramètres de notifications
     */
    public function saveNotifications(Request $request)
    {
        return $this->saveSettings($request, 'notifications');
    }
    
    /**
     * Méthode principale de sauvegarde
     */
    private function saveSettings(Request $request, $section = 'all')
    {
        try {
            $validated = $this->validateSection($request, $section);
            
            // Sauvegarder les paramètres
            Setting::saveMany($validated);
            
            // Appliquer le thème immédiatement si c'est la section apparence
            if ($section === 'appearance' || $section === 'all') {
                $this->applyThemeSettings();
            }
            
            // Répondre selon le type de requête
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paramètres sauvegardés avec succès',
                    'section' => $section,
                    'progress' => $this->calculateProgress(),
                ]);
            }
            
            return back()->with('success', "Paramètres $section sauvegardés avec succès");
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage(),
                ], 500);
            }
            
            return back()->with('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }
    
    /**
     * Exporter les données
     */
    public function exportData(Request $request)
    {
        try {
            // Exporter les paramètres
            $settingsData = Setting::export();
            
            // Exporter d'autres données si nécessaire
            $exportData = [
                'exported_at' => now()->toDateTimeString(),
                'settings' => $settingsData,
            ];
            
            // Créer un fichier JSON
            $fileName = 'anyxtech-backup-' . date('Y-m-d-H-i-s') . '.json';
            $fileContent = json_encode($exportData, JSON_PRETTY_PRINT);
            
            return response($fileContent)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }
    
    /**
     * Réinitialiser les données de test
     */
    public function resetData(Request $request)
    {
        try {
            // Valider la confirmation
            if (!$request->has('confirmed')) {
                return back()->with('error', 'Confirmation requise pour la réinitialisation');
            }
            
            // Réinitialiser les paramètres
            Setting::resetToDefaults();
            
            // Effacer le cache
            Cache::flush();
            
            return back()->with('success', 'Paramètres réinitialisés avec succès');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la réinitialisation: ' . $e->getMessage());
        }
    }
    
    /**
     * Valider les paramètres par section
     */
    private function validateSection(Request $request, $section)
    {
        $rules = [];
        
        // Définir les règles par section
        switch ($section) {
            case 'general':
                $rules = [
                    'general.farm_name' => 'required|string|max:255',
                    'general.address' => 'nullable|string|max:500',
                    'general.phone' => 'nullable|string|max:20',
                    'general.email' => 'nullable|email|max:255',
                    'general.currency' => 'required|string|in:EUR,USD,XOF',
                    'general.timezone' => 'required|string',
                    'general.language' => 'required|string|in:fr,en,es',
                ];
                break;
                
            case 'elevage':
                $rules = [
                    'elevage.gestation_days' => 'required|integer|min:28|max:35',
                    'elevage.min_mating_age' => 'required|integer|min:90|max:180',
                    'elevage.temperature_alert' => 'required|numeric|min:0|max:50',
                    'elevage.measurement_unit' => 'required|string|in:metric,imperial',
                    'elevage.rabbit_types' => 'nullable|array',
                    'elevage.rabbit_types.*' => 'string|in:viande,fourrure,compagnie',
                ];
                break;
                
            case 'appearance':
                $rules = [
                    'appearance.theme' => 'required|string|in:dark,light,auto',
                    'appearance.accent_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
                    'appearance.density' => 'required|string|in:compact,normal,comfort',
                ];
                break;
                
            case 'notifications':
                $rules = [
                    'notifications.email_enabled' => 'boolean',
                    'notifications.saillies_reminder' => 'boolean',
                    'notifications.birth_alerts' => 'boolean',
                    'notifications.vaccine_reminders' => 'boolean',
                    'notifications.health_alerts' => 'boolean',
                ];
                break;
                
            case 'all':
            default:
                $rules = array_merge(
                    $this->validateSection(new Request(), 'general'),
                    $this->validateSection(new Request(), 'elevage'),
                    $this->validateSection(new Request(), 'appearance'),
                    $this->validateSection(new Request(), 'notifications')
                );
                break;
        }
        
        $validated = $request->validate($rules);
        
        // Traiter les checkboxes pour les tableaux
        if ($section === 'elevage' && $request->has('elevage.rabbit_types')) {
            $validated['elevage.rabbit_types'] = $request->input('elevage.rabbit_types', []);
        }
        
        return $validated;
    }
    
    /**
     * Appliquer les paramètres du thème
     */
    private function applyThemeSettings()
    {
        $theme = Setting::get('appearance.theme', 'dark');
        
        if ($theme === 'auto') {
            $theme = $this->detectSystemTheme();
        }
        
        session(['current_theme' => $theme]);
        session(['accent_color' => Setting::get('appearance.accent_color', '#00D9FF')]);
    }
    
    /**
     * Détecter le thème système
     */
    private function detectSystemTheme()
    {
        if (isset($_COOKIE['prefers-color-scheme'])) {
            return $_COOKIE['prefers-color-scheme'] === 'dark' ? 'dark' : 'light';
        }
        
        return 'dark';
    }
    
    /**
     * Calculer le nombre de paramètres remplis
     */
    private function calculateFilledSettings($settings)
    {
        $count = 0;
        
        foreach ($settings as $category => $values) {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    if (!empty($value)) $count++;
                } else {
                    if ($value !== '' && $value !== null && $value !== false) $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Calculer la progression actuelle
     */
    private function calculateProgress()
    {
        $settings = Setting::getAllByCategory();
        $filledSettings = $this->calculateFilledSettings($settings);
        $totalSettings = 24; // Doit correspondre au nombre réel de paramètres
        
        return $totalSettings > 0 ? round(($filledSettings / $totalSettings) * 100) : 0;
    }
}