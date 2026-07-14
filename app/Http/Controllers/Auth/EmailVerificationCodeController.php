<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class EmailVerificationCodeController extends Controller
{
    /**
     * Send verification code to email (AJAX)
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(0, 999999));

        // Store code in cache for 10 minutes
        Cache::put("verification_code_{$email}", $code, 600);

        // Send email with HTML template
        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $email,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('🔐 Code de vérification - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return response()->json(['success' => true, 'message' => 'Code envoyé']);
    }

    /**
     * Verify the code (POST from modal)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->email;
        $code = $request->code;

        // ✅ SECURITY: Rate Limiting
        $throttleKey = 'verify_code:' . strtolower($email);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->route('connect')
                ->with('verification_pending', true)
                ->with('verification_email', $email)
                ->withErrors(['code' => "Trop de tentatives. Veuillez réessayer dans {$seconds} secondes."]);
        }

        $storedCode = Cache::get("verification_code_{$email}");

        if (!$storedCode || $storedCode !== $code) {
            RateLimiter::hit($throttleKey, 600);
            return redirect()->route('connect')
                ->with('verification_pending', true)
                ->with('verification_email', $email)
                ->withErrors(['code' => 'Code incorrect ou expiré. Veuillez demander un nouveau code.']);
        }

        // ✅ Clear rate limiter on success
        RateLimiter::clear($throttleKey);

        $pendingRegistration = Cache::get("registration_pending_{$email}");

        if ($pendingRegistration) {
            // ✅ Check if user already exists before creating
            $existingUser = User::where('email', $pendingRegistration['email'])->first();

            if ($existingUser) {
                // User already exists - just verify their email
                Log::info('📧 Verification: User exists, marking email as verified', [
                    'user_id' => $existingUser->id,
                    'email' => $existingUser->email,
                    'was_verified' => $existingUser->hasVerifiedEmail(),
                ]);

                $existingUser->email_verified_at = now();
                $existingUser->save();

                Log::info('✅ Email verified', [
                    'user_id' => $existingUser->id,
                    'verified_at' => $existingUser->email_verified_at,
                    'hasVerifiedEmail' => $existingUser->hasVerifiedEmail(),
                ]);

                event(new Verified($existingUser));

                // Clean up cache
                Cache::forget("registration_pending_{$email}");
                Cache::forget("verification_code_{$email}");

                session()->forget(['verification_pending', 'verification_email']);
                return redirect()->route('login')
                    ->with('success', '✅ Email vérifié avec succès ! Vous pouvez maintenant vous connecter.');
            }

            // ✅ Create Firm, User and Free Trial in transaction
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                // 1. Create the Firm
                $firm = Firm::create([
                    'name'        => $pendingRegistration['firm_name'],
                    'description' => $pendingRegistration['firm_description'] ?? null,
                    'status'      => 'active',
                    'owner_id'    => null, // Will be set after user creation
                ]);

                // 2. Create the User as firm_admin
                $user = User::create([
                    'name'              => $pendingRegistration['name'],
                    'email'             => $pendingRegistration['email'],
                    'password'          => $pendingRegistration['password'], // Already hashed in RegisteredUserController::store
                    'email_verified_at' => now(),
                    'role'              => 'firm_admin',
                    'firm_id'           => $firm->id,
                    'theme'             => 'light',
                    'language'          => session('locale') ?: ($request->getPreferredLanguage(['fr', 'en']) ?: 'fr'),
                    'status'            => 'active',
                ]);

                // 3. Link Firm Owner
                $firm->update(['owner_id' => $user->id]);

                // 4. Create 14-day Free Trial Subscription
                $trialPlan = \App\Models\SubscriptionPlan::where('name', 'Essai Gratuit')->first();
                if (!$trialPlan) {
                    Log::warning("Plan 'Essai Gratuit' introuvable. Création à la volée...");
                    $trialPlan = \App\Models\SubscriptionPlan::create([
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
                $subscription = \App\Models\Subscription::create([
                    'user_id'              => $user->id,
                    'firm_id'              => $firm->id,
                    'subscription_plan_id' => $trialPlan->id,
                    'status'               => 'active',
                    'start_date'           => now(),
                    'end_date'             => $endDate,
                    'price'                => 0,
                    'payment_method'       => 'manual',
                    'payment_reference'    => 'TRIAL_AUTO_' . $user->id,
                    'auto_renew'           => false,
                ]);

                // 5. Update user subscription metadata
                $user->update([
                    'subscription_status'  => 'active',
                    'subscription_ends_at' => $endDate,
                ]);

                \Illuminate\Support\Facades\DB::commit();

                Log::info('✅ Inscription complétée après vérification', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'firm_id' => $firm->id,
                ]);

                Cache::forget("registration_pending_{$email}");
                event(new Registered($user));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                Log::error('❌ Échec création de compte lors de la vérification: ' . $e->getMessage());

                return redirect()->route('connect')
                    ->with('verification_pending', true)
                    ->with('verification_email', $email)
                    ->withErrors(['code' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.']);
            }
        } else {
            $user = User::where('email', $email)->first();
            if ($user) {
                Log::info('📧 Verification: No pending registration, marking existing user as verified', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                $user->email_verified_at = now();
                $user->save();
            } else {
                return redirect()->route('connect')
                    ->with('verification_pending', true)
                    ->with('verification_email', $email)
                    ->withErrors(['email' => 'Données d\'inscription introuvables. Veuillez vous réinscrire.']);
            }
        }

        Cache::forget("verification_code_{$email}");
        event(new Verified($user));

        // ✅ Clear session and redirect to login
        session()->forget(['verification_pending', 'verification_email']);

        return redirect()->route('login')
            ->with('success', '✅ Email vérifié avec succès ! Vous pouvez maintenant vous connecter.');
    }

    /**
     * Resend verification code (AJAX)
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(0, 999999));

        Cache::put("verification_code_{$email}", $code, 1800);

        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $email,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('🔐 Nouveau code - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return response()->json(['success' => true, 'message' => 'Nouveau code envoyé']);
    }
}
