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

        // ✅ 2. DATABASE TRANSACTION (Tout ou Rien)
        DB::beginTransaction();
        try {
            // ✅ 3. CRÉER LA FIRME D'ABORD
            $firm = Firm::create([
                'name' => $request->firm_name,
                'description' => $request->firm_description,
                'status' => 'active',
                'owner_id' => null, // Sera défini juste après
            ]);

            // ✅ 4. CRÉER L'UTILISATEUR COMME FIRM_ADMIN
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'firm_admin', // ✅ Rôle par défaut : Administrateur de la ferme
                'firm_id' => $firm->id, // ✅ LIEN IMMÉDIAT AVEC LA FIRME
                'email_verified_at' => null, // Sera vérifié via le code
                'theme' => 'light',
                'language' => 'fr',
                'status' => 'active',
            ]);

            // ✅ 5. LIER LA FIRME À L'UTILISATEUR (PROPRIÉTAIRE)
            $firm->update(['owner_id' => $user->id]);

            // ================================================================
            // ✅ 6. CRÉATION AUTOMATIQUE DE L'ESSAI GRATUIT (14 JOURS)
            // ================================================================
            // A. Trouver le plan "Essai Gratuit" (DOIT EXISTER VIA SEEDER)
            $trialPlan = SubscriptionPlan::where('name', 'Essai Gratuit')->first();

            // B. Si le plan n'existe pas, on le crée (Sécurité - ne devrait pas arriver)
            if (!$trialPlan) {
                Log::warning("Plan 'Essai Gratuit' introuvable. Création à la volée...");
                $trialPlan = SubscriptionPlan::create([
                    'name' => 'Essai Gratuit',
                    'duration_months' => 0,
                    'price' => 0,
                    'is_active' => true,
                    'max_users' => 5,
                    'description' => 'Période d\'essai automatique 14 jours',
                    'features' => json_encode(['Accès complet', 'Jusqu\'à 5 utilisateurs', 'Support de base']),
                ]);
            }

            // C. Définir la date de fin (14 jours à partir de maintenant)
            $endDate = now()->addDays(14);

            // D. Créer l'abonnement lié à la FIRME et à l'UTILISATEUR
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'firm_id' => $firm->id, // ✅ CRUCIAL EN MULTI-TENANT (CORRECTION)
                'subscription_plan_id' => $trialPlan->id,
                'status' => 'active', // ✅ STATUT ACTIF IMMÉDIATEMENT
                'start_date' => now(),
                'end_date' => $endDate,
                'price' => 0,
                'payment_method' => 'manual',
                'payment_reference' => 'TRIAL_AUTO_' . $user->id,
                'auto_renew' => false,
            ]);

            // E. Mettre à jour les métadonnées de l'utilisateur
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
            ]);

            Log::info("✅ Essai gratuit créé avec succès pour la firme {$firm->id} (User: {$user->id}). Fin : {$endDate}");

            // ================================================================
            // ✅ 7. GÉNÉRER LE CODE DE VÉRIFICATION (6 chiffres)
            $code = sprintf('%06d', mt_rand(0, 999999));

            // ✅ 8. STOCKER EN CACHE POUR VÉRIFICATION (30 minutes)
            Cache::put("registration_pending_{$request->email}", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firm_id' => $firm->id,
            ], 1800);
            Cache::put("verification_code_{$request->email}", $code, 1800);

            // ✅ 9. ENVOYER L'EMAIL DE VÉRIFICATION
            Mail::send('emails.verification-code', [
                'code' => $code,
                'email' => $request->email,
                'name' => $request->name,
                'firm_name' => $firm->name,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('🔐 Code de vérification - CuniApp Élevage')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // ✅ 10. VALIDER LA TRANSACTION
            DB::commit();

            // ✅ 11. LOGGER L'INSCRIPTION (Audit)
            Log::info('Nouvelle inscription fermier avec essai gratuit', [
                'user_id' => $user->id,
                'email' => $user->email,
                'firm_id' => $firm->id,
                'firm_name' => $firm->name,
                'subscription_id' => $subscription->id,
            ]);

            // ✅ 13. REDIRIGER VERS LA PAGE D'ACCUEIL (Le modal apparaîtra)
            return redirect()
                ->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $request->email)
                ->with('success', 'Compte créé avec succès ! Un essai gratuit de 14 jours a été activé. Vérifiez votre email pour activer votre compte.');
        } catch (Exception $e) {
            // ✅ 14. ROLLBACK EN CAS D'ERREUR (Rien n'est sauvegardé)
            DB::rollBack();

            // ✅ 15. LOGGER L'ERREUR
            Log::error('❌ Échec de l\'inscription', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // ✅ 16. RETOURNER AVEC ERREUR
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'inscription: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
