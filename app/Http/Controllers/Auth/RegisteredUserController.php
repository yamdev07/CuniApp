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
     * Display registration (redirects to welcome page)
     */
    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

          $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_verified_at' => null, // ✅ MUST be null
    ]);

        // Generate verification code
        $code = sprintf('%06d', mt_rand(0, 999999));
        Cache::put("verification_code_{$user->email}", $code, 600);

        // Send email
        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $user->email,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('🔐 Code de vérification - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

         // ✅ CRITICAL: Log out ANY existing session
    Auth::logout();
    $request->session()->flush();
    $request->session()->regenerate();

        // Store verification state
        session([
            'verification_pending' => true,
            'verification_email' => $user->email,
        ]);

        return redirect()->route('welcome')
        ->with('success', 'Inscription réussie ! Vérifiez votre email pour activer votre compte.')
        ->with('verification_pending', true)
        ->with('verification_email', $user->email);
    }
}