<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Naissance;
use App\Models\Lapereau;
use App\Models\Sale;
use App\Models\SaleRabbit;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuniAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting CuniApp Seeder with Large Dataset...');

        // ========================================================================
        // STEP 1: Create Settings (Global Configuration)
        // ========================================================================
        $this->command->info('📋 Creating global settings...');
        $this->createSettings();

        // ========================================================================
        // STEP 2: Create Subscription Plans
        // ========================================================================
        $this->command->info('💳 Creating subscription plans...');
        $plans = $this->createSubscriptionPlans();

        // ========================================================================
        // STEP 3: Create Test Users (Accounts)
        // ========================================================================
        $this->command->info('👥 Creating test user accounts...');
        $users = $this->createUsers();

        // ========================================================================
        // STEP 4: Populate Data for Each User (Except One Empty Account)
        // ========================================================================
        $emptyAccountIndex = 0; // First account will be empty

        foreach ($users as $index => $user) {
            if ($index === $emptyAccountIndex) {
                $this->command->warn("⚠️  Skipping data for empty account: {$user->email}");
                continue;
            }

            $this->command->info("📊 Populating data for user: {$user->email}");
            $this->populateUserData($user, $plans, $index);
        }

        $this->command->info('✅ CuniApp Seeder completed successfully!');
        $this->command->info('📈 Summary:');
        $this->command->info('   - Users: ' . User::count());
        $this->command->info('   - Males: ' . Male::count());
        $this->command->info('   - Females: ' . Femelle::count());
        $this->command->info('   - Lapereaux: ' . Lapereau::count());
        $this->command->info('   - Saillies: ' . Saillie::count());
        $this->command->info('   - Mises Bas: ' . MiseBas::count());
        $this->command->info('   - Naissances: ' . Naissance::count());
        $this->command->info('   - Sales: ' . Sale::count());
        $this->command->info('   - Subscriptions: ' . Subscription::count());
    }

    /**
     * Create global settings
     */
    private function createSettings(): void
    {
        $settings = [
            ['key' => 'farm_name', 'value' => 'Ferme CuniApp Test', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_address', 'value' => 'Cotonou, Bénin', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_phone', 'value' => '+229 01 52 41 52 41', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_email', 'value' => 'contact@cuniapp.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'fedapay_public_key', 'value' => env('FEDAPAY_PUBLIC_KEY', 'pk_sandbox_test'), 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_secret_key', 'value' => env('FEDAPAY_SECRET_KEY', 'sk_sandbox_test'), 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_environment', 'value' => 'sandbox', 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_webhook_secret', 'value' => env('FEDAPAY_WEBHOOK_SECRET', 'wh_sandbox_test'), 'type' => 'string', 'group' => 'payments'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'label' => $setting['key'],
                ]
            );
        }
    }

    /**
     * Create subscription plans
     */
    private function createSubscriptionPlans(): array
    {
        $plansData = [
            ['name' => 'Mensuel', 'duration_months' => 1, 'price' => 2500, 'features' => ['Accès complet', 'Support email', 'Sauvegarde automatique']],
            ['name' => 'Trimestriel', 'duration_months' => 3, 'price' => 7500, 'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports avancés']],
            ['name' => 'Semestriel', 'duration_months' => 6, 'price' => 15000, 'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports avancés', 'Formation incluse']],
            ['name' => 'Annuel', 'duration_months' => 12, 'price' => 30000, 'features' => ['Accès complet', 'Support 24/7', 'Sauvegarde automatique', 'Rapports avancés', 'Formation incluse', 'API Access']],
        ];

        $plans = [];
        foreach ($plansData as $planData) {
            $plans[] = SubscriptionPlan::create([
                'name' => $planData['name'],
                'duration_months' => $planData['duration_months'],
                'price' => $planData['price'],
                'is_active' => true,
                'description' => "Plan {$planData['name']} CuniApp",
                'features' => $planData['features'],
            ]);
        }

        return $plans;
    }

    /**
     * Create test users
     */
    private function createUsers(): array
    {
        $usersData = [
            ['name' => 'Admin Test', 'email' => 'admin@cuniapp.test', 'role' => 'admin', 'empty' => true],
            ['name' => 'Éleveur 1', 'email' => 'eleveur1@cuniapp.test', 'role' => 'user', 'empty' => false],
            ['name' => 'Éleveur 2', 'email' => 'eleveur2@cuniapp.test', 'role' => 'user', 'empty' => false],
            ['name' => 'Éleveur 3', 'email' => 'eleveur3@cuniapp.test', 'role' => 'user', 'empty' => false],
            ['name' => 'Éleveur 4', 'email' => 'eleveur4@cuniapp.test', 'role' => 'user', 'empty' => false],
            ['name' => 'Éleveur 5', 'email' => 'eleveur5@cuniapp.test', 'role' => 'user', 'empty' => false],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $users[] = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => $userData['role'],
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonths(12),
                'theme' => 'light',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
            ]);
        }

        $this->command->info('   ✓ Created ' . count($users) . ' users (1 empty account)');
        return $users;
    }

    /**
     * Populate data for a specific user
     */
    private function populateUserData(User $user, array $plans, int $userIndex): void
    {
        DB::transaction(function () use ($user, $plans, $userIndex) {
            $baseDate = Carbon::now()->subMonths($userIndex * 2);

            // ========================================================================
            // Create Males (60 per account)
            // ========================================================================
            $this->command->info('   🐰 Creating 60 males...');
            $males = $this->createMales($user, 60, $baseDate);

            // ========================================================================
            // Create Females (200 per account)
            // ========================================================================
            $this->command->info('   🐰 Creating 200 females...');
            $females = $this->createFemales($user, 200, $baseDate);

            // ========================================================================
            // Create Saillies (150 per account)
            // ========================================================================
            $this->command->info('   💕 Creating 150 saillies...');
            $saillies = $this->createSaillies($user, $males, $females, 150, $baseDate);

            // ========================================================================
            // Create Mises Bas (100 per account)
            // ========================================================================
            $this->command->info('   🥚 Creating 100 mises bas...');
            $misesBas = $this->createMisesBas($user, $females, $saillies, 100, $baseDate);

            // ========================================================================
            // Create Naissances (100 per account)
            // ========================================================================
            $this->command->info('   🐣 Creating 100 naissances...');
            $naissances = $this->createNaissances($user, $misesBas, 100, $baseDate);

            // ========================================================================
            // Create Lapereaux (500 per account)
            // ========================================================================
            $this->command->info('   🐇 Creating 500 lapereaux...');
            $this->createLapereaux($user, $naissances, 500, $baseDate);

            // ========================================================================
            // Create Sales (50 per account)
            // ========================================================================
            $this->command->info('   💰 Creating 50 sales...');
            $this->createSales($user, $males, $females, 50, $baseDate);

            // ========================================================================
            // Create Subscription
            // ========================================================================
            $this->command->info('   💳 Creating subscription...');
            $this->createSubscription($user, $plans);

            // ========================================================================
            // Create Notifications
            // ========================================================================
            $this->command->info('   🔔 Creating notifications...');
            $this->createNotifications($user, 20);
        });
    }

    /**
     * Create male rabbits
     */
    private function createMales(User $user, int $count, Carbon $baseDate): array
    {
        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Nain', 'Angora', 'Bélier'];
        $males = [];

        for ($i = 1; $i <= $count; $i++) {
            $males[] = Male::create([
                'user_id' => $user->id,
                'code' => 'MAL-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Mâle {$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand(['Interne', 'Achat'])],
                'date_naissance' => $baseDate->copy()->subDays(rand(30, 365)),
                'etat' => ['Active', 'Inactive', 'Malade'][array_rand(['Active', 'Inactive', 'Malade'])],
                'created_at' => $baseDate->copy()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }

        return $males;
    }

    /**
     * Create female rabbits
     */
    private function createFemales(User $user, int $count, Carbon $baseDate): array
    {
        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Nain', 'Angora', 'Bélier'];
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];
        $females = [];

        for ($i = 1; $i <= $count; $i++) {
            $females[] = Femelle::create([
                'user_id' => $user->id,
                'code' => 'FEM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Femelle {$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand(['Interne', 'Achat'])],
                'date_naissance' => $baseDate->copy()->subDays(rand(30, 365)),
                'etat' => $etats[array_rand($etats)],
                'created_at' => $baseDate->copy()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }

        return $females;
    }

    /**
     * Create saillies (breeding records)
     */
    private function createSaillies(User $user, array $males, array $females, int $count, Carbon $baseDate): array
    {
        $saillies = [];

        for ($i = 1; $i <= $count; $i++) {
            $dateSaillie = $baseDate->copy()->subDays(rand(30, 200));
            $saillies[] = Saillie::create([
                'user_id' => $user->id,
                'femelle_id' => $females[array_rand($females)]->id,
                'male_id' => $males[array_rand($males)]->id,
                'date_saillie' => $dateSaillie,
                'date_palpage' => rand(0, 1) ? $dateSaillie->copy()->addDays(rand(10, 15)) : null,
                'palpation_resultat' => rand(0, 1) ? ['+', '-'][array_rand(['+', '-'])] : null,
                'date_mise_bas_theorique' => $dateSaillie->copy()->addDays(31),
                'created_at' => $dateSaillie->copy()->subDays(rand(0, 5)),
                'updated_at' => now(),
            ]);
        }

        return $saillies;
    }

    /**
     * Create mises bas (birthing records)
     */
    private function createMisesBas(User $user, array $females, array $saillies, int $count, Carbon $baseDate): array
    {
        $misesBas = [];

        for ($i = 1; $i <= $count; $i++) {
            $dateMiseBas = $baseDate->copy()->subDays(rand(20, 150));
            $femelle = $females[array_rand($females)];
            $saillie = count($saillies) > 0 ? $saillies[array_rand($saillies)] : null;

            $nbVivant = rand(3, 12);
            $nbMortNe = rand(0, 3);

            $misesBas[] = MiseBas::create([
                'user_id' => $user->id,
                'femelle_id' => $femelle->id,
                'saillie_id' => $saillie?->id,
                'date_mise_bas' => $dateMiseBas,
                'date_sevrage' => $dateMiseBas->copy()->addWeeks(6),
                'poids_moyen_sevrage' => rand(500, 800) / 1000,
                'created_at' => $dateMiseBas->copy()->subDays(rand(0, 3)),
                'updated_at' => now(),
            ]);
        }

        return $misesBas;
    }

    /**
     * Create naissances (birth records)
     */
    private function createNaissances(User $user, array $misesBas, int $count, Carbon $baseDate): array
    {
        $naissances = [];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        foreach ($misesBas as $index => $miseBas) {
            if ($index >= $count) break;

            $dateNaissance = $miseBas->date_mise_bas;
            $sexVerified = rand(0, 1) && $dateNaissance->diffInDays(now()) > 10;

            $naissances[] = Naissance::create([
                'user_id' => $user->id,
                'mise_bas_id' => $miseBas->id,
                'poids_moyen_naissance' => rand(40, 80),
                'etat_sante' => $etatsSante[array_rand($etatsSante)],
                'observations' => rand(0, 1) ? 'Portée en bonne santé' : null,
                'date_sevrage_prevue' => $dateNaissance->copy()->addWeeks(6),
                'date_vaccination_prevue' => $dateNaissance->copy()->addWeeks(4),
                'sex_verified' => $sexVerified,
                'sex_verified_at' => $sexVerified ? $dateNaissance->copy()->addDays(10) : null,
                'first_reminder_sent_at' => rand(0, 1) ? $dateNaissance->copy()->addDays(15) : null,
                'last_reminder_sent_at' => rand(0, 1) ? $dateNaissance->copy()->addDays(20) : null,
                'reminder_count' => rand(0, 3),
                'created_at' => $dateNaissance->copy()->subDays(rand(0, 2)),
                'updated_at' => now(),
            ]);
        }

        return $naissances;
    }

    /**
     * Create lapereaux (baby rabbits)
     */
    private function createLapereaux(User $user, array $naissances, int $count, Carbon $baseDate): void
    {
        $etats = ['vivant', 'vendu', 'mort'];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];
        $lapereauCount = 0;

        foreach ($naissances as $naissance) {
            if ($lapereauCount >= $count) break;

            $nbLapereaux = rand(3, 8);
            for ($i = 1; $i <= $nbLapereaux; $i++) {
                if ($lapereauCount >= $count) break;

                $lapereauCount++;
                $year = $baseDate->year;

                Lapereau::create([
                    'user_id' => $user->id,
                    'naissance_id' => $naissance->id,
                    'code' => 'LAP-' . $year . '-' . str_pad($lapereauCount, 4, '0', STR_PAD_LEFT),
                    'nom' => "Lapereau {$lapereauCount}",
                    'sex' => rand(0, 1) ? 'male' : 'female',
                    'etat' => $etats[array_rand($etats)],
                    'poids_naissance' => rand(40, 80),
                    'etat_sante' => $etatsSante[array_rand($etatsSante)],
                    'observations' => rand(0, 1) ? 'En bonne santé' : null,
                    'categorie' => ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'][array_rand(['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'])],
                    'alimentation_jour' => rand(50, 150),
                    'alimentation_semaine' => rand(350, 1050),
                    'created_at' => $naissance->created_at->copy()->subDays(rand(0, 1)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Create sales
     */
    private function createSales(User $user, array $males, array $females, int $count, Carbon $baseDate): void
    {
        $paymentStatuses = ['paid', 'pending', 'partial'];
        $buyerNames = ['Client 1', 'Client 2', 'Client 3', 'Marché Local', 'Partenaire Commercial', 'Éleveur Voisin'];

        for ($i = 1; $i <= $count; $i++) {
            $dateSale = $baseDate->copy()->subDays(rand(1, 180));
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $totalAmount = rand(15000, 100000);
            $amountPaid = $paymentStatus === 'paid' ? $totalAmount : ($paymentStatus === 'partial' ? $totalAmount * 0.5 : 0);

            $sale = Sale::create([
                'user_id' => $user->id,
                'date_sale' => $dateSale,
                'quantity' => rand(1, 5),
                'type' => ['male', 'female', 'lapereau'][array_rand(['male', 'female', 'lapereau'])],
                'category' => 'Vente Standard',
                'unit_price' => 0,
                'total_amount' => $totalAmount,
                'buyer_name' => $buyerNames[array_rand($buyerNames)],
                'buyer_contact' => '+229 01 ' . rand(10000000, 99999999),
                'buyer_address' => 'Cotonou, Bénin',
                'notes' => rand(0, 1) ? 'Paiement effectué' : null,
                'payment_status' => $paymentStatus,
                'amount_paid' => $amountPaid,
                'created_at' => $dateSale->copy()->subDays(rand(0, 2)),
                'updated_at' => now(),
            ]);

            // Create sale rabbit records
            $rabbitType = ['male', 'female', 'lapereau'][array_rand(['male', 'female', 'lapereau'])];
            if ($rabbitType === 'male' && count($males) > 0) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $males[array_rand($males)]->id,
                    'sale_price' => rand(20000, 30000),
                ]);
            } elseif ($rabbitType === 'female' && count($females) > 0) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $females[array_rand($females)]->id,
                    'sale_price' => rand(25000, 35000),
                ]);
            }
        }
    }

    /**
     * Create subscription for user
     */
    private function createSubscription(User $user, array $plans): void
    {
        $plan = $plans[array_rand($plans)];
        $startDate = now()->subMonths(rand(1, 6));
        $endDate = $startDate->copy()->addMonths($plan->duration_months);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $plan->price,
            'payment_method' => ['momo', 'celtis', 'moov'][array_rand(['momo', 'celtis', 'moov'])],
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'payment_reference' => 'REF-' . strtoupper(uniqid()),
            'auto_renew' => rand(0, 1),
        ]);

        // Create payment transaction
        PaymentTransaction::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'payment_method' => $subscription->payment_method,
            'transaction_id' => $subscription->transaction_id,
            'status' => 'completed',
            'provider' => 'fedapay',
            'phone_number' => '+229 01 ' . rand(10000000, 99999999),
            'paid_at' => $startDate,
            'created_at' => $startDate,
            'updated_at' => now(),
        ]);

        // Create invoice
        Invoice::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'payment_transaction_id' => PaymentTransaction::where('subscription_id', $subscription->id)->first()?->id,
            'invoice_number' => 'INV-' . now()->year . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'invoice_type' => 'subscription',
            'amount' => $plan->price,
            'tax_amount' => 0,
            'total_amount' => $plan->price,
            'currency' => 'XOF',
            'status' => 'paid',
            'invoice_date' => $startDate,
            'due_date' => $startDate->copy()->addDays(30),
            'paid_at' => $startDate,
            'billing_details' => ['name' => $user->name, 'email' => $user->email],
            'line_items' => [['description' => 'Abonnement CuniApp', 'quantity' => 1, 'unit_price' => $plan->price, 'total' => $plan->price]],
            'payment_method' => $subscription->payment_method,
            'transaction_reference' => $subscription->transaction_id,
            'pdf_generated' => true,
            'pdf_generated_at' => now(),
            'created_at' => $startDate,
            'updated_at' => now(),
        ]);
    }

    /**
     * Create notifications for user
     */
    private function createNotifications(User $user, int $count): void
    {
        $types = ['success', 'warning', 'info', 'error'];
        $titles = [
            'Nouvelle Saillie Enregistrée',
            'Mise Bas Enregistrée',
            'Vente Complétée',
            'Rappel: Vérification de Portée',
            'Abonnement Bientôt Expiré',
            'Paiement Réussi',
            'Nouveau Lapin Enregistré',
        ];

        for ($i = 1; $i <= $count; $i++) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $types[array_rand($types)],
                'title' => $titles[array_rand($titles)],
                'message' => 'Notification de test pour CuniApp Élevage',
                'action_url' => route('dashboard'),
                'icon' => 'bi-bell',
                'is_read' => rand(0, 1),
                'emailed' => rand(0, 1),
                'read_at' => rand(0, 1) ? now() : null,
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
