<?php
// app/Http/Controllers/FirmController.php - UPDATED
namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Traits\Notifiable;

class FirmController extends Controller
{
    use Notifiable;

    public function index()
    {
        $user = auth()->user();

        if (!$user->isFirmAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Accès réservé aux administrateurs de l\'entreprise');
        }

        $firm = $user->firm;

        if (!$firm) {
            abort(404, 'Entreprise non trouvée');
        }

        $activeUsersCount = $firm->active_users_count;
        $subscriptionLimit = $firm->subscription_limit;
        $usagePercentage = $firm->usage_percentage;
        $canAddMoreUsers = $firm->can_add_more_users;
        $remainingUsers = $firm->remaining_users;

        $employees = User::where('firm_id', $firm->id)
            ->where('role', 'employee')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // ✅ NEW: Firm Statistics
        $stats = [
            'total_males' => $firm->total_males,
            'total_femelles' => $firm->total_femelles,
            'total_sales' => $firm->sales()->count(),
            'total_revenue' => $firm->total_revenue,
            'total_saillies' => $firm->total_saillies,
            'total_naissances' => $firm->total_naissances,
        ];

        return view('firm.index', compact(
            'firm',
            'activeUsersCount',
            'subscriptionLimit',
            'usagePercentage',
            'canAddMoreUsers',
            'remainingUsers',
            'employees',
            'stats'
        ));
    }

    public function storeEmployee(Request $request)
    {
        $user = auth()->user();

        if (!$user->isFirmAdmin()) {
            return back()
                ->withErrors(['error' => 'Seul l\'administrateur peut ajouter des employés'])
                ->withInput();
        }

        $firm = $user->firm;

        // ✅ CRITICAL: Check subscription limit
        if (!$firm->can_add_more_users) {
            return back()
                ->withErrors(['error' => 'Limite d\'utilisateurs atteinte. Veuillez mettre à niveau votre abonnement.'])
                ->withInput();
        }

        // ✅ COMPREHENSIVE VALIDATION
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email' // ✅ Unique across ALL users
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                Rules\Password::defaults() // ✅ Same as registration
            ],
            'role' => ['required', 'in:employee'],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Format d\'email invalide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre utilisateur.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        try {
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'firm_id' => $firm->id,
                'email_verified_at' => now(), // ✅ Auto-verify employees
                'theme' => 'light',
                'language' => 'fr',
                'status' => 'active',
            ]);

            // ✅ Notify firm admin
            $this->notifyUser([
                'user_id' => $user->id,
                'type' => 'success',
                'title' => 'Employé Ajouté',
                'message' => "{$employee->name} ({$employee->email}) a été ajouté à votre entreprise.",
                'action_url' => route('firm.index'),
            ]);

            // ✅ Notify new employee
            $employee->notify(new \App\Notifications\EmployeeAddedNotification($employee, $firm));

            return back()->with('success', '✅ Employé ajouté avec succès !');
        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ Catch unique constraint violations
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['email' => 'Cette adresse email est déjà utilisée.'])
                    ->withInput();
            }
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'ajout: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function updateEmployee(Request $request, $userId)
    {
        $user = auth()->user();

        if (!$user->isFirmAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $employee = User::where('id', $userId)
            ->where('firm_id', $user->firm_id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId // ✅ Exclude current user
            ],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Employé mis à jour avec succès !');
    }

    public function employeeActivity(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->isFirmAdmin()) {
            abort(403);
        }

        $employee = \App\Models\User::where('id', $id)->where('firm_id', $user->firm_id)->firstOrFail();
        
        $period = $request->get('period', 30);
        $days = $period == '6_months' ? 180 : (int) $period;

        $activities = \App\Models\UserDailyActivity::where('user_id', $employee->id)
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('d/m');
            $data[] = isset($activities[$dateStr]) ? $activities[$dateStr]->hits : 0;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function deactivateEmployee($userId)
    {
        $user = auth()->user();

        if (!$user->isFirmAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $employee = User::where('id', $userId)
            ->where('firm_id', $user->firm_id)
            ->where('role', 'employee')
            ->firstOrFail();

        if ($employee->id === $user->firm->owner_id) {
            return back()->withErrors(['error' => 'Impossible de désactiver le propriétaire']);
        }

        $employee->update(['status' => 'inactive']);

        return back()->with('success', 'Employé désactivé avec succès !');
    }

    public function updateFirm(Request $request)
    {
        $user = auth()->user();
        if (!$user->isFirmAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $firm = $user->firm;
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        // ✅ LOG CHANGES
        if ($firm->name !== $validated['name']) {
            \App\Models\FirmAuditLog::log(
                $firm->id,
                $user->id,
                'firm_name_updated',
                'name',
                $firm->name,
                $validated['name']
            );
        }

        if ($firm->description !== $validated['description']) {
            \App\Models\FirmAuditLog::log(
                $firm->id,
                $user->id,
                'firm_description_updated',
                'description',
                $firm->description,
                $validated['description']
            );
        }

        $firm->update($validated);

        return back()->with('success', 'Informations de l\'entreprise mises à jour !');
    }

    public function deleteEmployee($userId)
    {
        $user = auth()->user();
        if (!$user->isFirmAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $employee = User::where('id', $userId)
            ->where('firm_id', $user->firm_id)
            ->where('role', 'employee')
            ->firstOrFail();

        if ($employee->id === $user->firm->owner_id) {
            return back()->withErrors(['error' => 'Impossible de supprimer le propriétaire']);
        }

        // ✅ LOG THE ACTION
        \App\Models\FirmAuditLog::log(
            $user->firm_id,
            $user->id,
            'employee_deleted',
            'user_id',
            null,
            $employee->id
        );

        $employeeName = $employee->name;
        $employee->delete();

        return back()->with('success', "L'employé {$employeeName} a été supprimé définitivement.");
    }

    public function patchUpdate(Request $request)
    {
        return $this->updateFirm($request);
    }

    /**
     * ✅ NEW: Show firm onboarding form (for Google Auth users or missing firms)
     */
    public function setup()
    {
        $user = auth()->user();
        if ($user->firm_id) {
            return redirect()->route('dashboard');
        }

        return view('firm.setup');
    }

    /**
     * ✅ NEW: Store firm created during onboarding
     */
    public function setupStore(Request $request)
    {
        $user = auth()->user();
        if ($user->firm_id) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'firm_name' => ['required', 'string', 'max:255', 'min:3'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
        ], [
            'firm_name.required' => 'Le nom de votre élevage/entreprise est obligatoire.',
            'firm_name.min' => 'Le nom doit contenir au moins 3 caractères.',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Create Firm
            $firm = Firm::create([
                'name' => $request->firm_name,
                'description' => $request->firm_description,
                'status' => 'active',
                'owner_id' => $user->id,
            ]);

            // 2. Link User to Firm and set role
            $user->update([
                'firm_id' => $firm->id,
                'role' => 'firm_admin', // Onboarding user becomes admin
            ]);

            // 3. Create 14-day trial (matching RegisteredUserController logic)
            $trialPlan = SubscriptionPlan::where('name', 'Essai Gratuit')->first();
            if (!$trialPlan) {
                $trialPlan = SubscriptionPlan::create([
                    'name' => 'Essai Gratuit',
                    'duration_months' => 0,
                    'price' => 0,
                    'is_active' => true,
                    'max_users' => 5,
                    'description' => 'Période d\'essai automatique 14 jours',
                    'features' => json_encode(['Accès complet', 'Jusqu\'à 5 utilisateurs', 'Support de base']),
                ]);
            }

            $endDate = now()->addDays(14);
            \App\Models\Subscription::create([
                'user_id' => $user->id,
                'firm_id' => $firm->id,
                'subscription_plan_id' => $trialPlan->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => $endDate,
                'price' => 0,
                'payment_method' => 'manual',
                'payment_reference' => 'TRIAL_ONBOARDING_' . $user->id,
                'auto_renew' => false,
            ]);

            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('dashboard')->with('success', '✅ Entreprise créée avec succès ! Votre essai gratuit de 14 jours est actif.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Onboarding Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création: ' . $e->getMessage()])->withInput();
        }
    }
}
    