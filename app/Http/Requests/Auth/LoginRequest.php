<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        // Attempt login first
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('Ces identifiants ne correspondent pas à nos enregistrements. Veuillez vérifier votre email et mot de passe.'),
            ]);
        }

        // ✅ CRITICAL: Block unverified users from logging in
        $user = Auth::user();
        if ($user && ! $user->hasVerifiedEmail()) {
            Auth::logout(); // Immediately log out unverified user
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('🔒 Votre email n\'est pas vérifié. Consultez votre boîte mail pour activer votre compte.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // 🔧 FIXED: User-friendly rate limit message
        throw ValidationException::withMessages([
            'email' => __('Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.', [
                'seconds' => $seconds
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
