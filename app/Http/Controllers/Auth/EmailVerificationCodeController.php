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
        $storedCode = Cache::get("verification_code_{$email}");

        if (!$storedCode || $storedCode !== $code) {
            return redirect()->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $email)
                ->withErrors(['code' => 'Code incorrect ou expiré. Veuillez demander un nouveau code.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            $pendingRegistration = Cache::get("registration_pending_{$email}");
            if ($pendingRegistration) {
                // Create the user
                $user = User::create([
                    'name' => $pendingRegistration['name'],
                    'email' => $pendingRegistration['email'],
                    'password' => $pendingRegistration['password'],
                    'email_verified_at' => now(), // ✅ Verify email immediately
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
            // ✅ ONLY mark as verified - DO NOT LOG IN
            $user->email_verified_at = now();
            $user->save();
        }

        Cache::forget("verification_code_{$email}");
        event(new Verified($user));

        // ✅ CLEAR verification session flags
        session()->forget(['verification_pending', 'verification_email']);

        // ✅ Redirect to welcome page WITHOUT logging in
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
