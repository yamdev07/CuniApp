<?php
// database/seeders/CuniAppSeeder.php

namespace Database\Seeders;

use App\Models\Femelle;
use App\Models\Male;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Naissance;
use App\Models\Lapereau;
use App\Models\User;
use App\Models\Firm;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use App\Models\Sale;
use App\Models\SaleRabbit;
use App\Models\Notification;
use App\Models\Invoice;
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

    /** Store created users for credential display */
    private array $credentials = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->printHeader();
        $this->command->info('🧹 Cleaning existing data...');
        $this->cleanTables();

        $this->command->info("🚀 Starting CuniApp Multi-Tenancy Database Seeding...\n");

        // Seed in correct order (respecting foreign keys)
        $this->seedSettings();
        $this->seedSubscriptionPlans();
        $this->seedSuperAdmin();
        $this->seedFirmsAndUsers();
        $this->seedBreedingData();
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
        $this->command->line('║       🐰  CUNIAPP MULTI-TENANCY SEEDER  🐰                  ║');
        $this->command->line('║                                                              ║');
        $this->command->line('║     1 Super Admin • 10 Firms • Bulk Breeding Data           ║');
        $this->command->line('║                                                              ║');
        $this->command->line('╚══════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }

    private function printFooter(): void
    {
        $this->command->line('');
        $this->command->info('✅ Multi-tenancy seeding completed successfully!');
        $this->command->info('🔑 See login credentials below for all accounts');
        $this->command->line('');
    }

    private function cleanTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            // Child tables first
            'sale_rabbits',
            'payment_transactions',
            'invoices',
            'subscriptions',
            'lapereaux',
            'naissances',
            'mises_bas',
            'saillies',
            'notifications',
            'pending_payments',
            'firm_audit_logs',
            // Parent tables
            'sales',
            'femelles',
            'males',
            'subscription_plans',
            'settings',
            'users',
            'firms',
            // Laravel system tables
            'sessions',
            'password_reset_tokens',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info("  ✓ All tables cleaned");
    }

    // ========================================================================
    // SEEDING METHODS
    // ========================================================================

    private function seedSettings(): void
    {
        $this->command->info("⚙️  Seeding Settings...");

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

        $this->command->info("  ✓ " . count($settings) . " settings created");
    }

    private function seedSubscriptionPlans(): void
    {
        $this->command->info("📋 Seeding Subscription Plans...");

        $plans = [
            [
                'name' => 'Mensuel',
                'duration_months' => 1,
                'price' => 2500,
                'max_users' => 5,
                'is_active' => true,
                'features' => ['Accès complet', 'Support email', 'Sauvegarde journalière'],
            ],
            [
                'name' => 'Trimestriel',
                'duration_months' => 3,
                'price' => 7500,
                'max_users' => 5,
                'is_active' => true,
                'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports mensuels'],
            ],
            [
                'name' => 'Semestriel',
                'duration_months' => 6,
                'price' => 15000,
                'max_users' => 8,
                'is_active' => true,
                'features' => ['Accès complet', 'Support prioritaire 24/7', 'Sauvegarde automatique', 'Rapports avancés', 'Export données'],
            ],
            [
                'name' => 'Annuel',
                'duration_months' => 12,
                'price' => 30000,
                'max_users' => 10,
                'is_active' => true,
                'features' => ['Accès complet', 'Support VIP', 'Sauvegarde automatique', 'Rapports personnalisés', 'Export illimité', 'Formation incluse'],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info("  ✓ 4 subscription plans created with user limits");
    }

    private function seedSuperAdmin(): void
    {
        $this->command->info("👑 Seeding Super Admin...");

        // ─────────────────────────────────────────────────────────────────────
        // ADMIN — system access without subscription
        // ─────────────────────────────────────────────────────────────────────
        $superAdmin =    User::create([
            'name'                    => 'Admin CuniApp',
            'email'                   => 'admin@cuniapp.bj',
            'password'                => Hash::make('SuperAdmin123!'),
            'email_verified_at'       => now(),
            'role'                    => 'super_admin',
            'status'                  => 'active',
            'theme'                   => 'light',
            'language'                => 'fr',
            'notifications_email'     => true,
            'notifications_dashboard' => true,
        ]);

        $this->credentials['super_admin'] = [
            'email' => $superAdmin->email,
            'password' => 'SuperAdmin123!',
            'role' => 'Super Administrator',
            'description' => 'Accès global à toutes les firmes et statistiques',
        ];

        $this->command->info("  ✓ Super Admin created: {$superAdmin->email}");
    }

    private function seedFirmsAndUsers(): void
    {
        $this->command->info("🏢 Seeding 10 Firms with Admins & Employees...");

        $firmNames = [
            'Élevage du Nord',
            'Ferme Lapin Doré',
            'Cuniculture Moderne',
            'Élevage Familial Akpakpa',
            'Ferme Bio Lapins',
            'CuniPro Bénin',
            'Élevage de la Vallée',
            'Ferme des Collines',
            'Lapins Premium',
            'Élevage Communautaire'
        ];

        $races = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
        $planDurations = [1, 3, 6, 12];
        $subscriptionStatuses = ['active', 'active', 'active', 'expired', 'grace_period']; // Weighted towards active

        for ($i = 1; $i <= 10; $i++) {
            $randomDate = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

            // ── Create Firm ─────────────────────────────────────────────
            $firm = Firm::create([
                'name' => $firmNames[$i - 1],
                'description' => "Entreprise d'élevage de lapins - Zone " . chr(64 + $i),
                'owner_id' => null, // Will be set after firm admin creation
                'status' => 'active',
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            // ── Create Firm Admin ────────────────────────────────────────
            $firmAdmin = User::create([
                'name' => "Admin {$firmNames[$i - 1]}",
                'email' => "admin{$i}@cuniapp.bj",
                'password' => Hash::make("Firm{$i}123!"),
                'email_verified_at' => $randomDate,
                'role' => 'firm_admin',
                'firm_id' => $firm->id,
                'status' => 'active',
                'theme' => rand(0, 1) ? 'light' : 'dark',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            // Link firm to its admin (owner)
            $firm->update(['owner_id' => $firmAdmin->id]);

            // Store credentials
            $this->credentials["firm_{$i}_admin"] = [
                'email' => $firmAdmin->email,
                'password' => "Firm{$i}123!",
                'role' => 'Firm Administrator',
                'firm' => $firm->name,
                'description' => "Gère l'entreprise et ses employés",
            ];

            // ── Create Subscription for Firm ────────────────────────────
            $planDuration = $planDurations[array_rand($planDurations)];
            $plan = SubscriptionPlan::where('duration_months', $planDuration)->first();
            $status = $subscriptionStatuses[array_rand($subscriptionStatuses)];

            $startDate = now()->subDays(rand(0, 30));
            $endDate = $startDate->copy()->addMonths($planDuration);
            if ($status === 'expired') $endDate = now()->subDays(rand(1, 30));
            if ($status === 'grace_period') $endDate = now()->subDays(rand(1, 3));

            $subscription = Subscription::create([
                'user_id' => $firmAdmin->id,
                'firm_id' => $firm->id,
                'subscription_plan_id' => $plan->id,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price' => $plan->price,
                'payment_method' => 'manual',
                'payment_reference' => 'FIRM' . $i . '-' . strtoupper(Str::random(8)),
                'auto_renew' => rand(0, 1),
            ]);

            // Payment Transaction
            PaymentTransaction::create([
                'user_id' => $firmAdmin->id,
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_method' => 'manual',
                'transaction_id' => 'TXN-FIRM' . $i . '-' . strtoupper(Str::random(8)),
                'status' => 'completed',
                'provider' => 'manual',
                'paid_at' => $startDate,
            ]);

            // Invoice
            $this->createInvoice(
                $firmAdmin,
                $subscription,
                $firm->id,
                $startDate,
                $plan->price,
                $status === 'active' ? 'paid' : 'pending',
                $plan->name
            );

            // Update user subscription fields
            $firmAdmin->update([
                'subscription_status' => $status,
                'subscription_ends_at' => $endDate,
                'last_subscription_at' => $startDate,
            ]);

            // ── Create Employees (varies by plan) ───────────────────────
            $maxUsers = $plan->max_users;
            $numEmployees = rand(1, $maxUsers - 1); // Leave room for admin

            for ($e = 1; $e <= $numEmployees; $e++) {
                $employee = User::create([
                    'name' => "Employé {$e} - {$firm->name}",
                    'email' => "emp{$i}_{$e}@cuniapp.bj",
                    'password' => Hash::make("Emp{$i}{$e}123!"),
                    'email_verified_at' => now(),
                    'role' => 'employee',
                    'firm_id' => $firm->id,
                    'status' => 'active',
                    'theme' => 'system',
                    'language' => 'fr',
                    'notifications_email' => rand(0, 1),
                    'notifications_dashboard' => true,
                    'subscription_status' => 'inactive', // Employees don't have individual subs
                ]);

                $this->credentials["firm_{$i}_emp_{$e}"] = [
                    'email' => $employee->email,
                    'password' => "Emp{$i}{$e}123!",
                    'role' => 'Employee',
                    'firm' => $firm->name,
                    'description' => "Accès limité aux données de l'entreprise",
                ];
            }

            $this->command->info("  ✓ Firm #{$i}: {$firm->name} | Admin + {$numEmployees} employés | Plan: {$plan->name} | Statut: {$status}");
        }

        $this->command->info("  ✓ 10 firms created with admins and employees");
    }

    private function seedBreedingData(): void
    {
        $this->command->info("🐇 Seeding bulk breeding data per firm...");

        $firms = Firm::all();
        $races = ['Californien', 'Géant des Flandres', 'Blanc de Vienne', 'Rex', 'Nouvelle-Zélande'];
        $etatsMales = ['Active', 'Inactive', 'Malade', 'vendu'];
        $etatsFemelles = ['Active', 'Gestante', 'Allaitante', 'Vide', 'vendu'];
        $etatsLaps = ['vivant', 'vendu', 'mort', 'archivé'];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];
        $categories = ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'];
        $origines = ['Interne', 'Achat'];

        foreach ($firms as $firm) {
            // Get firm admin for user_id references
            $firmAdmin = $firm->owner;
            if (!$firmAdmin) continue;

            // Config per firm (varies by subscription status for realism)
            $isActive = $firm->subscriptions()->where('status', 'active')->exists();
            $baseCount = $isActive ? 50 : 10;

            $config = [
                'males' => $baseCount + rand(0, 20),
                'females' => $baseCount * 3 + rand(0, 50),
                'saillies' => $baseCount * 4 + rand(0, 80),
                'mises_bas' => $baseCount * 3 + rand(0, 60),
                'naissances' => $baseCount * 2 + rand(0, 40),
                'lapereaux_per_birth' => rand(4, 8),
                'sales' => rand(5, 30),
            ];

            // ── 1. MALES ───────────────────────────────────────────────
            for ($i = 1; $i <= $config['males']; $i++) {
                Male::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'code' => 'MAL-F' . $firm->id . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom' => "Mâle-F{$firm->id}-{$i}",
                    'race' => $races[array_rand($races)],
                    'origine' => $origines[array_rand($origines)],
                    'date_naissance' => now()->subMonths(rand(6, 36))->subDays(rand(0, 30)),
                    'etat' => $etatsMales[array_rand($etatsMales)],
                ]);
            }

            // ── 2. FEMELLES ────────────────────────────────────────────
            for ($i = 1; $i <= $config['females']; $i++) {
                Femelle::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'code' => 'FEM-F' . $firm->id . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom' => "Femelle-F{$firm->id}-{$i}",
                    'race' => $races[array_rand($races)],
                    'origine' => $origines[array_rand($origines)],
                    'date_naissance' => now()->subMonths(rand(6, 36))->subDays(rand(0, 30)),
                    'etat' => $etatsFemelles[array_rand($etatsFemelles)],
                ]);
            }

            // ── 3. SAILLIES ────────────────────────────────────────────
            $femelles = Femelle::where('firm_id', $firm->id)->inRandomOrder()->limit($config['saillies'])->get();
            $males = Male::where('firm_id', $firm->id)->get();

            foreach ($femelles as $femelle) {
                if ($males->isEmpty()) break;
                $male = $males->random();
                $dateSaillie = now()->subDays(rand(10, 180));

                Saillie::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'femelle_id' => $femelle->id,
                    'male_id' => $male->id,
                    'date_saillie' => $dateSaillie->format('Y-m-d'),
                    'date_palpage' => rand(0, 1) ? $dateSaillie->copy()->addDays(rand(10, 14))->format('Y-m-d') : null,
                    'palpation_resultat' => rand(0, 1) ? ['+', '-'][array_rand([0, 1])] : null,
                    'date_mise_bas_theorique' => $dateSaillie->copy()->addDays(31)->format('Y-m-d'),
                ]);
            }

            // ── 4. MISES BAS ───────────────────────────────────────────
            $sailliesPos = Saillie::where('firm_id', $firm->id)
                ->whereNotNull('date_palpage')
                ->where('palpation_resultat', '+')
                ->inRandomOrder()
                ->limit($config['mises_bas'])
                ->get();

            if ($sailliesPos->count() < $config['mises_bas']) {
                $remaining = $config['mises_bas'] - $sailliesPos->count();
                $extra = Saillie::where('firm_id', $firm->id)
                    ->whereNotIn('id', $sailliesPos->pluck('id'))
                    ->inRandomOrder()
                    ->limit($remaining)
                    ->get();
                $sailliesPos = $sailliesPos->merge($extra);
            }

            foreach ($sailliesPos as $saillie) {
                $dateMiseBas = Carbon::parse($saillie->date_saillie)->addDays(31);
                MiseBas::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'femelle_id' => $saillie->femelle_id,
                    'saillie_id' => $saillie->id,
                    'date_mise_bas' => $dateMiseBas->format('Y-m-d'),
                    'date_sevrage' => $dateMiseBas->copy()->addWeeks(6)->format('Y-m-d'),
                    'poids_moyen_sevrage' => rand(500, 1500) / 1000,
                ]);
            }

            // ── 5. NAISSANCES ──────────────────────────────────────────
            $misesBas = MiseBas::where('firm_id', $firm->id)
                ->inRandomOrder()
                ->limit($config['naissances'])
                ->get();

            foreach ($misesBas as $miseBas) {
                $dateMiseBas = Carbon::parse($miseBas->date_mise_bas);
                Naissance::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'mise_bas_id' => $miseBas->id,
                    'saillie_id' => $miseBas->saillie_id,
                    'poids_moyen_naissance' => rand(40, 80),
                    'etat_sante' => $etatsSante[array_rand($etatsSante)],
                    'observations' => 'Portée en bonne santé – ' . Str::random(20),
                    'date_sevrage_prevue' => $dateMiseBas->copy()->addWeeks(6)->format('Y-m-d'),
                    'date_vaccination_prevue' => $dateMiseBas->copy()->addWeeks(4)->format('Y-m-d'),
                    'sex_verified' => rand(0, 10) > 6,
                    'sex_verified_at' => rand(0, 10) > 6 ? now()->subDays(rand(1, 10)) : null,
                    'reminder_count' => rand(0, 3),
                ]);
            }

            // ── 6. LAPEREAUX ───────────────────────────────────────────
            $naissances = Naissance::where('firm_id', $firm->id)->get();
            $lapCount = 0;

            foreach ($naissances as $naissance) {
                $nb = rand(
                    max(1, $config['lapereaux_per_birth'] - 1),
                    $config['lapereaux_per_birth'] + 2
                );
                for ($j = 1; $j <= $nb; $j++) {
                    $lapCount++;
                    $code = "LAP-F{$firm->id}-" . str_pad($lapCount, 5, '0', STR_PAD_LEFT);

                    Lapereau::create([
                        'user_id' => $firmAdmin->id,
                        'firm_id' => $firm->id,
                        'naissance_id' => $naissance->id,
                        'code' => $code,
                        'nom' => "Lapereau-F{$firm->id}-{$lapCount}",
                        'sex' => rand(0, 1) ? 'male' : 'female',
                        'etat' => $etatsLaps[array_rand($etatsLaps)],
                        'poids_naissance' => rand(40, 90),
                        'etat_sante' => $etatsSante[array_rand($etatsSante)],
                        'observations' => 'Lapereau en bonne santé – ' . Str::random(15),
                        'categorie' => $categories[array_rand($categories)],
                        'alimentation_jour' => rand(50, 200) / 100,
                        'alimentation_semaine' => rand(300, 1200) / 100,
                    ]);
                }
            }

            $this->command->info("  ✓ Firm #{$firm->id}: {$config['males']}♂ | {$config['females']}♀ | ~{$lapCount} lapereaux");
        }

        $this->command->info("✅ All breeding data seeded across 10 firms");
    }

    private function seedSales(): void
    {
        $this->command->info("💰 Seeding Sales per firm...");

        $firms = Firm::all();
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        foreach ($firms as $firm) {
            $firmAdmin = $firm->owner;
            if (!$firmAdmin) continue;

            $isActive = $firm->subscriptions()->where('status', 'active')->exists();
            $numSales = $isActive ? rand(10, 30) : rand(1, 5);

            for ($s = 1; $s <= $numSales; $s++) {
                $type = ['male', 'female', 'lapereau'][array_rand(['male', 'female', 'lapereau'])];
                $price = $type === 'male' ? 25000 : ($type === 'female' ? 30000 : 15000);
                $amountPaid = rand(0, 1) ? $price : rand(0, $price);

                $sale = Sale::create([
                    'user_id' => $firmAdmin->id,
                    'firm_id' => $firm->id,
                    'date_sale' => now()->subDays(rand(1, 120)),
                    'quantity' => 1,
                    'type' => $type,
                    'unit_price' => $price,
                    'total_amount' => $price,
                    'buyer_name' => 'Acheteur-' . strtoupper(Str::random(6)),
                    'buyer_contact' => '+229' . rand(90000000, 99999999),
                    'payment_status' => $amountPaid >= $price ? 'paid' : ($amountPaid > 0 ? 'partial' : 'pending'),
                    'amount_paid' => $amountPaid,
                    'notes' => 'Vente générée automatiquement',
                ]);

                // Link to actual rabbit (polymorphic)
                $rabbit = match ($type) {
                    'male' => Male::where('firm_id', $firm->id)->where('etat', 'vendu')->inRandomOrder()->first(),
                    'female' => Femelle::where('firm_id', $firm->id)->where('etat', 'vendu')->inRandomOrder()->first(),
                    'lapereau' => Lapereau::where('firm_id', $firm->id)->where('etat', 'vendu')->inRandomOrder()->first(),
                };

                if ($rabbit) {
                    SaleRabbit::create([
                        'sale_id' => $sale->id,
                        'rabbit_type' => $type,
                        'rabbit_id' => $rabbit->id,
                        'sale_price' => $price,
                    ]);
                }
            }

            $this->command->info("  ✓ Firm #{$firm->id}: {$numSales} ventes");
        }
    }

    private function seedNotifications(): void
    {
        $this->command->info("🔔 Seeding Notifications...");

        $users = User::whereIn('role', ['firm_admin', 'employee'])->get();
        $types = ['success', 'info', 'warning', 'error'];
        $icons = [
            'success' => 'bi-check-circle-fill',
            'info' => 'bi-info-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'error' => 'bi-x-circle-fill',
        ];

        $messages = [
            'success' => ['Saillie enregistrée avec succès', 'Mise-bas confirmée', 'Vente clôturée'],
            'info' => ['Rappel: palpation prévue', 'Nouvel employé ajouté', 'Rapport mensuel disponible'],
            'warning' => ['Abonnement expirant bientôt', 'Stock mâles faible', 'Poids sevrage inférieur'],
            'error' => ['Erreur de synchronisation', 'Paiement refusé', 'Données manquantes'],
        ];

        foreach ($users as $user) {
            for ($i = 1; $i <= rand(2, 8); $i++) {
                $type = $types[array_rand($types)];
                $msg = $messages[$type][array_rand($messages[$type])];

                Notification::create([
                    'user_id' => $user->id,
                    'firm_id' => $user->firm_id,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'type' => $type,
                    'title' => match ($type) {
                        'success' => '✅ Succès',
                        'info' => 'ℹ️ Information',
                        'warning' => '⚠️ Attention',
                        'error' => '❌ Erreur',
                    },
                    'message' => $msg . ' #' . rand(100, 999),
                    'action_url' => route('dashboard'),
                    'icon' => $icons[$type],
                    'is_read' => (bool) rand(0, 1),
                    'emailed' => (bool) rand(0, 1),
                    'read_at' => rand(0, 1) ? now()->subHours(rand(1, 48)) : null,
                ]);
            }
        }

        $this->command->info("  ✓ Notifications created for firm users");
    }

    // ========================================================================
    // INVOICE HELPER
    // ========================================================================

    private function createInvoice(
        User $user,
        Subscription $subscription,
        int $firmId,
        Carbon $invoiceDate,
        float $amount,
        string $status,
        string $planName
    ): void {
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($this->invoiceCounter++, 5, '0', STR_PAD_LEFT);

        DB::table('invoices')->insert([
            'user_id' => $user->id,
            'firm_id' => $firmId,
            'subscription_id' => $subscription->id,
            'invoice_number' => $invoiceNumber,
            'invoice_type' => 'subscription',
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'currency' => 'XOF',
            'status' => $status,
            'invoice_date' => $invoiceDate->toDateString(),
            'due_date' => $invoiceDate->copy()->addDays(3)->toDateString(),
            'paid_at' => $status === 'paid' ? $invoiceDate->toDateTimeString() : null,
            'pdf_generated' => false,
            'billing_details' => json_encode([
                'name' => $user->firm->name ?? $user->name,
                'email' => $user->email,
                'address' => 'Cotonou, Bénin',
            ]),
            'line_items' => json_encode([[
                'description' => 'Abonnement CuniApp – ' . $planName,
                'quantity' => 1,
                'unit_price' => $amount,
                'total' => $amount,
            ]]),
            'payment_method' => 'manual',
            'transaction_reference' => $subscription->payment_reference,
            'notes' => 'Facture générée automatiquement lors du seeding.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ========================================================================
    // CREDENTIALS DISPLAY — PRETTIFIED
    // ========================================================================

    private function printLoginCredentials(): void
    {
        $this->command->line('');
        $this->command->line('╔══════════════════════════════════════════════════════════════════════╗');
        $this->command->line('║                     🔑  LOGIN CREDENTIALS  🔑                        ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');

        // ── Super Admin ─────────────────────────────────────────────────
        $sa = $this->credentials['super_admin'];
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👑  SUPER ADMIN                                                     ║');
        $this->command->line('║  ──────────────────────────────────────────────────────────────────  ║');
        $this->command->line(sprintf('║  %-10s : %-50s  ║', 'Email', $sa['email']));
        $this->command->line(sprintf('║  %-10s : %-50s  ║', 'Password', $sa['password']));
        $this->command->line(sprintf('║  %-10s : %-50s  ║', 'Role', $sa['role']));
        $this->command->line(sprintf('║  %-10s : %-50s  ║', 'Accès', $sa['description']));
        $this->command->line('║                                                                      ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');

        // ── Firm Admins ─────────────────────────────────────────────────
        $this->command->line('║                                                                      ║');
        $this->command->line('║  🏢  FIRM ADMINISTRATORS (10 firms)                                  ║');
        $this->command->line('║  ──────────────────────────────────────────────────────────────────  ║');

        $firmAdmins = array_filter($this->credentials, fn($k) => str_starts_with($k, 'firm_') && str_ends_with($k, '_admin'), ARRAY_FILTER_USE_KEY);

        foreach ($firmAdmins as $key => $cred) {
            $firmNum = explode('_', $key)[1];
            $this->command->line('║                                                                      ║');
            $this->command->line(sprintf('║  📋  FIRM #%-2s — %-30s                          ║', $firmNum, $cred['firm']));
            $this->command->line(sprintf('║  Email    : %-50s  ║', $cred['email']));
            $this->command->line(sprintf('║  Password : %-50s  ║', $cred['password']));
            $this->command->line(sprintf('║  Role     : %-50s  ║', $cred['role']));
            $this->command->line(sprintf('║  Note     : %-50s  ║', $cred['description']));
        }

        $this->command->line('║                                                                      ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');

        // ── Sample Employees (show first 2 per firm for brevity) ─────────
        $this->command->line('║                                                                      ║');
        $this->command->line('║  👥  SAMPLE EMPLOYEES (2 per firm shown)                            ║');
        $this->command->line('║  ──────────────────────────────────────────────────────────────────  ║');

        $sampleEmps = array_filter($this->credentials, fn($k) => preg_match('/firm_\d+_emp_[12]$/', $k), ARRAY_FILTER_USE_KEY);

        foreach ($sampleEmps as $key => $cred) {
            preg_match('/firm_(\d+)_emp_(\d+)/', $key, $matches);
            $firmNum = $matches[1];
            $empNum = $matches[2];
            $this->command->line(sprintf(
                '║  🧑‍💼  Firm #%-2s • Employé %-1s : %-45s  ║',
                $firmNum,
                $empNum,
                $cred['email']
            ));
            $this->command->line(sprintf('║       Pass: %-50s  ║', $cred['password']));
        }

        $this->command->line('║                                                                      ║');
        $this->command->line('╠══════════════════════════════════════════════════════════════════════╣');
        $this->command->line('║  💡  TIPS:                                                            ║');
        $this->command->line('║  • All passwords end with "123!" for easy testing                   ║');
        $this->command->line('║  • Firm admins can add/manage employees within their plan limit     ║');
        $this->command->line('║  • Super admin can view/impersonate any firm                        ║');
        $this->command->line('║  • Employees see only data scoped to their firm_id                  ║');
        $this->command->line('╚══════════════════════════════════════════════════════════════════════╝');
        $this->command->line('');
    }
}
