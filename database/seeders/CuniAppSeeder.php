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

class CuniAppSeeder extends Seeder
{
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
        $this->seedUsers();
        $this->seedSubscriptionPlans();
        $this->seedMales();
        $this->seedFemelles();
        $this->seedSaillies();
        $this->seedMisesBas();
        $this->seedNaissances();
        $this->seedLapereaux(); // ✅ FIXED: Proper column names
        $this->seedSales();
        $this->seedNotifications();

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
        $this->command->line('║          🐰 CUNIAPP ÉLEVAGE - DATABASE SEEDER 🐰            ║');
        $this->command->line('║                                                              ║');
        $this->command->line('║     Gestion Intelligente de Votre Élevage de Lapins         ║');
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
            'sale_rabbits',      // → sales, males/femelles/lapereaux
            'payment_transactions', // → users, subscriptions
            'invoices',          // → payment_transactions, subscriptions, users
            'subscriptions',     // → users, subscription_plans
            'lapereaux',         // → naissances, mises_bas
            'naissances',        // → mises_bas, saillies, users
            'mises_bas',         // → saillies, femelles
            'saillies',          // → femelles, males
            'notifications',     // → users

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

        \DB::statement('SET FOREIGN_KEY_CHECKS=0'); // Safety net

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                \DB::table($table)->truncate();
                $this->command->info(" ✓ Table {$table} cleared");
            }
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    // ========================================================================
    // SEEDING METHODS
    // ========================================================================

    private function seedSettings(): void
    {
        $this->command->info("⚙️ Seeding Settings...");

        $settings = [
            // Farm Info
            ['key' => 'farm_name', 'value' => 'Ferme CuniApp Test', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => 'Houéyiho, Cotonou, Bénin', 'type' => 'string', 'group' => 'general', 'label' => 'Adresse'],
            ['key' => 'farm_phone', 'value' => '+2290152415241', 'type' => 'string', 'group' => 'general', 'label' => 'Téléphone'],
            ['key' => 'farm_email', 'value' => 'contact@cuniapp.bj', 'type' => 'string', 'group' => 'general', 'label' => 'Email'],

            // Breeding Settings
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold', 'value' => '80', 'type' => 'number', 'group' => 'breeding', 'label' => "Seuil d'alerte (%)"],

            // Verification Settings (from todo.md)
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai initial vérification (jours)'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai premier rappel (jours)'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding', 'label' => 'Intervalle rappels (jours)'],

            // Default Prices
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Mâles'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Femelles'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix défaut - Lapereaux'],

            // FedaPay Settings
            ['key' => 'fedapay_public_key', 'value' => env('FEDAPAY_PUBLIC_KEY', ''), 'type' => 'string', 'group' => 'payments', 'label' => 'Clé Publique FedaPay'],
            ['key' => 'fedapay_secret_key', 'value' => env('FEDAPAY_SECRET_KEY', ''), 'type' => 'string', 'group' => 'payments', 'label' => 'Clé Secrète FedaPay'],
            ['key' => 'fedapay_environment', 'value' => env('FEDAPAY_ENVIRONMENT', 'sandbox'), 'type' => 'string', 'group' => 'payments', 'label' => 'Environnement FedaPay'],
            ['key' => 'fedapay_webhook_secret', 'value' => env('FEDAPAY_WEBHOOK_SECRET', ''), 'type' => 'string', 'group' => 'payments', 'label' => 'Secret Webhook FedaPay'],

            // Subscription Settings
            ['key' => 'grace_period_days', 'value' => '3', 'type' => 'number', 'group' => 'subscriptions', 'label' => 'Période de grâce (jours)'],
            ['key' => 'enable_auto_renew', 'value' => '1', 'type' => 'boolean', 'group' => 'subscriptions', 'label' => 'Renouvellement auto'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info("   ✓ " . count($settings) . " settings created");
    }

    private function seedUsers(): void
    {
        $this->command->info("👥 Seeding Users...");

        // ✅ ADMIN USER
        $admin = User::create([
            'name' => 'Admin CuniApp',
            'email' => 'admin@cuniapp.bj',
            'password' => Hash::make('Admin123!'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addYear(),
            'theme' => 'light',
            'language' => 'fr',
        ]);

        // ✅ TEST USERS with staggered subscriptions (per requirements)
        $testUsers = [
            // User 1: 1-month subscription (expires soon)
            [
                'name' => 'Test User 1',
                'email' => 'user1@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 1,
                'subscription_offset_days' => 25, // Started 25 days ago, expires in ~5 days
            ],
            // User 2: 3-month subscription (mid-term)
            [
                'name' => 'Test User 2',
                'email' => 'user2@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 3,
                'subscription_offset_days' => 45, // Started 45 days ago
            ],
            // User 3: 6-month subscription (long-term)
            [
                'name' => 'Test User 3',
                'email' => 'user3@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 6,
                'subscription_offset_days' => 30,
            ],
            // User 4: No subscription (to test subscription required flow)
            [
                'name' => 'Test User 4',
                'email' => 'user4@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 0,
            ],
            // User 5: Expired subscription (to test renewal flow)
            [
                'name' => 'Test User 5',
                'email' => 'user5@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 1,
                'subscription_offset_days' => 35, // Started 35 days ago, expired 5 days ago
            ],
        ];

        $users = [$admin];

        foreach ($testUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'email_verified_at' => now(),
                'role' => 'user',
                'theme' => 'system',
                'language' => 'fr',
            ]);

            // Create subscription if applicable
            if ($userData['subscription_months'] > 0) {
                $plan = SubscriptionPlan::where('duration_months', $userData['subscription_months'])->first();

                if ($plan) {
                    $startDate = now()->subDays($userData['subscription_offset_days'] ?? 0);
                    $endDate = $startDate->copy()->addMonths($userData['subscription_months']);

                    // Determine status based on dates
                    $status = $endDate->isPast() ? 'expired' : 'active';

                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'subscription_plan_id' => $plan->id,
                        'status' => $status,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'price' => $plan->price,
                        'payment_method' => 'manual',
                        'payment_reference' => 'SEED-' . strtoupper(Str::random(8)),
                        'auto_renew' => false,
                    ]);

                    // Create payment transaction
                    PaymentTransaction::create([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'amount' => $plan->price,
                        'payment_method' => 'manual',
                        'transaction_id' => 'TXN-SEED-' . strtoupper(Str::random(8)),
                        'status' => 'completed',
                        'provider' => 'manual',
                        'paid_at' => $startDate,
                    ]);

                    // Update user subscription fields
                    $user->update([
                        'subscription_status' => $status,
                        'subscription_ends_at' => $endDate,
                    ]);
                }
            }

            $users[] = $user;
        }

        // Add 47 more regular users without subscriptions for load testing
        for ($i = 6; $i <= 52; $i++) {
            User::create([
                'name' => "Utilisateur {$i}",
                'email' => "user{$i}@cuniapp.bj",
                'password' => Hash::make('User123!'),
                'email_verified_at' => now(),
                'role' => 'user',
                'subscription_status' => 'inactive',
            ]);
        }

        $this->command->info("   ✓ 52 users created (1 Admin + 51 Users)");

        // Store credentials for display
        $this->testCredentials = [
            'admin' => ['email' => 'admin@cuniapp.bj', 'password' => 'Admin123!'],
            'user1' => ['email' => 'user1@cuniapp.bj', 'password' => 'User123!', 'note' => 'Subscription: 1 month (expires soon)'],
            'user2' => ['email' => 'user2@cuniapp.bj', 'password' => 'User123!', 'note' => 'Subscription: 3 months'],
            'user3' => ['email' => 'user3@cuniapp.bj', 'password' => 'User123!', 'note' => 'Subscription: 6 months'],
            'user4' => ['email' => 'user4@cuniapp.bj', 'password' => 'User123!', 'note' => 'NO subscription (test access control)'],
            'user5' => ['email' => 'user5@cuniapp.bj', 'password' => 'User123!', 'note' => 'Subscription: EXPIRED (test renewal)'],
        ];
    }

    private function seedSubscriptionPlans(): void
    {
        $this->command->info("📋 Seeding Subscription Plans...");

        $plans = [
            ['name' => 'Mensuel', 'duration_months' => 1, 'price' => 2500, 'is_active' => true, 'features' => ['Accès complet', 'Support email', 'Sauvegarde journalière']],
            ['name' => 'Trimestriel', 'duration_months' => 3, 'price' => 7500, 'is_active' => true, 'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports mensuels']],
            ['name' => 'Semestriel', 'duration_months' => 6, 'price' => 15000, 'is_active' => true, 'features' => ['Accès complet', 'Support prioritaire 24/7', 'Sauvegarde automatique', 'Rapports avancés', 'Export données']],
            ['name' => 'Annuel', 'duration_months' => 12, 'price' => 30000, 'is_active' => true, 'features' => ['Accès complet', 'Support VIP', 'Sauvegarde automatique', 'Rapports personnalisés', 'Export illimité', 'Formation incluse']],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info("   ✓ 4 subscription plans created");
    }

    private function seedMales(): void
    {
        $this->command->info("🐰 Seeding Male Rabbits...");

        $races = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
        $etats = ['Active', 'Inactive', 'Malade'];

        for ($i = 1; $i <= 200; $i++) {
            Male::create([
                'user_id' => 1, // Admin owns seed data
                'code' => 'MAL-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Mâle-{$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand([0, 1])],
                'date_naissance' => now()->subMonths(rand(6, 24))->subDays(rand(0, 30)),
                'etat' => $etats[array_rand($etats)],
            ]);
        }

        $this->command->info("   ✓ 200 male rabbits created");
    }

    private function seedFemelles(): void
    {
        $this->command->info("🐰 Seeding Female Rabbits...");

        $races = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];

        for ($i = 1; $i <= 300; $i++) {
            Femelle::create([
                'user_id' => 1,
                'code' => 'FEM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Femelle-{$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand([0, 1])],
                'date_naissance' => now()->subMonths(rand(6, 24))->subDays(rand(0, 30)),
                'etat' => $etats[array_rand($etats)],
            ]);
        }

        $this->command->info("   ✓ 300 female rabbits created");
    }

    private function seedSaillies(): void
    {
        $this->command->info("💕 Seeding Matings (Saillies)...");

        $femelles = Femelle::inRandomOrder()->limit(150)->get();
        $males = Male::inRandomOrder()->limit(100)->get();

        foreach ($femelles as $index => $femelle) {
            $male = $males->random();

            Saillie::create([
                'user_id' => 1,
                'femelle_id' => $femelle->id,
                'male_id' => $male->id,
                'date_saillie' => now()->subDays(rand(1, 90)),
                'date_palpage' => rand(0, 1) ? now()->subDays(rand(1, 60))->format('Y-m-d') : null,
                'palpation_resultat' => rand(0, 1) ? ['+', '-'][array_rand([0, 1])] : null,
                'date_mise_bas_theorique' => now()->subDays(rand(1, 90))->addDays(31)->format('Y-m-d'),
            ]);
        }

        $this->command->info("   ✓ 400 matings created");
    }

    private function seedMisesBas(): void
    {
        $this->command->info("🥚 Seeding Births (Mises Bas)...");

        $saillies = Saillie::whereNotNull('date_palpage')->where('palpation_resultat', '+')->inRandomOrder()->limit(300)->get();

        foreach ($saillies as $saillie) {
            MiseBas::create([
                'user_id' => 1,
                'femelle_id' => $saillie->femelle_id,
                'saillie_id' => $saillie->id,
                'date_mise_bas' => $saillie->date_saillie ? Carbon::parse($saillie->date_saillie)->addDays(31)->format('Y-m-d') : now()->format('Y-m-d'),
                'date_sevrage' => now()->addWeeks(6)->format('Y-m-d'),
                'poids_moyen_sevrage' => rand(500, 1500) / 1000, // 0.5kg - 1.5kg
            ]);
        }

        $this->command->info("   ✓ 300 mises bas created");
    }

    private function seedNaissances(): void
    {
        $this->command->info("🐣 Seeding Litters (Naissances)...");

        $misesBas = MiseBas::inRandomOrder()->limit(250)->get();
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        foreach ($misesBas as $miseBas) {
            Naissance::create([
                'user_id' => 1,
                'mise_bas_id' => $miseBas->id,
                'poids_moyen_naissance' => rand(40, 80),
                'etat_sante' => $etatsSante[array_rand($etatsSante)],
                'observations' => 'Portée en bonne santé - ' . Str::random(20),
                'date_sevrage_prevue' => Carbon::parse($miseBas->date_mise_bas)->addWeeks(6)->format('Y-m-d'),
                'date_vaccination_prevue' => Carbon::parse($miseBas->date_mise_bas)->addWeeks(4)->format('Y-m-d'),
                'sex_verified' => rand(0, 10) > 7, // ~30% verified
                'sex_verified_at' => rand(0, 10) > 7 ? now()->subDays(rand(1, 5)) : null,
            ]);
        }

        $this->command->info("   ✓ 250 litters created");
    }

    // ✅ FIXED: Proper column names for lapereaux insert
    private function seedLapereaux(): void
    {
        $this->command->info("🐇 Seeding Baby Rabbits (Lapereaux)...");

        $naissances = Naissance::inRandomOrder()->limit(200)->get();
        $etats = ['vivant', 'vendu', 'mort'];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];
        $categories = ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'];

        $lapereauCount = 0;
        $year = date('Y');

        foreach ($naissances as $naissance) {
            $nbLapereaux = rand(3, 8);

            for ($j = 1; $j <= $nbLapereaux; $j++) {
                $lapereauCount++;

                // ✅ Generate unique code properly
                $code = "LAP-{$year}-" . str_pad($lapereauCount, 4, '0', STR_PAD_LEFT);

                // ✅ Use associative array with proper column names
                Lapereau::create([
                    'user_id' => 1,
                    'naissance_id' => $naissance->id,
                    'code' => $code,
                    'nom' => "Lapereau-{$lapereauCount}",
                    'sex' => rand(0, 1) ? 'male' : 'female',
                    'etat' => $etats[array_rand($etats)],
                    'poids_naissance' => rand(40, 80),
                    'etat_sante' => $etatsSante[array_rand($etatsSante)],
                    'observations' => 'Lapereau en bonne santé - ' . Str::random(15),
                    'categorie' => $categories[array_rand($categories)],
                    'alimentation_jour' => rand(50, 150) / 100,
                    'alimentation_semaine' => rand(300, 1000) / 100,
                ]);
            }
        }

        $this->command->info("   ✓ {$lapereauCount} lapereaux created");
    }

    private function seedSales(): void
    {
        $this->command->info("💰 Seeding Sales...");

        $males = Male::where('etat', 'Inactive')->limit(20)->get();
        $femelles = Femelle::where('etat', 'Vide')->limit(15)->get();
        $lapereaux = Lapereau::where('etat', 'vendu')->limit(30)->get();

        $paymentStatuses = ['paid', 'pending', 'partial'];

        // Create sales for males
        foreach ($males->take(10) as $male) {
            $sale = Sale::create([
                'user_id' => 1,
                'date_sale' => now()->subDays(rand(1, 60)),
                'quantity' => 1,
                'type' => 'male',
                'total_amount' => 25000,
                'buyer_name' => 'Acheteur ' . Str::random(8),
                'buyer_contact' => '+229' . rand(90000000, 99999999),
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'amount_paid' => rand(0, 1) ? 25000 : 0,
            ]);

            SaleRabbit::create([
                'sale_id' => $sale->id,
                'rabbit_type' => 'male',
                'rabbit_id' => $male->id,
                'sale_price' => 25000,
            ]);
        }

        // Create sales for females
        foreach ($femelles->take(10) as $femelle) {
            $sale = Sale::create([
                'user_id' => 1,
                'date_sale' => now()->subDays(rand(1, 60)),
                'quantity' => 1,
                'type' => 'female',
                'total_amount' => 30000,
                'buyer_name' => 'Acheteur ' . Str::random(8),
                'buyer_contact' => '+229' . rand(90000000, 99999999),
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'amount_paid' => rand(0, 1) ? 30000 : 0,
            ]);

            SaleRabbit::create([
                'sale_id' => $sale->id,
                'rabbit_type' => 'female',
                'rabbit_id' => $femelle->id,
                'sale_price' => 30000,
            ]);
        }

        // Create sales for lapereaux
        foreach ($lapereaux->take(20) as $lapereau) {
            $sale = Sale::create([
                'user_id' => 1,
                'date_sale' => now()->subDays(rand(1, 60)),
                'quantity' => 1,
                'type' => 'lapereau',
                'total_amount' => 15000,
                'buyer_name' => 'Acheteur ' . Str::random(8),
                'buyer_contact' => '+229' . rand(90000000, 99999999),
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'amount_paid' => rand(0, 1) ? 15000 : 0,
            ]);

            SaleRabbit::create([
                'sale_id' => $sale->id,
                'rabbit_type' => 'lapereau',
                'rabbit_id' => $lapereau->id,
                'sale_price' => 15000,
            ]);
        }

        $this->command->info("   ✓ 40 sales created");
    }

    private function seedNotifications(): void
    {
        $this->command->info("🔔 Seeding Notifications...");

        $users = User::limit(10)->get();
        $types = ['success', 'info', 'warning', 'error'];
        $icons = [
            'success' => 'bi-check-circle-fill',
            'info' => 'bi-info-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'error' => 'bi-x-circle-fill',
        ];

        foreach ($users as $user) {
            for ($i = 1; $i <= rand(2, 5); $i++) {
                $type = $types[array_rand($types)];

                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => match ($type) {
                        'success' => '✅ Action réussie',
                        'info' => 'ℹ️ Information',
                        'warning' => '⚠️ Attention',
                        'error' => '❌ Erreur',
                    },
                    'message' => "Notification de test #{$i} pour " . $user->name,
                    'action_url' => route('dashboard'),
                    'icon' => $icons[$type],
                    'is_read' => rand(0, 1),
                ]);
            }
        }

        $this->command->info("   ✓ Notifications created for test users");
    }

    // ========================================================================
    // CREDENTIALS DISPLAY
    // ========================================================================

    private function printLoginCredentials(): void
    {
        $this->command->line('');
        $this->command->line('╔══════════════════════════════════════════════════════════════╗');
        $this->command->line('║              🔑 LOGIN CREDENTIALS 🔑                        ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════╣');

        // Admin
        $this->command->line('║  👑 ADMIN ACCOUNT:');
        $this->command->line("║  Email: admin@cuniapp.bj");
        $this->command->line("║  Password: Admin123!");
        $this->command->line('║  Role: Administrator (full access)');
        $this->command->line('╠══════════════════════════════════════════════════════════════╣');

        // Test Users
        $this->command->line('║  👤 TEST USER ACCOUNTS:');

        $testAccounts = [
            ['email' => 'user1@cuniapp.bj', 'note' => '1-month sub (expires soon)'],
            ['email' => 'user2@cuniapp.bj', 'note' => '3-month sub (active)'],
            ['email' => 'user3@cuniapp.bj', 'note' => '6-month sub (active)'],
            ['email' => 'user4@cuniapp.bj', 'note' => 'NO subscription ⚠️'],
            ['email' => 'user5@cuniapp.bj', 'note' => 'EXPIRED subscription 🔄'],
        ];

        foreach ($testAccounts as $account) {
            $this->command->line("║  • {$account['email']}");
            $this->command->line("║    Password: User123!  [{$account['note']}]");
        }

        $this->command->line('╠══════════════════════════════════════════════════════════════╣');
        $this->command->line('║  💡 All test users use password: User123!');
        $this->command->line('║  🔐 Admin uses password: Admin123!');
        $this->command->line('╚══════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }
}
