<?php
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
        
        // ✅ Only Firm Admins can access
        if (!$user->isFirmAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Accès réservé aux administrateurs de l\'entreprise');
        }

        $firm = $user->firm;
        
        if (!$firm) {
            abort(404, 'Entreprise non trouvée');
        }

        // Calculate usage
        $activeUsersCount = $firm->active_users_count;
        $subscriptionLimit = $firm->subscription_limit;
        $usagePercentage = $firm->usage_percentage;
        $canAddMoreUsers = $firm->can_add_more_users;
        $remainingUsers = $firm->remaining_users;

        // Get employees
        $employees = User::where('firm_id', $firm->id)
            ->where('role', 'employee')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get firm stats
        $stats = [
            'total_males' => $firm->total_males,
            'total_femelles' => $firm->total_femelles,
            'total_sales' => $firm->sales()->count(),
            'total_revenue' => $firm->total_revenue,
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
            abort(403, 'Seul l\'administrateur peut ajouter des employés');
        }

        $firm = $user->firm;

        // ✅ Check subscription limit
        if (!$firm->can_add_more_users) {
            return back()
                ->withErrors(['error' => 'Limite d\'utilisateurs atteinte. Veuillez mettre à niveau votre abonnement.'])
                ->withInput();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:employee'],
        ]);

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'firm_id' => $firm->id,
            'email_verified_at' => now(),
            'theme' => 'light',
            'language' => 'fr',
        ]);

        $this->notifyUser([
            'user_id' => $user->id,
            'type' => 'success',
            'title' => 'Employé Ajouté',
            'message' => "{$employee->name} a été ajouté à votre entreprise.",
            'action_url' => route('firm.index'),
        ]);

        return back()->with('success', 'Employé ajouté avec succès !');
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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
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

        // Don't allow deactivating the owner
        if ($employee->id === $user->firm->owner_id) {
            return back()->withErrors(['error' => 'Impossible de désactiver le propriétaire']);
        }

        $employee->update(['status' => 'inactive']);

        return back()->with('success', 'Employé désactivé avec succès !');
    }
}