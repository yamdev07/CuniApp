<?php

namespace Database\Seeders;

use App\Models\Femelle;
use App\Models\Male;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Naissance;
use App\Models\Lapereau;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use App\Models\Sale;
use App\Models\SaleRabbit;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CuniAppSeeder extends Seeder
{
    /** Invoice counter — ensures globally unique invoice numbers */
    private int $invoiceCounter = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->printHeader();

        $this->command->info('🧹 Cleaning existing data...');
        $this->cleanTables();

        $this->command->info("🚀 Starting CuniApp Database Seeding...\n");

        // Seed in correct order (respecting foreign keys)
        $this->seedSettings();
        $this->seedSubscriptionPlans();
        $this->seedUsers();
        $this->seedUserData();

        $this->printFooter();
        $this->printLoginCredentials();
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    private function printHeader(): void
    {
        $this->command->line('');
        $this->command->line('╔══════════════════════════════════════════════════════════════╗');
        $this->command->line('║                                                              ║');
        $this->command->line('║       🐰  CUNIAPP ÉLEVAGE - DATABASE SEEDER  🐰             ║');
        $this->command->line('║                                                              ║');
        $this->command->line('║     Gestion Intelligente de Votre Élevage de Lapins          ║');
        $this->command->line('║                                                              ║');
        $this->command->line('╚══════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }

    private function printFooter(): void
    {
        $this->command->line('');
        $this->command->line('✅ Seeding completed successfully!');
        $this->command->line('🔑 See login credentials below');
        $this->command->line('');
    }

    private function cleanTables(): void
    {
        // Truncate in REVERSE dependency order (children first, then parents)
        $tables = [
            // Level 1: Tables that reference others (truncate FIRST)
            'sale_rabbits',         // → sales, males/femelles/lapereaux
            'payment_transactions', // → users, subscriptions
            'invoices',             // → payment_transactions, subscriptions, users
            'subscriptions',        // → users, subscription_plans
            'lapereaux',            // → naissances, mises_bas
            'naissances',           // → mises_bas, saillies, users
            'mises_bas',            // → saillies, femelles
            'saillies',             // → femelles, males
            'notifications',        // → users

            // Level 2: Parent tables (truncate LAST)
            'sales',
            'femelles',
            'males',
            'subscription_plans',
            'settings',
            'users',

            // Laravel system tables
            'sessions',
            'password_reset_tokens',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("  ✓ Table {$table} cleared");
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    // ========================================================================
    // SEEDING METHODS
    // ========================================================================

    private function seedSettings(): void
    {
        $this->command->info("⚙️  Seeding Settings...");

        $settings = [
            // Farm Info
            ['key' => 'farm_name',    'value' => 'Ferme CuniApp Test',        'type' => 'string',  'group' => 'general',       'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => 'Houéyiho, Cotonou, Bénin',  'type' => 'string',  'group' => 'general',       'label' => 'Adresse'],
            ['key' => 'farm_phone',   'value' => '+2290152415241',             'type' => 'string',  'group' => 'general',       'label' => 'Téléphone'],
            ['key' => 'farm_email',   'value' => 'contact@cuniapp.bj',        'type' => 'string',  'group' => 'general',       'label' => 'Email'],

            // Breeding Settings
            ['key' => 'gestation_days',             'value' => '31', 'type' => 'number',  'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks',              'value' => '6',  'type' => 'number',  'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold',            'value' => '80', 'type' => 'number',  'group' => 'breeding', 'label' => "Seuil d'alerte (%)"],
            ['key' => 'verification_initial_days',  'value' => '10', 'type' => 'number',  'group' => 'breeding', 'label' => 'Délai initial vérification (jours)'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number',  'group' => 'breeding', 'label' => 'Délai premier rappel (jours)'],
            ['key' => 'verification_interval_days', 'value' => '5',  'type' => 'number',  'group' => 'breeding', 'label' => 'Intervalle rappels (jours)'],

            // Default Prices
            ['key' => 'default_price_male',      'value' => '25000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Mâles'],
            ['key' => 'default_price_female',    'value' => '30000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Femelles'],
            ['key' => 'default_price_lapereau',  'value' => '15000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Lapereaux'],

            // FedaPay Settings
            ['key' => 'fedapay_public_key',      'value' => env('FEDAPAY_PUBLIC_KEY', ''),        'type' => 'string', 'group' => 'payments', 'label' => 'Clé Publique FedaPay'],
            ['key' => 'fedapay_secret_key',      'value' => env('FEDAPAY_SECRET_KEY', ''),        'type' => 'string', 'group' => 'payments', 'label' => 'Clé Secrète FedaPay'],
            ['key' => 'fedapay_environment',     'value' => env('FEDAPAY_ENVIRONMENT', 'sandbox'), 'type' => 'string', 'group' => 'payments', 'label' => 'Environnement FedaPay'],
            ['key' => 'fedapay_webhook_secret',  'value' => env('FEDAPAY_WEBHOOK_SECRET', ''),    'type' => 'string', 'group' => 'payments', 'label' => 'Secret Webhook FedaPay'],

            // Subscription Settings
            ['key' => 'grace_period_days',   'value' => '3', 'type' => 'number',  'group' => 'subscriptions', 'label' => 'Période de grâce (jours)'],
            ['key' => 'enable_auto_renew',   'value' => '1', 'type' => 'boolean', 'group' => 'subscriptions', 'label' => 'Renouvellement auto'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info("  ✓ " . count($settings) . " settings created");
    }

    private function seedSubscriptionPlans(): void
    {
        $this->command->info("📋 Seeding Subscription Plans...");

        $plans = [
            [
                'name'           => 'Mensuel',
                'duration_months' => 1,
                'price'          => 2500,
                'is_active'      => true,
                'features'       => ['Accès complet', 'Support email', 'Sauvegarde journalière'],
            ],
            [
                'name'           => 'Trimestriel',
                'duration_months' => 3,
                'price'          => 7500,
                'is_active'      => true,
                'features'       => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports mensuels'],
            ],
            [
                'name'           => 'Semestriel',
                'duration_months' => 6,
                'price'          => 15000,
                'is_active'      => true,
                'features'       => ['Accès complet', 'Support prioritaire 24/7', 'Sauvegarde automatique', 'Rapports avancés', 'Export données'],
            ],
            [
                'name'           => 'Annuel',
                'duration_months' => 12,
                'price'          => 30000,
                'is_active'      => true,
                'features'       => ['Accès complet', 'Support VIP', 'Sauvegarde automatique', 'Rapports personnalisés', 'Export illimité', 'Formation incluse'],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info("  ✓ 4 subscription plans created");
    }

    private function seedUsers(): void
    {
        $this->command->info("👥 Seeding Users...");

        // ─────────────────────────────────────────────────────────────────────
        // ADMIN — full access, active subscription, huge dataset
        // ─────────────────────────────────────────────────────────────────────
        User::create([
            'name'                    => 'Admin CuniApp',
            'email'                   => 'admin@cuniapp.bj',
            'password'                => Hash::make('Admin123!'),
            'email_verified_at'       => now(),
            'role'                    => 'admin',
            'subscription_status'     => 'active',
            'subscription_ends_at'    => now()->addYear(),
            'last_subscription_at'    => now()->subDays(10),
            'theme'                   => 'light',
            'language'                => 'fr',
            'notifications_email'     => true,
            'notifications_dashboard' => true,
        ]);

        // ─────────────────────────────────────────────────────────────────────
        // TEST USERS — each with a different subscription plan
        // ─────────────────────────────────────────────────────────────────────
        $testUsers = [
            [
                'name'                    => 'Test User 1',
                'email'                   => 'user1@cuniapp.bj',
                'password'                => 'User123!',
                'subscription_months'     => 1,
                'subscription_offset_days' => 5,   // started 5 days ago, ~25 days left
            ],
            [
                'name'                    => 'Test User 2',
                'email'                   => 'user2@cuniapp.bj',
                'password'                => 'User123!',
                'subscription_months'     => 3,
                'subscription_offset_days' => 15,
            ],
            [
                'name'                    => 'Test User 3',
                'email'                   => 'user3@cuniapp.bj',
                'password'                => 'User123!',
                'subscription_months'     => 6,
                'subscription_offset_days' => 30,
            ],
            [
                'name'                    => 'Test User 4',
                'email'                   => 'user4@cuniapp.bj',
                'password'                => 'User123!',
                'subscription_months'     => 12,
                'subscription_offset_days' => 50,
            ],
            [
                // No subscription, no data
                'name'                    => 'Test User 5',
                'email'                   => 'user5@cuniapp.bj',
                'password'                => 'User123!',
                'subscription_months'     => 0,
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::create([
                'name'                    => $userData['name'],
                'email'                   => $userData['email'],
                'password'                => Hash::make($userData['password']),
                'email_verified_at'       => now(),
                'role'                    => 'user',
                'theme'                   => 'system',
                'language'               => 'fr',
                'subscription_status'     => 'inactive',
                'notifications_email'     => true,
                'notifications_dashboard' => true,
            ]);

            if ($userData['subscription_months'] > 0) {
                $plan = SubscriptionPlan::where('duration_months', $userData['subscription_months'])->first();
                if ($plan) {
                    $startDate = now()->subDays($userData['subscription_offset_days'] ?? 0);
                    $endDate   = $startDate->copy()->addMonths($userData['subscription_months']);
                    $status    = $endDate->isPast() ? 'expired' : 'active';

                    $subscription = Subscription::create([
                        'user_id'              => $user->id,
                        'subscription_plan_id' => $plan->id,
                        'status'               => $status,
                        'start_date'           => $startDate,
                        'end_date'             => $endDate,
                        'price'                => $plan->price,
                        'payment_method'       => 'manual',
                        'payment_reference'    => 'SEED-' . strtoupper(Str::random(8)),
                        'auto_renew'           => false,
                    ]);

                    $transaction = PaymentTransaction::create([
                        'user_id'         => $user->id,
                        'subscription_id' => $subscription->id,
                        'amount'          => $plan->price,
                        'payment_method'  => 'manual',
                        'transaction_id'  => 'TXN-SEED-' . strtoupper(Str::random(8)),
                        'status'          => 'completed',
                        'provider'        => 'manual',
                        'paid_at'         => $startDate,
                    ]);

                    // ── Generate invoice for this subscription ─────────────
                    $this->createInvoice($user, $subscription, $transaction, $startDate, $plan->price, $status, $plan->name);

                    $user->update([
                        'subscription_status'  => $status,
                        'subscription_ends_at' => $endDate,
                        'last_subscription_at' => $startDate,
                    ]);
                }
            }
        }

        $this->command->info("  ✓ 6 users created (Admin + User1→User5)");
    }

    // ========================================================================
    // INVOICE HELPER
    // ========================================================================

    /**
     * Create a single invoice record and return it.
     */
    private function createInvoice(
        User               $user,
        Subscription       $subscription,
        PaymentTransaction $transaction,
        Carbon             $invoiceDate,
        float              $amount,
        string             $subStatus = 'active',
        string             $planName  = 'Plan'
    ): void {
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($this->invoiceCounter++, 5, '0', STR_PAD_LEFT);
        $invoiceStatus = ($subStatus === 'active' || $subStatus === 'expired') ? 'paid' : 'pending';

        DB::table('invoices')->insert([
            'user_id'                 => $user->id,
            'subscription_id'         => $subscription->id,
            'payment_transaction_id'  => $transaction->id,
            'invoice_number'          => $invoiceNumber,
            'invoice_type'            => 'subscription',
            'amount'                  => $amount,
            'tax_amount'              => 0,
            'total_amount'            => $amount,
            'currency'                => 'XOF',
            'status'                  => $invoiceStatus,
            'invoice_date'            => $invoiceDate->toDateString(),
            'due_date'                => $invoiceDate->copy()->addDays(3)->toDateString(),
            'paid_at'                 => $invoiceStatus === 'paid' ? $invoiceDate->toDateTimeString() : null,
            'pdf_generated'           => false,
            'billing_details'         => json_encode([
                'name'    => $user->name,
                'email'   => $user->email,
                'address' => 'Cotonou, Bénin',
            ]),
            'line_items' => json_encode([
                [
                    'description' => 'Abonnement CuniApp – ' . $planName,
                    'quantity'    => 1,
                    'unit_price'  => $amount,
                    'total'       => $amount,
                ],
            ]),
            'payment_method'         => 'manual',
            'transaction_reference'  => $transaction->transaction_id,
            'notes'                  => 'Facture générée automatiquement lors du seeding.',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }

    // ========================================================================
    // ANIMAL DATA SEEDING
    // ========================================================================

    private function seedUserData(): void
    {
        $this->command->info("📊 Seeding animal data for each user...\n");

        /**
         * Dataset sizes per account
         * ─────────────────────────────────────────────────────────────────────
         * Key           │ Admin │ User1 │ User2 │ User3 │ User4 │ User5
         * ─────────────────────────────────────────────────────────────────────
         * males         │  60   │  60   │  60   │  60   │  10   │   0
         * females       │ 200   │ 200   │ 200   │ 200   │  20   │   0
         * saillies      │ 300   │ 300   │ 300   │ 300   │  25   │   0
         * mises_bas     │ 250   │ 250   │ 250   │ 250   │  20   │   0
         * naissances    │ 200   │ 200   │ 200   │ 200   │  15   │   0
         * lapereaux_per │   5   │   5   │   5   │   5   │   3   │   0
         * → ~lapereaux  │~1000  │~1000  │~1000  │~1000  │  ~45  │   0
         * sales         │  80   │  80   │  80   │  80   │   5   │   0
         * notifs        │  20   │  20   │  20   │  20   │   5   │   0
         * invoices      │   0*  │   1   │   1   │   1   │   1   │   0
         * ─────────────────────────────────────────────────────────────────────
         * * Admin invoice seeded separately in seedUsers() via admin sub logic
         *   (admin has no sub plan row here; sub created inline below)
         */
        $userConfigs = [
            // user_id => config
            1 => ['males' => 60,  'females' => 200, 'saillies' => 300, 'mises_bas' => 250, 'naissances' => 200, 'lapereaux_per' => 5, 'sales' => 80, 'notifs' => 20], // Admin
            2 => ['males' => 60,  'females' => 200, 'saillies' => 300, 'mises_bas' => 250, 'naissances' => 200, 'lapereaux_per' => 5, 'sales' => 80, 'notifs' => 20], // User1 – 1-month sub
            3 => ['males' => 60,  'females' => 200, 'saillies' => 300, 'mises_bas' => 250, 'naissances' => 200, 'lapereaux_per' => 5, 'sales' => 80, 'notifs' => 20], // User2 – 3-month sub
            4 => ['males' => 60,  'females' => 200, 'saillies' => 300, 'mises_bas' => 250, 'naissances' => 200, 'lapereaux_per' => 5, 'sales' => 80, 'notifs' => 20], // User3 – 6-month sub
            5 => ['males' => 10,  'females' =>  20, 'saillies' =>  25, 'mises_bas' =>  20, 'naissances' =>  15, 'lapereaux_per' => 3, 'sales' =>  5, 'notifs' =>  5], // User4 – 1-year sub, small
            6 => ['males' =>  0,  'females' =>   0, 'saillies' =>   0, 'mises_bas' =>   0, 'naissances' =>   0, 'lapereaux_per' => 0, 'sales' =>  0, 'notifs' =>  0], // User5 – no sub, empty
        ];

        $races         = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
        $etatsMales    = ['Active', 'Inactive', 'Malade'];
        $etatsFemelles = ['Active', 'Gestante', 'Allaitante', 'Vide'];
        $etatsLaps     = ['vivant', 'vendu', 'mort'];
        $etatsSante    = ['Excellent', 'Bon', 'Moyen', 'Faible'];
        $categories    = ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'];
        $origines      = ['Interne', 'Achat'];
        $year          = date('Y');

        foreach ($userConfigs as $userId => $cfg) {
            $user = User::find($userId);
            if (!$user) {
                $this->command->warn("  ⚠  User ID {$userId} not found, skipping.");
                continue;
            }

            $this->command->info("  ──────────────────────────────────────────────────────");
            $this->command->info("  👤 Seeding data for [{$user->email}] (ID: {$userId})...");

            // ── Skip empty accounts ───────────────────────────────────────────
            if ($cfg['males'] === 0 && $cfg['females'] === 0) {
                $this->command->info("     (empty account – skipping)");
                continue;
            }

            // ── Seed admin subscription + invoice (admin has no plan row above) ──
            if ($userId === 1) {
                $adminPlan = SubscriptionPlan::where('duration_months', 12)->first();
                if ($adminPlan) {
                    $adminStart = now()->subDays(10);
                    $adminEnd   = $adminStart->copy()->addYear();

                    $adminSub = Subscription::create([
                        'user_id'              => $userId,
                        'subscription_plan_id' => $adminPlan->id,
                        'status'               => 'active',
                        'start_date'           => $adminStart,
                        'end_date'             => $adminEnd,
                        'price'                => $adminPlan->price,
                        'payment_method'       => 'manual',
                        'payment_reference'    => 'SEED-ADMIN-' . strtoupper(Str::random(6)),
                        'auto_renew'           => true,
                    ]);

                    $adminTxn = PaymentTransaction::create([
                        'user_id'         => $userId,
                        'subscription_id' => $adminSub->id,
                        'amount'          => $adminPlan->price,
                        'payment_method'  => 'manual',
                        'transaction_id'  => 'TXN-ADMIN-' . strtoupper(Str::random(8)),
                        'status'          => 'completed',
                        'provider'        => 'manual',
                        'paid_at'         => $adminStart,
                    ]);

                    $this->createInvoice($user, $adminSub, $adminTxn, $adminStart, $adminPlan->price, 'active', $adminPlan->name);
                    $this->command->info("     ✓ Admin subscription + invoice created");
                }
            }

            // ── 1. MALES ─────────────────────────────────────────────────────
            for ($i = 1; $i <= $cfg['males']; $i++) {
                Male::create([
                    'user_id'        => $userId,
                    'code'           => 'MAL-' . $userId . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom'            => "Mâle-U{$userId}-{$i}",
                    'race'           => $races[array_rand($races)],
                    'origine'        => $origines[array_rand($origines)],
                    'date_naissance' => now()->subMonths(rand(6, 36))->subDays(rand(0, 30)),
                    'etat'           => $etatsMales[array_rand($etatsMales)],
                ]);
            }
            $this->command->info("     ✓ {$cfg['males']} mâles créés");

            // ── 2. FEMELLES ──────────────────────────────────────────────────
            for ($i = 1; $i <= $cfg['females']; $i++) {
                Femelle::create([
                    'user_id'        => $userId,
                    'code'           => 'FEM-' . $userId . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom'            => "Femelle-U{$userId}-{$i}",
                    'race'           => $races[array_rand($races)],
                    'origine'        => $origines[array_rand($origines)],
                    'date_naissance' => now()->subMonths(rand(6, 36))->subDays(rand(0, 30)),
                    'etat'           => $etatsFemelles[array_rand($etatsFemelles)],
                ]);
            }
            $this->command->info("     ✓ {$cfg['females']} femelles créées");

            // ── 3. SAILLIES ──────────────────────────────────────────────────
            $femelles = Femelle::where('user_id', $userId)->inRandomOrder()->limit($cfg['saillies'])->get();
            $males    = Male::where('user_id', $userId)->get();

            foreach ($femelles as $femelle) {
                if ($males->isEmpty()) break;
                $male        = $males->random();
                $dateSaillie = now()->subDays(rand(10, 180));

                Saillie::create([
                    'user_id'                  => $userId,
                    'femelle_id'               => $femelle->id,
                    'male_id'                  => $male->id,
                    'date_saillie'             => $dateSaillie->format('Y-m-d'),
                    'date_palpage'             => rand(0, 1) ? $dateSaillie->copy()->addDays(rand(10, 14))->format('Y-m-d') : null,
                    'palpation_resultat'       => rand(0, 1) ? ['+', '-'][array_rand([0, 1])] : null,
                    'date_mise_bas_theorique'  => $dateSaillie->copy()->addDays(31)->format('Y-m-d'),
                ]);
            }
            $this->command->info("     ✓ {$cfg['saillies']} saillies créées");

            // ── 4. MISES BAS ─────────────────────────────────────────────────
            // Prefer saillies with a positive palpation result
            $sailliesPositives = Saillie::where('user_id', $userId)
                ->whereNotNull('date_palpage')
                ->where('palpation_resultat', '+')
                ->inRandomOrder()
                ->limit($cfg['mises_bas'])
                ->get();

            // Fill up from any saillies if not enough positives
            if ($sailliesPositives->count() < $cfg['mises_bas']) {
                $remaining = $cfg['mises_bas'] - $sailliesPositives->count();
                $extra     = Saillie::where('user_id', $userId)
                    ->whereNotIn('id', $sailliesPositives->pluck('id'))
                    ->inRandomOrder()
                    ->limit($remaining)
                    ->get();
                $sailliesPositives = $sailliesPositives->merge($extra);
            }

            foreach ($sailliesPositives as $saillie) {
                $dateMiseBas = Carbon::parse($saillie->date_saillie)->addDays(31);
                MiseBas::create([
                    'user_id'             => $userId,
                    'femelle_id'          => $saillie->femelle_id,
                    'saillie_id'          => $saillie->id,
                    'date_mise_bas'       => $dateMiseBas->format('Y-m-d'),
                    'date_sevrage'        => $dateMiseBas->copy()->addWeeks(6)->format('Y-m-d'),
                    'poids_moyen_sevrage' => rand(500, 1500) / 1000,
                ]);
            }
            $this->command->info("     ✓ " . $sailliesPositives->count() . " mises-bas créées");

            // ── 5. NAISSANCES ────────────────────────────────────────────────
            $misesBas = MiseBas::where('user_id', $userId)
                ->inRandomOrder()
                ->limit($cfg['naissances'])
                ->get();

            foreach ($misesBas as $miseBas) {
                $dateMiseBas = Carbon::parse($miseBas->date_mise_bas);
                Naissance::create([
                    'user_id'                  => $userId,
                    'mise_bas_id'              => $miseBas->id,
                    'saillie_id'               => $miseBas->saillie_id,
                    'poids_moyen_naissance'    => rand(40, 80),
                    'etat_sante'               => $etatsSante[array_rand($etatsSante)],
                    'observations'             => 'Portée en bonne santé – ' . Str::random(20),
                    'date_sevrage_prevue'      => $dateMiseBas->copy()->addWeeks(6)->format('Y-m-d'),
                    'date_vaccination_prevue'  => $dateMiseBas->copy()->addWeeks(4)->format('Y-m-d'),
                    'sex_verified'             => rand(0, 10) > 6,
                    'sex_verified_at'          => rand(0, 10) > 6 ? now()->subDays(rand(1, 10)) : null,
                    'reminder_count'           => rand(0, 3),
                ]);
            }
            $this->command->info("     ✓ " . $misesBas->count() . " naissances créées");

            // ── 6. LAPEREAUX ─────────────────────────────────────────────────
            if ($cfg['lapereaux_per'] > 0) {
                $naissances   = Naissance::where('user_id', $userId)->get();
                $lapereauCount = 0;

                foreach ($naissances as $naissance) {
                    $nb = rand(
                        max(1, $cfg['lapereaux_per'] - 1),
                        $cfg['lapereaux_per'] + 2
                    );

                    for ($j = 1; $j <= $nb; $j++) {
                        $lapereauCount++;
                        $code = "LAP-{$year}-U{$userId}-" . str_pad($lapereauCount, 5, '0', STR_PAD_LEFT);

                        Lapereau::create([
                            'user_id'           => $userId,
                            'naissance_id'      => $naissance->id,
                            'code'              => $code,
                            'nom'               => "Lapereau-U{$userId}-{$lapereauCount}",
                            'sex'               => rand(0, 1) ? 'male' : 'female',
                            'etat'              => $etatsLaps[array_rand($etatsLaps)],
                            'poids_naissance'   => rand(40, 90),
                            'etat_sante'        => $etatsSante[array_rand($etatsSante)],
                            'observations'      => 'Lapereau en bonne santé – ' . Str::random(15),
                            'categorie'         => $categories[array_rand($categories)],
                            'alimentation_jour' => rand(50, 200) / 100,
                            'alimentation_semaine' => rand(300, 1200) / 100,
                        ]);
                    }
                }
                $this->command->info("     ✓ {$lapereauCount} lapereaux créés");
            }

            // ── 7. SALES ─────────────────────────────────────────────────────
            if ($cfg['sales'] > 0) {
                $limitPerType  = max(1, intval($cfg['sales'] / 3));

                $malesForSale    = Male::where('user_id', $userId)->where('etat', 'Inactive')->limit($limitPerType)->get();
                $femellesForSale = Femelle::where('user_id', $userId)->where('etat', 'Vide')->limit($limitPerType)->get();
                $lapereauxForSale = Lapereau::where('user_id', $userId)->where('etat', 'vendu')->limit($limitPerType)->get();

                $totalSales = 0;

                foreach ($malesForSale as $male) {
                    $amountPaid = rand(0, 1) ? 25000 : rand(5000, 20000);
                    $sale = Sale::create([
                        'user_id'        => $userId,
                        'date_sale'      => now()->subDays(rand(1, 120)),
                        'quantity'       => 1,
                        'type'           => 'male',
                        'total_amount'   => 25000,
                        'buyer_name'     => 'Acheteur-' . strtoupper(Str::random(6)),
                        'buyer_contact'  => '+229' . rand(90000000, 99999999),
                        'payment_status' => $amountPaid >= 25000 ? 'paid' : ($amountPaid > 0 ? 'partial' : 'pending'),
                        'amount_paid'    => $amountPaid,
                    ]);
                    SaleRabbit::create([
                        'sale_id'     => $sale->id,
                        'rabbit_type' => 'male',
                        'rabbit_id'   => $male->id,
                        'sale_price'  => 25000,
                    ]);
                    $totalSales++;
                }

                foreach ($femellesForSale as $femelle) {
                    $amountPaid = rand(0, 1) ? 30000 : rand(5000, 25000);
                    $sale = Sale::create([
                        'user_id'        => $userId,
                        'date_sale'      => now()->subDays(rand(1, 120)),
                        'quantity'       => 1,
                        'type'           => 'female',
                        'total_amount'   => 30000,
                        'buyer_name'     => 'Acheteur-' . strtoupper(Str::random(6)),
                        'buyer_contact'  => '+229' . rand(90000000, 99999999),
                        'payment_status' => $amountPaid >= 30000 ? 'paid' : ($amountPaid > 0 ? 'partial' : 'pending'),
                        'amount_paid'    => $amountPaid,
                    ]);
                    SaleRabbit::create([
                        'sale_id'     => $sale->id,
                        'rabbit_type' => 'female',
                        'rabbit_id'   => $femelle->id,
                        'sale_price'  => 30000,
                    ]);
                    $totalSales++;
                }

                foreach ($lapereauxForSale as $lapereau) {
                    $amountPaid = rand(0, 1) ? 15000 : rand(3000, 12000);
                    $sale = Sale::create([
                        'user_id'        => $userId,
                        'date_sale'      => now()->subDays(rand(1, 120)),
                        'quantity'       => 1,
                        'type'           => 'lapereau',
                        'total_amount'   => 15000,
                        'buyer_name'     => 'Acheteur-' . strtoupper(Str::random(6)),
                        'buyer_contact'  => '+229' . rand(90000000, 99999999),
                        'payment_status' => $amountPaid >= 15000 ? 'paid' : ($amountPaid > 0 ? 'partial' : 'pending'),
                        'amount_paid'    => $amountPaid,
                    ]);
                    SaleRabbit::create([
                        'sale_id'     => $sale->id,
                        'rabbit_type' => 'lapereau',
                        'rabbit_id'   => $lapereau->id,
                        'sale_price'  => 15000,
                    ]);
                    $totalSales++;
                }

                $this->command->info("     ✓ {$totalSales} ventes créées");
            }

            // ── 8. NOTIFICATIONS ─────────────────────────────────────────────
            if ($cfg['notifs'] > 0) {
                $types = ['success', 'info', 'warning', 'error'];
                $icons = [
                    'success' => 'bi-check-circle-fill',
                    'info'    => 'bi-info-circle-fill',
                    'warning' => 'bi-exclamation-triangle-fill',
                    'error'   => 'bi-x-circle-fill',
                ];
                $notifMessages = [
                    'success' => [
                        'Saillie enregistrée avec succès pour la femelle #%s.',
                        'Mise-bas confirmée – %s lapereaux nés en bonne santé.',
                        'Vente clôturée avec paiement complet.',
                        'Données de la portée mises à jour.',
                        'Sevrage programmé créé pour la portée.',
                    ],
                    'info' => [
                        'Rappel : palpation prévue dans 2 jours pour femelle #%s.',
                        'Le mâle #%s a été utilisé pour 3 saillies ce mois-ci.',
                        'Mise-bas théorique dans 5 jours pour la femelle #%s.',
                        'Sevrage prévu dans 3 jours.',
                        'Vérification de sexe en attente pour la portée #%s.',
                    ],
                    'warning' => [
                        'Abonnement expirant dans %s jours – pensez à renouveler.',
                        "Femelle #%s n'a pas été saillie depuis plus de 60 jours.",
                        'Poids moyen au sevrage inférieur à la norme pour la portée #%s.',
                        'Lapereaux en état "Faible" détectés dans la portée #%s.',
                        'Stock mâles faible – seulement %s disponibles.',
                    ],
                    'error' => [
                        "Erreur lors de l'enregistrement de la saillie.",
                        'Paiement refusé pour la vente #%s.',
                        'Impossible de générer le rapport – données manquantes.',
                        'Connexion à FedaPay échouée, réessayez.',
                        'Mise-bas #%s introuvable dans la base de données.',
                    ],
                ];

                for ($i = 1; $i <= $cfg['notifs']; $i++) {
                    $type    = $types[array_rand($types)];
                    $msgPool = $notifMessages[$type];
                    $message = sprintf($msgPool[array_rand($msgPool)], rand(1, 999));

                    Notification::create([
                        'user_id'    => $userId,
                        'type'       => $type,
                        'title'      => match ($type) {
                            'success' => '✅ Succès',
                            'info'    => 'ℹ️ Information',
                            'warning' => '⚠️ Attention',
                            'error'   => '❌ Erreur',
                        },
                        'message'    => $message,
                        'action_url' => route('dashboard'),
                        'icon'       => $icons[$type],
                        'is_read'    => (bool) rand(0, 1),
                        'emailed'    => (bool) rand(0, 1),
                        'read_at'    => rand(0, 1) ? now()->subHours(rand(1, 48)) : null,
                    ]);
                }

                $this->command->info("     ✓ {$cfg['notifs']} notifications créées");
            }
        }

        $this->command->line('');
        $this->command->info("✅ All user data seeded successfully.");
    }

    // ========================================================================
    // CREDENTIALS DISPLAY
    // ========================================================================

    private function printLoginCredentials(): void
    {
        $this->command->line('');
        $this->command->line('╔══════════════════════════════════════════════════════════════════════╗');
        $this->command->line('║                     🔑  LOGIN CREDENTIALS  🔑                       ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👑  ADMIN ACCOUNT                                                   ║');
        $this->command->line('║  ──────────────────────────────────────────────────────────────────  ║');
        $this->command->line('║  Email    : admin@cuniapp.bj                                         ║');
        $this->command->line('║  Password : Admin123!                                                ║');
        $this->command->line('║  Role     : Administrator                                            ║');
        $this->command->line('║  Sub      : Active (1 an – plan Annuel)                              ║');
        $this->command->line('║  Dataset  : 60 mâles │ 200 femelles │ ~1 000 lapereaux               ║');
        $this->command->line('║             300 saillies │ 250 mises-bas │ 200 naissances             ║');
        $this->command->line('║             80 ventes │ 20 notifs │ 1 facture                        ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👤  USER 1 – user1@cuniapp.bj  /  User123!                         ║');
        $this->command->line('║  Subscription : 1 mois · actif · ~25 jours restants                 ║');
        $this->command->line('║  Dataset  : 60 mâles │ 200 femelles │ ~1 000 lapereaux               ║');
        $this->command->line('║             300 saillies │ 250 mises-bas │ 200 naissances             ║');
        $this->command->line('║             80 ventes │ 20 notifs │ 1 facture                        ║');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👤  USER 2 – user2@cuniapp.bj  /  User123!                         ║');
        $this->command->line('║  Subscription : 3 mois · actif                                      ║');
        $this->command->line('║  Dataset  : 60 mâles │ 200 femelles │ ~1 000 lapereaux               ║');
        $this->command->line('║             300 saillies │ 250 mises-bas │ 200 naissances             ║');
        $this->command->line('║             80 ventes │ 20 notifs │ 1 facture                        ║');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👤  USER 3 – user3@cuniapp.bj  /  User123!                         ║');
        $this->command->line('║  Subscription : 6 mois · actif                                      ║');
        $this->command->line('║  Dataset  : 60 mâles │ 200 femelles │ ~1 000 lapereaux               ║');
        $this->command->line('║             300 saillies │ 250 mises-bas │ 200 naissances             ║');
        $this->command->line('║             80 ventes │ 20 notifs │ 1 facture                        ║');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👤  USER 4 – user4@cuniapp.bj  /  User123!                         ║');
        $this->command->line('║  Subscription : 1 an · actif (petite ferme)                         ║');
        $this->command->line('║  Dataset  : 10 mâles │ 20 femelles │ ~45 lapereaux                   ║');
        $this->command->line('║             25 saillies │ 20 mises-bas │ 15 naissances               ║');
        $this->command->line('║             5 ventes │ 5 notifs │ 1 facture                          ║');
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👤  USER 5 – user5@cuniapp.bj  /  User123!                         ║');
        $this->command->line('║  Subscription : ❌ Aucune                                            ║');
        $this->command->line('║  Dataset  : 🫙 EMPTY – aucune donnée                                 ║');
        $this->command->line('║                                                                      ║');
        $this->command->line('╚══════════════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }
}
