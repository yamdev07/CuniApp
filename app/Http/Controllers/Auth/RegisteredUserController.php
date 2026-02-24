<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        // 🔒 CORRECTION SÉCURITÉ : Hash obligatoire du mot de passe
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ✅ HASHÉ
            'email_verified_at' => null,
        ]);

        // Générer et envoyer le code
        $code = sprintf('%06d', mt_rand(0, 999999));
        Cache::put("verification_code_{$user->email}", $code, 600);

        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $user->email,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('🔐 Code de vérification - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // 🔑 CORRECTION DÉCISIVE : Nettoyage COMPLET de la session
        Auth::logout(); // Déconnexion explicite
        $request->session()->flush(); // ⚠️ Supprime TOUTE la session existante
        $request->session()->regenerate(); // Nouvelle session propre (guest state)

        // ✅ Session propre : on peut maintenant stocker les données de vérification
        session([
            'verification_pending' => true,
            'verification_email' => $user->email,
        ]);

        return redirect()->route('welcome')
            ->with('success', 'Inscription réussie ! Veuillez vérifier votre email pour activer votre compte.')
            ->with('verification_pending', true)
            ->with('verification_email', $user->email);
    }
}
