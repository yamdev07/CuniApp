<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\ProfileUpdatedNotification;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà prise.',
            'current_password.required_with' => 'Le mot de passe actuel est requis pour définir un nouveau mot de passe.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'new_password' => 'Le mot de passe doit contenir au moins 8 caractères, incluant des majuscules, minuscules, chiffres et caractères spéciaux.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('new_password')) {
            // Vérification de sécurité avec le mot de passe actuel
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Votre mot de passe actuel est incorrect.']);
            }

            $user->password = $request->new_password;
        }

        $user->save();

        // Maintien de la session active même après modification du mot de passe
        Auth::guard('web')->login($user);

        // Envoi de la notification mail (sans données sensibles)
        $user->notify(new ProfileUpdatedNotification());

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


}



