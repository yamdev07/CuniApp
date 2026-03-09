<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback(\Illuminate\Http\Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Try to find by Google ID first
            $user = User::where('google_id', $googleUser->getId())->first();

            // 2. If not found, try to find by Email (for existing email users linking Google)
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            if ($user) {
                // If user exists, we only update tokens and google_id, we DO NOT touch the name
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                // Only for NEW users do we take the name from Google
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'password' => null,
                    'email_verified_at' => now(),
                    'theme' => 'light',
                    'language' => 'fr',
                ]);
            }

            $request->session()->regenerate();
            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'L\'authentification via Google a échoué. Veuillez réessayer.');
        }
    }
}
