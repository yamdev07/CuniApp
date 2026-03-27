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
            'status' => ['required', 'in:active,inactive'],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status ?? 'active',
        ]);

        return back()->with('success', 'Employé mis à jour avec succès !');
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
}
    