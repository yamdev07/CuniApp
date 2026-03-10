<?php
// app/Http/Controllers/Admin/SubscriptionManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionManagementController extends Controller
{
    /**
     * List all users with subscription status
     */
    public function index(Request $request)
    {
        $query = User::with(['activeSubscription.plan']);

        // Filter by subscription status
        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->whereHas('subscriptions', function ($q) {
                    $q->where('status', 'active')
                        ->where('end_date', '>=', now());
                });
            } elseif ($status === 'expired') {
                $query->whereDoesntHave('subscriptions', function ($q) {
                    $q->where('status', 'active')
                        ->where('end_date', '>=', now());
                });
            } elseif ($status === 'cancelled') {
                $query->whereHas('subscriptions', function ($q) {
                    $q->where('status', 'cancelled');
                });
            }
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')
                ->where('end_date', '>=', now())->count(),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereBetween('end_date', [now(), now()->addDays(7)])->count(),
            'revenue_this_month' => PaymentTransaction::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        return view('admin.subscriptions.index', compact('users', 'stats'));
    }

    /**
     * View user subscription details
     */
    public function show($userId)
    {
        $user = User::with(['subscriptions.plan', 'paymentTransactions'])->findOrFail($userId);

        $subscriptions = Subscription::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $paymentHistory = PaymentTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.subscriptions.show', compact('user', 'subscriptions', 'paymentHistory'));
    }

    /**
     * Manually activate subscription
     */
    public function activate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration_months' => 'nullable|integer|min:1|max:24',
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        $durationMonths = $request->duration_months ?? $plan->duration_months;

        DB::beginTransaction();
        try {
            // Deactivate existing active subscriptions
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Create new subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths($durationMonths),
                'price' => $plan->price,
                'payment_method' => 'manual',
                'payment_reference' => 'MANUAL-' . strtoupper(uniqid()),
                'auto_renew' => false,
            ]);

            // Create payment transaction
            PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_method' => 'manual',
                'transaction_id' => 'MANUAL-TXN-' . strtoupper(uniqid()),
                'status' => 'completed',
                'provider' => 'manual',
                'paid_at' => now(),
            ]);

            // Update user
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $subscription->end_date,
            ]);

            DB::commit();

            return redirect()->route('admin.subscriptions.show', $user->id)
                ->with('success', 'Abonnement activé avec succès pour ' . $user->name);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate subscription
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        DB::beginTransaction();
        try {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->reason ?? 'Désactivation par administrateur',
            ]);

            // Update user
            $subscription->user->update([
                'subscription_status' => 'expired',
                'subscription_ends_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.subscriptions.show', $subscription->user_id)
                ->with('success', 'Abonnement désactivé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Extend subscription period
     */
    public function extend(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'months' => 'required|integer|min:1|max:24',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        DB::beginTransaction();
        try {
            $subscription->update([
                'end_date' => $subscription->end_date->addMonths($request->months),
            ]);

            // Update user
            $subscription->user->update([
                'subscription_ends_at' => $subscription->end_date,
            ]);

            DB::commit();

            return redirect()->route('admin.subscriptions.show', $subscription->user_id)
                ->with('success', "Abonnement prolongé de {$request->months} mois avec succès");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * View all payment transactions
     */
    public function transactions(Request $request)
    {
        $query = PaymentTransaction::with(['user', 'subscription']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(25);

        $stats = [
            'total_revenue' => PaymentTransaction::where('status', 'completed')->sum('amount'),
            'pending_count' => PaymentTransaction::where('status', 'pending')->count(),
            'failed_count' => PaymentTransaction::where('status', 'failed')->count(),
            'completed_count' => PaymentTransaction::where('status', 'completed')->count(),
        ];

        return view('admin.subscriptions.transactions', compact('transactions', 'stats'));
    }

    /**
     * Export subscription data
     */
    public function export(Request $request)
    {
        $subscriptions = Subscription::with(['user', 'plan', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'subscriptions_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'User', 'Email', 'Plan', 'Price', 'Status', 'Start Date', 'End Date', 'Payment Method'];

        $callback = function () use ($subscriptions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->id,
                    $subscription->user->name,
                    $subscription->user->email,
                    $subscription->plan->name,
                    $subscription->price,
                    $subscription->status,
                    $subscription->start_date->format('Y-m-d'),
                    $subscription->end_date->format('Y-m-d'),
                    $subscription->payment_method,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
