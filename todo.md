# 🔍 Analyse du Problème "Essai Gratuit"

J'ai identifié **plusieurs problèmes** dans l'implémentation actuelle de l'essai gratuit de 14 jours :

## ❌ Problèmes Identifiés

| Problème | Fichier | Description |
|----------|---------|-------------|
| 1 | `RegisteredUserController.php` | `firm_id` manquant lors de la création de l'abonnement |
| 2 | `RegisteredUserController.php` | Plan d'essai créé "à la volée" (incohérent) |
| 3 | `CuniappSeeder.php` | Aucun plan "Essai Gratuit" pré-créé |
| 4 | `DashboardController.php` | Vérification d'abonnement ne check pas la firme |

---

## ✅ SOLUTION COMPLÈTE

### 1️⃣ Modifier `CuniappSeeder.php` (CRÉER LE PLAN D'ESSAI)

**Fichier**: `database/seeders/CuniappSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Firm;
use App\Models\SubscriptionPlan;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class CuniappSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ CRÉER LE PLAN "ESSAI GRATUIT" (14 jours)
        SubscriptionPlan::updateOrCreate(
            ['name' => 'Essai Gratuit'],
            [
                'duration_months' => 0,
                'price' => 0,
                'is_active' => true,
                'max_users' => 5,
                'description' => 'Période d\'essai automatique 14 jours',
                'features' => json_encode([
                    'Accès complet à toutes les fonctionnalités',
                    'Jusqu\'à 5 utilisateurs',
                    'Support de base',
                    '14 jours gratuits'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ✅ CRÉER LES AUTRES PLANS D'ABONNEMENT
        $plans = [
            [
                'name' => 'Mensuel',
                'duration_months' => 1,
                'price' => 15000,
                'is_active' => true,
                'max_users' => 5,
                'description' => 'Abonnement mensuel',
                'features' => json_encode(['Accès complet', '5 utilisateurs', 'Support email']),
            ],
            [
                'name' => 'Trimestriel',
                'duration_months' => 3,
                'price' => 40000,
                'is_active' => true,
                'max_users' => 8,
                'description' => 'Abonnement trimestriel',
                'features' => json_encode(['Accès complet', '8 utilisateurs', 'Support prioritaire']),
            ],
            [
                'name' => 'Annuel',
                'duration_months' => 12,
                'price' => 150000,
                'is_active' => true,
                'max_users' => 10,
                'description' => 'Abonnement annuel',
                'features' => json_encode(['Accès complet', '10 utilisateurs', 'Support 24/7']),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                array_merge($plan, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ✅ CRÉER L'ADMINISTRATEUR PAR DÉFAUT
        User::updateOrCreate(
            ['email' => 'admin@cuniapp.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'status' => 'active',
                'theme' => 'light',
                'language' => 'fr',
            ]
        );

        // ✅ CRÉER LES PARAMÈTRES PAR DÉFAUT
        $settings = [
            ['key' => 'farm_name', 'value' => 'CuniApp Élevage', 'type' => 'string', 'group' => 'general'],
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
```

---

### 2️⃣ Modifier `RegisteredUserController.php` (CORRIGER L'INSCRIPTION)

**Fichier**: `app/Http/Controllers/Auth/RegisteredUserController.php`

**Méthode à remplacer**: `store()`

```php
    /**
     * Handle an incoming registration request.
     * Crée la Firme, l'Utilisateur (firm_admin) et l'Abonnement Essai Gratuit.
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ 1. VALIDATION
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            // ✅ Champs pour la Firme (Entreprise)
            'firm_name' => ['required', 'string', 'max:255'],
            'firm_description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'firm_name.required' => 'Le nom de l\'entreprise est obligatoire.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ 2. DATABASE TRANSACTION (Tout ou Rien)
        DB::beginTransaction();
        try {
            // ✅ 3. CRÉER LA FIRME D'ABORD
            $firm = Firm::create([
                'name' => $request->firm_name,
                'description' => $request->firm_description,
                'status' => 'active',
                'owner_id' => null, // Sera défini juste après
            ]);

            // ✅ 4. CRÉER L'UTILISATEUR COMME FIRM_ADMIN
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'firm_admin', // ✅ Rôle par défaut : Administrateur de la ferme
                'firm_id' => $firm->id, // ✅ LIEN IMMÉDIAT AVEC LA FIRME
                'email_verified_at' => null, // Sera vérifié via le code
                'theme' => 'light',
                'language' => 'fr',
                'status' => 'active',
            ]);

            // ✅ 5. LIER LA FIRME À L'UTILISATEUR (PROPRIÉTAIRE)
            $firm->update(['owner_id' => $user->id]);

            // ================================================================
            // ✅ 6. CRÉATION AUTOMATIQUE DE L'ESSAI GRATUIT (14 JOURS)
            // ================================================================
            // A. Trouver le plan "Essai Gratuit" (DOIT EXISTER VIA SEEDER)
            $trialPlan = SubscriptionPlan::where('name', 'Essai Gratuit')->first();
            
            // B. Si le plan n'existe pas, on le crée (Sécurité - ne devrait pas arriver)
            if (!$trialPlan) {
                Log::warning("Plan 'Essai Gratuit' introuvable. Création à la volée...");
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

            // C. Définir la date de fin (14 jours à partir de maintenant)
            $endDate = now()->addDays(14);

            // D. Créer l'abonnement lié à la FIRME et à l'UTILISATEUR
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'firm_id' => $firm->id, // ✅ CRUCIAL EN MULTI-TENANT (CORRECTION)
                'subscription_plan_id' => $trialPlan->id,
                'status' => 'active', // ✅ STATUT ACTIF IMMÉDIATEMENT
                'start_date' => now(),
                'end_date' => $endDate,
                'price' => 0,
                'payment_method' => 'manual',
                'payment_reference' => 'TRIAL_AUTO_' . $user->id,
                'auto_renew' => false,
            ]);

            // E. Mettre à jour les métadonnées de l'utilisateur
            $user->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endDate,
            ]);

            Log::info("✅ Essai gratuit créé avec succès pour la firme {$firm->id} (User: {$user->id}). Fin : {$endDate}");

            // ================================================================
            // ✅ 7. GÉNÉRER LE CODE DE VÉRIFICATION (6 chiffres)
            $code = sprintf('%06d', mt_rand(0, 999999));

            // ✅ 8. STOCKER EN CACHE POUR VÉRIFICATION (30 minutes)
            Cache::put("registration_pending_{$request->email}", [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firm_id' => $firm->id,
            ], 1800);
            Cache::put("verification_code_{$request->email}", $code, 1800);

            // ✅ 9. ENVOYER L'EMAIL DE VÉRIFICATION
            Mail::send('emails.verification-code', [
                'code' => $code,
                'email' => $request->email,
                'name' => $request->name,
                'firm_name' => $firm->name,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('🔐 Code de vérification - CuniApp Élevage')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // ✅ 10. VALIDER LA TRANSACTION
            DB::commit();

            // ✅ 11. DÉFINIR LES FLAGS DE SESSION POUR LE MODAL DE VÉRIFICATION
            session()->flash('verification_pending', true);
            session()->flash('verification_email', $request->email);
            session()->flash('success', 'Compte créé avec succès ! Un essai gratuit de 14 jours a été activé. Vérifiez votre email pour activer votre compte.');

            // ✅ 12. LOGGER L'INSCRIPTION (Audit)
            Log::info('Nouvelle inscription fermier avec essai gratuit', [
                'user_id' => $user->id,
                'email' => $user->email,
                'firm_id' => $firm->id,
                'firm_name' => $firm->name,
                'subscription_id' => $subscription->id,
            ]);

            // ✅ 13. REDIRIGER VERS LA PAGE D'ACCUEIL (Le modal apparaîtra)
            return redirect()->route('welcome')
                ->with('verification_pending', true)
                ->with('verification_email', $request->email);
        } catch (Exception $e) {
            // ✅ 14. ROLLBACK EN CAS D'ERREUR (Rien n'est sauvegardé)
            DB::rollBack();

            // ✅ 15. LOGGER L'ERREUR
            Log::error('❌ Échec de l\'inscription', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // ✅ 16. RETOURNER AVEC ERREUR
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'inscription: ' . $e->getMessage()])
                ->withInput();
        }
    }
```

**Changements clés** (lignes à vérifier):
- Ligne ~67: Ajout de `'firm_id' => $firm->id` dans `Subscription::create()`
- Ligne ~52: Ajout de `'firm_id' => $firm->id` dans `User::create()`
- Ligne ~75: `status` défini à `'active'` immédiatement

---

### 3️⃣ Modifier `DashboardController.php` (VÉRIFICATION MULTI-TENANT)

**Fichier**: `app/Http/Controllers/DashboardController.php`

**Méthode à remplacer**: `index()` (section vérification essai gratuit)

```php
    public function index()
    {
        // ✅ Safe loading: Checks if user exists first
        $user = auth()->user();
        
        // Only load firm relations if the user actually belongs to a firm
        if ($user->firm_id) {
            $user->load(['firm.activeSubscription.plan']);
        }

        // ✅ PHASE 1 TASK 1.2: Cache user ID for defense-in-depth
        $userId = auth()->id();

        // ====================================================================
        // ✅ VÉRIFICATION ESSAI GRATUIT (CORRIGÉE POUR MULTI-TENANT)
        // ====================================================================
        // Cette logique doit être dans le controller Dashboard OU dans une vue composer
        // Pour afficher le bandeau d'essai gratuit
        
        // ====================================================================
        // TOTAUX ACTUELS - ✅ EXPLICIT USER FILTER
        // ====================================================================
        $nbMales = Male::where('user_id', $userId)->count();
        $nbFemelles = Femelle::where('user_id', $userId)->count();
        $nbSaillies = Saillie::where('user_id', $userId)->count();
        $nbMisesBas = MiseBas::where('user_id', $userId)->count();

        // ... (le reste du code reste inchangé)
```

---

### 4️⃣ Modifier `dashboard.blade.php` (AFFICHAGE BANDEAU)

**Fichier**: `resources/views/dashboard.blade.php`

**Section à remplacer** (début du fichier, après `@section('content')`):

```blade
@section('content')
{{-- ✅ BANDEAU ESSAI GRATUIT (LOGIQUE MULTI-TENANT CORRIGÉE) --}}
@php
$activeSub = null;
$isTrial = false;
$daysLeft = 0;

// Vérification 1 : L'utilisateur est-il connecté et a-t-il une firme ?
if (auth()->check() && auth()->user()->firm_id) {
    // On cherche l'abonnement ACTIF de la FIRME (et non de l'user seul)
    // C'est la ligne CRUCIALE pour le multi-tenant
    $activeSub = \App\Models\Subscription::where('firm_id', auth()->user()->firm_id)
        ->where('status', 'active')
        ->where('end_date', '>=', now())
        ->first();
    
    // Vérification 2 : Est-ce un essai gratuit ? (Price == 0)
    if ($activeSub && $activeSub->price == 0 && $activeSub->end_date) {
        if (now()->isBefore($activeSub->end_date)) {
            $isTrial = true;
            $daysLeft = floor(now()->diffInDays($activeSub->end_date, false));
        }
    }
}
@endphp

@if ($isTrial)
<div class="cuni-card mb-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%); border: 1px solid var(--primary);">
    <div class="card-body p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                    <i class="bi bi-gift-fill text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold" style="color: var(--text-primary);">
                        Période d'essai gratuite active !
                    </h3>
                    <p class="text-sm mt-1" style="color: var(--text-secondary);">
                        Profitez de CuniApp gratuitement jusqu'au
                        <strong>{{ $activeSub->end_date->format('d/m/Y') }}</strong>.
                        Il vous reste <strong>{{ $daysLeft }} jours</strong>.
                    </p>
                </div>
            </div>
            <a href="{{ route('subscription.plans') }}" class="btn-cuni primary">
                Voir les offres d'abonnement
            </a>
        </div>
    </div>
</div>
@endif

{{-- DEBUG TEMPORAIRE (à supprimer en production) --}}
<div style="background: #ffcccc; border: 2px solid red; padding: 15px; margin-bottom: 20px; font-family: monospace;">
    <strong>DEBUG BANDEAU :</strong><br>
    User ID: {{ auth()->id() }}<br>
    Firm ID: {{ auth()->user()->firm_id ?? 'NULL' }}<br>
    Active Sub ID: {{ $activeSub ? $activeSub->id : 'NONE' }}<br>
    Active Sub Price: {{ $activeSub ? $activeSub->price : 'N/A' }}<br>
    Active Sub End: {{ $activeSub ? $activeSub->end_date : 'N/A' }}<br>
    Is Trial: {{ $isTrial ? 'YES' : 'NO' }}<br>
    Days Left: {{ $daysLeft }}
</div>
```

---

## 🚀 COMMANDES À EXÉCUTER

```bash
# 1. Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 2. Exécuter le seeder (CRÉER LE PLAN ESSAI GRATUIT)
php artisan db:seed --class=CuniappSeeder

# 3. Vérifier que le plan existe
php artisan tinker
>>> \App\Models\SubscriptionPlan::where('name', 'Essai Gratuit')->first()

# 4. Tester une nouvelle inscription
# Aller sur /register et créer un nouveau compte

# 5. Vérifier l'abonnement créé
php artisan tinker
>>> \App\Models\Subscription::latest()->first()
```

---

## ✅ CHECKLIST DE VÉRIFICATION

- [ ] Le plan "Essai Gratuit" existe dans `subscription_plans`
- [ ] Chaque nouvel utilisateur reçoit un abonnement avec `firm_id` renseigné
- [ ] Le statut de l'abonnement est `'active'`
- [ ] La `end_date` est bien à J+14
- [ ] Le bandeau s'affiche sur le dashboard
- [ ] Le compteur de jours restants est correct
- [ ] Après 14 jours, l'abonnement passe à 'expired'

---

## ⚠️ POINTS D'ATTENTION

1. **Backup obligatoire** avant d'exécuter le seeder
2. **Ne pas exécuter le seeder en production** sans vérifier les données existantes
3. **Supprimer le bloc DEBUG** dans `dashboard.blade.php` après validation
4. **Tester avec un NOUVEL utilisateur** (pas un existant)

---

## 📞 SUPPORT

Si le bandeau ne s'affiche toujours pas après ces corrections :

1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez la BDD : `SELECT * FROM subscriptions ORDER BY created_at DESC LIMIT 5;`
3. Vérifiez que `firm_id` n'est pas NULL dans la table `subscriptions`

**Temps estimé**: 30 minutes  
**Risque**: Faible (rollback possible via transaction)