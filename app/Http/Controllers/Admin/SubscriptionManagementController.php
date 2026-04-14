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
use Illuminate\Support\Facades\Log;
use App\Notifications\SubscriptionExpiredNotification;
use App\Notifications\SubscriptionActivatedNotification;
use Carbon\Carbon;

class SubscriptionManagementController extends Controller
{
    /**
     * List all users with subscription status
     */
    public function index(Request $request)
    {
        // Only list firm_admin users (never employees)
        $query = User::where('role', 'firm_admin')
            ->where(function($q) {
                $q->whereDoesntHave('subscriptions')
                  ->orWhereHas('subscriptions', function($sq) {
                      $sq->whereNull('archived_at');
                  });
            })->with(['activeSubscriptionRelation.plan', 'subscriptions' => function($q) {
                $q->whereNull('archived_at')->latest();
            }]);

        // Filtre par statut d'abonnement
        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->whereHas('subscriptions', function ($sq) {
                    $sq->where('status', 'active')
                       ->where('end_date', '>=', now())
                       ->whereNull('archived_at');
                });
            } elseif ($status === 'expired') {
                $query->whereHas('subscriptions', function ($sq) {
                    $sq->where('end_date', '<', now())
                       ->whereNull('archived_at');
                });
            } elseif ($status === 'cancelled') {
                $query->whereHas('subscriptions', function ($sq) {
                    $sq->where('status', 'cancelled')
                       ->whereNull('archived_at');
                });
            } elseif ($status === 'inactive') {
                // User status is explicitly inactive
                $query->where('status', 'inactive');
            } elseif ($status === 'failed') {
                // User has at least one failed payment transaction
                $query->whereHas('paymentTransactions', function ($q) {
                    $q->where('status', 'failed');
                });
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_users' => User::where('role', 'firm_admin')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')
                ->where('end_date', '>=', now())
                ->whereNull('archived_at')
                ->count(),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereBetween('end_date', [now(), now()->addDays(7)])
                ->whereNull('archived_at')
                ->count(),
            'total_revenue' => DB::table('payment_transactions')
                ->where('status', 'completed')
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
        $user = User::with(['subscriptions.plan', 'paymentTransactions', 'firm.activeSubscription.plan'])->findOrFail($userId);

        // Security: Subscription management is only for firm owners/admins
        if (!$user->isFirmAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Cet utilisateur n\'est pas un administrateur d\'entreprise. Les employés ne gèrent pas d\'abonnements.');
        }

        $subscriptions = Subscription::where('user_id', $userId)
            ->whereNull('archived_at') // ✅ EXCLUDE ARCHIVED
            ->orderBy('created_at', 'desc')
            ->paginate(15);


        // If employee and no direct subscriptions, maybe show firm's history? 
        // For now, let's just make sure the view has access to the firm's active sub.

        $paymentHistory = PaymentTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.subscriptions.show', compact('user', 'subscriptions', 'paymentHistory'));
    }


    /**
     * Manually activate subscription
     */
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

        // ✅ FIX: Cast to integer to prevent Carbon TypeError
        $durationMonths = (int) ($request->duration_months ?? $plan->duration_months);

        DB::beginTransaction();
        try {
            // ✅ STACKING LOGIC: Find existing active subscription to extend its end date
            $existingActive = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('end_date', '>=', now())
                ->latest()
                ->first();

            $startDate = $existingActive ? $existingActive->end_date : now();
            $newEndDate = (clone $startDate)->addMonths($durationMonths);

            // Create new subscription entry (we don't "update" the old one's ID, we add a new period)
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'firm_id' => $user->firm_id, // ✅ CRITICAL: Assign firm_id for multi-tenancy
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'start_date' => $startDate,
                'end_date' => $newEndDate,
                'price' => $plan->price,
                'payment_method' => 'manual',
                'payment_reference' => 'MANUAL-' . strtoupper(uniqid()),
                'auto_renew' => false,
            ]);

            // Create payment transaction
            $paymentTransaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_method' => 'manual',
                'transaction_id' => 'MANUAL-TXN-' . strtoupper(uniqid()),
                'status' => 'completed',
                'provider' => 'manual',
                'paid_at' => now(),
            ]);

            // ✅ CREATE INVOICE for manual activation
            try {
                $invoiceService = new \App\Services\InvoiceService();
                $invoice = $invoiceService->createFromTransaction($paymentTransaction);

                // Send invoice email
                if ($invoice) {
                    $user->notify(new \App\Notifications\InvoiceEmailNotification($invoice));
                }
            } catch (\Exception $e) {
                Log::error('Admin manual invoice creation failed: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                ]);
                // Don't fail the activation if invoice creation fails
            }

            // Update user status and end date
            $user->update([
                'status' => 'active', // Ensure user is active if they were inactive
                'subscription_status' => 'active',
                'subscription_ends_at' => $newEndDate,
            ]);

            // ✅ SEND ACTIVATION NOTIFICATION (BEFORE commit)
            $user->notify(new SubscriptionActivatedNotification($subscription));

            DB::commit();

            return redirect()->route('admin.subscriptions.show', $user->id)
                ->with('success', 'Abonnement activé avec succès pour ' . $user->name . '. Facture générée.');
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

            // ✅ SEND EXPIRATION NOTIFICATION (BEFORE commit)
            $subscription->user->notify(new SubscriptionExpiredNotification($subscription));

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
                'status' => 'active', // Ensure user is active
                'subscription_status' => 'active',
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
        $query = PaymentTransaction::whereHas('user', function($q) {
            $q->where('role', 'firm_admin');
        })->with(['user', 'subscription']);

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


    /**
     * Archiver un abonnement (Actif ou Expiré)
     */
    public function archive($id)
    {
        $subscription = \App\Models\Subscription::findOrFail($id);
        
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Déjà archivé ? On ignore
        if ($subscription->archived_at) {
            return back()->with('info', 'Cet abonnement est déjà archivé.');
        }

        $subscription->archived_at = now();
        $subscription->save();

        // ✅ Sync User status
        if ($subscription->user) {
            $user = $subscription->user;
            // Re-evaluating status (might be expired or none now)
            $activeSub = $user->activeSubscription();
            if ($activeSub) {
                $user->subscription_status = 'active';
                $user->subscription_ends_at = $activeSub->end_date;
            } else {
                $user->subscription_status = 'inactive';
                $user->subscription_ends_at = null;
            }
            $user->save();
        }

        return back()->with('success', 'Abonnement archivé avec succès.');
    }





        /**
     * Restaurer un abonnement (Sécurisé)
     */
    public function restore($id)
    {
        try {
            $subscription = \App\Models\Subscription::where('id', $id)->firstOrFail();
            
            // On retire la marque d'archivage
            $subscription->archived_at = null;
            
            // Logique de statut intelligente
            $now = now();
            if ($subscription->end_date && $subscription->end_date > $now) {
                $subscription->status = 'active';
            } else {
                $subscription->status = 'expired';
            }
            
            $subscription->save();

            // ✅ Mise à jour de l'utilisateur (avec re-validation)
            if ($subscription->user) {
                $user = $subscription->user;
                $activeSub = $user->activeSubscription(); // Might be another one now

                if ($activeSub) {
                    $user->subscription_status = 'active';
                    $user->subscription_ends_at = $activeSub->end_date;
                } else {
                    $user->subscription_status = $subscription->status;
                    $user->subscription_ends_at = $subscription->end_date;
                }
                $user->save();
            }

            $msg = 'Abonnement restauré avec succès.';
            return back()->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }


    


        /**
     * Afficher la liste des archives
     */
    public function archives()
    {
        // On récupère les ABONNEMENTS archivés directement
        $archivedSubscriptions = \App\Models\Subscription::with(['user', 'plan'])
            ->whereNotNull('archived_at')
            ->orderBy('archived_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_archived' => \App\Models\Subscription::whereNotNull('archived_at')->count(),
        ];

        return view('admin.subscriptions.archives', compact('archivedSubscriptions', 'stats'));
    }



  
        /**
     * Archiver TOUS les abonnements d'un utilisateur (Sauve du temps)
     */
    public function archiveAll($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $user = \App\Models\User::findOrFail($userId);
        
        // On archive tout ce qui ne l'est pas
        \App\Models\Subscription::where('user_id', $userId)
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);

        // Sync user status
        $user->subscription_status = 'inactive';
        $user->subscription_ends_at = null;
        $user->save();

        return back()->with('success', 'Tous les abonnements de ' . $user->name . ' ont été archivés.');
    }

    /**
     * Supprimer DÉFINITIVEMENT un abonnement (Irréversible)
     */
    public function destroy($id) {

        $subscription = \App\Models\Subscription::findOrFail($id);
        
        // forceDelete() contourne le SoftDelete et efface vraiment la ligne de la BDD
        $subscription->forceDelete(); 
        
        return back()->with('success', 'Abonnement supprimé DÉFINITIVEMENT de la base de données.');
    }
}
