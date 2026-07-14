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
        $query = Firm::with(['owner', 'activeSubscription'])
            ->withCount(['users as active_users_count' => function ($q) {
                $q->where('status', 'active');
            }])
            ->with(['users' => function ($q) {
                $q->select('id', 'name', 'firm_id', 'role', 'status', 'last_seen_at')
                    ->where('role', 'firm_admin')
                    ->orderByDesc('last_seen_at');
            }]);

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

        if ($request->filled('activity')) {
            $activity = $request->activity;
            if ($activity === 'online') {
                $query->whereHas('users', function ($q) {
                    $q->where('role', 'firm_admin')
                        ->whereNotNull('last_seen_at')
                        ->where('last_seen_at', '>=', now()->subMinutes(5));
                });
            } elseif ($activity === 'recent') {
                $query->whereHas('users', function ($q) {
                    $q->where('role', 'firm_admin')
                        ->whereNotNull('last_seen_at')
                        ->where('last_seen_at', '>=', now()->subHours(24));
                });
            } elseif ($activity === 'inactive') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('users', function ($sq) {
                        $sq->where('role', 'firm_admin')
                            ->whereNotNull('last_seen_at')
                            ->where('last_seen_at', '>=', now()->subHours(24));
                    });
                });
            }
        }

        $query->withSum(['sales as total_revenue' => function ($q) {
            $q->where('payment_status', 'paid');
        }], 'total_amount');

        $sort = $request->get('sort', 'revenue');
        if ($sort === 'activity') {
            $query->withMax(['users as latest_activity' => function ($q) {
                $q->where('role', 'firm_admin');
            }], 'last_seen_at')
            ->orderBy('latest_activity', 'DESC');
        } else {
            $query->orderByDesc('total_revenue');
        }

        $firms = $query->paginate(20)->withQueryString();

        return view('super-admin.firms.index', compact('firms'));
    }

    public function firmsActivity()
    {
        $firms = Firm::with(['users' => function ($q) {
            $q->select('id', 'firm_id', 'role', 'last_seen_at')
                ->where('role', 'firm_admin');
        }])->get();

        $data = $firms->map(function ($firm) {
            $admin = $firm->users->first();
            return [
                'id' => $firm->id,
                'last_seen_at' => $admin?->last_seen_at?->toISOString(),
                'is_online' => $admin?->isOnline() ?? false,
            ];
        });

        return response()->json($data);
    }

    public function firmUsersActivity($id)
    {
        $users = User::where('firm_id', $id)
            ->select('id', 'last_seen_at', 'status')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'last_seen_at' => $user->last_seen_at?->toISOString(),
                    'is_online' => $user->isOnline(),
                ];
            });

        return response()->json($users);
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
        $firm = Firm::with(['owner', 'activeSubscription.plan'])->findOrFail($id);

        $usersQuery = $firm->users()->orderByRaw('
            CASE WHEN last_seen_at IS NULL THEN 1 ELSE 0 END ASC,
            last_seen_at DESC
        ');

        if (request('user_search')) {
            $search = request('user_search');
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (request('user_role')) {
            $usersQuery->where('role', request('user_role'));
        }

        if (request('user_status')) {
            $usersQuery->where('status', request('user_status'));
        }

        if (request('user_activity') === 'online') {
            $usersQuery->whereNotNull('last_seen_at')
                ->where('last_seen_at', '>=', now()->subMinutes(5));
        } elseif (request('user_activity') === 'recent') {
            $usersQuery->whereNotNull('last_seen_at')
                ->where('last_seen_at', '>=', now()->subHours(24));
        } elseif (request('user_activity') === 'inactive') {
            $usersQuery->where(function ($q) {
                $q->whereNull('last_seen_at')
                    ->orWhere('last_seen_at', '<', now()->subHours(24));
            });
        }

        $users = $usersQuery->get();

        $onlineCount = $users->filter(fn($u) => $u->isOnline())->count();

        $stats = [
            'total_males' => $firm->total_males,
            'total_femelles' => $firm->total_femelles,
            'total_sales' => $firm->sales()->count(),
            'total_revenue' => $firm->total_revenue,
            'user_count' => $firm->users()->count(),
            'subscription_limit' => $firm->subscription_limit,
            'online_count' => $onlineCount,
        ];

        return view('super-admin.firms.show', compact('firm', 'users', 'stats'));
    }
}
