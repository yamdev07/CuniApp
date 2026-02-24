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
        return view('welcome');
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

        // Generate 6-digit verification code
        $code = sprintf('%06d', mt_rand(0, 999999));
        
        // Store code in cache for 10 minutes
        Cache::put("verification_code_{$user->email}", $code, 600);

        // Generate verification URL for email
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(10),
            ['email' => $user->email, 'code' => $code]
        );

        // Send verification email with HTML template
        Mail::send('emails.verification-code', [
            'code' => $code,
            'email' => $user->email,
            'verificationUrl' => $verificationUrl,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('🔐 Code de vérification - CuniApp Élevage')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Store session variables for verification modal
        session([
            'verification_pending' => true,
            'verification_email' => $user->email,
        ]);

        // Redirect to welcome page with verification modal trigger
        return redirect()->route('welcome')
            ->with('verification_pending', true)
            ->with('verification_email', $user->email);
    }
}