<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class EmailVerificationCodeController extends Controller
{
    /**
     * Send verification code to email
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
        
        // Send email
        Mail::raw("Votre code de vérification CuniApp est : {$code}\n\nCe code expire dans 10 minutes.", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Code de vérification CuniApp');
        });

        return response()->json(['success' => true]);
    }

    /**
     * Verify the code
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

        if (!$storedCode) {
            return back()->withErrors(['code' => 'Code expiré ou invalide. Veuillez demander un nouveau code.']);
        }

        if ($storedCode !== $code) {
            return back()->withErrors(['code' => 'Code incorrect. Veuillez vérifier et réessayer.']);
        }

        // Code is valid - mark email as verified
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
            
            // Clear the verification code
            Cache::forget("verification_code_{$email}");
            
            // Log in the user
            Auth::login($user);
            
            return redirect()->route('dashboard')
                ->with('success', 'Email vérifié avec succès ! Bienvenue sur CuniApp.');
        }

        return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(0, 999999));
        
        Cache::put("verification_code_{$email}", $code, 600);
        
        Mail::raw("Votre nouveau code de vérification CuniApp est : {$code}\n\nCe code expire dans 10 minutes.", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Nouveau code de vérification CuniApp');
        });

        return response()->json(['success' => true]);
    }
}