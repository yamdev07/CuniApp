<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the form to change the password.
     */
    public function showChangeForm()
    {
        return view('auth.password-change');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password' => 'Le mot de passe doit contenir au moins 8 caractères, incluant des majuscules, minuscules, chiffres et caractères spéciaux.',
        ]);

        $user = auth()->user();

        // Security check: must not be the same as the current temporary password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Votre nouveau mot de passe doit être différent de celui créé par votre administrateur.']);
        }
        
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Re-login solely after password change to maintain session
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Votre mot de passe a été mis à jour avec succès. Bienvenue !');
    }
}
