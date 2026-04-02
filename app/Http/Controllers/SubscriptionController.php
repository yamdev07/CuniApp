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
use Illuminate\Support\Facades\Log;

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
            ->where('price', '>', 0)
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
    // public function create(Request $request)
    // {
    //     $planId = $request->query('plan_id');

    //     if (!$planId) {
    //         return redirect()->route('subscription.plans')
    //             ->with('error', 'Veuillez sélectionner un plan d\'abonnement.');
    //     }

    //     $plan = SubscriptionPlan::findOrFail($planId);

    //     if (!$plan->is_active) {
    //         return redirect()->route('subscription.plans')
    //             ->with('error', 'Ce plan n\'est plus disponible.');
    //     }

    //     $user = Auth::user();
    //     $currentSubscription = $user->activeSubscription();

    //     // Check if user already has active subscription
    //     if ($currentSubscription && $currentSubscription->end_date->isFuture()) {
    //         return redirect()->route('subscription.status')
    //             ->with('warning', 'Vous avez déjà un abonnement actif.');
    //     }

    //     return view('subscription.subscribe', compact('plan', 'currentSubscription'));
    // }



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

    // ✅ CORRECTION : Bloquer seulement si l'utilisateur a déjà un abonnement PAYANT actif
    // Autoriser l'upgrade depuis un essai gratuit (price = 0)
    if ($currentSubscription && $currentSubscription->end_date?->isFuture() && $currentSubscription->price > 0) {


        return redirect()->route('subscription.status')
            ->with('warning', 'Vous avez déjà un abonnement actif. Veuillez attendre son expiration ou le annuler pour souscrire à un nouveau plan.');
    }

    // ✅ Si l'utilisateur a un essai gratuit, il peut upgrade vers un plan payant
    // La nouvelle subscription remplacera l'essai (logique métier à gérer dans store())

    return view('subscription.subscribe', compact('plan', 'currentSubscription'));
}



    /**
     * Process NEW subscription request
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'plan_id' => 'required|exists:subscription_plans,id',
    //         'payment_method' => 'required|in:momo,celtis,moov,manual',
    //         'phone_number' => 'nullable|required_if:payment_method,momo,celtis,moov|string|min:8',
    //         'auto_renew' => 'boolean',
    //     ]);

    //     $user = Auth::user();
    //     $plan = SubscriptionPlan::findOrFail($request->plan_id);

    //     DB::beginTransaction();

    //     try {
    //         // Create subscription with PENDING status
    //         $subscription = Subscription::create([
    //             'user_id' => $user->id,
    //             'firm_id' => $user->firm_id, // ✅ ADD THIS: Link to Firm
    //             'subscription_plan_id' => $plan->id,
    //             'status' => 'pending',
    //             'start_date' => now(),
    //             'end_date' => now()->addMonths($plan->duration_months),
    //             'price' => $plan->price,
    //             'payment_method' => $request->payment_method,
    //             'auto_renew' => $request->auto_renew ?? false,
    //         ]);

    //         // Create payment transaction
    //         $transaction = PaymentTransaction::create([
    //             'user_id' => $user->id,
    //             'subscription_id' => $subscription->id,
    //             'amount' => $plan->price,
    //             'payment_method' => $request->payment_method,
    //             'transaction_id' => 'TXN-' . strtoupper(uniqid()),
    //             'status' => 'pending',
    //             'provider' => $request->payment_method,
    //             'phone_number' => $request->phone_number,
    //         ]);

    //         DB::commit();

    //         // Redirect to payment initiation
    //         return redirect()->route('payment.initiate', [
    //             'transaction_id' => $transaction->transaction_id
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Subscription creation failed: ' . $e->getMessage());

    //         return redirect()->route('subscription.plans')
    //             ->with('error', 'Erreur lors de la création de l\'abonnement: ' . $e->getMessage());
    //     }
    // }





   
    // /**
    //  * Process NEW subscription request
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'plan_id' => 'required|exists:subscription_plans,id',
    //         'payment_method' => 'required|in:momo,celtis,moov,manual',
    //         'phone_number' => 'nullable|required_if:payment_method,momo,celtis,moov|string|min:8',
    //         'auto_renew' => 'boolean',
    //     ]);

    //     $user = Auth::user();
    //     $plan = SubscriptionPlan::findOrFail($request->plan_id);
    //     $currentSubscription = $user->activeSubscription();

    //     DB::beginTransaction();

    //     try {
    //         // ✅ Si l'utilisateur a un essai gratuit actif, le marquer comme "upgraded"
    //         if ($currentSubscription && $currentSubscription->price <= 0 && $currentSubscription->end_date->isFuture()) {
    //             // Option 1 : Annuler l'essai gratuit
    //             $currentSubscription->update([
    //                 'status' => 'upgraded',
    //                 'upgraded_at' => now(),
    //             ]);

    //             // Option 2 : Ou simplement laisser l'essai expirer naturellement
    //             // (la nouvelle subscription prendra le relais)
    //         }

    //         // Créer la nouvelle subscription
    //         $subscription = Subscription::create([
    //             'user_id' => $user->id,
    //             'firm_id' => $user->firm_id,
    //             'subscription_plan_id' => $plan->id,
    //             'status' => 'pending',
    //             'start_date' => now(),
    //             'end_date' => now()->addMonths($plan->duration_months),
    //             'price' => $plan->price,
    //             'payment_method' => $request->payment_method,
    //             'auto_renew' => $request->auto_renew ?? false,
    //         ]);

    //         // Créer la transaction de paiement
    //         $transaction = PaymentTransaction::create([
    //             'user_id' => $user->id,
    //             'subscription_id' => $subscription->id,
    //             'amount' => $plan->price,
    //             'payment_method' => $request->payment_method,
    //             'transaction_id' => 'TXN-' . strtoupper(uniqid()),
    //             'status' => 'pending',
    //             'provider' => $request->payment_method,
    //             'phone_number' => $request->phone_number,
    //         ]);

    //         DB::commit();

    //         // ✅ Rediriger vers le formulaire de paiement (initiate.blade.php)
    //         return redirect()->route('payment.initiate', [
    //             'transaction_id' => $transaction->transaction_id
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Subscription creation failed: ' . $e->getMessage());

    //         return redirect()->route('subscription.plans')
    //             ->with('error', 'Erreur lors de la création de l\'abonnement: ' . $e->getMessage());
    //     }
    // }



    /**
 * Process NEW subscription request
 */
public function store(Request $request)
{
    $request->validate([
        'plan_id' => 'required|exists:subscription_plans,id',
        'payment_method' => 'required|in:momo,celtis,moov,manual',
        'phone_number' => 'nullable|required_if:payment_method,momo,celtis,moov|string|min:8',
        'auto_renew' => 'boolean',
    ]);

    $user = Auth::user();
    $plan = SubscriptionPlan::findOrFail($request->plan_id);
    $currentSubscription = $user->activeSubscription();

    DB::beginTransaction();

    try {
        // ✅ GUARD: Prevent multiple free trials (even if expired/cancelled)
        if ($plan->price <= 0) {
            $hasHadTrial = Subscription::where('user_id', $user->id)
                ->whereHas('plan', function($q) {
                    $q->where('price', '<=', 0);
                })->exists();
            
            if ($hasHadTrial) {
                return redirect()->route('subscription.plans')
                    ->with('error', 'Vous avez déjà bénéficié d\'un essai gratuit. Veuillez choisir un plan payant.');
            }
        }

        // ✅ Si l'utilisateur a un essai gratuit actif, on ne le modifie pas
        // Il expirera naturellement, le nouvel abonnement prendra le relais

        
        // Créer la nouvelle subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'firm_id' => $user->firm_id,
            'subscription_plan_id' => $plan->id,
            'status' => 'pending',  // ← Statut valide
            'start_date' => now(),
            'end_date' => now()->addMonths($plan->duration_months),
            'price' => $plan->price,
            'payment_method' => $request->payment_method,
            'auto_renew' => $request->auto_renew ?? false,
        ]);

        // Créer la transaction de paiement
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

        // ✅ Si c'est un essai gratuit (0 FCFA), activer immédiatement sans paiement
        if ($plan->price <= 0) {
            $subscription->update([
                'status' => 'active',  // ← Statut valide
                'activated_at' => now(),
            ]);
            
            $transaction->update([
                'status' => 'completed',  // ← Statut valide
                'completed_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('dashboard')
                ->with('success', '🎉 Votre essai gratuit de 14 jours est activé !');
        }

        DB::commit();

        // Rediriger vers le formulaire de paiement pour les plans payants
        return redirect()->route('payment.initiate', [
            'transaction_id' => $transaction->transaction_id
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Subscription creation failed: ' . $e->getMessage());

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

        // ✅ Load firm relationship to avoid errors in view
        $user->load('firm.activeSubscription.plan');

        $subscription = $user->activeSubscription();

        // Load ALL subscriptions with transactions for history table
        $allSubscriptions = Subscription::where('user_id', $user->id)
            ->with(['plan', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $paymentHistory = PaymentTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('subscription.status', compact('subscription', 'allSubscriptions', 'paymentHistory'));
    }

    /**
     * Renew EXISTING subscription (or retry failed one)
     */
    // public function renew(Request $request)
    // {
    //     $validated = $request->validate([
    //         'subscription_id' => 'required|exists:subscriptions,id',
    //         'payment_method' => 'required|in:momo,celtis,moov,manual',
    //         'phone_number' => 'nullable|string|min:8|max:15',
    //     ]);

    //     $user = Auth::user();
    //     $subscription = Subscription::where('user_id', $user->id)
    //         ->findOrFail($validated['subscription_id']);

    //     $plan = $subscription->plan;

    //     if (!$plan || !$plan->is_active) {
    //         return redirect()->route('subscription.status')
    //             ->with('error', 'Ce plan n\'est plus disponible.');
    //     }

    //     DB::beginTransaction();

    //     try {
    //         // Create NEW subscription for renewal
    //         $newSubscription = Subscription::create([
    //             'user_id' => $user->id,
    //             'firm_id' => $user->firm_id, // ✅ ADD THIS: Link to Firm
    //             'subscription_plan_id' => $plan->id,
    //             'status' => 'pending',
    //             'start_date' => $subscription->end_date->isFuture() ? $subscription->end_date : now(),
    //             'end_date' => ($subscription->end_date->isFuture() ? $subscription->end_date : now())
    //                 ->addMonths($plan->duration_months),
    //             'price' => $plan->price,
    //             'payment_method' => $validated['payment_method'],
    //             'auto_renew' => $subscription->auto_renew,
    //         ]);

    //         // Create payment transaction
    //         $transaction = PaymentTransaction::create([
    //             'user_id' => $user->id,
    //             'subscription_id' => $newSubscription->id,
    //             'amount' => $plan->price,
    //             'payment_method' => $validated['payment_method'],
    //             'transaction_id' => 'TXN-' . strtoupper(uniqid()),
    //             'status' => 'pending',
    //             'provider' => $validated['payment_method'],
    //             'phone_number' => $validated['phone_number'] ?? null,
    //         ]);

    //         DB::commit();

    //         // Redirect to payment initiation
    //         return redirect()->route('payment.initiate', [
    //             'transaction_id' => $transaction->transaction_id
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Subscription renewal failed: ' . $e->getMessage(), [
    //             'user_id' => $user->id,
    //             'subscription_id' => $validated['subscription_id'] ?? null,
    //         ]);

    //         return redirect()->route('subscription.status')
    //             ->with('error', 'Erreur lors du renouvellement: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }




    /**
     * Renew EXISTING subscription (or retry failed one)
     */
    public function renew(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'required|in:momo,celtis,moov,manual',
            'phone_number' => 'nullable|string|min:8|max:15',
        ]);

        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)
            ->findOrFail($validated['subscription_id']);

        $plan = $subscription->plan;

        // ✅ CORRECTION CRITIQUE : Bloquer le renouvellement des plans gratuits
        if (!$plan || !$plan->is_active || $plan->price <= 0) {
            return redirect()->route('subscription.plans')
                ->with('warning', 'ℹ️ L\'essai gratuit ne peut pas être renouvelé. Veuillez choisir une offre payante pour continuer.');
        }


        if (!$plan->is_active) {
            return redirect()->route('subscription.status')
                ->with('error', 'Ce plan n\'est plus disponible.');
        }

        DB::beginTransaction();

        try {
            // Créer le nouvel abonnement avec le plan payant sélectionné
            $newSubscription = Subscription::create([
                'user_id' => $user->id,
                'firm_id' => $user->firm_id,
                'subscription_plan_id' => $plan->id,
                'status' => 'pending',
                'start_date' => $subscription->end_date->isFuture() ? $subscription->end_date : now(),
                'end_date' => ($subscription->end_date->isFuture() ? $subscription->end_date : now())
                    ->addMonths($plan->duration_months),
                'price' => $plan->price,
                'payment_method' => $validated['payment_method'],
                'auto_renew' => $subscription->auto_renew,
            ]);

            // Créer la transaction de paiement
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $newSubscription->id,
                'amount' => $plan->price,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'pending',
                'provider' => $validated['payment_method'],
                'phone_number' => $validated['phone_number'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('payment.initiate', [
                'transaction_id' => $transaction->transaction_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription renewal failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'subscription_id' => $validated['subscription_id'] ?? null,
            ]);

            return redirect()->route('subscription.status')
                ->with('error', 'Erreur lors du renouvellement: ' . $e->getMessage())
                ->withInput();
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

        DB::beginTransaction();

        try {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            $user->update([
                'subscription_status' => 'expired',
                'subscription_ends_at' => $subscription->end_date,
            ]);

            DB::commit();

            return redirect()->route('subscription.status')
                ->with('success', 'Votre abonnement a été annulé.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subscription.status')
                ->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }
}
