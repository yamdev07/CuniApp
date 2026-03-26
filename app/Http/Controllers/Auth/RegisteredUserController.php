// app/Http/Controllers/Auth/RegisteredUserController.php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Firm;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('welcome');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            // ✅ NEW: Firm fields for Firm Admin registration
            'firm_name' => ['required', 'string', 'max:255'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::beginTransaction();
        try {
            // 1. Create the Firm first
            $firm = Firm::create([
                'name' => $request->firm_name,
                'description' => $request->firm_description,
                'status' => 'active',
                'owner_id' => null, // Will be set after user creation
            ]);

            // 2. Create the User as Firm Admin
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'firm_admin', // ✅ Default role for new registrations
                'firm_id' => $firm->id,
                'email_verified_at' => null, // Will be verified via code
                'theme' => 'light',
                'language' => 'fr',
            ]);

            // 3. Link firm to user (owner)
            $firm->update(['owner_id' => $user->id]);

            // 4. Generate verification code
            $code = sprintf('%06d', mt_rand(0, 999999));
            Cache::put("registration_pending_{$request->email}", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'firm_id' => $firm->id,
            ], 1800);
            Cache::put("verification_code_{$request->email}", $code, 1800);

            // 5. Send verification email
            Mail::send('emails.verification-code', [
                'code' => $code,
                'email' => $request->email,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('🔐 Code de vérification - CuniApp Élevage')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            DB::commit();

            session([
                'verification_pending' => true,
                'verification_email' => $request->email,
            ]);

            return redirect()->route('welcome')
                ->with('success', 'Code de vérification envoyé ! Vérifiez votre email pour activer votre compte.')
                ->with('verification_pending', true)
                ->with('verification_email', $request->email);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'inscription: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
