<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\ProfileUpdatedNotification;


class SettingsController extends Controller
{

    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $user = User::find(Auth::id());

        return view('settings.index', compact('settings', 'user'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings.*' => 'nullable',
            'farm_name' => 'nullable|string|max:255',
            'farm_address' => 'nullable|string|max:500',
            'farm_phone' => 'nullable|string|max:20',
            'farm_email' => 'nullable|email|max:255',
            'gestation_days' => 'nullable|integer|min:28|max:35',
            'weaning_weeks' => 'nullable|integer|min:4|max:8',
            'alert_threshold' => 'nullable|integer|min:1|max:100',
            'theme' => 'nullable|in:dark,light',
            'language' => 'nullable|in:fr,en',
            'notifications_email' => 'nullable|boolean',
            'notifications_dashboard' => 'nullable|boolean',
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

        // Préférences utilisateur
        Setting::set('theme', $request->theme ?? 'dark', 'string', 'user', 'Thème');
        Setting::set('language', $request->language ?? 'fr', 'string', 'user', 'Langue');
        Setting::set('notifications_email', $request->notifications_email ? '1' : '0', 'boolean', 'user', 'Notifications email');
        Setting::set('notifications_dashboard', $request->notifications_dashboard ? '1' : '0', 'boolean', 'user', 'Notifications dashboard');

        return redirect()->route('settings.index')
            ->with('success', 'Paramètres enregistrés avec succès !');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà prise.',
            'current_password.required_with' => 'Le mot de passe actuel est requis pour définir un nouveau mot de passe.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'new_password' => 'Le mot de passe doit contenir au moins 8 caractères, incluant des majuscules, minuscules, chiffres et caractères spéciaux.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('new_password')) {
            // Vérification de sécurité avec le mot de passe actuel
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Votre mot de passe actuel est incorrect.']);
            }

            // On assigne le mot de passe en clair : le Model User s'occupe du Hash 
            // automatiquement grâce au cast 'password' => 'hashed'
            $user->password = $request->new_password;
        }

        $user->save();

        // Maintien de la session active même après modification du mot de passe
        Auth::guard('web')->login($user);

        // Envoi de la notification mail (sans données sensibles)
        $user->notify(new ProfileUpdatedNotification());

        $user->save();

        return redirect()->to(route('settings.index'))
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