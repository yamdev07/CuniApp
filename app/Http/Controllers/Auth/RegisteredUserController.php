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

        $registrationData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        // Generate verification code
        $code = sprintf('%06d', mt_rand(0, 999999));
        
        // Cache registration data and verification code for 30 minutes (1800 seconds)
        Cache::put("registration_pending_{$request->email}", $registrationData, 1800);
        Cache::put("verification_code_{$request->email}", $code, 1800);

        // Send email
        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $request->email,
        ], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('🔐 Code de vérification - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Store verification state
        session([
            'verification_pending' => true,
            'verification_email' => $request->email,
        ]);

        return redirect()->route('welcome')
        ->with('success', 'Code de vérification envoyé ! Vérifiez votre email pour activer votre compte.')
        ->with('verification_pending', true)
        ->with('verification_email', $request->email);
    }
}