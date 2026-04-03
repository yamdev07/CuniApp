<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\ProfileUpdatedNotification;
use App\Traits\Notifiable;

class SettingsController extends Controller
{
    use Notifiable;

    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $user = Auth::user();
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
            'theme' => 'nullable|in:system,light,dark',
            'language' => 'nullable|in:fr,en',
            'notifications_email' => 'nullable|boolean',
            'notifications_dashboard' => 'nullable|boolean',
            // ✅ NEW: Verification Settings
            'verification_initial_days' => 'nullable|integer|min:5|max:30',
            'verification_reminder_days' => 'nullable|integer|min:10|max:60',
            'verification_interval_days' => 'nullable|integer|min:1|max:15',
        ]);


        $user = Auth::user();

        if ($request->has('theme')) {
            $user->theme = $request->theme;
        }

        if ($request->has('language')) {
            $user->language = $request->language ?? 'fr';
        }

        if ($request->has('notifications_email')) {
            $user->notifications_email = $request->boolean('notifications_email');
        }

        if ($request->has('notifications_dashboard')) {
            $user->notifications_dashboard = $request->boolean('notifications_dashboard');
        }

        $user->save();

        // Save general settings
        // Save general settings (if present)
        if ($request->has('farm_name')) Setting::set('farm_name', $request->farm_name, 'string', 'general', 'Nom de la ferme');
        if ($request->has('farm_address')) Setting::set('farm_address', $request->farm_address, 'string', 'general', 'Adresse');
        if ($request->has('farm_phone')) Setting::set('farm_phone', $request->farm_phone, 'string', 'general', 'Téléphone');
        if ($request->has('farm_email')) Setting::set('farm_email', $request->farm_email, 'string', 'general', 'Email');
        
        if ($request->has('gestation_days')) Setting::set('gestation_days', $request->gestation_days ?? 31, 'number', 'breeding', 'Jours de gestation');
        if ($request->has('weaning_weeks')) Setting::set('weaning_weeks', $request->weaning_weeks ?? 6, 'number', 'breeding', 'Semaines de sevrage');
        if ($request->has('alert_threshold')) Setting::set('alert_threshold', $request->alert_threshold ?? 80, 'number', 'breeding', "Seuil d'alerte (%)");

        // ✅ NEW: Save verification settings
        // ✅ NEW: Save verification settings
        if ($request->has('verification_initial_days')) Setting::set('verification_initial_days', $request->verification_initial_days ?? 10, 'number', 'breeding', 'Délai initial de vérification (jours)');
        if ($request->has('verification_reminder_days')) Setting::set('verification_reminder_days', $request->verification_reminder_days ?? 15, 'number', 'breeding', 'Délai premier rappel (jours)');
        if ($request->has('verification_interval_days')) Setting::set('verification_interval_days', $request->verification_interval_days ?? 5, 'number', 'breeding', 'Intervalle des rappels (jours)');

        // In SettingsController@update method
        // In SettingsController@update method
        if ($request->has('default_price_male')) Setting::set('default_price_male', $request->default_price_male ?? 25000, 'number', 'sales', 'Prix par défaut - Mâles');
        if ($request->has('default_price_female')) Setting::set('default_price_female', $request->default_price_female ?? 30000, 'number', 'sales', 'Prix par défaut - Femelles');
        if ($request->has('default_price_lapereau')) Setting::set('default_price_lapereau', $request->default_price_lapereau ?? 15000, 'number', 'sales', 'Prix par défaut - Lapereaux');

        // Dans la méthode update(), après les autres Setting::set()
        // Dans la méthode update(), après les autres Setting::set()
        if ($request->has('fedapay_public_key')) Setting::set('fedapay_public_key', $request->fedapay_public_key ?? '', 'string', 'payments', 'Clé Publique FedaPay');
        if ($request->has('fedapay_secret_key')) Setting::set('fedapay_secret_key', $request->fedapay_secret_key ?? '', 'string', 'payments', 'Clé Secrète FedaPay');
        if ($request->has('fedapay_environment')) Setting::set('fedapay_environment', $request->fedapay_environment ?? 'sandbox', 'string', 'payments', 'Environnement FedaPay');


        // In SettingsController@update method, ADD THIS after the existing Setting::set() calls:

        // ✅ NEW: Handle Firm Settings for Firm Admins
        if ($user->isFirmAdmin() && $user->firm) {
            if ($request->has('firm_name') || $request->has('firm_description')) {
                $firmData = [];
                if ($request->has('firm_name')) {
                    $firmData['name'] = $request->firm_name;
                }
                if ($request->has('firm_description')) {
                    $firmData['description'] = $request->firm_description;
                }
                if (!empty($firmData)) {
                    $user->firm->update($firmData);
                }
            }
        }



        return redirect()->route('settings.index')
            ->with('success', 'Paramètres enregistrés avec succès !')
            ->with('active_tab', $request->active_tab ?? 'system-tab');
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
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Votre mot de passe actuel est incorrect.'])
                    ->with('active_tab', 'profile-tab');
            }
            $user->password = $request->new_password;

            // Log in again ONLY if password changed
            Auth::guard('web')->login($user);
        }

        $user->save();
        $user->notify(new ProfileUpdatedNotification());

        return redirect()->to(route('settings.index'))
            ->with('success', 'Profil mis à jour avec succès !')
            ->with('active_tab', 'profile-tab');
    }

    public function exportData()
    {
        $data = [
            'femelles' => \App\Models\Femelle::all(),
            'males' => \App\Models\Male::all(),
            'saillies' => \App\Models\Saillie::all(),
            'mises_bas' => \App\Models\MiseBas::all(),
            'naissances' => \App\Models\Naissance::all(),
        ];
        $filename = 'cuniapp_export_' . date('Y-m-d') . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return response($json, 200)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return redirect()->route('settings.index')
            ->with('success', 'Cache vidé avec succès !');
    }
}
