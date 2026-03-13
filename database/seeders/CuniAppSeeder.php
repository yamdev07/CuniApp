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
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\PaymentTransaction;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CuniAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting CuniApp Élevage Seeding...');

        // =====================================================================
        // STEP 1: Create Subscription Plans
        // =====================================================================
        $this->command->info('📦 Creating Subscription Plans...');
        
        $plans = [
            ['name' => 'Mensuel', 'duration_months' => 1, 'price' => 2500, 'features' => ['Accès complet', 'Support email', 'Sauvegarde automatique']],
            ['name' => 'Trimestriel', 'duration_months' => 3, 'price' => 7500, 'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports mensuels']],
            ['name' => 'Semestriel', 'duration_months' => 6, 'price' => 15000, 'features' => ['Accès complet', 'Support prioritaire', 'Sauvegarde automatique', 'Rapports mensuels', 'Formation incluse']],
            ['name' => 'Annuel', 'duration_months' => 12, 'price' => 30000, 'features' => ['Accès complet', 'Support 24/7', 'Sauvegarde automatique', 'Rapports mensuels', 'Formation incluse', 'Fonctionnalités beta']],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::firstOrCreate(
                ['name' => $planData['name'], 'duration_months' => $planData['duration_months']],
                [
                    'price' => $planData['price'],
                    'is_active' => true,
                    'features' => $planData['features'],
                    'description' => "Abonnement {$planData['duration_months']} mois",
                ]
            );
        }

        $this->command->info('✅ Subscription Plans Created');

        // =====================================================================
        // STEP 2: Create Users with Different Subscription Statuses
        // =====================================================================
        $this->command->info('👥 Creating User Accounts...');

        // Admin Account - POPULATED
        $admin = User::firstOrCreate(
            ['email' => 'admin@cuniapp.com'],
            [
                'name' => 'Administrateur CuniApp',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addYears(5),
                'theme' => 'light',
                'language' => 'fr',
            ]
        );

        // User 1 - 1 Month Subscription - LARGE DATASET
        $user1 = User::firstOrCreate(
            ['email' => 'user1@cuniapp.com'],
            [
                'name' => 'Utilisateur Test 1 Mois',
                'password' => Hash::make('User1@123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonth(),
                'theme' => 'light',
                'language' => 'fr',
            ]
        );

        // User 2 - 3 Month Subscription - LARGE DATASET
        $user2 = User::firstOrCreate(
            ['email' => 'user2@cuniapp.com'],
            [
                'name' => 'Utilisateur Test 3 Mois',
                'password' => Hash::make('User2@123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonths(3),
                'theme' => 'dark',
                'language' => 'fr',
            ]
        );

        // User 3 - 6 Month Subscription - LARGE DATASET
        $user3 = User::firstOrCreate(
            ['email' => 'user3@cuniapp.com'],
            [
                'name' => 'Utilisateur Test 6 Mois',
                'password' => Hash::make('User3@123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonths(6),
                'theme' => 'light',
                'language' => 'fr',
            ]
        );

        // User 4 - 1 Year Subscription - SMALL DATASET
        $user4 = User::firstOrCreate(
            ['email' => 'user4@cuniapp.com'],
            [
                'name' => 'Utilisateur Test 1 An',
                'password' => Hash::make('User4@123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addYear(),
                'theme' => 'dark',
                'language' => 'fr',
            ]
        );

        // User 5 - NO Subscription - EMPTY ACCOUNT
        $user5 = User::firstOrCreate(
            ['email' => 'user5@cuniapp.com'],
            [
                'name' => 'Utilisateur Sans Abonnement',
                'password' => Hash::make('User5@123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'inactive',
                'subscription_ends_at' => null,
                'theme' => 'light',
                'language' => 'fr',
            ]
        );

        $this->command->info('✅ User Accounts Created');

        // =====================================================================
        // STEP 3: Create Subscriptions & Payment Transactions
        // =====================================================================
        $this->command->info('💳 Creating Subscriptions & Payments...');

        $this->createSubscription($admin, 12, 30000, 'manual', 'completed');
        $this->createSubscription($user1, 1, 2500, 'momo', 'completed');
        $this->createSubscription($user2, 3, 7500, 'celtis', 'completed');
        $this->createSubscription($user3, 6, 15000, 'moov', 'completed');
        $this->createSubscription($user4, 12, 30000, 'momo', 'completed');
        // User 5 - No subscription

        $this->command->info('✅ Subscriptions Created');

        // =====================================================================
        // STEP 4: Populate Data for Each User
        // =====================================================================
        
        // ADMIN - LARGE DATASET (60 males, 200 females, 500 lapereaux)
        $this->command->info('📊 Populating Admin Account (Large Dataset)...');
        $this->populateUserData($admin, 60, 200, 500, 150, 100, 80, 50, 30);

        // USER 1 - LARGE DATASET (60 males, 200 females, 500 lapereaux)
        $this->command->info('📊 Populating User 1 Account (Large Dataset)...');
        $this->populateUserData($user1, 60, 200, 500, 150, 100, 80, 50, 30);

        // USER 2 - LARGE DATASET (60 males, 200 females, 500 lapereaux)
        $this->command->info('📊 Populating User 2 Account (Large Dataset)...');
        $this->populateUserData($user2, 60, 200, 500, 150, 100, 80, 50, 30);

        // USER 3 - LARGE DATASET (60 males, 200 females, 500 lapereaux)
        $this->command->info('📊 Populating User 3 Account (Large Dataset)...');
        $this->populateUserData($user3, 60, 200, 500, 150, 100, 80, 50, 30);

        // USER 4 - SMALL DATASET (10 males, 20 females, 50 lapereaux)
        $this->command->info('📊 Populating User 4 Account (Small Dataset)...');
        $this->populateUserData($user4, 10, 20, 50, 15, 10, 8, 5, 3);

        // USER 5 - EMPTY (No data)
        $this->command->info('⚠️  User 5 Account Left Empty (No Subscription)');

        // =====================================================================
        // STEP 5: Create Settings
        // =====================================================================
        $this->command->info('⚙️ Creating Default Settings...');
        
        $settings = [
            ['key' => 'farm_name', 'value' => 'Ferme CuniApp Test', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_address', 'value' => 'Houéyiho après le pont devant Volta United, Cotonou, Bénin', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_phone', 'value' => '+2290152415241', 'type' => 'string', 'group' => 'general'],
            ['key' => 'farm_email', 'value' => 'contact@cuniapp.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding'],
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales'],
            ['key' => 'fedapay_public_key', 'value' => env('FEDAPAY_PUBLIC_KEY', ''), 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_secret_key', 'value' => env('FEDAPAY_SECRET_KEY', ''), 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_environment', 'value' => 'sandbox', 'type' => 'string', 'group' => 'payments'],
            ['key' => 'fedapay_webhook_secret', 'value' => env('FEDAPAY_WEBHOOK_SECRET', ''), 'type' => 'string', 'group' => 'payments'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('✅ Settings Created');

        // =====================================================================
        // FINAL: Display Login Credentials
        // =====================================================================
        $this->displayLoginCredentials();
    }

    /**
     * Create subscription and payment transaction for user
     */
    private function createSubscription(User $user, int $months, int $price, string $method, string $status): void
    {
        $plan = SubscriptionPlan::where('duration_months', $months)->first();
        
        if (!$plan) {
            $this->command->warn("⚠️ Plan for {$months} months not found");
            return;
        }

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => $status,
            'start_date' => now(),
            'end_date' => now()->addMonths($months),
            'price' => $price,
            'payment_method' => $method,
            'payment_reference' => 'SUB-' . strtoupper(uniqid()),
            'auto_renew' => false,
        ]);

        PaymentTransaction::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $price,
            'payment_method' => $method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => $status,
            'provider' => $method,
            'paid_at' => $status === 'completed' ? now() : null,
        ]);
    }

    /**
     * Populate user account with test data
     */
    private function populateUserData(
        User $user,
        int $maleCount,
        int $femaleCount,
        int $lapereauCount,
        int $saillieCount,
        int $miseBasCount,
        int $naissanceCount,
        int $saleCount,
        int $notificationCount
    ): void {
        $this->command->getOutput()->write("  Creating {$maleCount} males... ");
        $males = $this->createMales($user, $maleCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$femaleCount} females... ");
        $females = $this->createFemales($user, $femaleCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$saillieCount} saillies... ");
        $saillies = $this->createSaillies($user, $females, $males, $saillieCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$miseBasCount} mises bas... ");
        $misesBas = $this->createMisesBas($user, $females, $saillies, $miseBasCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$naissanceCount} naissances... ");
        $naissances = $this->createNaissances($user, $misesBas, $naissanceCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$lapereauCount} lapereaux... ");
        $this->createLapereaux($user, $naissances, $lapereauCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$saleCount} sales... ");
        $this->createSales($user, $males, $females, $saleCount);
        $this->command->info('✅');

        $this->command->getOutput()->write("  Creating {$notificationCount} notifications... ");
        $this->createNotifications($user, $notificationCount);
        $this->command->info('✅');
    }

    /**
     * Create male rabbits
     */
    private function createMales(User $user, int $count): array
    {
        $males = [];
        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Bélier', 'Angora'];
        $etats = ['Active', 'Inactive', 'Malade'];

        for ($i = 1; $i <= $count; $i++) {
            $males[] = Male::create([
                'user_id' => $user->id,
                'code' => 'MAL-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Mâle {$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand(['Interne', 'Achat'])],
                'date_naissance' => now()->subMonths(rand(6, 24)),
                'etat' => $etats[array_rand($etats)],
            ]);
        }

        return $males;
    }

    /**
     * Create female rabbits
     */
    private function createFemales(User $user, int $count): array
    {
        $females = [];
        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Bélier', 'Angora'];
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];

        for ($i = 1; $i <= $count; $i++) {
            $females[] = Femelle::create([
                'user_id' => $user->id,
                'code' => 'FEM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => "Femelle {$i}",
                'race' => $races[array_rand($races)],
                'origine' => ['Interne', 'Achat'][array_rand(['Interne', 'Achat'])],
                'date_naissance' => now()->subMonths(rand(6, 24)),
                'etat' => $etats[array_rand($etats)],
            ]);
        }

        return $females;
    }

    /**
     * Create saillies (breeding records)
     */
    private function createSaillies(User $user, array $females, array $males, int $count): array
    {
        $saillies = [];
        $resultats = ['+', '-', null];

        for ($i = 0; $i < $count; $i++) {
            $femelle = $females[array_rand($females)];
            $male = $males[array_rand($males)];
            $dateSaillie = now()->subDays(rand(1, 90));

            $saillies[] = Saillie::create([
                'user_id' => $user->id,
                'femelle_id' => $femelle->id,
                'male_id' => $male->id,
                'date_saillie' => $dateSaillie,
                'date_palpage' => rand(0, 1) ? $dateSaillie->addDays(rand(10, 15)) : null,
                'palpation_resultat' => $resultats[array_rand($resultats)],
                'date_mise_bas_theorique' => $dateSaillie->addDays(31),
            ]);
        }

        return $saillies;
    }

    /**
     * Create mises bas (birth events)
     */
    private function createMisesBas(User $user, array $females, array $saillies, int $count): array
    {
        $misesBas = [];

        for ($i = 0; $i < $count; $i++) {
            $femelle = $females[array_rand($females)];
            $saillie = count($saillies) > 0 ? $saillies[array_rand($saillies)] : null;

            $misesBas[] = MiseBas::create([
                'user_id' => $user->id,
                'femelle_id' => $femelle->id,
                'saillie_id' => $saillie?->id,
                'date_mise_bas' => now()->subDays(rand(1, 120)),
                'date_sevrage' => now()->addDays(rand(30, 60)),
                'poids_moyen_sevrage' => rand(500, 800) / 1000,
            ]);
        }

        return $misesBas;
    }

    /**
     * Create naissances (birth records)
     */
    private function createNaissances(User $user, array $misesBas, int $count): array
    {
        $naissances = [];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        foreach ($misesBas as $index => $miseBas) {
            if ($index >= $count) break;

            $naissances[] = Naissance::create([
                'user_id' => $user->id,
                'mise_bas_id' => $miseBas->id,
                'poids_moyen_naissance' => rand(40, 80),
                'etat_sante' => $etatsSante[array_rand($etatsSante)],
                'observations' => 'Portée en bonne santé',
                'date_sevrage_prevue' => $miseBas->date_mise_bas->addWeeks(6),
                'sex_verified' => rand(0, 1),
                'sex_verified_at' => rand(0, 1) ? now() : null,
                'first_reminder_sent_at' => rand(0, 1) ? now()->subDays(5) : null,
                'last_reminder_sent_at' => rand(0, 1) ? now()->subDays(2) : null,
                'reminder_count' => rand(0, 3),
            ]);
        }

        return $naissances;
    }

    /**
     * Create lapereaux (baby rabbits)
     */
    private function createLapereaux(User $user, array $naissances, int $count): void
    {
        $etats = ['vivant', 'vendu', 'mort'];
        $sexes = ['male', 'female'];
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        $perNaissance = max(1, (int)($count / max(1, count($naissances))));

        foreach ($naissances as $index => $naissance) {
            for ($j = 0; $j < $perNaissance; $j++) {
                $currentCount = Lapereau::where('naissance_id', $naissance->id)->count();
                if ($currentCount >= $perNaissance) break;

                Lapereau::create([
                    'user_id' => $user->id,
                    'naissance_id' => $naissance->id,
                    'code' => 'LAP-' . date('Y') . '-' . str_pad(Lapereau::count() + 1, 4, '0', STR_PAD_LEFT),
                    'nom' => "Lapereau " . ($index + 1) . "-" . ($j + 1),
                    'sex' => $sexes[array_rand($sexes)],
                    'etat' => $etats[array_rand($etats)],
                    'poids_naissance' => rand(40, 80),
                    'etat_sante' => $etatsSante[array_rand($etatsSante)],
                    'categorie' => ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'][array_rand(['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'])],
                ]);
            }
        }
    }

    /**
     * Create sales
     */
    private function createSales(User $user, array $males, array $females, int $count): void
    {
        $paymentStatus = ['paid', 'pending', 'partial'];

        for ($i = 0; $i < $count; $i++) {
            $totalAmount = rand(15000, 100000);
            $paymentStatusValue = $paymentStatus[array_rand($paymentStatus)];

            $sale = Sale::create([
                'user_id' => $user->id,
                'date_sale' => now()->subDays(rand(1, 90)),
                'quantity' => rand(1, 5),
                'type' => ['male', 'female', 'lapereau'][array_rand(['male', 'female', 'lapereau'])],
                'buyer_name' => "Client " . ($i + 1),
                'buyer_contact' => '+229' . rand(10000000, 99999999),
                'payment_status' => $paymentStatusValue,
                'total_amount' => $totalAmount,
                'amount_paid' => $paymentStatusValue === 'paid' ? $totalAmount : ($paymentStatusValue === 'partial' ? $totalAmount * 0.5 : 0),
                'notes' => 'Vente test',
            ]);

            // Link some rabbits to sale
            if (count($males) > 0 && rand(0, 1)) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $males[array_rand($males)]->id,
                    'sale_price' => rand(20000, 35000),
                ]);
            }

            if (count($females) > 0 && rand(0, 1)) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $females[array_rand($females)]->id,
                    'sale_price' => rand(25000, 40000),
                ]);
            }
        }
    }

    /**
     * Create notifications
     */
    private function createNotifications(User $user, int $count): void
    {
        $types = ['success', 'warning', 'info', 'error'];
        $titles = [
            'Nouveau lapin enregistré',
            'Saillie planifiée',
            'Mise bas enregistrée',
            'Vente complétée',
            'Rappel: Vérification de portée',
            'Abonnement expirant bientôt',
            'Paiement réussi',
            'Mise à jour disponible',
        ];

        for ($i = 0; $i < $count; $i++) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $types[array_rand($types)],
                'title' => $titles[array_rand($titles)],
                'message' => "Notification test #" . ($i + 1) . " pour tester le système de notifications",
                'action_url' => route('dashboard'),
                'icon' => 'bi-bell-fill',
                'is_read' => rand(0, 1),
                'emailed' => rand(0, 1),
                'read_at' => rand(0, 1) ? now() : null,
            ]);
        }
    }

    /**
     * Display login credentials
     */
    private function displayLoginCredentials(): void
    {
        $this->command->newLine(2);
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║         🔐 CUNIAPP ÉLEVAGE - LOGIN CREDENTIALS 🔐            ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║                                                              ║');
        $this->command->info('║  👑 ADMIN ACCOUNT (Populated - Large Dataset)               ║');
        $this->command->info('║  Email: admin@cuniapp.com                                    ║');
        $this->command->info('║  Password: Admin@123                                         ║');
        $this->command->info('║  Subscription: Active (5 years)                              ║');
        $this->command->info('║  Data: 60 males, 200 females, 500 lapereaux                  ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  👤 USER 1 (Populated - Large Dataset)                       ║');
        $this->command->info('║  Email: user1@cuniapp.com                                    ║');
        $this->command->info('║  Password: User1@123                                         ║');
        $this->command->info('║  Subscription: 1 Month                                       ║');
        $this->command->info('║  Data: 60 males, 200 females, 500 lapereaux                  ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  👤 USER 2 (Populated - Large Dataset)                       ║');
        $this->command->info('║  Email: user2@cuniapp.com                                    ║');
        $this->command->info('║  Password: User2@123                                         ║');
        $this->command->info('║  Subscription: 3 Months                                      ║');
        $this->command->info('║  Data: 60 males, 200 females, 500 lapereaux                  ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  👤 USER 3 (Populated - Large Dataset)                       ║');
        $this->command->info('║  Email: user3@cuniapp.com                                    ║');
        $this->command->info('║  Password: User3@123                                         ║');
        $this->command->info('║  Subscription: 6 Months                                      ║');
        $this->command->info('║  Data: 60 males, 200 females, 500 lapereaux                  ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  👤 USER 4 (Populated - Small Dataset)                       ║');
        $this->command->info('║  Email: user4@cuniapp.com                                    ║');
        $this->command->info('║  Password: User4@123                                         ║');
        $this->command->info('║  Subscription: 1 Year                                        ║');
        $this->command->info('║  Data: 10 males, 20 females, 50 lapereaux                    ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  👤 USER 5 (EMPTY - No Subscription)                         ║');
        $this->command->info('║  Email: user5@cuniapp.com                                    ║');
        $this->command->info('║  Password: User5@123                                         ║');
        $this->command->info('║  Subscription: NONE                                          ║');
        $this->command->info('║  Data: EMPTY (Test subscription required flow)               ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->newLine();
        $this->command->info('✅ Seeding Completed Successfully!');
        $this->command->info('📊 Total Records Created:');
        $this->command->info('   - Users: 6 (1 Admin + 5 Users)');
        $this->command->info('   - Males: ' . Male::count());
        $this->command->info('   - Femelles: ' . Femelle::count());
        $this->command->info('   - Lapereaux: ' . Lapereau::count());
        $this->command->info('   - Saillies: ' . Saillie::count());
        $this->command->info('   - Mises Bas: ' . MiseBas::count());
        $this->command->info('   - Naissances: ' . Naissance::count());
        $this->command->info('   - Sales: ' . Sale::count());
        $this->command->info('   - Notifications: ' . Notification::count());
        $this->command->info('   - Subscriptions: ' . Subscription::count());
        $this->command->newLine();
    }
}