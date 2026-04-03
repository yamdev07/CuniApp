<?php
// app/Http/Controllers/SuperAdminController.php - UPDATED
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
            'total_users' => User::where('role', 'firm_admin')->count(),
            'total_revenue_month' => PaymentTransaction::whereHas('user', function($q) {
                    $q->where('role', 'firm_admin');
                })->where('status', 'completed')
                ->whereMonth('created_at', $now->month)
                ->sum('amount'),
            'total_revenue_year' => PaymentTransaction::whereHas('user', function($q) {
                    $q->where('role', 'firm_admin');
                })->where('status', 'completed')
                ->whereYear('created_at', $now->year)
                ->sum('amount'),
            'active_subscriptions' => Subscription::where(fn($q) => $q->where('status', 'active'))
                ->where(fn($q) => $q->where('end_date', '>=', $now->toDateTimeString()))
                ->whereNull('archived_at')

                ->count(),
            'expiring_soon' => Subscription::where(fn($q) => $q->where('status', 'active'))
                ->whereBetween('end_date', [$now, $now->copy()->addDays(7)])
                ->whereNull('archived_at')
                ->count(),

        ];

        // Top Firms by Revenue (Leaderboard)
        $topFirms = Firm::with(['owner', 'activeSubscription'])
            ->where(fn($q) => $q->where('status', 'active'))
            ->withCount(['sales as total_sales' => function ($q) {
                $q->where(fn($sub) => $sub->where('payment_status', 'paid'));
            }])
            ->withSum(['sales as total_revenue' => function ($q) {
                $q->where(fn($sub) => $sub->where('payment_status', 'paid'));
            }], 'total_amount')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Recent Signups (Last 7 days)
        $recentSignups = User::where(fn($q) => $q->where('role', 'firm_admin'))
            ->whereBetween('created_at', [$now->copy()->subDays(7), $now])
            ->with('firm')
            ->orderByDesc('created_at')
            ->get();

        // Login Activity (Last 24h) - Only Firm Admins
        $activeUsers24h = \Illuminate\Support\Facades\DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('users.role', 'firm_admin')
            ->where('sessions.last_activity', '>=', (int) $now->copy()->subHours(24)->timestamp)
            ->whereNotNull('sessions.user_id')
            ->distinct('sessions.user_id')
            ->count('sessions.user_id');

        // ✅ SIGNUP EVOLUTION DATA (Last 30 days) - ADD THIS
        $signupEvolution = User::where(fn($q) => $q->where('role', 'firm_admin'))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Build arrays for Chart.js
        $signupLabels = [];
        $signupCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $signupLabels[] = now()->subDays($i)->format('d/m');  // e.g., "23/03"
            $signupCounts[] = $signupEvolution->get($date) ?? 0;  // 0 if no signups that day
        }

        // Detailed Recent Activities (Last 48h) - Only Firm Admins
        $recentActivities = \App\Models\UserDailyActivity::with(['user.firm'])
            ->whereHas('user', function($q) {
                $q->where('role', 'firm_admin');
            })
            ->where('date', '>=', now()->subDays(2))
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('super-admin.dashboard', compact(
            'stats',
            'topFirms',
            'recentSignups',
            'activeUsers24h',
            'signupLabels',
            'signupCounts',
            'recentActivities'
        ));
    }


    public function firms(Request $request)
    {
        $query = Firm::with(['owner', 'activeSubscription']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('owner', function ($sq) use ($searchTerm) {
                        $sq->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        $firms = $query->orderByDesc('created_at')->paginate(20);

        return view('super-admin.firms.index', compact('firms'));
    }

    public function banFirm($id)
    {
        $firm = Firm::findOrFail($id);
        $firm->update(['status' => 'banned']);

        // ✅ LOG THE ACTION
        \App\Models\FirmAuditLog::log(
            $firm->id,
            auth()->id(),
            'firm_banned',
            'status',
            'active',
            'banned'
        );

        // ✅ NOTIFY FIRM ADMIN
        if ($firm->owner) {
            $firm->owner->notify(new \App\Notifications\FirmBannedNotification($firm));
        }

        // Logout all users from this firm
        User::where(fn($q) => $q->where('firm_id', $firm->id))->update(['status' => 'inactive']);

        return back()->with('success', "L'entreprise {$firm->name} a été bannie.");
    }

    public function activateFirm($id)
    {
        $firm = Firm::findOrFail($id);

        $firm->update(['status' => 'active']);

        User::where(fn($q) => $q->where('firm_id', $firm->id))->update(['status' => 'active']);

        return back()->with('success', "L'entreprise {$firm->name} a été activée.");
    }

    // ✅ NEW: View Firm Details
    public function showFirm($id)
    {
        $firm = Firm::with(['owner', 'activeSubscription.plan', 'users' => function($q) {
            $q->where('role', 'firm_admin');
        }])->findOrFail($id);

        $stats = [
            'total_males' => $firm->total_males,
            'total_femelles' => $firm->total_femelles,
            'total_sales' => $firm->sales()->count(),
            'total_revenue' => $firm->total_revenue,
            'user_count' => $firm->users()->where('role', 'firm_admin')->count(),
            'subscription_limit' => $firm->subscription_limit,
        ];

        return view('super-admin.firms.show', compact('firm', 'stats'));
    }
}
