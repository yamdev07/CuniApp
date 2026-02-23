<?php
// app/Http/Middleware/ApplyTheme.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class ApplyTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $theme = $this->getTheme();
        
        // Stocker le thème dans la session
        session(['current_theme' => $theme]);
        
        // Ajouter le thème aux données partagées avec toutes les vues
        view()->share('currentTheme', $theme);
        
        // Ajouter le thème comme attribut à la requête
        $request->attributes->set('theme', $theme);
        
        return $next($request);
    }
    
    /**
     * Déterminer le thème à appliquer
     */
    private function getTheme()
    {
        $theme = Setting::get('appearance.theme', 'dark');
        
        if ($theme === 'auto') {
            $theme = $this->detectSystemTheme();
        }
        
        return $theme;
    }
    
    /**
     * Détecter le thème du système
     */
    private function detectSystemTheme()
    {
        // Vérifier la préférence système via cookie JavaScript
        if (isset($_COOKIE['prefers-color-scheme'])) {
            return $_COOKIE['prefers-color-scheme'] === 'dark' ? 'dark' : 'light';
        }
        
        // Vérifier l'en-tête Accept
        $acceptHeader = request()->header('Accept', '');
        if (strpos($acceptHeader, 'dark') !== false) {
            return 'dark';
        }
        
        // Par défaut, utiliser le thème sombre
        return 'dark';
    }
    
    /**
     * Détecter la couleur d'accentuation
     */
    private function getAccentColor()
    {
        return Setting::get('appearance.accent_color', '#00D9FF');
    }
}