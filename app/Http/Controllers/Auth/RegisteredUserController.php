<?php
// app/Http/Controllers/Auth/RegisteredUserController.php
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ 1. VALIDATION
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            // ✅ Firm fields for Firm Admin registration
            'firm_name' => ['required', 'string', 'max:255'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'firm_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ 2. DATABASE TRANSACTION (All or Nothing)
        DB::beginTransaction();
        try {
            // ✅ 3. CREATE THE FIRM FIRST
            $firm = Firm::create([
                'name' => $request->firm_name,
                'description' => $request->firm_description,
                'status' => 'active',
                'owner_id' => null, // Will be set after user creation
            ]);

            // ✅ 4. CREATE THE USER AS FIRM ADMIN
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'firm_admin', // ✅ Default role for new registrations
                'firm_id' => $firm->id,
                'email_verified_at' => null, // Will be verified via code
                'theme' => 'light',
                'language' => 'fr',
                'status' => 'active',
            ]);

            // ✅ 5. LINK FIRM TO USER (OWNER)
            $firm->update(['owner_id' => $user->id]);

            // ✅ 6. GENERATE VERIFICATION CODE (6 digits)
            $code = sprintf('%06d', mt_rand(0, 999999));

            // ✅ 7. STORE IN CACHE FOR VERIFICATION (30 minutes)
            Cache::put("registration_pending_{$request->email}", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Already hashed
                'firm_id' => $firm->id,
            ], 1800);

            Cache::put("verification_code_{$request->email}", $code, 1800);

            // ✅ 8. SEND VERIFICATION EMAIL
            Mail::send('emails.verification-code', [
                'code' => $code,
                'email' => $request->email,
                'name' => $request->name,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('🔐 Code de vérification - CuniApp Élevage')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // ✅ 9. COMMIT TRANSACTION
            DB::commit();

            // ✅ 10. SET SESSION FLAGS FOR VERIFICATION MODAL
            session()->flash('verification_pending', true);
            session()->flash('verification_email', $request->email);
            session()->flash('success', 'Code de vérification envoyé ! Vérifiez votre email pour activer votre compte.');

            // ✅ 11. LOG THE REGISTRATION (For audit)
            Log::info('New user registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'firm_id' => $firm->id,
                'firm_name' => $firm->name,
            ]);

            // ✅ 12. REDIRECT TO WELCOME PAGE (Modal will appear)
            return redirect()->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $request->email);
        } catch (\Exception $e) {
            // ✅ 13. ROLLBACK ON ERROR
            DB::rollBack();

            // ✅ 14. LOG THE ERROR
            Log::error('Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // ✅ 15. RETURN WITH ERROR
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'inscription: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
