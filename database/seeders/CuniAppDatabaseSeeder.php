<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Naissance;
use App\Models\Lapereau;
use App\Models\Sale;
use App\Models\SaleRabbit;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentTransaction;

class CuniAppDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting CuniApp Database Seeding...');
        $this->command->info('');

        // Seed in order (respecting foreign keys)
        $this->seedSettings();
        $this->seedUsers();
        $this->seedSubscriptionPlans();
        $this->seedMales();
        $this->seedFemelles();
        $this->seedSaillies();
        $this->seedMisesBas();
        $this->seedNaissances();
        $this->seedLapereaux();
        $this->seedSales();
        $this->seedSubscriptions();
        $this->seedNotifications();

        $this->command->info('');
        $this->command->info('✅ All seeds completed successfully!');
    }

    /**
     * Seed application settings
     */
    private function seedSettings(): void
    {
        $this->command->info('⚙️ Seeding Settings...');

        $settings = [
            // General Settings
            ['key' => 'farm_name', 'value' => 'Ferme Lapin d\'Or', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => 'Houéyiho après le pont devant Volta United, Cotonou, Bénin', 'type' => 'string', 'group' => 'general', 'label' => 'Adresse'],
            ['key' => 'farm_phone', 'value' => '+2290152415241', 'type' => 'string', 'group' => 'general', 'label' => 'Téléphone'],
            ['key' => 'farm_email', 'value' => 'contact@cuniapp.com', 'type' => 'string', 'group' => 'general', 'label' => 'Email'],

            // Breeding Settings
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold', 'value' => '80', 'type' => 'number', 'group' => 'breeding', 'label' => 'Seuil d\'alerte (%)'],

            // Verification Settings
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai initial de vérification'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai premier rappel'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding', 'label' => 'Intervalle des rappels'],

            // Sales Settings
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Mâles'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Femelles'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Lapereaux'],

            // Payment Settings
            ['key' => 'momo_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'MTN MoMo API Key'],
            ['key' => 'momo_api_secret', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'MTN MoMo API Secret'],
            ['key' => 'celtis_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Celtis Cash API Key'],
            ['key' => 'moov_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Moov Pay API Key'],
            ['key' => 'grace_period_days', 'value' => '3', 'type' => 'number', 'group' => 'subscriptions', 'label' => 'Grace Period (Days)'],
            ['key' => 'enable_auto_renew', 'value' => '1', 'type' => 'boolean', 'group' => 'subscriptions', 'label' => 'Enable Auto-Renewal'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->line('   ✓ <fg=green>' . count($settings) . ' settings created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed users (Admin + Regular Users)
     */
    private function seedUsers(): void
    {
        $this->command->info('👥 Seeding Users...');

        // Admin User
        User::create([
            'name' => 'Administrateur CuniApp',
            'email' => 'admin@cuniapp.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addYear(),
            'theme' => 'dark',
            'language' => 'fr',
            'notifications_email' => true,
            'notifications_dashboard' => true,
        ]);

        // Regular User
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'user@cuniapp.com',
            'password' => Hash::make('user123'),
            'email_verified_at' => now(),
            'role' => 'user',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addMonths(3),
            'theme' => 'light',
            'language' => 'fr',
            'notifications_email' => true,
            'notifications_dashboard' => true,
        ]);

        // Additional Test Users (50 users for testing)
        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $users[] = [
                'name' => "Utilisateur {$i}",
                'email' => "user{$i}@cuniapp.com",
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'user',
                'subscription_status' => $i % 3 === 0 ? 'active' : 'inactive',
                'subscription_ends_at' => $i % 3 === 0 ? now()->addMonths(rand(1, 12)) : null,
                'theme' => ['light', 'dark', 'system'][array_rand(['light', 'dark', 'system'])],
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        User::insert($users);

        $this->command->line('   ✓ <fg=green>52 users created (1 Admin + 51 Users)</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed subscription plans
     */
    private function seedSubscriptionPlans(): void
    {
        $this->command->info('📋 Seeding Subscription Plans...');

        $plans = [
            [
                'name' => 'Mensuel',
                'duration_months' => 1,
                'price' => 2500,
                'is_active' => true,
                'description' => 'Abonnement mensuel pour gérer votre élevage',
                'features' => json_encode([
                    'Gestion illimitée des lapins',
                    'Suivi des reproductions',
                    'Tableau de bord analytique',
                    'Notifications par email',
                    'Support technique'
                ]),
            ],
            [
                'name' => 'Trimestriel',
                'duration_months' => 3,
                'price' => 7500,
                'is_active' => true,
                'description' => 'Économisez 10% avec l\'abonnement trimestriel',
                'features' => json_encode([
                    'Gestion illimitée des lapins',
                    'Suivi des reproductions',
                    'Tableau de bord analytique',
                    'Notifications par email',
                    'Support technique prioritaire',
                    'Export des données'
                ]),
            ],
            [
                'name' => 'Semestriel',
                'duration_months' => 6,
                'price' => 15000,
                'is_active' => true,
                'description' => 'Économisez 20% avec l\'abonnement semestriel',
                'features' => json_encode([
                    'Gestion illimitée des lapins',
                    'Suivi des reproductions',
                    'Tableau de bord analytique',
                    'Notifications par email',
                    'Support technique prioritaire',
                    'Export des données',
                    'Formation en ligne',
                    'Rapports personnalisés'
                ]),
            ],
            [
                'name' => 'Annuel',
                'duration_months' => 12,
                'price' => 30000,
                'is_active' => true,
                'description' => 'Meilleure offre - Économisez 30% avec l\'abonnement annuel',
                'features' => json_encode([
                    'Gestion illimitée des lapins',
                    'Suivi des reproductions',
                    'Tableau de bord analytique',
                    'Notifications par email',
                    'Support technique 24/7',
                    'Export des données',
                    'Formation en ligne',
                    'Rapports personnalisés',
                    'Accès API',
                    'Multi-utilisateurs'
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->line('   ✓ <fg=green>4 subscription plans created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed male rabbits
     */
    private function seedMales(): void
    {
        $this->command->info('🐰 Seeding Male Rabbits...');

        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Bélier', 'Angora', 'Nain de Couleur', 'Argenté de Champagne'];
        $origins = ['Interne', 'Achat'];
        $etats = ['Active', 'Inactive', 'Malade'];

        $males = [];
        for ($i = 1; $i <= 200; $i++) {
            $code = 'MAL-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $males[] = [
                'code' => $code,
                'nom' => "Mâle {$code}",
                'race' => $races[array_rand($races)],
                'origine' => $origins[array_rand($origins)],
                'date_naissance' => now()->subMonths(rand(6, 36)),
                'etat' => $etats[array_rand($etats)],
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        Male::insert($males);

        $this->command->line('   ✓ <fg=green>200 male rabbits created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed female rabbits
     */
    private function seedFemelles(): void
    {
        $this->command->info('🐰 Seeding Female Rabbits...');

        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Bélier', 'Angora', 'Nain de Couleur', 'Argenté de Champagne'];
        $origins = ['Interne', 'Achat'];
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];

        $femelles = [];
        for ($i = 1; $i <= 300; $i++) {
            $code = 'FEM-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $femelles[] = [
                'code' => $code,
                'nom' => "Femelle {$code}",
                'race' => $races[array_rand($races)],
                'origine' => $origins[array_rand($origins)],
                'date_naissance' => now()->subMonths(rand(6, 36)),
                'etat' => $etats[array_rand($etats)],
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        Femelle::insert($femelles);

        $this->command->line('   ✓ <fg=green>300 female rabbits created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed matings (saillies)
     */
    private function seedSaillies(): void
    {
        $this->command->info('💕 Seeding Matings (Saillies)...');

        $femelles = Femelle::all();
        $males = Male::all();
        $palpationResults = ['+', '-', null];

        $saillies = [];
        for ($i = 1; $i <= 400; $i++) {
            $dateSaillie = now()->subDays(rand(1, 365));
            $datePalpage = $dateSaillie->copy()->addDays(rand(10, 15));
            $palpationResult = $palpationResults[array_rand($palpationResults)];

            $saillies[] = [
                'femelle_id' => $femelles->random()->id,
                'male_id' => $males->random()->id,
                'date_saillie' => $dateSaillie,
                'date_palpage' => $palpationResult ? $datePalpage : null,
                'palpation_resultat' => $palpationResult,
                'date_mise_bas_theorique' => $dateSaillie->copy()->addDays(31),
                'created_at' => $dateSaillie,
                'updated_at' => now(),
            ];
        }

        Saillie::insert($saillies);

        $this->command->line('   ✓ <fg=green>400 matings created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed births (mises bas)
     */
    protected function seedMisesBas(): void
    {
        $this->command->getOutput()->writeln("\n🥚 Seeding Births (Mises Bas)...");

        $femelles = Femelle::all();
        $saillies = Saillie::all();

        for ($i = 0; $i < 300; $i++) {
            // Create Mise Bas (without count columns)
            $miseBas = MiseBas::create([
                'femelle_id' => $femelles->random()->id,
                'saillie_id' => $saillies->random()->id,
                'date_mise_bas' => fake()->dateTimeBetween('-1 year', 'now'),
                'date_sevrage' => fake()->dateTimeBetween('+1 month', '+2 months'),
                'poids_moyen_sevrage' => fake()->randomFloat(2, 0.4, 0.9),
            ]);

            // ✅ Create related Naissance with Lapereaux to generate counts
            $naissance = Naissance::create([
                'mise_bas_id' => $miseBas->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'etat_sante' => fake()->randomElement(['Excellent', 'Bon', 'Moyen', 'Faible']),
                'poids_moyen_naissance' => fake()->randomFloat(2, 40, 90),
                'date_sevrage_prevue' => $miseBas->date_sevrage,
            ]);

            // Create individual Lapereaux (this populates the counts)
            $nbVivant = fake()->numberBetween(4, 10);
            $nbMort = fake()->numberBetween(0, 2);

            for ($j = 0; $j < $nbVivant; $j++) {
                Lapereau::create([
                    'naissance_id' => $naissance->id,
                    'code' => Lapereau::generateUniqueCode(),
                    'nom' => fake()->firstName,
                    'sex' => fake()->randomElement(['male', 'female']),
                    'etat' => 'vivant',
                    'poids_naissance' => fake()->randomFloat(2, 40, 80),
                    'etat_sante' => 'Bon',
                ]);
            }

            for ($j = 0; $j < $nbMort; $j++) {
                Lapereau::create([
                    'naissance_id' => $naissance->id,
                    'code' => Lapereau::generateUniqueCode(),
                    'nom' => null,
                    'sex' => null,
                    'etat' => 'mort',
                    'poids_naissance' => fake()->randomFloat(2, 30, 60),
                    'etat_sante' => 'Faible',
                ]);
            }
        }
        $this->command->getOutput()->writeln("   ✓ 300 mises bas created with related naissances");
    }

    /**
     * Seed litters (naissances)
     */
    private function seedNaissances(): void
    {
        $this->command->info('🐣 Seeding Litters (Naissances)...');

        $misesBas = MiseBas::all();
        $etatsSante = ['Excellent', 'Bon', 'Moyen', 'Faible'];

        $naissances = [];
        for ($i = 1; $i <= 250; $i++) {
            $miseBas = $misesBas->random();
            $dateNaissance = $miseBas->date_mise_bas;

            $naissances[] = [
                'mise_bas_id' => $miseBas->id,
                'user_id' => User::first()->id,
                'poids_moyen_naissance' => rand(40, 80),
                'etat_sante' => $etatsSante[array_rand($etatsSante)],
                'observations' => "Portée normale - " . Str::random(20),
                'date_sevrage_prevue' => $dateNaissance->copy()->addWeeks(6),
                'date_vaccination_prevue' => $dateNaissance->copy()->addWeeks(4),
                'sex_verified' => rand(0, 1),
                'sex_verified_at' => rand(0, 1) ? now() : null,
                'first_reminder_sent_at' => rand(0, 1) ? now()->subDays(5) : null,
                'last_reminder_sent_at' => rand(0, 1) ? now()->subDays(2) : null,
                'reminder_count' => rand(0, 3),
                'created_at' => $dateNaissance,
                'updated_at' => now(),
            ];
        }

        Naissance::insert($naissances);

        $this->command->line('   ✓ <fg=green>250 litters created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed baby rabbits (lapereaux)
     */
    private function seedLapereaux(): void
    {
        $this->command->info('🐇 Seeding Baby Rabbits (Lapereaux)...');

        $naissanceIds = \App\Models\Naissance::pluck('id')->toArray();
        $healthStates = ['Excellent', 'Bon', 'Moyen', 'Faible'];
        $etats = ['vivant', 'vendu', 'mort'];

        for ($i = 0; $i < 500; $i++) {
            \App\Models\Lapereau::create([
                'naissance_id' => $naissanceIds[array_rand($naissanceIds)],
                'code' => \App\Models\Lapereau::generateUniqueCode(),
                'nom' => "Lapin #" . ($i + 1),
                'sex' => ['male', 'female'][array_rand(['male', 'female'])],
                'etat' => $etats[array_rand($etats)],
                'poids_naissance' => rand(40, 90),
                'etat_sante' => $healthStates[array_rand($healthStates)],
                'observations' => rand(0, 1) ? Str::random(30) : null,
                'categorie' => ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'][array_rand(['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'])],
                'alimentation_jour' => round(rand(5, 25) / 100, 2),
                'alimentation_semaine' => round(rand(35, 175) / 100, 2),
            ]);

            if (($i + 1) % 100 === 0) {
                $count = $i + 1;
                $this->command->info("   → {$count}/500 lapereaux created...");
            }
        }

        $this->command->info('   ✓ 500 lapereaux created');
    }

    /**
     * Seed sales
     */
    private function seedSales(): void
    {
        $this->command->info('💰 Seeding Sales...');

        $users = User::all();
        $paymentStatus = ['paid', 'pending', 'partial'];
        $types = ['male', 'female', 'lapereau', 'groupe'];

        $sales = [];
        for ($i = 1; $i <= 150; $i++) {
            $quantity = rand(1, 10);
            $totalAmount = $quantity * rand(15000, 35000);
            $paymentStatusValue = $paymentStatus[array_rand($paymentStatus)];

            $sales[] = [
                'date_sale' => now()->subDays(rand(1, 365)),
                'quantity' => $quantity,
                'type' => $types[array_rand($types)],
                'category' => 'Standard',
                'unit_price' => $totalAmount / $quantity,
                'total_amount' => $totalAmount,
                'buyer_name' => "Client " . Str::random(10),
                'buyer_contact' => '+229' . rand(10000000, 99999999),
                'buyer_address' => 'Cotonou, Bénin',
                'notes' => 'Vente normale - ' . Str::random(20),
                'payment_status' => $paymentStatusValue,
                'amount_paid' => $paymentStatusValue === 'paid' ? $totalAmount : ($paymentStatusValue === 'partial' ? $totalAmount * 0.5 : 0),
                'user_id' => $users->random()->id,
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        Sale::insert($sales);

        $this->command->line('   ✓ <fg=green>150 sales created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed subscriptions
     */
    private function seedSubscriptions(): void
    {
        $this->command->info('📝 Seeding Subscriptions...');

        $users = User::all();
        $plans = SubscriptionPlan::all();
        $statuses = ['active', 'expired', 'cancelled', 'pending', 'grace_period'];
        $paymentMethods = ['momo', 'celtis', 'moov', 'manual'];

        $subscriptions = [];
        for ($i = 1; $i <= 100; $i++) {
            $plan = $plans->random();
            $startDate = now()->subMonths(rand(1, 12));
            $endDate = $startDate->copy()->addMonths($plan->duration_months);

            $subscriptions[] = [
                'user_id' => $users->random()->id,
                'subscription_plan_id' => $plan->id,
                'status' => $statuses[array_rand($statuses)],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'cancelled_at' => rand(0, 1) ? now() : null,
                'price' => $plan->price,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'transaction_id' => 'TXN-' . Str::upper(Str::random(12)),
                'payment_reference' => 'REF-' . Str::upper(Str::random(8)),
                'auto_renew' => rand(0, 1),
                'cancellation_reason' => rand(0, 1) ? 'Changement de fournisseur' : null,
                'created_at' => $startDate,
                'updated_at' => now(),
            ];
        }

        Subscription::insert($subscriptions);

        $this->command->line('   ✓ <fg=green>100 subscriptions created</fg=green>');
        $this->command->info('');
    }

    /**
     * Seed notifications
     */
    private function seedNotifications(): void
    {
        $this->command->info('🔔 Seeding Notifications...');

        $users = User::all();
        $types = ['success', 'warning', 'info', 'error'];
        $titles = [
            'Nouvelle Saillie Enregistrée',
            'Mise Bas Enregistrée',
            'Vente Enregistrée',
            'Paiement Reçu',
            'Subscription Expiring Soon',
            'Verification Required',
            'Rappel: Vérification de Portée',
            'Nouveau Lapin Enregistré',
        ];

        $notifications = [];
        for ($i = 1; $i <= 500; $i++) {
            $type = $types[array_rand($types)];
            $notifications[] = [
                'user_id' => $users->random()->id,
                'type' => $type,
                'title' => $titles[array_rand($titles)],
                'message' => "Notification message " . Str::random(50),
                'action_url' => route('dashboard'),
                'icon' => 'bi-bell',
                'is_read' => rand(0, 1),
                'emailed' => rand(0, 1),
                'read_at' => rand(0, 1) ? now() : null,
                'created_at' => now()->subDays(rand(1, 90)),
                'updated_at' => now(),
            ];
        }

        Notification::insert($notifications);

        $this->command->line('   ✓ <fg=green>500 notifications created</fg=green>');
        $this->command->info('');
    }
}
