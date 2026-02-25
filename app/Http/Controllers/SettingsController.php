<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Notifiable;

class SettingsController extends Controller
{
    use Notifiable;

    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $user = User::find(Auth::id());
        
        return view('settings.index', compact('settings', 'user'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'farm_name' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:500',
            'farm_phone' => 'nullable|string|max:20',
            'farm_email' => 'nullable|email|max:255',
            'gestation_days' => 'nullable|integer|min:28|max:35',
            'weaning_weeks' => 'nullable|integer|min:4|max:8',
            'alert_threshold' => 'nullable|integer|min:1|max:100',
            'theme' => 'nullable|in:dark,light',
            'language' => 'nullable|in:fr,en',
        ]);

        // Paramètres généraux (Ferme)
        Setting::set('farm_name', $request->farm_name, 'string', 'general', 'Nom de la ferme');
        Setting::set('farm_address', $request->farm_address, 'string', 'general', 'Adresse');
        Setting::set('farm_phone', $request->farm_phone, 'string', 'general', 'Téléphone');
        Setting::set('farm_email', $request->farm_email, 'string', 'general', 'Email');

        // Paramètres d'élevage
        Setting::set('gestation_days', $request->gestation_days ?? 31, 'number', 'breeding', 'Jours de gestation');
        Setting::set('weaning_weeks', $request->weaning_weeks ?? 6, 'number', 'breeding', 'Semaines de sevrage');
        Setting::set('alert_threshold', $request->alert_threshold ?? 80, 'number', 'breeding', 'Seuil d\'alerte (%)');

        // Préférences système
        Setting::set('theme', $request->theme ?? 'dark', 'string', 'system', 'Thème');
        Setting::set('language', $request->language ?? 'fr', 'string', 'system', 'Langue');

        // Create notification for settings update
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Paramètres sauvegardés',
            'message' => 'Vos paramètres d\'élevage et de système ont été mis à jour',
            'action_url' => route('settings.index')
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Paramètres enregistrés avec succès !');
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'notifications_email' => 'nullable|boolean',
            'notifications_dashboard' => 'nullable|boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        // Handle password change
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }
        
        // Update notification preferences (USER-SPECIFIC)
        $user->notifications_email = $request->boolean('notifications_email', true);
        $user->notifications_dashboard = $request->boolean('notifications_dashboard', true);
        
        $user->save();

        // Create notification for profile update
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Profil mis à jour',
            'message' => "Vos préférences de notification ont été mises à jour : " .
                        ($user->notifications_email ? '📧 Emails activés' : '📧 Emails désactivés') . " | " .
                        ($user->notifications_dashboard ? '🔔 Dashboard activé' : '🔔 Dashboard désactivé'),
            'action_url' => route('settings.index')
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    public function exportData()
    {
        // Fonctionnalité d'export des données
        $data = [
            'femelles' => \App\Models\Femelle::all(),
            'males' => \App\Models\Male::all(),
            'saillies' => \App\Models\Saillie::all(),
            'mises_bas' => \App\Models\MiseBas::all(),
        ];
        
        $filename = 'cuniapp_export_' . date('Y-m-d') . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        return response($json, 200)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function clearCache()
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        return redirect()->route('settings.index')
            ->with('success', 'Cache vidé avec succès !');
    }
}