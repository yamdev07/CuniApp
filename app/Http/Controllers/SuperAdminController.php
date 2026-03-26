<?php
// app/Http/Controllers/SuperAdminController.php
namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\User;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();

        // Global Stats
        $stats = [
            'total_firms' => Firm::count(),
            'active_firms' => Firm::where('status', 'active')->count(),
            'banned_firms' => Firm::where('status', 'banned')->count(),
            'total_users' => User::count(),
            'total_revenue_month' => PaymentTransaction::where('status', 'completed')
                ->whereMonth('created_at', $now->month)
                ->sum('amount'),
            'total_revenue_year' => PaymentTransaction::where('status', 'completed')
                ->whereYear('created_at', $now->year)
                ->sum('amount'),
            'active_subscriptions' => Subscription::where('status', 'active')
                ->where('end_date', '>=', $now)
                ->count(),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereBetween('end_date', [$now, $now->copy()->addDays(7)])
                ->count(),
        ];

        // Top Firms by Revenue (Leaderboard)
        $topFirms = Firm::with(['owner', 'activeSubscription'])
            ->where('status', 'active')
            ->withCount(['sales as total_sales' => function ($q) {
                $q->where('payment_status', 'paid');
            }])
            ->withSum(['sales as total_revenue' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'total_amount')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Recent Signups (Last 7 days)
        $recentSignups = User::where('role', 'firm_admin')
            ->whereBetween('created_at', [$now->copy()->subDays(7), $now])
            ->with('firm')
            ->orderByDesc('created_at')
            ->get();

        // Login Activity (Last 24h - from sessions if tracked)
        $activeUsers24h = User::whereHas('sessions', function ($q) {
            $q->where('last_activity', '>=', $now->copy()->subHours(24)->timestamp);
        })->count();

        return view('super-admin.dashboard', compact('stats', 'topFirms', 'recentSignups', 'activeUsers24h'));
    }

    public function firms(Request $request)
    {
        $query = Firm::with(['owner', 'activeSubscription']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhereHas('owner', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%");
                });
        }

        $firms = $query->orderByDesc('created_at')->paginate(20);

        return view('super-admin.firms.index', compact('firms'));
    }

    public function banFirm($id)
    {
        $firm = Firm::findOrFail($id);
        $firm->update(['status' => 'banned']);

        // Logout all users from this firm
        User::where('firm_id', $firm->id)->update(['status' => 'inactive']);

        return back()->with('success', "L'entreprise {$firm->name} a été bannie.");
    }

    public function activateFirm($id)
    {
        $firm = Firm::findOrFail($id);
        $firm->update(['status' => 'active']);

        User::where('firm_id', $firm->id)->update(['status' => 'active']);

        return back()->with('success', "L'entreprise {$firm->name} a été activée.");
    }
}
