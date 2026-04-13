<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        $redirectUrl = rtrim(config('app.url'), '/') . '/customer/social-login/google/callback';
    
        Log::info('Google OAuth redirect initiated', [
            'redirect_uri' => $redirectUrl,
            'client_id' => config('services.google.client_id'),
            'app_url' => config('app.url'),
        ]);

        return Socialite::driver('google')
            ->redirectUrl($redirectUrl)
            ->redirect();
    }

    /**
     * Obtain the user information from Google and decide the flow.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Try to find by Google ID first
            $user = User::where('google_id', $googleUser->getId())->first();

            // 2. If not found, try to find by Email (existing email-only account linking Google)
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            if ($user) {
                // Security check: User deactivated or Firm banned
                if ($user->status === 'inactive' || ($user->firm && $user->firm->isBanned())) {
                    $message = ($user->status === 'inactive') 
                        ? 'Votre compte est désactivé. Veuillez contacter contact@anyxtech.com'
                        : 'Votre entreprise a été suspendue. Veuillez contacter contact@anyxtech.com';
                        
                    return redirect()->route('welcome')->with('error', $message);
                }

                // ✅ EXISTING USER: Only update tokens and google_id, never touch name or role
                $user->update([
                    'google_id'            => $googleUser->getId(),
                    'google_token'         => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'email_verified_at'    => $user->email_verified_at ?? now(),
                ]);

                $request->session()->regenerate();
                Auth::login($user, true);

                // Redirect based on role
                if ($user->isSuperAdmin()) {
                    return redirect()->route('super.admin.dashboard');
                }

                // Security check: Subscription active? (Trial or Paid)
                if ($user->firm && !$user->firm->activeSubscription()->exists()) {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('welcome')->with('error', 'Votre abonnement a expiré. Veuillez contacter votre administrateur ou contact@anyxtech.com pour le réactiver.');
                }

                return redirect()->route('dashboard');
            }

            // ✅ NEW USER: Store Google info in session, redirect to complete registration
            $request->session()->put('google_oauth_pending_user', [
                'name'          => $googleUser->getName(),
                'email'         => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'token'         => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]);

            return redirect()->route('auth.google.complete');
        } catch (Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('welcome')
                ->with('error', 'L\'authentification via Google a échoué. Veuillez réessayer.');
        }
    }

    /**
     * Show the form to complete Google registration (firm name & description).
     */
    public function showCompleteRegistration(Request $request)
    {
        // Guard: if no pending Google user in session, abort
        if (!$request->session()->has('google_oauth_pending_user')) {
            return redirect()->route('welcome')
                ->with('error', 'Session expirée. Veuillez vous connecter à nouveau via Google.');
        }

        $pendingUser = $request->session()->get('google_oauth_pending_user');

        return view('auth.google-complete', ['pendingUser' => $pendingUser]);
    }

    /**
     * Complete registration: create firm, user (firm_admin), and free trial.
     */
    public function completeRegistration(Request $request)
    {
        // Guard: if no pending Google user in session, abort
        $pendingUser = $request->session()->get('google_oauth_pending_user');
        if (!$pendingUser) {
            return redirect()->route('welcome')
                ->with('error', 'Session expirée. Veuillez vous connecter à nouveau via Google.');
        }

        $validator = Validator::make($request->all(), [
            'firm_name'        => ['required', 'string', 'max:255'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
            'terms'            => ['accepted'],
        ], [
            'firm_name.required' => 'Le nom de votre entreprise est obligatoire.',
            'terms.accepted'     => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Create the Firm
            $firm = Firm::create([
                'name'        => $request->firm_name,
                'description' => $request->firm_description,
                'status'      => 'active',
                'owner_id'    => null, // Will be set after user creation
            ]);

            // 2. Create the User as firm_admin (verified via Google)
            $user = User::create([
                'name'                 => $pendingUser['name'],
                'email'                => $pendingUser['email'],
                'google_id'            => $pendingUser['google_id'],
                'google_token'         => $pendingUser['token'],
                'google_refresh_token' => $pendingUser['refresh_token'],
                'password'             => null,
                'email_verified_at'    => now(), // Google email is pre-verified
                'role'                 => 'firm_admin',
                'firm_id'              => $firm->id,
                'theme'                => 'light',
                'language'             => 'fr',
                'status'               => 'active',
            ]);

            // 3. Link firm owner
            $firm->update(['owner_id' => $user->id]);

            // 4. Create 14-day Free Trial Subscription
            $trialPlan = SubscriptionPlan::where('name', 'Essai Gratuit')->first();

            if (!$trialPlan) {
                Log::warning("Plan 'Essai Gratuit' introuvable. Création à la volée...");
                $trialPlan = SubscriptionPlan::create([
                    'name'             => 'Essai Gratuit',
                    'duration_months'  => 0,
                    'price'            => 0,
                    'is_active'        => true,
                    'max_users'        => 5,
                    'description'      => 'Période d\'essai automatique 14 jours',
                    'features'         => json_encode(['Accès complet', 'Jusqu\'à 5 utilisateurs', 'Support de base']),
                ]);
            }

            $endDate = now()->addDays(14);

            Subscription::create([
                'user_id'              => $user->id,
                'firm_id'              => $firm->id,
                'subscription_plan_id' => $trialPlan->id,
                'status'               => 'active',
                'start_date'           => now(),
                'end_date'             => $endDate,
                'price'                => 0,
                'payment_method'       => 'manual',
                'payment_reference'    => 'TRIAL_GOOGLE_' . $user->id,
                'auto_renew'           => false,
            ]);

            // 5. Update user subscription metadata
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
            ]);

            DB::commit();

            Log::info('✅ Inscription Google complétée', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'firm_id' => $firm->id,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenue sur CuniApp ! Votre essai gratuit de 14 jours a démarré.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('❌ Échec inscription Google: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.'])
                ->withInput();
        }
    }
}
