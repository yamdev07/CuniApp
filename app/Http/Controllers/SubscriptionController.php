<?php
// app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display available subscription plans
     */
    public function index()
    {
        $user = Auth::user();
        $currentSubscription = $user->activeSubscription();
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('duration_months')
            ->get();

        $paymentHistory = PaymentTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('subscription.plans', compact('plans', 'currentSubscription', 'paymentHistory'));
    }

    /**
     * Show subscription form for selected plan
     */
    public function create(Request $request)
    {
        $planId = $request->query('plan_id');

        if (!$planId) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Veuillez sélectionner un plan d\'abonnement.');
        }

        $plan = SubscriptionPlan::findOrFail($planId);

        if (!$plan->is_active) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Ce plan n\'est plus disponible.');
        }

        $user = Auth::user();
        $currentSubscription = $user->activeSubscription();

        // Check if user already has active subscription
        if ($currentSubscription && $currentSubscription->end_date->isFuture()) {
            return redirect()->route('subscription.status')
                ->with('warning', 'Vous avez déjà un abonnement actif. Veuillez le renouveler ou attendre son expiration.');
        }

        return view('subscription.subscribe', compact('plan', 'currentSubscription'));
    }

    /**
     * Process subscription request
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:momo,celtis,moov,manual',
            'phone_number' => 'required_if:payment_method,momo,celtis,moov|nullable',
            'auto_renew' => 'boolean',
        ]);

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // ❌ CHECK: Don't allow if there's already a pending subscription
        $pendingSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingSubscription) {
            return redirect()->route('subscription.status')
                ->with('warning', 'Vous avez déjà une demande d\'abonnement en attente.');
        }

        DB::beginTransaction();
        try {
            // ✅ Create subscription with PENDING status (NOT active)
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'pending', // ← CRITICAL: Must be pending until payment confirmed
                'start_date' => now(),
                'end_date' => now()->addMonths($plan->duration_months),
                'price' => $plan->price,
                'payment_method' => $request->payment_method,
                'auto_renew' => $request->auto_renew ?? false,
            ]);

            // ✅ Create payment transaction
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_method' => $request->payment_method,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'pending',
                'provider' => $request->payment_method,
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();

            // ✅ REDIRECT to payment initiation (NOT activate immediately)
            return redirect()->route('payment.initiate', [
                'transaction_id' => $transaction->transaction_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscription.plans')
                ->with('error', 'Erreur lors de la création de l\'abonnement: ' . $e->getMessage());
        }
    }

    /**
     * Show subscription details and status
     */
    public function show()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        $allSubscriptions = Subscription::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $paymentHistory = PaymentTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('subscription.status', compact('subscription', 'allSubscriptions', 'paymentHistory'));
    }

    /**
     * Renew existing subscription
     */

    public function renew(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'required|in:momo,celtis,moov,manual',
            'phone_number' => 'required_if:payment_method,momo,celtis,moov|nullable',
        ]);

        $user = Auth::user();

        // ✅ Verify subscription belongs to user
        $subscription = Subscription::where('user_id', $user->id)
            ->findOrFail($request->subscription_id);

        $plan = $subscription->plan;

        DB::beginTransaction();
        try {
            // ✅ Create NEW subscription (don't modify existing)
            $newSubscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'pending', // ← Pending until payment
                'start_date' => $subscription->end_date->isFuture()
                    ? $subscription->end_date
                    : now(),
                'end_date' => ($subscription->end_date->isFuture()
                    ? $subscription->end_date
                    : now())->addMonths($plan->duration_months),
                'price' => $plan->price,
                'payment_method' => $request->payment_method,
                'auto_renew' => $subscription->auto_renew,
            ]);

            // ✅ Create payment transaction
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $newSubscription->id,
                'amount' => $plan->price,
                'payment_method' => $request->payment_method,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'pending',
                'provider' => $request->payment_method,
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();

            // ✅ Redirect to payment
            return redirect()->route('payment.initiate', [
                'transaction_id' => $transaction->transaction_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscription.status')
                ->with('error', 'Erreur lors du renouvellement: ' . $e->getMessage());
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'cancellation_reason' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->findOrFail($request->subscription_id);

        // Check if subscription belongs to user
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Accès non autorisé à cet abonnement.');
        }

        DB::beginTransaction();
        try {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Update user subscription status
            $user->update([
                'subscription_status' => 'expired',
                'subscription_ends_at' => $subscription->end_date,
            ]);

            DB::commit();

            return redirect()->route('subscription.status')
                ->with('success', 'Votre abonnement a été annulé. Vous aurez accès jusqu\'à la fin de la période payée.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscription.status')
                ->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }
}
