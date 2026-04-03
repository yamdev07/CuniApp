<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the welcome page
     */
    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle authentication
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        
        // Security check: User deactivated or Firm banned
        if ($user->status === 'inactive' || ($user->firm && $user->firm->isBanned())) {
            $message = ($user->status === 'inactive') 
                ? 'Votre compte est désactivé. Veuillez contacter contact@anyxtech.com'
                : 'Votre entreprise a été suspendue. Veuillez contacter contact@anyxtech.com';

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome')->withErrors(['error' => $message]);
        }

        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('super.admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout
     */
    // app/Http/Controllers/Auth/AuthenticatedSessionController.php

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome'); // Always go back to welcome page
    }
}
