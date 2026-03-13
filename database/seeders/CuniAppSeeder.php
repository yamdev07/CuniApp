import sys
import re

content = """<?php

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
            // User 1: 1-month subscription (active)
            [
                'name' => 'Test User 1',
                'email' => 'user1@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 1,
                'subscription_offset_days' => 5, // Started 5 days ago, expires in ~25 days
            ],
            // User 2: 3-month subscription
            [
                'name' => 'Test User 2',
                'email' => 'user2@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 3,
                'subscription_offset_days' => 15,
            ],
            // User 3: 6-month subscription
            [
                'name' => 'Test User 3',
                'email' => 'user3@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 6,
                'subscription_offset_days' => 30,
            ],
            // User 4: 1 year subscription
            [
                'name' => 'Test User 4',
                'email' => 'user4@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 12,
                'subscription_offset_days' => 50,
            ],
            // User 5: No subscription
            [
                'name' => 'Test User 5',
                'email' => 'user5@cuniapp.bj',
                'password' => 'User123!',
                'subscription_months' => 0,
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
                'subscription_status' => 'inactive',
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

        $this->command->info("   ✓ Users created (Admin + 5 Test Users)");
    }
    
    private function seedUserData(): void
    {
        $this->command->info("📊 Seeding Data for each user...");
        
        $userConfigs = [
            1 => ['males' => 60, 'females' => 200, 'saillies' => 250, 'mises_bas' => 200, 'naissances' => 170, 'lapereaux_per' => 3, 'sales' => 50, 'notifs' => 10], // Admin
            2 => ['males' => 60, 'females' => 200, 'saillies' => 250, 'mises_bas' => 200, 'naissances' => 170, 'lapereaux_per' => 3, 'sales' => 50, 'notifs' => 10], // User 1
            3 => ['males' => 60, 'females' => 200, 'saillies' => 250, 'mises_bas' => 200, 'naissances' => 170, 'lapereaux_per' => 3, 'sales' => 50, 'notifs' => 10], // User 2
            4 => ['males' => 60, 'females' => 200, 'saillies' => 250, 'mises_bas' => 200, 'naissances' => 170, 'lapereaux_per' => 3, 'sales' => 50, 'notifs' => 10], // User 3
            5 => ['males' => 10, 'females' => 20, 'saillies' => 25, 'mises_bas' => 20, 'naissances' => 16, 'lapereaux_per' => 3, 'sales' => 5, 'notifs' => 3],   // User 4
            6 => ['males' => 0, 'females' => 0, 'saillies' => 0, 'mises_bas' => 0, 'naissances' => 0, 'lapereaux_per' => 0, 'sales' => 0, 'notifs' => 0], // User 5
        ];

        
        foreach ($userConfigs as $userId => $config) {
            $this->command->info("   > Seeding for User ID {$userId}...");
            $user = User::find($userId);
            if (!$user) continue;
            
            // Seed Males
            $races = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
            $etats = ['Active', 'Inactive', 'Malade'];
            
            for ($i = 1; $i <= $config['males']; $i++) {
                Male::create([
                    'user_id' => $userId,
                    'code' => 'MAL-' . $userId . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom' => "Mâle-U{$userId}-{$i}",
                    'race' => $races[array_rand($races)],
                    'origine' => ['Interne', 'Achat'][array_rand([0, 1])],
                    'date_naissance' => now()->subMonths(rand(6, 24))->subDays(rand(0, 30)),
                    'etat' => $etats[array_rand($etats)],
                ]);
            }
            
            // Seed Females
            $etatsFemelles = ['Active', 'Gestante', 'Allaitante', 'Vide'];
            for ($i = 1; $i <= $config['females']; $i++) {
                Femelle::create([
                    'user_id' => $userId,
                    'code' => 'FEM-' . $userId . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom' => "Femelle-U{$userId}-{$i}",
                    'race' => $races[array_rand($races)],
                    'origine' => ['Interne', 'Achat'][array_rand([0, 1])],
                    'date_naissance' => now()->subMonths(rand(6, 24))->subDays(rand(0, 30)),
                    'etat' => $etatsFemelles[array_rand($etatsFemelles)],
                ]);
            }

            // If no females or males, skip breeding cycles
            if ($config['saillies'] == 0 || $config['males'] == 0 || $config['females'] == 0) continue;

            // Seed Saillies
            $femelles = Femelle::where('user_id', $userId)->inRandomOrder()->limit($config['saillies'])->get();
            $males = Male::where('user_id', $userId)->get();
            
            foreach ($femelles as $index => $femelle) {
                if ($males->isEmpty()) break;
                $male = $males->random();

                Saillie::create([
                    'user_id' => $userId,
                    'femelle_id' => $femelle->id,
                    'male_id' => $male->id,
                    'date_saillie' => now()->subDays(rand(1, 90)),
                    'date_palpage' => rand(0, 1) ? now()->subDays(rand(1, 60))->format('Y-m-d') : null,
                    'palpation_resultat' => rand(0, 1) ? ['+', '-'][array_rand([0, 1])] : null,
                    'date_mise_bas_theorique' => now()->subDays(rand(1, 90))->addDays(31)->format('Y-m-d'),
                ]);
            }

            // Seed MisesBas
            $saillies = Saillie::where('user_id', $userId)->whereNotNull('date_palpage')->where('palpation_resultat', '+')->inRandomOrder()->limit($config['mises_bas'])->get();
            
            foreach ($saillies as $saillie) {
                MiseBas::create([
                    'user_id' => $userId,
                    'femelle_id' => $saillie->femelle_id,
                    'saillie_id' => $saillie->id,
                    'date_mise_bas' => $saillie->date_saillie ? Carbon::parse($saillie->date_saillie)->addDays(31)->format('Y-m-d') : now()->format('Y-m-d'),
                    'date_sevrage' => now()->addWeeks(6)->format('Y-m-d'),
                    'poids_moyen_sevrage' => rand(500, 1500) / 1000,
                ]);
            }

            // Seed Naissances
            $misesBas = MiseBas::where('user_id', $userId)->inRandomOrder()->limit($config['naissances'])->get();
            $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];
            
            foreach ($misesBas as $miseBas) {
                Naissance::create([
                    'user_id' => $userId,
                    'mise_bas_id' => $miseBas->id,
                    'poids_moyen_naissance' => rand(40, 80),
                    'etat_sante' => $etatsSante[array_rand($etatsSante)],
                    'observations' => 'Portée en bonne santé - ' . Str::random(20),
                    'date_sevrage_prevue' => Carbon::parse($miseBas->date_mise_bas)->addWeeks(6)->format('Y-m-d'),
                    'date_vaccination_prevue' => Carbon::parse($miseBas->date_mise_bas)->addWeeks(4)->format('Y-m-d'),
                    'sex_verified' => rand(0, 10) > 7,
                    'sex_verified_at' => rand(0, 10) > 7 ? now()->subDays(rand(1, 5)) : null,
                ]);
            }

            // Seed Lapereaux
            $naissances = Naissance::where('user_id', $userId)->get();
            $etatsLaps = ['vivant', 'vendu', 'mort'];
            $categories = ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'];
            
            $lapereauCount = 0;
            $year = date('Y');
            
            foreach ($naissances as $naissance) {
                // Ensure lapereaux per configured amounts
                $nbLapereaux = rand(max(1, $config['lapereaux_per'] - 1), $config['lapereaux_per'] + 1);
                
                for ($j = 1; $j <= $nbLapereaux; $j++) {
                    $lapereauCount++;
                    $code = "LAP-{$year}-U{$userId}-" . str_pad($lapereauCount, 4, '0', STR_PAD_LEFT);

                    Lapereau::create([
                        'user_id' => $userId,
                        'naissance_id' => $naissance->id,
                        'code' => $code,
                        'nom' => "Lapereau-U{$userId}-{$lapereauCount}",
                        'sex' => rand(0, 1) ? 'male' : 'female',
                        'etat' => $etatsLaps[array_rand($etatsLaps)],
                        'poids_naissance' => rand(40, 80),
                        'etat_sante' => $etatsSante[array_rand($etatsSante)],
                        'observations' => 'Lapereau en bonne santé - ' . Str::random(15),
                        'categorie' => $categories[array_rand($categories)],
                        'alimentation_jour' => rand(50, 150) / 100,
                        'alimentation_semaine' => rand(300, 1000) / 100,
                    ]);
                }
            }
            
            // Seed Sales
            if ($config['sales'] > 0) {
                $paymentStatuses = ['paid', 'pending', 'partial'];
                $malesForSale = Male::where('user_id', $userId)->where('etat', 'Inactive')->limit(max(1, $config['sales']/3))->get();
                $femellesForSale = Femelle::where('user_id', $userId)->where('etat', 'Vide')->limit(max(1, $config['sales']/3))->get();
                $lapereauxForSale = Lapereau::where('user_id', $userId)->where('etat', 'vendu')->limit(max(1, $config['sales']/3))->get();

                foreach ($malesForSale as $male) {
                    $sale = Sale::create([
                        'user_id' => $userId,
                        'date_sale' => now()->subDays(rand(1, 60)),
                        'quantity' => 1,
                        'type' => 'male',
                        'total_amount' => 25000,
                        'buyer_name' => 'Acheteur ' . Str::random(8),
                        'buyer_contact' => '+229' . rand(90000000, 99999999),
                        'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                        'amount_paid' => rand(0, 1) ? 25000 : 0,
                    ]);
                    SaleRabbit::create(['sale_id' => $sale->id, 'rabbit_type' => 'male', 'rabbit_id' => $male->id, 'sale_price' => 25000]);
                }

                foreach ($femellesForSale as $femelle) {
                    $sale = Sale::create([
                        'user_id' => $userId,
                        'date_sale' => now()->subDays(rand(1, 60)),
                        'quantity' => 1,
                        'type' => 'female',
                        'total_amount' => 30000,
                        'buyer_name' => 'Acheteur ' . Str::random(8),
                        'buyer_contact' => '+229' . rand(90000000, 99999999),
                        'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                        'amount_paid' => rand(0, 1) ? 30000 : 0,
                    ]);
                    SaleRabbit::create(['sale_id' => $sale->id, 'rabbit_type' => 'female', 'rabbit_id' => $femelle->id, 'sale_price' => 30000]);
                }

                foreach ($lapereauxForSale as $lapereau) {
                    $sale = Sale::create([
                        'user_id' => $userId,
                        'date_sale' => now()->subDays(rand(1, 60)),
                        'quantity' => 1,
                        'type' => 'lapereau',
                        'total_amount' => 15000,
                        'buyer_name' => 'Acheteur ' . Str::random(8),
                        'buyer_contact' => '+229' . rand(90000000, 99999999),
                        'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                        'amount_paid' => rand(0, 1) ? 15000 : 0,
                    ]);
                    SaleRabbit::create(['sale_id' => $sale->id, 'rabbit_type' => 'lapereau', 'rabbit_id' => $lapereau->id, 'sale_price' => 15000]);
                }
            }
            
            // Seed Notifications
            if ($config['notifs'] > 0) {
                $types = ['success', 'info', 'warning', 'error'];
                $icons = [
                    'success' => 'bi-check-circle-fill',
                    'info' => 'bi-info-circle-fill',
                    'warning' => 'bi-exclamation-triangle-fill',
                    'error' => 'bi-x-circle-fill',
                ];

                for ($i = 1; $i <= $config['notifs']; $i++) {
                    $type = $types[array_rand($types)];
                    Notification::create([
                        'user_id' => $userId,
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
        }
        
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
        $this->command->line('║  Role: Administrator (full access) | ✨ Huge dataset');
        $this->command->line('╠══════════════════════════════════════════════════════════════╣');

        // Test Users
        $this->command->line('║  👤 TEST USER ACCOUNTS:');

        $testAccounts = [
            ['email' => 'user1@cuniapp.bj', 'note' => '1-month sub (active) | ✨ Huge dataset'],
            ['email' => 'user2@cuniapp.bj', 'note' => '3-month sub (active) | ✨ Huge dataset'],
            ['email' => 'user3@cuniapp.bj', 'note' => '6-month sub (active) | ✨ Huge dataset'],
            ['email' => 'user4@cuniapp.bj', 'note' => '1-year sub (active)  | 🐣 Few dataset'],
            ['email' => 'user5@cuniapp.bj', 'note' => 'NO subscription ⚠️    | 🫙 Empty dataset'],
        ];

        foreach ($testAccounts as $account) {
            $this->command->line("║  • {$account['email']}");
            $this->command->line("║    Password: User123!");
            $this->command->line("║    {$account['note']}");
            $this->command->line("║");
        }

        $this->command->line('╠══════════════════════════════════════════════════════════════╣');
        $this->command->line('║  💡 All test users use password: User123!');
        $this->command->line('║  🔐 Admin uses password: Admin123!');
        $this->command->line('╚══════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }
}
"""

with open('/home/lionel/Documents/1_Software_Dev/CuniApp/database/seeders/CuniAppSeeder.php', 'w') as f:
    f.write(content)

print("Updated CuniAppSeeder.php successfully.")
