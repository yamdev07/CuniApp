<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;

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

        // ✅ SECURITY FIX: Rate Limiting (5 attempts per 10 minutes)
        $throttleKey = 'verify_code:' . strtolower($email);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $email)
                ->withErrors(['code' => "Trop de tentatives. Veuillez réessayer dans {$seconds} secondes."]);
        }

        $storedCode = Cache::get("verification_code_{$email}");

        if (!$storedCode || $storedCode !== $code) {
            // ✅ Increment failure count
            RateLimiter::hit($throttleKey, 600); // 10 minutes decay

            return redirect()->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $email)
                ->withErrors(['code' => 'Code incorrect ou expiré. Veuillez demander un nouveau code.']);
        }

        // ✅ Clear rate limiter on success
        RateLimiter::clear($throttleKey);

        $user = User::where('email', $email)->first();

        if (!$user) {
            $pendingRegistration = Cache::get("registration_pending_{$email}");
            if ($pendingRegistration) {
                $user = User::create([
                    'name' => $pendingRegistration['name'],
                    'email' => $pendingRegistration['email'],
                    'password' => $pendingRegistration['password'],
                    'email_verified_at' => now(),
                ]);
                Cache::forget("registration_pending_{$email}");
                event(new Registered($user));
            } else {
                return redirect()->route('welcome')
                    ->with('verification_pending', true)
                    ->with('verification_email', $email)
                    ->withErrors(['email' => 'Données d\'inscription introuvables. Veuillez vous réinscrire.']);
            }
        } else {
            $user->email_verified_at = now();
            $user->save();
        }

        Cache::forget("verification_code_{$email}");
        event(new Verified($user));
        session()->forget(['verification_pending', 'verification_email']);

        return redirect()->route('welcome')
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
