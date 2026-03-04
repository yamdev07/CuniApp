<?php
// database/seeders/CuniAppSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
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
use App\Models\Notification;
use App\Models\Setting;

class CuniAppSeeder extends Seeder
{
    // ========================================================================
    // CONFIGURATION CONSTANTS
    // ========================================================================
    
    private const TOTAL_MALES = 50;
    private const TOTAL_FEMELLES = 150;
    private const TOTAL_SAILLIES = 200;
    private const TOTAL_MISES_BAS = 180;
    private const TOTAL_NAISSANCES = 180;
    private const TOTAL_LAPEREAUX = 1200;
    private const TOTAL_SALES = 300;
    private const TOTAL_USERS = 10;
    private const TOTAL_NOTIFICATIONS = 500;
    private const TOTAL_SETTINGS = 25;
    
    // Race types for rabbits
    private const RACES = [
        'Californien',
        'Géant des Flandres',
        'Blanc de Vienne',
        'Rex',
        'Nain de Couleur',
        'Bélier Français',
        'Argenté de Champagne',
        'Fauve de Bourgogne',
        'Hermine',
        'Gris de Vienne',
        'Angora',
        'Teddy',
        'Hotot',
        'Polonais',
        'Russe',
    ];
    
    // Female states
    private const FEMELLE_ETATS = ['Active', 'Gestante', 'Allaitante', 'Vide'];
    
    // Male states
    private const MALE_ETATS = ['Active', 'Inactive', 'Malade'];
    
    // Health statuses
    private const HEALTH_STATUSES = ['Excellent', 'Bon', 'Moyen', 'Faible'];
    
    // Rabbit states
    private const LAPIN_ETATS = ['vivant', 'mort', 'vendu'];
    
    // Payment statuses
    private const PAYMENT_STATUSES = ['paid', 'pending', 'partial'];
    
    // Sale types
    private const SALE_TYPES = ['male', 'female', 'lapereau', 'groupe'];
    
    // Notification types
    private const NOTIFICATION_TYPES = ['success', 'warning', 'info', 'error'];
    
    // Buyer names for sales
    private const BUYER_NAMES = [
        'Ferme Lapin d\'Or',
        'Élevage du Val de Loire',
        'Coopérative Agricole Centre',
        'Marché de Gros Paris',
        'Restaurant Le Terroir',
        'Boucherie Dupont',
        'Épicerie Fine Martin',
        'Ferme Bio Nature',
        'Élevage Familial Bernard',
        'Coopérative du Sud-Ouest',
        'Grossiste Alimentaire Lyon',
        'Restaurant Gastronomique',
        'Traiteur Prestige',
        'Marché Local Bordeaux',
        'Élevage Traditionnel',
        'Ferme Pédagogique',
        'Association Agricole',
        'Centre de Formation',
        'École Vétérinaire',
        'Laboratoire de Recherche',
    ];
    
    // Notification titles
    private const NOTIFICATION_TITLES = [
        'Nouvelle Saillie Enregistrée',
        'Mise Bas Enregistrée',
        'Vérification de Portée Requise',
        'Rappel: Vérification en Attente',
        'Nouvelle Vente Enregistrée',
        'Paiement Reçu',
        'Statut de Paiement Mis à Jour',
        'Femelle Modifiée',
        'Mâle Ajouté',
        'Lapereau Enregistré',
        'Naissance & Lapereaux Enregistrés',
        'État de la Femelle Mis à Jour',
        'Palpation Réalisée',
        'Vente Modifiée',
        'Mise Bas Supprimée',
        'Naissance Supprimée',
        'Vente Supprimée',
        'Profil Mis à Jour',
        'Paramètres Enregistrés',
        'Export de Données Généré',
    ];
    
    // Stored data for relationships
    private array $users = [];
    private array $males = [];
    private array $femelles = [];
    private array $saillies = [];
    private array $misesBas = [];
    private array $naissances = [];
    private array $lapereaux = [];
    private array $sales = [];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startTime = microtime(true);
        
        $this->command->info('🐰 Starting CuniApp Élevage Data Seeding...');
        $this->command->info('═══════════════════════════════════════════════════');
        
        // Seed in logical order (respecting foreign keys)
        $this->seedSettings();
        $this->seedUsers();
        $this->seedMales();
        $this->seedFemelles();
        $this->seedSaillies();
        $this->seedMisesBas();
        $this->seedNaissances();
        $this->seedLapereaux();
        $this->seedSales();
        $this->seedNotifications();
        
        // Display summary
        $this->displaySummary();
        
        // Display login credentials
        $this->displayLoginCredentials();
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info("⏱️  Total seeding time: {$duration} seconds");
        $this->command->info('═══════════════════════════════════════════════════');
    }
    
    // ========================================================================
    // SETTINGS SEEDING
    // ========================================================================
    
    /**
     * Seed application settings
     */
    private function seedSettings(): void
    {
        $this->command->info('⚙️  Seeding Settings...');
        
        $settings = [
            // General Settings
            ['key' => 'farm_name', 'value' => 'CuniApp Élevage', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => '123 Route de la Campagne, 75000 Paris', 'type' => 'string', 'group' => 'general', 'label' => 'Adresse'],
            ['key' => 'farm_phone', 'value' => '+33 6 12 34 56 78', 'type' => 'string', 'group' => 'general', 'label' => 'Téléphone'],
            ['key' => 'farm_email', 'value' => 'contact@cuniapp-elevage.fr', 'type' => 'string', 'group' => 'general', 'label' => 'Email'],
            
            // Breeding Settings
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold', 'value' => '80', 'type' => 'number', 'group' => 'breeding', 'label' => 'Seuil d\'alerte (%)'],
            
            // Verification Settings
            ['key' => 'verification_initial_days', 'value' => '10', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai initial de vérification (jours)'],
            ['key' => 'verification_reminder_days', 'value' => '15', 'type' => 'number', 'group' => 'breeding', 'label' => 'Délai premier rappel (jours)'],
            ['key' => 'verification_interval_days', 'value' => '5', 'type' => 'number', 'group' => 'breeding', 'label' => 'Intervalle des rappels (jours)'],
            
            // Notification Settings
            ['key' => 'notifications_email', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notifications par email'],
            ['key' => 'notifications_dashboard', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notifications sur le dashboard'],
            
            // System Settings
            ['key' => 'theme', 'value' => 'system', 'type' => 'string', 'group' => 'system', 'label' => 'Thème de l\'application'],
            ['key' => 'language', 'value' => 'fr', 'type' => 'string', 'group' => 'system', 'label' => 'Langue de l\'application'],
            ['key' => 'timezone', 'value' => 'Europe/Paris', 'type' => 'string', 'group' => 'system', 'label' => 'Fuseau horaire'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'string', 'group' => 'system', 'label' => 'Format de date'],
            ['key' => 'currency', 'value' => 'FCFA', 'type' => 'string', 'group' => 'system', 'label' => 'Devise'],
            ['key' => 'decimal_separator', 'value' => ',', 'type' => 'string', 'group' => 'system', 'label' => 'Séparateur décimal'],
            ['key' => 'thousands_separator', 'value' => ' ', 'type' => 'string', 'group' => 'system', 'label' => 'Séparateur de milliers'],
            
            // Business Settings
            ['key' => 'tax_rate', 'value' => '0', 'type' => 'number', 'group' => 'business', 'label' => 'Taux de taxe (%)'],
            ['key' => 'default_payment_terms', 'value' => '30', 'type' => 'number', 'group' => 'business', 'label' => 'Délai de paiement (jours)'],
            ['key' => 'invoice_prefix', 'value' => 'FACT-', 'type' => 'string', 'group' => 'business', 'label' => 'Préfixe facture'],
            ['key' => 'quote_prefix', 'value' => 'DEVIS-', 'type' => 'string', 'group' => 'business', 'label' => 'Préfixe devis'],
            
            // Inventory Settings
            ['key' => 'low_stock_threshold', 'value' => '10', 'type' => 'number', 'group' => 'inventory', 'label' => 'Seuil d\'alerte stock'],
            ['key' => 'auto_reorder', 'value' => '0', 'type' => 'boolean', 'group' => 'inventory', 'label' => 'Réapprovisionnement automatique'],
            
            // Report Settings
            ['key' => 'report_frequency', 'value' => 'monthly', 'type' => 'string', 'group' => 'reports', 'label' => 'Fréquence des rapports'],
            ['key' => 'auto_backup', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Sauvegarde automatique'],
            ['key' => 'backup_retention_days', 'value' => '90', 'type' => 'number', 'group' => 'reports', 'label' => 'Jours de rétention'],
            
            // Security Settings
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'number', 'group' => 'security', 'label' => 'Timeout de session (minutes)'],
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'number', 'group' => 'security', 'label' => 'Longueur minimale mot de passe'],
            ['key' => 'require_2fa', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'label' => 'Exiger 2FA'],
        ];
        
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        
        $this->command->info('   ✓ Created ' . count($settings) . ' settings');
    }
    
    // ========================================================================
    // USER SEEDING
    // ========================================================================
    
    /**
     * Seed users with different roles
     */
    private function seedUsers(): void
    {
        $this->command->info('👤 Seeding Users...');
        
        // Admin user
        $admin = User::create([
            'name' => 'Administrateur CuniApp',
            'email' => 'admin@cuniapp.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'notifications_email' => true,
            'notifications_dashboard' => true,
            'theme' => 'dark',
            'language' => 'fr',
            'created_at' => now()->subMonths(6),
            'updated_at' => now(),
        ]);
        
        $this->users[] = $admin;
        $this->command->info('   ✓ Created admin user: admin@cuniapp.com');
        
        // Manager user
        $manager = User::create([
            'name' => 'Gérant Élevage',
            'email' => 'manager@cuniapp.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'notifications_email' => true,
            'notifications_dashboard' => true,
            'theme' => 'light',
            'language' => 'fr',
            'created_at' => now()->subMonths(5),
            'updated_at' => now(),
        ]);
        
        $this->users[] = $manager;
        $this->command->info('   ✓ Created manager user: manager@cuniapp.com');
        
        // Regular users
        $userNames = [
            'Jean Dupont',
            'Marie Martin',
            'Pierre Bernard',
            'Sophie Petit',
            'Lucas Moreau',
            'Emma Laurent',
            'Thomas Simon',
            'Léa Michel',
        ];
        
        $userEmails = [
            'jean.dupont@cuniapp.com',
            'marie.martin@cuniapp.com',
            'pierre.bernard@cuniapp.com',
            'sophie.petit@cuniapp.com',
            'lucas.moreau@cuniapp.com',
            'emma.laurent@cuniapp.com',
            'thomas.simon@cuniapp.com',
            'lea.michel@cuniapp.com',
        ];
        
        foreach (range(0, 7) as $index) {
            $user = User::create([
                'name' => $userNames[$index],
                'email' => $userEmails[$index],
                'password' => Hash::make('password123'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'notifications_email' => rand(0, 1) === 1,
                'notifications_dashboard' => rand(0, 1) === 1,
                'theme' => ['system', 'light', 'dark'][rand(0, 2)],
                'language' => ['fr', 'en'][rand(0, 1)],
                'created_at' => now()->subMonths(rand(1, 4)),
                'updated_at' => now(),
            ]);
            
            $this->users[] = $user;
        }
        
        $this->command->info('   ✓ Created ' . count($this->users) . ' users total');
    }
    
    // ========================================================================
    // MALE RABBITS SEEDING
    // ========================================================================
    
    /**
     * Seed male rabbits
     */
    private function seedMales(): void
    {
        $this->command->info('🐰 Seeding Male Rabbits...');
        
        for ($i = 1; $i <= self::TOTAL_MALES; $i++) {
            $code = 'MAL-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $race = self::RACES[array_rand(self::RACES)];
            $origine = ['Interne', 'Achat'][rand(0, 1)];
            $etat = self::MALE_ETATS[array_rand(self::MALE_ETATS)];
            
            // Generate birth date (between 6 months and 3 years ago)
            $daysAgo = rand(180, 1095);
            $dateNaissance = now()->subDays($daysAgo);
            
            $male = Male::create([
                'code' => $code,
                'nom' => $this->generateMaleName($i),
                'race' => $race,
                'origine' => $origine,
                'date_naissance' => $dateNaissance,
                'etat' => $etat,
                'created_at' => now()->subMonths(rand(1, 6)),
                'updated_at' => now(),
            ]);
            
            $this->males[] = $male;
            
            if ($i % 10 === 0) {
                $this->command->info("   ✓ Created {$i} males...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->males) . ' males total');
    }
    
    /**
     * Generate male rabbit name
     */
    private function generateMaleName(int $index): string
    {
        $names = [
            'Max', 'Rocky', 'Thor', 'Zeus', 'Apollo', 'Titan', 'Hercule', 'Sultan',
            'Rex', 'Duke', 'King', 'Prince', 'Boss', 'Chief', 'Master', 'Lord',
            'Felix', 'Oscar', 'Charlie', 'Buddy', 'Cooper', 'Jack', 'Leo', 'Sam',
            'Gaston', 'Marius', 'Victor', 'Arthur', 'Louis', 'Henri', 'Charles',
            'Simba', 'Shadow', 'Storm', 'Blaze', 'Flash', 'Bolt', 'Ace', 'Jet',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    // ========================================================================
    // FEMALE RABBITS SEEDING
    // ========================================================================
    
    /**
     * Seed female rabbits
     */
    private function seedFemelles(): void
    {
        $this->command->info('🐰 Seeding Female Rabbits...');
        
        for ($i = 1; $i <= self::TOTAL_FEMELLES; $i++) {
            $code = 'FEM-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $race = self::RACES[array_rand(self::RACES)];
            $origine = ['Interne', 'Achat'][rand(0, 1)];
            $etat = self::FEMELLE_ETATS[array_rand(self::FEMELLE_ETATS)];
            
            // Generate birth date (between 4 months and 4 years ago)
            $daysAgo = rand(120, 1460);
            $dateNaissance = now()->subDays($daysAgo);
            
            $femelle = Femelle::create([
                'code' => $code,
                'nom' => $this->generateFemelleName($i),
                'race' => $race,
                'origine' => $origine,
                'date_naissance' => $dateNaissance,
                'etat' => $etat,
                'created_at' => now()->subMonths(rand(1, 6)),
                'updated_at' => now(),
            ]);
            
            $this->femelles[] = $femelle;
            
            if ($i % 20 === 0) {
                $this->command->info("   ✓ Created {$i} femelles...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->femelles) . ' femelles total');
    }
    
    /**
     * Generate female rabbit name
     */
    private function generateFemelleName(int $index): string
    {
        $names = [
            'Lily', 'Bella', 'Luna', 'Daisy', 'Rosie', 'Coco', 'Molly', 'Ruby',
            'Emma', 'Olivia', 'Sophie', 'Chloe', 'Grace', 'Rose', 'Pearl', 'Jade',
            'Fleur', 'Rose', 'Violette', 'Marguerite', 'Pâquerette', 'Iris', 'Orchidée',
            'Princesse', 'Reine', 'Duchesse', 'Comtesse', 'Baronne', 'Lady', 'Miss',
            'Nala', 'Kiara', 'Simba', 'Zara', 'Nina', 'Lola', 'Mia', 'Lea',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    // ========================================================================
    // MATING (SAILLIES) SEEDING
    // ========================================================================
    
    /**
     * Seed matings (saillies)
     */
    private function seedSaillies(): void
    {
        $this->command->info('💕 Seeding Saillies (Matings)...');
        
        for ($i = 1; $i <= self::TOTAL_SAILLIES; $i++) {
            // Select random male and female
            $male = $this->males[array_rand($this->males)];
            $femelle = $this->femelles[array_rand($this->femelles)];
            
            // Generate saillie date (between 1 month and 8 months ago)
            $daysAgo = rand(30, 240);
            $dateSaillie = now()->subDays($daysAgo);
            
            // Palpation date (10-15 days after saillie)
            $datePalpage = $dateSaillie->copy()->addDays(rand(10, 15));
            
            // Palpation result
            $palpationResultat = ['+', '-', null][rand(0, 2)];
            
            // Theoretical birth date (31 days after saillie)
            $dateMiseBasTheorique = $dateSaillie->copy()->addDays(31);
            
            $saillie = Saillie::create([
                'femelle_id' => $femelle->id,
                'male_id' => $male->id,
                'date_saillie' => $dateSaillie,
                'date_palpage' => $palpationResultat ? $datePalpage : null,
                'palpation_resultat' => $palpationResultat,
                'date_mise_bas_theorique' => $dateMiseBasTheorique,
                'created_at' => $dateSaillie,
                'updated_at' => now(),
            ]);
            
            $this->saillies[] = $saillie;
            
            if ($i % 25 === 0) {
                $this->command->info("   ✓ Created {$i} saillies...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->saillies) . ' saillies total');
    }
    
    // ========================================================================
    // BIRTH (MISES BAS) SEEDING
    // ========================================================================
    
    /**
     * Seed births (mises bas)
     */
    private function seedMisesBas(): void
    {
        $this->command->info('🥚 Seeding Mises Bas (Births)...');
        
        // Only create mises bas for saillies with positive palpation
        $positiveSaillies = array_filter($this->saillies, fn($s) => $s->palpation_resultat === '+');
        
        $misesBasCount = min(self::TOTAL_MISES_BAS, count($positiveSaillies));
        
        for ($i = 0; $i < $misesBasCount; $i++) {
            $saillie = $positiveSaillies[array_rand($positiveSaillies)];
            $femelle = Femelle::find($saillie->femelle_id);
            
            // Birth date (around theoretical date, +/- 3 days)
            $dateMiseBas = $saillie->date_mise_bas_theorique->copy()->addDays(rand(-3, 3));
            
            // Weaning date (6 weeks after birth)
            $dateSevrage = $dateMiseBas->copy()->addWeeks(6);
            
            // Average weaning weight (0.5-1.5 kg)
            $poidsMoyenSevrage = round(rand(500, 1500) / 1000, 2);
            
            $miseBas = MiseBas::create([
                'femelle_id' => $femelle->id,
                'saillie_id' => $saillie->id,
                'date_mise_bas' => $dateMiseBas,
                'date_sevrage' => $dateSevrage,
                'poids_moyen_sevrage' => $poidsMoyenSevrage,
                'created_at' => $dateMiseBas,
                'updated_at' => now(),
            ]);
            
            $this->misesBas[] = $miseBas;
            
            if (($i + 1) % 20 === 0) {
                $this->command->info("   ✓ Created " . ($i + 1) . " mises bas...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->misesBas) . ' mises bas total');
    }
    
    // ========================================================================
    // LITTER (NAISSANCES) SEEDING
    // ========================================================================
    
    /**
     * Seed litters (naissances)
     */
    private function seedNaissances(): void
    {
        $this->command->info('🐣 Seeding Naissances (Litters)...');
        
        foreach ($this->misesBas as $index => $miseBas) {
            $femelle = $miseBas->femelle;
            $saillie = $miseBas->saillie;
            
            // Health status of the litter
            $etatSante = self::HEALTH_STATUSES[array_rand(self::HEALTH_STATUSES)];
            
            // Average birth weight (40-80 grams)
            $poidsMoyenNaissance = rand(40, 80);
            
            // Expected weaning date
            $dateSevragePrevue = $miseBas->date_mise_bas->copy()->addWeeks(6);
            
            // Expected vaccination date
            $dateVaccinationPrevue = $miseBas->date_mise_bas->copy()->addWeeks(8);
            
            // Sex verification (some verified, some not)
            $sexVerified = rand(0, 1) === 1;
            $sexVerifiedAt = $sexVerified ? $miseBas->date_mise_bas->copy()->addDays(rand(10, 30)) : null;
            
            // Reminder tracking
            $reminderCount = $sexVerified ? 0 : rand(0, 3);
            $firstReminderSentAt = $reminderCount > 0 ? $miseBas->date_mise_bas->copy()->addDays(15) : null;
            $lastReminderSentAt = $reminderCount > 0 ? now()->subDays(rand(1, 10)) : null;
            
            $naissance = Naissance::create([
                'mise_bas_id' => $miseBas->id,
                'poids_moyen_naissance' => $poidsMoyenNaissance,
                'etat_sante' => $etatSante,
                'observations' => $this->generateNaissanceObservations(),
                'date_sevrage_prevue' => $dateSevragePrevue,
                'date_vaccination_prevue' => $dateVaccinationPrevue,
                'sex_verified' => $sexVerified,
                'sex_verified_at' => $sexVerifiedAt,
                'first_reminder_sent_at' => $firstReminderSentAt,
                'last_reminder_sent_at' => $lastReminderSentAt,
                'reminder_count' => $reminderCount,
                'is_archived' => rand(0, 10) === 1, // 10% archived
                'archived_at' => rand(0, 10) === 1 ? now() : null,
                'created_at' => $miseBas->date_mise_bas,
                'updated_at' => now(),
            ]);
            
            $this->naissances[] = $naissance;
            
            if (($index + 1) % 20 === 0) {
                $this->command->info("   ✓ Created " . ($index + 1) . " naissances...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->naissances) . ' naissances total');
    }
    
    /**
     * Generate observations for naissance
     */
    private function generateNaissanceObservations(): ?string
    {
        $observations = [
            null,
            'Portée en bonne santé',
            'Quelques lapereaux faibles',
            'Mère très attentive',
            'Surveillance recommandée',
            'Développement normal',
            'Poids dans la moyenne',
            'Aucune anomalie détectée',
            'Suivi vétérinaire conseillé',
            'Portée particulièrement vigoureuse',
        ];
        
        return $observations[array_rand($observations)];
    }
    
    // ========================================================================
    // BABY RABBITS (LAPEREAUX) SEEDING
    // ========================================================================
    
    /**
     * Seed baby rabbits (lapereaux)
     */
    private function seedLapereaux(): void
    {
        $this->command->info('🐇 Seeding Lapereaux (Baby Rabbits)...');
        
        $lapereauCount = 0;
        $targetLapereaux = self::TOTAL_LAPEREAUX;
        
        foreach ($this->naissances as $index => $naissance) {
            if ($lapereauCount >= $targetLapereaux) {
                break;
            }
            
            // Number of rabbits per litter (4-10)
            $nbLapereaux = min(rand(4, 10), $targetLapereaux - $lapereauCount);
            
            for ($j = 0; $j < $nbLapereaux; $j++) {
                $lapereauCount++;
                
                // Generate unique code
                $code = Lapereau::generateUniqueCode();
                
                // Sex (if verified, otherwise null)
                $sex = $naissance->sex_verified ? ['male', 'female'][rand(0, 1)] : null;
                
                // State
                $etat = self::LAPIN_ETATS[array_rand(self::LAPIN_ETATS)];
                
                // Birth weight (35-90 grams)
                $poidsNaissance = rand(35, 90);
                
                // Health status
                $etatSante = self::HEALTH_STATUSES[array_rand(self::HEALTH_STATUSES)];
                
                // Observations
                $observations = $this->generateLapereauObservations();
                
                $lapereau = Lapereau::create([
                    'naissance_id' => $naissance->id,
                    'code' => $code,
                    'nom' => $this->generateLapereauName($lapereauCount),
                    'sex' => $sex,
                    'etat' => $etat,
                    'poids_naissance' => $poidsNaissance,
                    'etat_sante' => $etatSante,
                    'observations' => $observations,
                    'categorie' => $this->getCategorie($naissance->date_naissance),
                    'alimentation_jour' => round(rand(50, 150) / 100, 2),
                    'alimentation_semaine' => round(rand(350, 1050) / 100, 2),
                    'created_at' => $naissance->created_at,
                    'updated_at' => now(),
                ]);
                
                $this->lapereaux[] = $lapereau;
            }
            
            if (($index + 1) % 20 === 0) {
                $this->command->info("   ✓ Created {$lapereauCount} lapereaux...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->lapereaux) . ' lapereaux total');
    }
    
    /**
     * Generate lapereau name
     */
    private function generateLapereauName(int $index): string
    {
        $names = [
            'Toto', 'Titi', 'Tutu', 'Coco', 'Lolo', 'Mimi', 'Kiki', 'Doudou',
            'Bibi', 'Gigi', 'Fifi', 'Riri', 'Zizi', 'Nunu', 'Pipi', 'Qiqi',
            'Bunny', 'Fluffy', 'Snowball', 'Cotton', 'Puff', 'Hoppy', 'Thumper',
            'Peter', 'Benjamin', 'Flopsy', 'Mopsy', 'Cottontail', 'Velvet', 'Silky',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    /**
     * Generate lapereau observations
     */
    private function generateLapereauObservations(): ?string
    {
        $observations = [
            null,
            'En bonne santé',
            'Vigoureux',
            'Poids normal',
            'Développement bon',
            'Surveillance nécessaire',
            'Alimentation normale',
            'Comportement actif',
            null,
            null,
        ];
        
        return $observations[array_rand($observations)];
    }
    
    /**
     * Get category based on age
     */
    private function getCategorie(?Carbon $dateNaissance): ?string
    {
        if (!$dateNaissance) {
            return null;
        }
        
        $ageSemaines = floor($dateNaissance->diffInDays(now()) / 7);
        
        if ($ageSemaines < 5) {
            return '<5 semaines';
        } elseif ($ageSemaines < 8) {
            return '5-8 semaines';
        } elseif ($ageSemaines < 12) {
            return '8-12 semaines';
        } else {
            return '+12 semaines';
        }
    }
    
    // ========================================================================
    // SALES SEEDING
    // ========================================================================
    
    /**
     * Seed sales records
     */
    private function seedSales(): void
    {
        $this->command->info('💰 Seeding Sales...');
        
        for ($i = 1; $i <= self::TOTAL_SALES; $i++) {
            // Select random user
            $user = $this->users[array_rand($this->users)];
            
            // Sale type
            $type = self::SALE_TYPES[array_rand(self::SALE_TYPES)];
            
            // Category
            $categories = ['5-8 semaines', '8-12 semaines', '+12 semaines', 'reproducteur', 'compagnie'];
            $category = $categories[array_rand($categories)];
            
            // Quantity (1-20)
            $quantity = rand(1, 20);
            
            // Unit price (5000-50000 FCFA)
            $unitPrice = rand(5000, 50000);
            
            // Total amount
            $totalAmount = $quantity * $unitPrice;
            
            // Buyer info
            $buyerName = self::BUYER_NAMES[array_rand(self::BUYER_NAMES)] . ' ' . rand(1, 100);
            $buyerContact = '+33 6 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
            $buyerAddress = rand(1, 500) . ' Rue de la République, 7500' . rand(1, 9) . ' Paris';
            
            // Payment status
            $paymentStatus = self::PAYMENT_STATUSES[array_rand(self::PAYMENT_STATUSES)];
            
            // Amount paid
            if ($paymentStatus === 'paid') {
                $amountPaid = $totalAmount;
            } elseif ($paymentStatus === 'partial') {
                $amountPaid = rand(1000, $totalAmount - 1000);
            } else {
                $amountPaid = 0;
            }
            
            // Sale date (between 1 day and 6 months ago)
            $dateSale = now()->subDays(rand(1, 180));
            
            // Notes
            $notes = $this->generateSaleNotes();
            
            $sale = Sale::create([
                'user_id' => $user->id,
                'date_sale' => $dateSale,
                'quantity' => $quantity,
                'type' => $type,
                'category' => $category,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'buyer_name' => $buyerName,
                'buyer_contact' => $buyerContact,
                'buyer_address' => $buyerAddress,
                'notes' => $notes,
                'payment_status' => $paymentStatus,
                'amount_paid' => $amountPaid,
                'created_at' => $dateSale,
                'updated_at' => now(),
            ]);
            
            $this->sales[] = $sale;
            
            if ($i % 30 === 0) {
                $this->command->info("   ✓ Created {$i} sales...");
            }
        }
        
        $this->command->info('   ✓ Created ' . count($this->sales) . ' sales total');
    }
    
    /**
     * Generate sale notes
     */
    private function generateSaleNotes(): ?string
    {
        $notes = [
            null,
            'Paiement effectué en espèces',
            'Livraison à domicile prévue',
            'Client fidèle, remise accordée',
            'Première commande',
            'Paiement en plusieurs fois',
            'Facture envoyée par email',
            'Remise de 10% appliquée',
            'Livraison gratuite',
            'Garantie 30 jours',
            null,
            null,
        ];
        
        return $notes[array_rand($notes)];
    }
    
    // ========================================================================
    // NOTIFICATIONS SEEDING
    // ========================================================================
    
    /**
     * Seed notifications
     */
    private function seedNotifications(): void
    {
        $this->command->info('🔔 Seeding Notifications...');
        
        for ($i = 1; $i <= self::TOTAL_NOTIFICATIONS; $i++) {
            // Select random user
            $user = $this->users[array_rand($this->users)];
            
            // Notification type
            $type = self::NOTIFICATION_TYPES[array_rand(self::NOTIFICATION_TYPES)];
            
            // Title and message
            $title = self::NOTIFICATION_TITLES[array_rand(self::NOTIFICATION_TITLES)];
            $message = $this->generateNotificationMessage($title);
            
            // Action URL
            $actionUrls = [
                route('dashboard'),
                route('males.index'),
                route('femelles.index'),
                route('saillies.index'),
                route('mises-bas.index'),
                route('naissances.index'),
                route('sales.index'),
                route('notifications.index'),
            ];
            $actionUrl = $actionUrls[array_rand($actionUrls)];
            
            // Icon based on type
            $icons = [
                'success' => 'bi-check-circle-fill',
                'warning' => 'bi-exclamation-triangle-fill',
                'error' => 'bi-x-circle-fill',
                'info' => 'bi-info-circle-fill',
            ];
            $icon = $icons[$type];
            
            // Read status
            $isRead = rand(0, 1) === 1;
            $readAt = $isRead ? now()->subDays(rand(1, 30)) : null;
            
            // Emailed status
            $emailed = rand(0, 1) === 1;
            
            // Created at (between 1 day and 3 months ago)
            $createdAt = now()->subDays(rand(1, 90));
            
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'action_url' => $actionUrl,
                'icon' => $icon,
                'is_read' => $isRead,
                'emailed' => $emailed,
                'read_at' => $readAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            if ($i % 50 === 0) {
                $this->command->info("   ✓ Created {$i} notifications...");
            }
        }
        
        $this->command->info('   ✓ Created ' . self::TOTAL_NOTIFICATIONS . ' notifications total');
    }
    
    /**
     * Generate notification message
     */
    private function generateNotificationMessage(string $title): string
    {
        $messages = [
            'Une nouvelle action nécessite votre attention.',
            'Mise à jour effectuée avec succès.',
            'Veuillez vérifier les informations.',
            'Nouvelle donnée enregistrée dans le système.',
            'Rappel: Action en attente de validation.',
            'Information importante concernant votre élevage.',
            'Document généré et disponible.',
            'Statistiques mises à jour.',
            'Configuration modifiée.',
            'Rapport disponible pour consultation.',
        ];
        
        return $messages[array_rand($messages)];
    }
    
    // ========================================================================
    // SUMMARY DISPLAY
    // ========================================================================
    
    /**
     * Display seeding summary
     */
    private function displaySummary(): void
    {
        $this->command->info('');
        $this->command->info('📊 SEEDING SUMMARY');
        $this->command->info('═══════════════════════════════════════════════════');
        
        $summary = [
            '👤 Users' => count($this->users),
            '🐰 Males' => count($this->males),
            '🐰 Femelles' => count($this->femelles),
            '💕 Saillies' => count($this->saillies),
            '🥚 Mises Bas' => count($this->misesBas),
            '🐣 Naissances' => count($this->naissances),
            '🐇 Lapereaux' => count($this->lapereaux),
            '💰 Sales' => count($this->sales),
            '🔔 Notifications' => self::TOTAL_NOTIFICATIONS,
            '⚙️  Settings' => self::TOTAL_SETTINGS,
        ];
        
        foreach ($summary as $label => $count) {
            $this->command->info(sprintf('   %-20s %s', $label, str_pad($count, 5, ' ', STR_PAD_LEFT)));
        }
        
        $this->command->info('═══════════════════════════════════════════════════');
        
        // Calculate totals
        $totalRabbits = count($this->males) + count($this->femelles) + count($this->lapereaux);
        $totalRevenue = Sale::sum('total_amount');
        $totalPaid = Sale::sum('amount_paid');
        $pendingPayments = $totalRevenue - $totalPaid;
        
        $this->command->info('');
        $this->command->info('💰 FINANCIAL SUMMARY');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info(sprintf('   %-20s %s', 'Total Rabbits:', number_format($totalRabbits)));
        $this->command->info(sprintf('   %-20s %s FCFA', 'Total Revenue:', number_format($totalRevenue, 0, ',', ' ')));
        $this->command->info(sprintf('   %-20s %s FCFA', 'Total Paid:', number_format($totalPaid, 0, ',', ' ')));
        $this->command->info(sprintf('   %-20s %s FCFA', 'Pending Payments:', number_format($pendingPayments, 0, ',', ' ')));
        $this->command->info('═══════════════════════════════════════════════════');
    }
    
    // ========================================================================
    // LOGIN CREDENTIALS DISPLAY
    // ========================================================================
    
    /**
     * Display login credentials for created accounts
     */
    private function displayLoginCredentials(): void
    {
        $this->command->info('');
        $this->command->info('🔐 LOGIN CREDENTIALS');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('⚠️  IMPORTANT: Save these credentials securely!');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('');
        
        // Admin account
        $this->command->info('👑 ADMINISTRATOR ACCOUNT:');
        $this->command->info('   Email:    admin@cuniapp.com');
        $this->command->info('   Password: password123');
        $this->command->info('   URL:      http://localhost:8000/welcome');
        $this->command->info('');
        
        // Manager account
        $this->command->info('📋 MANAGER ACCOUNT:');
        $this->command->info('   Email:    manager@cuniapp.com');
        $this->command->info('   Password: password123');
        $this->command->info('   URL:      http://localhost:8000/welcome');
        $this->command->info('');
        
        // Regular users
        $this->command->info('👥 REGULAR USER ACCOUNTS:');
        $this->command->info('   Password: password123 (for all users)');
        $this->command->info('   URL:      http://localhost:8000/welcome');
        $this->command->info('');
        
        $regularUsers = array_slice($this->users, 2); // Skip admin and manager
        foreach ($regularUsers as $index => $user) {
            $this->command->info(sprintf('   %2d. %-30s %s', 
                $index + 1, 
                $user->email, 
                '(' . $user->name . ')'
            ));
        }
        
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ All accounts use the same password: password123');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}