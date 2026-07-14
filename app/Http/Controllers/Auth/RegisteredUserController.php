<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Firm;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Exception;

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
     * Crée la Firme, l'Utilisateur (firm_admin) et l'Abonnement Essai Gratuit.
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ 1. VALIDATION
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required','accepted'],
            // ✅ Champs pour la Firme (Entreprise)
            'firm_name' => ['required', 'string', 'max:255'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'firm_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ✅ 2. GENERATE VERIFICATION CODE (6 digits)
            $code = sprintf('%06d', mt_rand(0, 999999));

            // ✅ 3. STORE PENDING DATA IN CACHE (30 minutes)
            Cache::put("registration_pending_{$request->email}", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firm_name' => $request->firm_name,
                'firm_description' => $request->firm_description,
            ], 1800);
            Cache::put("verification_code_{$request->email}", $code, 1800);

            // ✅ 4. SEND VERIFICATION EMAIL
            Mail::send('emails.verification-code', [
                'code' => $code,
                'email' => $request->email,
                'name' => $request->name,
                'firm_name' => $request->firm_name,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('🔐 Code de vérification - CuniApp Élevage')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Pending registration created (awaiting verification code)', [
                'email' => $request->email,
                'firm_name' => $request->firm_name,
            ]);

            // ✅ 5. REDIRECT WITH MODAL FLAG
            return redirect()
                ->route('connect')
                ->with('verification_pending', true)
                ->with('verification_email', $request->email)
                ->with('success', 'Un code de vérification a été envoyé à votre adresse email pour finaliser la création de votre compte.');
        } catch (Exception $e) {
            // ✅ 6. LOG ERROR
            Log::error('❌ Échec de la préparation de l\'inscription', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // ✅ 7. RETURN WITH ERROR
            return back()
                ->withErrors(['error' => 'Erreur lors de la préparation de l\'inscription: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
