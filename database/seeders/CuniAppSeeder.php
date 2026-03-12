<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
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
use Carbon\Carbon;

class CuniappSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSettings();
        $this->seedSubscriptionPlans();
        $this->seedUsers();
        $this->seedBreedingData();
        $this->seedSalesData();
        $this->displayCredentials();
    }

    /**
     * Seed application settings
     */
    private function seedSettings(): void
    {
        $this->command->info('⚙️  Seeding Settings...');
        
        $settings = [
            // Farm Info
            ['key' => 'farm_name', 'value' => 'Ferme CuniApp Test', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => 'Houéyiho après le pont devant Volta United, Cotonou, Bénin', 'type' => 'string', 'group' => 'general', 'label' => 'Adresse'],
            ['key' => 'farm_phone', 'value' => '+2290152415241', 'type' => 'string', 'group' => 'general', 'label' => 'Téléphone'],
            ['key' => 'farm_email', 'value' => 'contact@anyxtech.com', 'type' => 'string', 'group' => 'general', 'label' => 'Email'],
            
            // Breeding Settings
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold', 'value' => '80', 'type' => 'number', 'group' => 'breeding', 'label' => "Seuil d'alerte (%)"],
            
            // Verification Settings
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai initial de vérification (jours)'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai premier rappel (jours)'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding', 'label' => 'Intervalle des rappels (jours)'],
            
            // Default Prices
            ['key' => 'default_price_male', 'value' => '25000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Mâles'],
            ['key' => 'default_price_female', 'value' => '30000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Femelles'],
            ['key' => 'default_price_lapereau', 'value' => '15000', 'type' => 'number', 'group' => 'sales', 'label' => 'Prix par défaut - Lapereaux'],
            
            // FedaPay Settings
            ['key' => 'fedapay_public_key', 'value' => 'pk_sandbox_ueJp-OoTG2G0wIc1bxKX4GOz', 'type' => 'string', 'group' => 'payments', 'label' => 'Clé Publique FedaPay'],
            ['key' => 'fedapay_secret_key', 'value' => 'sk_sandbox_nSuimlDFVbiDjTFJMrF5xXop', 'type' => 'string', 'group' => 'payments', 'label' => 'Clé Secrète FedaPay'],
            ['key' => 'fedapay_environment', 'value' => 'sandbox', 'type' => 'string', 'group' => 'payments', 'label' => 'Environnement FedaPay'],
            ['key' => 'fedapay_webhook_secret', 'value' => 'wh_sandbox_di5NWZ7Ggk3DtVibooKFeiKz', 'type' => 'string', 'group' => 'payments', 'label' => 'Secret Webhook FedaPay'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'label' => $setting['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('   ✓ Settings seeded');
    }

    /**
     * Seed subscription plans (from todo.md)
     */
    private function seedSubscriptionPlans(): void
    {
        $this->command->info('💳 Seeding Subscription Plans...');
        
        $plans = [
            [
                'name' => 'Mensuel',
                'duration_months' => 1,
                'price' => 2500,
                'is_active' => true,
                'description' => 'Abonnement mensuel de base',
                'features' => json_encode([
                    'Accès complet à toutes les fonctionnalités',
                    'Support par email',
                    'Sauvegarde automatique',
                    'Mises à jour régulières'
                ]),
            ],
            [
                'name' => 'Trimestriel',
                'duration_months' => 3,
                'price' => 7500,
                'is_active' => true,
                'description' => 'Économisez 15% avec l\'abonnement trimestriel',
                'features' => json_encode([
                    'Accès complet à toutes les fonctionnalités',
                    'Support prioritaire',
                    'Sauvegarde automatique',
                    'Mises à jour régulières',
                    'Rapports avancés'
                ]),
            ],
            [
                'name' => 'Semestriel',
                'duration_months' => 6,
                'price' => 15000,
                'is_active' => true,
                'description' => 'Économisez 25% avec l\'abonnement semestriel',
                'features' => json_encode([
                    'Accès complet à toutes les fonctionnalités',
                    'Support prioritaire 24/7',
                    'Sauvegarde automatique',
                    'Mises à jour régulières',
                    'Rapports avancés',
                    'Formation en ligne'
                ]),
            ],
            [
                'name' => 'Annuel',
                'duration_months' => 12,
                'price' => 30000,
                'is_active' => true,
                'description' => 'Meilleure offre - Économisez 50%!',
                'features' => json_encode([
                    'Accès complet à toutes les fonctionnalités',
                    'Support prioritaire 24/7',
                    'Sauvegarde automatique',
                    'Mises à jour régulières',
                    'Rapports avancés',
                    'Formation en ligne',
                    'Consultation gratuite',
                    'API Access'
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('subscription_plans')->updateOrInsert(
                ['name' => $plan['name']],
                array_merge($plan, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('   ✓ 4 Subscription Plans seeded');
    }

    /**
     * Seed users with subscriptions
     */
    private function seedUsers(): void
    {
        $this->command->info('👥 Seeding Users...');
        
        $plans = SubscriptionPlan::all();
        
        // 1. Admin Account
        $admin = User::create([
            'name' => 'Administrateur CuniApp',
            'email' => 'admin@cuniapp.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addYear(),
            'theme' => 'dark',
            'language' => 'fr',
            'notifications_email' => true,
            'notifications_dashboard' => true,
        ]);

        // Admin subscription (free - manual)
        Subscription::create([
            'user_id' => $admin->id,
            'subscription_plan_id' => $plans[3]->id, // Annual plan
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'price' => 0,
            'payment_method' => 'manual',
            'payment_reference' => 'ADMIN-FREE',
            'auto_renew' => true,
        ]);

        $this->command->info('   ✓ Admin account created');

        // 2-6. Five Normal Users with Different Subscriptions
        $userSubscriptions = [
            ['name' => 'Test User 1', 'email' => 'user1@cuniapp.com', 'plan_index' => 0, 'plan_name' => 'Mensuel'],
            ['name' => 'Test User 2', 'email' => 'user2@cuniapp.com', 'plan_index' => 1, 'plan_name' => 'Trimestriel'],
            ['name' => 'Test User 3', 'email' => 'user3@cuniapp.com', 'plan_index' => 2, 'plan_name' => 'Semestriel'],
            ['name' => 'Test User 4', 'email' => 'user4@cuniapp.com', 'plan_index' => 3, 'plan_name' => 'Annuel'],
            ['name' => 'Test User 5', 'email' => 'user5@cuniapp.com', 'plan_index' => 0, 'plan_name' => 'Mensuel (Expiré)'],
        ];

        foreach ($userSubscriptions as $index => $userData) {
            $plan = $plans[$userData['plan_index']];
            
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'user',
                'subscription_status' => $index === 4 ? 'expired' : 'active',
                'subscription_ends_at' => $index === 4 ? now()->subDays(5) : now()->addMonths($plan->duration_months),
                'theme' => 'system',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
            ]);

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => $index === 4 ? 'expired' : 'active',
                'start_date' => $index === 4 ? now()->subMonths($plan->duration_months + 1) : now(),
                'end_date' => $user->subscription_ends_at,
                'price' => $plan->price,
                'payment_method' => 'momo',
                'payment_reference' => 'TEST-' . strtoupper(uniqid()),
                'auto_renew' => $index !== 4,
            ]);

            // Create payment transaction
            PaymentTransaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $plan->price,
                'payment_method' => 'momo',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => $index === 4 ? 'completed' : 'completed',
                'provider' => 'fedapay',
                'phone_number' => '+22901' . rand(10000000, 99999999),
                'paid_at' => now(),
            ]);

            $this->command->info("   ✓ User {$index + 1}: {$userData['email']} ({$userData['plan_name']})");
        }

        $this->command->info('   ✓ 5 Normal user accounts created');
    }

    /**
     * Seed breeding data (males, femelles, saillies, mises_bas, naissances, lapereaux)
     */
    private function seedBreedingData(): void
    {
        $this->command->info('🐰 Seeding Breeding Data...');
        
        $admin = User::where('role', 'admin')->first();
        $userId = $admin ? $admin->id : 1;

        // Seed Males (10 males)
        $males = [];
        $maleNames = ['Titan', 'Max', 'Rocky', 'Zeus', 'Apollo', 'Thor', 'Hercule', 'Sultan', 'Prince', 'Duke'];
        $races = ['Géant des Flandres', 'Californien', 'Blanc de Vienne', 'Rex', 'Nain'];
        
        for ($i = 0; $i < 10; $i++) {
            $male = Male::create([
                'user_id' => $userId,
                'code' => 'MAL-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nom' => $maleNames[$i],
                'race' => $races[array_rand($races)],
                'origine' => rand(0, 1) ? 'Interne' : 'Achat',
                'date_naissance' => now()->subMonths(rand(6, 24)),
                'etat' => rand(0, 10) > 8 ? 'Inactive' : 'Active',
            ]);
            $males[] = $male;
        }
        $this->command->info('   ✓ 10 Males seeded');

        // Seed Femelles (15 femelles)
        $femelles = [];
        $femelleNames = ['Luna', 'Bella', 'Daisy', 'Rosie', 'Coco', 'Mina', 'Lola', 'Nina', 'Zoe', 'Ruby', 'Lily', 'Mia', 'Olivia', 'Emma', 'Chloé'];
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];
        
        for ($i = 0; $i < 15; $i++) {
            $femelle = Femelle::create([
                'user_id' => $userId,
                'code' => 'FEM-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'nom' => $femelleNames[$i],
                'race' => $races[array_rand($races)],
                'origine' => rand(0, 1) ? 'Interne' : 'Achat',
                'date_naissance' => now()->subMonths(rand(6, 24)),
                'etat' => $etats[array_rand($etats)],
            ]);
            $femelles[] = $femelle;
        }
        $this->command->info('   ✓ 15 Femelles seeded');

        // Seed Saillies (20 saillies)
        $saillies = [];
        for ($i = 0; $i < 20; $i++) {
            $dateSaillie = now()->subDays(rand(35, 120));
            $saillie = Saillie::create([
                'user_id' => $userId,
                'femelle_id' => $femelles[array_rand($femelles)]->id,
                'male_id' => $males[array_rand($males)]->id,
                'date_saillie' => $dateSaillie,
                'date_palpage' => rand(0, 1) ? $dateSaillie->addDays(rand(10, 15)) : null,
                'palpation_resultat' => rand(0, 10) > 2 ? '+' : '-',
                'date_mise_bas_theorique' => $dateSaillie->addDays(31),
            ]);
            $saillies[] = $saillie;
        }
        $this->command->info('   ✓ 20 Saillies seeded');

        // Seed Mises Bas (15 mises bas)
        $misesBas = [];
        for ($i = 0; $i < 15; $i++) {
            $femelle = $femelles[array_rand($femelles)];
            $dateMiseBas = now()->subDays(rand(10, 90));
            
            $miseBas = MiseBas::create([
                'user_id' => $userId,
                'femelle_id' => $femelle->id,
                'saillie_id' => rand(0, 1) ? $saillies[array_rand($saillies)]->id : null,
                'date_mise_bas' => $dateMiseBas,
                'date_sevrage' => $dateMiseBas->addWeeks(6),
                'poids_moyen_sevrage' => rand(500, 900) / 1000,
            ]);
            $misesBas[] = $miseBas;
        }
        $this->command->info('   ✓ 15 Mises Bas seeded');

        // Seed Naissances & Lapereaux (20 naissances, ~120 lapereaux)
        $totalLapereaux = 0;
        for ($i = 0; $i < 20; $i++) {
            $miseBas = $misesBas[array_rand($misesBas)];
            $nbVivant = rand(4, 10);
            $nbMortNe = rand(0, 2);
            
            $naissance = Naissance::create([
                'user_id' => $userId,
                'mise_bas_id' => $miseBas->id,
                'poids_moyen_naissance' => rand(50, 80),
                'etat_sante' => ['Excellent', 'Bon', 'Moyen', 'Faible'][array_rand(['Excellent', 'Bon', 'Moyen', 'Faible'])],
                'observations' => rand(0, 1) ? 'Portée en bonne santé' : null,
                'date_sevrage_prevue' => $miseBas->date_mise_bas->addWeeks(6),
                'sex_verified' => rand(0, 10) > 3,
                'sex_verified_at' => rand(0, 10) > 3 ? now() : null,
                'first_reminder_sent_at' => rand(0, 10) > 5 ? now()->subDays(5) : null,
                'last_reminder_sent_at' => rand(0, 10) > 7 ? now()->subDays(2) : null,
                'reminder_count' => rand(0, 3),
            ]);

            // Create lapereaux for this naissance
            for ($j = 0; $j < $nbVivant + $nbMortNe; $j++) {
                Lapereau::create([
                    'user_id' => $userId,
                    'naissance_id' => $naissance->id,
                    'code' => Lapereau::generateUniqueCode(),
                    'nom' => rand(0, 1) ? null : 'Lapereau ' . ($i + 1) . '-' . ($j + 1),
                    'sex' => rand(0, 10) > 3 ? (rand(0, 1) ? 'male' : 'female') : null,
                    'etat' => $j < $nbMortNe ? 'mort' : 'vivant',
                    'poids_naissance' => rand(50, 80),
                    'etat_sante' => ['Excellent', 'Bon', 'Moyen', 'Faible'][array_rand(['Excellent', 'Bon', 'Moyen', 'Faible'])],
                    'observations' => null,
                ]);
                $totalLapereaux++;
            }
        }
        $this->command->info("   ✓ 20 Naissances seeded ({$totalLapereaux} lapereaux)");
    }

    /**
     * Seed sales data
     */
    private function seedSalesData(): void
    {
        $this->command->info('💰 Seeding Sales Data...');
        
        $admin = User::where('role', 'admin')->first();
        $userId = $admin ? $admin->id : 1;

        // Get some lapereaux to sell
        $lapereaux = Lapereau::where('etat', 'vivant')->limit(15)->get();
        $males = Male::where('etat', 'Active')->limit(5)->get();
        $femelles = Femelle::where('etat', 'Active')->limit(5)->get();

        $buyerNames = ['M. Koffi', 'Mme. Adjara', 'M. Dossou', 'Mme. Mensah', 'M. Agbani', 'Mme. Hounkpe', 'M. Sossou', 'Mme. Gbédji'];
        
        // Create 10 sales
        for ($i = 0; $i < 10; $i++) {
            $totalAmount = 0;
            $quantity = 0;
            
            $sale = Sale::create([
                'user_id' => $userId,
                'date_sale' => now()->subDays(rand(1, 60)),
                'quantity' => rand(1, 5),
                'type' => ['male', 'female', 'lapereau', 'groupe'][array_rand(['male', 'female', 'lapereau', 'groupe'])],
                'buyer_name' => $buyerNames[array_rand($buyerNames)],
                'buyer_contact' => '+2290' . rand(10000000, 99999999),
                'buyer_address' => rand(0, 1) ? 'Cotonou, Bénin' : null,
                'notes' => rand(0, 1) ? 'Client fidèle' : null,
                'payment_status' => ['paid', 'paid', 'paid', 'pending', 'partial'][array_rand(['paid', 'paid', 'paid', 'pending', 'partial'])],
                'total_amount' => 0, // Will be updated
                'amount_paid' => 0, // Will be updated
            ]);

            // Add sale rabbits
            $rabbitType = rand(0, 2);
            if ($rabbitType === 0 && $lapereaux->count() > 0) {
                $rabbit = $lapereaux->random();
                $price = 15000;
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'lapereau',
                    'rabbit_id' => $rabbit->id,
                    'sale_price' => $price,
                ]);
                $totalAmount += $price;
                $quantity++;
            } elseif ($rabbitType === 1 && $males->count() > 0) {
                $rabbit = $males->random();
                $price = 25000;
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $rabbit->id,
                    'sale_price' => $price,
                ]);
                $totalAmount += $price;
                $quantity++;
            } elseif ($rabbitType === 2 && $femelles->count() > 0) {
                $rabbit = $femelles->random();
                $price = 30000;
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $rabbit->id,
                    'sale_price' => $price,
                ]);
                $totalAmount += $price;
                $quantity++;
            }

            // Update sale with totals
            $sale->update([
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'amount_paid' => $sale->payment_status === 'paid' ? $totalAmount : ($sale->payment_status === 'partial' ? $totalAmount * 0.5 : 0),
            ]);
        }

        $this->command->info('   ✓ 10 Sales seeded');
    }

    /**
     * Display all login credentials in a pretty console format
     */
    private function displayCredentials(): void
    {
        $this->command->newLine();
        $this->command->info('╔════════════════════════════════════════════════════════════════╗');
        $this->command->info('║          🎉 CUNIAPP ÉLEVAGE - TEST ACCOUNTS READY! 🎉          ║');
        $this->command->info('╚════════════════════════════════════════════════════════════════╝');
        $this->command->newLine();

        $this->command->warn('┌─────────────────────────────────────────────────────────────────┐');
        $this->command->warn('│  👑 ADMINISTRATEUR ACCOUNT                                     │');
        $this->command->warn('└─────────────────────────────────────────────────────────────────┘');
        $this->command->table(
            ['Email', 'Mot de passe', 'Rôle', 'Abonnement'],
            [
                ['admin@cuniapp.com', 'password123', 'Admin', 'Annuel (Gratuit)'],
            ]
        );

        $this->command->newLine();
        $this->command->warn('┌─────────────────────────────────────────────────────────────────┐');
        $this->command->warn('│  👥 NORMAL USER ACCOUNTS                                       │');
        $this->command->warn('└─────────────────────────────────────────────────────────────────┘');
        $this->command->table(
            ['Email', 'Mot de passe', 'Abonnement', 'Statut'],
            [
                ['user1@cuniapp.com', 'password123', 'Mensuel (2,500 FCFA)', 'Actif'],
                ['user2@cuniapp.com', 'password123', 'Trimestriel (7,500 FCFA)', 'Actif'],
                ['user3@cuniapp.com', 'password123', 'Semestriel (15,000 FCFA)', 'Actif'],
                ['user4@cuniapp.com', 'password123', 'Annuel (30,000 FCFA)', 'Actif'],
                ['user5@cuniapp.com', 'password123', 'Mensuel (2,500 FCFA)', 'Expiré'],
            ]
        );

        $this->command->newLine();
        $this->command->info('┌─────────────────────────────────────────────────────────────────┐');
        $this->command->info('│  📊 DATABASE SUMMARY                                          │');
        $this->command->info('└─────────────────────────────────────────────────────────────────┘');
        $this->command->table(
            ['Table', 'Records'],
            [
                ['Utilisateurs', User::count()],
                ['Plans d\'abonnement', SubscriptionPlan::count()],
                ['Abonnements actifs', Subscription::where('status', 'active')->count()],
                ['Mâles', Male::count()],
                ['Femelles', Femelle::count()],
                ['Saillies', Saillie::count()],
                ['Mises Bas', MiseBas::count()],
                ['Naissances', Naissance::count()],
                ['Lapereaux', Lapereau::count()],
                ['Ventes', Sale::count()],
            ]
        );

        $this->command->newLine();
        $this->command->info('🔗 Login URL: ' . config('app.url') . '/welcome');
        $this->command->newLine();
        $this->command->comment('💡 Tip: All accounts use the same password: password123');
        $this->command->newLine();
    }
}