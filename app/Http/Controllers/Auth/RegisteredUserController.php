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
        return view('auth.register');
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        // Create user but don't log in yet
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Not verified yet
        ]);

        // Generate verification code
        $code = sprintf('%06d', mt_rand(0, 999999));
        Cache::put("verification_code_{$user->email}", $code, 600);

        // Send verification email
        Mail::raw("Bienvenue sur CuniApp !\n\nVotre code de vérification est : {$code}\n\nCe code expire dans 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Vérifiez votre email - CuniApp');
        });

        // Store user ID in session for verification
        session(['pending_verification_user_id' => $user->id]);

        // Return to welcome page with verification modal trigger
        return redirect()->route('welcome')
            ->with('verification_pending', true)
            ->with('verification_email', $user->email);
    }
}