<?php
// database/seeders/CuniAppSeeder.php
// ============================================================================
// CuniApp Élevage - Comprehensive Database Seeder
// ============================================================================
// This seeder populates ALL tables with logically connected, realistic data
// for testing and development purposes.
//
// Tables seeded:
// - users (10 accounts with different roles)
// - settings (31 application configuration entries)
// - males (50 male rabbits with varied attributes)
// - femelles (150 female rabbits with reproduction states)
// - saillies (200 mating records with palpation tracking)
// - mises_bas (77 birth events linked to positive saillies)
// - naissances (77 litter records with verification tracking) ⭐ FIXED: user_id
// - lapereaux (1200+ baby rabbits with individual health data)
// - sales (300 sales records with payment tracking)
// - notifications (500 user activity notifications)
//
// All data is logically connected:
// Males/Femelles → Saillies → MisesBas → Naissances → Lapereaux → Sales
//
// LOGIN CREDENTIALS ARE CONSOLED AT THE END!
// ============================================================================

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

// ============================================================================
// MAIN SEEDER CLASS - 2000+ LINES OF COMPREHENSIVE SEEDING LOGIC
// ============================================================================
class CuniAppSeeder extends Seeder
{
    // =========================================================================
    // SECTION 1: CONFIGURATION CONSTANTS & DATA ARRAYS
    // =========================================================================
    
    /**
     * Target counts for each entity type
     * These values ensure a realistic, testable dataset
     */
    private const TOTAL_MALES = 50;
    private const TOTAL_FEMELLES = 150;
    private const TOTAL_SAILLIES = 200;
    private const TOTAL_MISES_BAS = 180;
    private const TOTAL_NAISSANCES = 180;
    private const TOTAL_LAPEREAUX = 1200;
    private const TOTAL_SALES = 300;
    private const TOTAL_USERS = 10;
    private const TOTAL_NOTIFICATIONS = 500;
    private const TOTAL_SETTINGS = 31;
    
    /**
     * Available rabbit breeds for realistic data generation
     * Includes popular French and international breeds
     */
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
        'Castorrex',
        'Bleu de Vienne',
        'Lièvre Belge',
        'Papillon',
        'Satin',
    ];
    
    /**
     * Possible states for female rabbits
     * Used in reproduction cycle tracking
     */
    private const FEMELLE_ETATS = ['Active', 'Gestante', 'Allaitante', 'Vide'];
    
    /**
     * Possible states for male rabbits
     * Used in availability and health tracking
     */
    private const MALE_ETATS = ['Active', 'Inactive', 'Malade'];
    
    /**
     * Health status options for rabbits
     * Used in individual and litter health tracking
     */
    private const HEALTH_STATUSES = ['Excellent', 'Bon', 'Moyen', 'Faible'];
    
    /**
     * Life states for baby rabbits (lapereaux)
     * Tracks survival and commercial status
     */
    private const LAPIN_ETATS = ['vivant', 'mort', 'vendu'];
    
    /**
     * Payment status options for sales
     * Used in financial tracking and reporting
     */
    private const PAYMENT_STATUSES = ['paid', 'pending', 'partial'];
    
    /**
     * Types of items that can be sold
     * Used in sales categorization and reporting
     */
    private const SALE_TYPES = ['male', 'female', 'lapereau', 'groupe'];
    
    /**
     * Notification type categories
     * Used for visual differentiation in UI
     */
    private const NOTIFICATION_TYPES = ['success', 'warning', 'info', 'error'];
    
    /**
     * Realistic buyer names for sales records
     * Simulates actual customer base for testing
     */
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
        'Particulier - Amateur',
        'Collectionneur Races Rares',
        'Export International',
        'Marché de Noël',
        'Foire Agricole',
    ];
    
    /**
     * Notification titles for activity feed
     * Simulates real application notifications
     */
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
        'Alerte: Portée en Retard',
        'Rappel: Vaccination Prévue',
        'Nouvel Utilisateur Inscrit',
        'Sauvegarde Automatique',
        'Mise à Jour Appliquée',
    ];
    
    /**
     * Observation templates for naissance records
     * Adds realistic variation to health notes
     */
    private const NAISSANCE_OBSERVATIONS = [
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
        'Température du nid optimale',
        'Allaitement régulier observé',
        'Croissance conforme aux attentes',
        'Comportement maternel excellent',
        'Précautions sanitaires prises',
    ];
    
    /**
     * Observation templates for individual lapereaux
     * Adds detail to baby rabbit records
     */
    private const LAPEREAU_OBSERVATIONS = [
        null,
        'En bonne santé',
        'Vigoureux',
        'Poids normal',
        'Développement bon',
        'Surveillance nécessaire',
        'Alimentation normale',
        'Comportement actif',
        'Réflexes présents',
        'Pelage en bonne santé',
        null,
        null,
        null,
    ];
    
    /**
     * Sale notes for transaction records
     * Simulates real sales documentation
     */
    private const SALE_NOTES = [
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
        'Certificat de santé fourni',
        'Conseils d\'élevage remis',
        'Contact pour suivi post-vente',
        null,
        null,
    ];
    
    /**
     * Notification message templates
     * Provides variety in user communications
     */
    private const NOTIFICATION_MESSAGES = [
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
        'Alerte: Seuil critique atteint.',
        'Tâche planifiée exécutée.',
        'Synchronisation terminée.',
        'Sauvegarde effectuée avec succès.',
        'Mise à jour de sécurité appliquée.',
    ];
    
    // =========================================================================
    // SECTION 2: STORAGE PROPERTIES FOR RELATIONSHIP TRACKING
    // =========================================================================
    
    /**
     * Store created users for relationship assignment
     * @var array<App\Models\User>
     */
    private array $users = [];
    
    /**
     * Store created males for mating relationships
     * @var array<App\Models\Male>
     */
    private array $males = [];
    
    /**
     * Store created females for reproduction tracking
     * @var array<App\Models\Femelle>
     */
    private array $femelles = [];
    
    /**
     * Store created saillies for birth linking
     * @var array<App\Models\Saillie>
     */
    private array $saillies = [];
    
    /**
     * Store created mises bas for litter creation
     * @var array<App\Models\MiseBas>
     */
    private array $misesBas = [];
    
    /**
     * Store created naissances for lapereau linking
     * @var array<App\Models\Naissance>
     */
    private array $naissances = [];
    
    /**
     * Store created lapereaux for sales linking
     * @var array<App\Models\Lapereau>
     */
    private array $lapereaux = [];
    
    /**
     * Store created sales for financial reporting
     * @var array<App\Models\Sale>
     */
    private array $sales = [];
    
    // =========================================================================
    // SECTION 3: MAIN ENTRY POINT - RUN METHOD
    // =========================================================================
    
    /**
     * Run the database seeds.
     * 
     * This is the main orchestration method that calls all seeding methods
     * in the correct order to respect foreign key constraints.
     * 
     * Execution order:
     * 1. Settings (no dependencies)
     * 2. Users (no dependencies)
     * 3. Males (no dependencies)
     * 4. Femelles (no dependencies)
     * 5. Saillies (depends on males, femelles)
     * 6. MisesBas (depends on saillies with + result)
     * 7. Naissances (depends on mises_bas) ⭐ FIXED: includes user_id
     * 8. Lapereaux (depends on naissances)
     * 9. Sales (depends on users, can link to lapereaux)
     * 10. Notifications (depends on users)
     * 
     * @return void
     */
    public function run(): void
    {
        $startTime = microtime(true);
        
        $this->command->info('🐰 Starting CuniApp Élevage Data Seeding...');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('📋 Seeding ' . self::TOTAL_USERS . ' users');
        $this->command->info('🐰 Seeding ' . self::TOTAL_MALES . ' males');
        $this->command->info('🐰 Seeding ' . self::TOTAL_FEMELLES . ' femelles');
        $this->command->info('💕 Seeding ' . self::TOTAL_SAILLIES . ' saillies');
        $this->command->info('🥚 Seeding ' . self::TOTAL_MISES_BAS . ' mises bas');
        $this->command->info('🐣 Seeding ' . self::TOTAL_NAISSANCES . ' naissances');
        $this->command->info('🐇 Seeding ' . self::TOTAL_LAPEREAUX . ' lapereaux');
        $this->command->info('💰 Seeding ' . self::TOTAL_SALES . ' sales');
        $this->command->info('🔔 Seeding ' . self::TOTAL_NOTIFICATIONS . ' notifications');
        $this->command->info('⚙️  Seeding ' . self::TOTAL_SETTINGS . ' settings');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('');
        
        // Seed in logical order (respecting foreign keys)
        $this->seedSettings();
        $this->seedUsers();
        $this->seedMales();
        $this->seedFemelles();
        $this->seedSaillies();
        $this->seedMisesBas();
        $this->seedNaissances(); // ⭐ FIXED: Now includes user_id
        $this->seedLapereaux();
        $this->seedSales();
        $this->seedNotifications();
        
        // Display comprehensive summary
        $this->displaySummary();
        
        // ⭐ CRITICAL: Display login credentials prominently
        $this->displayLoginCredentials();
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info("⏱️  Total seeding time: {$duration} seconds");
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ All tables populated with logically connected data!');
        $this->command->info('═══════════════════════════════════════════════════');
    }
    
    // =========================================================================
    // SECTION 4: SETTINGS SEEDING METHODS
    // =========================================================================
    
    /**
     * Seed application settings with comprehensive configuration
     * 
     * Settings are grouped by functionality:
     * - general: Farm identification and contact info
     * - breeding: Reproduction cycle parameters
     * - verification: Birth verification timing rules
     * - notifications: User communication preferences
     * - system: Application behavior and localization
     * - business: Financial and invoicing rules
     * - inventory: Stock management thresholds
     * - reports: Data export and backup settings
     * - security: Authentication and session policies
     * 
     * @return void
     */
    private function seedSettings(): void
    {
        $this->command->info('⚙️  Seeding Settings...');
        
        $settings = [
            // -----------------------------------------------------------------
            // General Settings - Farm Identification
            // -----------------------------------------------------------------
            [
                'key' => 'farm_name',
                'value' => 'CuniApp Élevage',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nom de la ferme',
                'description' => 'Nom officiel de l\'exploitation',
            ],
            [
                'key' => 'farm_address',
                'value' => '123 Route de la Campagne, 75000 Paris',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Adresse',
                'description' => 'Adresse postale de la ferme',
            ],
            [
                'key' => 'farm_phone',
                'value' => '+33 6 12 34 56 78',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Téléphone',
                'description' => 'Numéro de contact principal',
            ],
            [
                'key' => 'farm_email',
                'value' => 'contact@cuniapp-elevage.fr',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Email',
                'description' => 'Adresse email de contact',
            ],
            
            // -----------------------------------------------------------------
            // Breeding Settings - Reproduction Parameters
            // -----------------------------------------------------------------
            [
                'key' => 'gestation_days',
                'value' => '31',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Jours de gestation',
                'description' => 'Durée moyenne de gestation des lapines',
            ],
            [
                'key' => 'weaning_weeks',
                'value' => '6',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Semaines de sevrage',
                'description' => 'Âge recommandé pour le sevrage',
            ],
            [
                'key' => 'alert_threshold',
                'value' => '80',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Seuil d\'alerte (%)',
                'description' => 'Pourcentage pour déclencher les alertes',
            ],
            
            // -----------------------------------------------------------------
            // Verification Settings - Birth Verification Rules ⭐ NEW
            // -----------------------------------------------------------------
            [
                'key' => 'verification_initial_days',
                'value' => '10',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Délai initial de vérification (jours)',
                'description' => 'Nombre de jours avant première notification de vérification',
            ],
            [
                'key' => 'verification_reminder_days',
                'value' => '15',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Délai premier rappel (jours)',
                'description' => 'Nombre de jours avant le premier rappel si non vérifié',
            ],
            [
                'key' => 'verification_interval_days',
                'value' => '5',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Intervalle des rappels (jours)',
                'description' => 'Fréquence des rappels suivants',
            ],
            
            // -----------------------------------------------------------------
            // Notification Settings - User Communication
            // -----------------------------------------------------------------
            [
                'key' => 'notifications_email',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Notifications par email',
                'description' => 'Activer l\'envoi de notifications par email',
            ],
            [
                'key' => 'notifications_dashboard',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Notifications sur le dashboard',
                'description' => 'Afficher les notifications dans l\'interface',
            ],
            
            // -----------------------------------------------------------------
            // System Settings - Application Behavior
            // -----------------------------------------------------------------
            [
                'key' => 'theme',
                'value' => 'system',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Thème de l\'application',
                'description' => 'Apparence visuelle (system/light/dark)',
            ],
            [
                'key' => 'language',
                'value' => 'fr',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Langue de l\'application',
                'description' => 'Langue d\'affichage par défaut',
            ],
            [
                'key' => 'timezone',
                'value' => 'Europe/Paris',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Fuseau horaire',
                'description' => 'Fuseau horaire pour les dates',
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Format de date',
                'description' => 'Format d\'affichage des dates',
            ],
            [
                'key' => 'currency',
                'value' => 'FCFA',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Devise',
                'description' => 'Devise pour les montants financiers',
            ],
            [
                'key' => 'decimal_separator',
                'value' => ',',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Séparateur décimal',
                'description' => 'Caractère pour séparer les décimales',
            ],
            [
                'key' => 'thousands_separator',
                'value' => ' ',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Séparateur de milliers',
                'description' => 'Caractère pour séparer les milliers',
            ],
            
            // -----------------------------------------------------------------
            // Business Settings - Financial Configuration
            // -----------------------------------------------------------------
            [
                'key' => 'tax_rate',
                'value' => '0',
                'type' => 'number',
                'group' => 'business',
                'label' => 'Taux de taxe (%)',
                'description' => 'Taux de TVA ou taxes applicables',
            ],
            [
                'key' => 'default_payment_terms',
                'value' => '30',
                'type' => 'number',
                'group' => 'business',
                'label' => 'Délai de paiement (jours)',
                'description' => 'Délai par défaut pour les paiements',
            ],
            [
                'key' => 'invoice_prefix',
                'value' => 'FACT-',
                'type' => 'string',
                'group' => 'business',
                'label' => 'Préfixe facture',
                'description' => 'Préfixe pour les numéros de facture',
            ],
            [
                'key' => 'quote_prefix',
                'value' => 'DEVIS-',
                'type' => 'string',
                'group' => 'business',
                'label' => 'Préfixe devis',
                'description' => 'Préfixe pour les numéros de devis',
            ],
            
            // -----------------------------------------------------------------
            // Inventory Settings - Stock Management
            // -----------------------------------------------------------------
            [
                'key' => 'low_stock_threshold',
                'value' => '10',
                'type' => 'number',
                'group' => 'inventory',
                'label' => 'Seuil d\'alerte stock',
                'description' => 'Quantité minimale avant alerte de stock',
            ],
            [
                'key' => 'auto_reorder',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'inventory',
                'label' => 'Réapprovisionnement automatique',
                'description' => 'Activer les commandes automatiques',
            ],
            
            // -----------------------------------------------------------------
            // Report Settings - Data Management
            // -----------------------------------------------------------------
            [
                'key' => 'report_frequency',
                'value' => 'monthly',
                'type' => 'string',
                'group' => 'reports',
                'label' => 'Fréquence des rapports',
                'description' => 'Périodicité de génération des rapports',
            ],
            [
                'key' => 'auto_backup',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'reports',
                'label' => 'Sauvegarde automatique',
                'description' => 'Activer les sauvegardes automatiques',
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '90',
                'type' => 'number',
                'group' => 'reports',
                'label' => 'Jours de rétention',
                'description' => 'Durée de conservation des sauvegardes',
            ],
            
            // -----------------------------------------------------------------
            // Security Settings - Access Control
            // -----------------------------------------------------------------
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'number',
                'group' => 'security',
                'label' => 'Timeout de session (minutes)',
                'description' => 'Durée d\'inactivité avant déconnexion',
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'number',
                'group' => 'security',
                'label' => 'Longueur minimale mot de passe',
                'description' => 'Nombre minimal de caractères requis',
            ],
            [
                'key' => 'require_2fa',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Exiger 2FA',
                'description' => 'Activer l\'authentification à deux facteurs',
            ],
            
            // -----------------------------------------------------------------
            // Additional Settings for Completeness
            // -----------------------------------------------------------------
            [
                'key' => 'max_rabbits_per_cage',
                'value' => '8',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Max lapins par cage',
                'description' => 'Capacité maximale recommandée par cage',
            ],
            [
                'key' => 'vaccination_schedule',
                'value' => '8 semaines',
                'type' => 'string',
                'group' => 'breeding',
                'label' => 'Calendrier de vaccination',
                'description' => 'Âge recommandé pour la première vaccination',
            ],
            [
                'key' => 'quarantine_days',
                'value' => '14',
                'type' => 'number',
                'group' => 'breeding',
                'label' => 'Jours de quarantaine',
                'description' => 'Durée d\'isolement pour nouveaux arrivants',
            ],
            [
                'key' => 'export_format',
                'value' => 'csv',
                'type' => 'string',
                'group' => 'reports',
                'label' => 'Format d\'export',
                'description' => 'Format par défaut pour les exports de données',
            ],
            [
                'key' => 'auto_logout_inactive',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Déconnexion auto inactive',
                'description' => 'Déconnecter les sessions inactives',
            ],
            [
                'key' => 'enable_audit_log',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Activer journal d\'audit',
                'description' => 'Enregistrer les actions importantes',
            ],
        ];
        
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
        
        $this->command->info('   ✓ Created ' . count($settings) . ' settings');
    }
    
    // =========================================================================
    // SECTION 5: USER SEEDING METHODS
    // =========================================================================
    
    /**
     * Seed users with different roles and preferences
     * 
     * Creates:
     * - 1 Administrator with full access
     * - 1 Manager with operational access
     * - 8 Regular users with varied preferences
     * 
     * All users have email_verified_at set for immediate login.
     * All use the same password for easy testing: password123
     * 
     * @return void
     */
    private function seedUsers(): void
    {
        $this->command->info('👤 Seeding Users...');
        
        // ---------------------------------------------------------------------
        // Administrator Account - Full System Access
        // ---------------------------------------------------------------------
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
        
        // ---------------------------------------------------------------------
        // Manager Account - Operational Access
        // ---------------------------------------------------------------------
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
        
        // ---------------------------------------------------------------------
        // Regular User Accounts - Varied Preferences
        // ---------------------------------------------------------------------
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
    
    // =========================================================================
    // SECTION 6: MALE RABBIT SEEDING METHODS
    // =========================================================================
    
    /**
     * Generate realistic male rabbit name
     * 
     * Combines popular rabbit names with unique index for variety.
     * Names are culturally appropriate for French context.
     * 
     * @param int $index Sequential index for name selection
     * @return string Generated rabbit name
     */
    private function generateMaleName(int $index): string
    {
        $names = [
            'Max', 'Rocky', 'Thor', 'Zeus', 'Apollo', 'Titan', 'Hercule', 'Sultan',
            'Rex', 'Duke', 'King', 'Prince', 'Boss', 'Chief', 'Master', 'Lord',
            'Felix', 'Oscar', 'Charlie', 'Buddy', 'Cooper', 'Jack', 'Leo', 'Sam',
            'Gaston', 'Marius', 'Victor', 'Arthur', 'Louis', 'Henri', 'Charles',
            'Simba', 'Shadow', 'Storm', 'Blaze', 'Flash', 'Bolt', 'Ace', 'Jet',
            'Milo', 'Teddy', 'Biscuit', 'Caramel', 'Noisette', 'Chocolat', 'Moka',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    /**
     * Seed male rabbits with realistic attributes
     * 
     * Each male has:
     * - Unique code (MAL-XXXX format)
     * - Name, breed, origin
     * - Birth date (6 months to 3 years ago)
     * - State (Active/Inactive/Malade)
     * 
     * @return void
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
    
    // =========================================================================
    // SECTION 7: FEMALE RABBIT SEEDING METHODS
    // =========================================================================
    
    /**
     * Generate realistic female rabbit name
     * 
     * Combines popular female rabbit names with unique index.
     * Names include French floral and noble themes.
     * 
     * @param int $index Sequential index for name selection
     * @return string Generated rabbit name
     */
    private function generateFemelleName(int $index): string
    {
        $names = [
            'Lily', 'Bella', 'Luna', 'Daisy', 'Rosie', 'Coco', 'Molly', 'Ruby',
            'Emma', 'Olivia', 'Sophie', 'Chloe', 'Grace', 'Rose', 'Pearl', 'Jade',
            'Fleur', 'Rose', 'Violette', 'Marguerite', 'Pâquerette', 'Iris', 'Orchidée',
            'Princesse', 'Reine', 'Duchesse', 'Comtesse', 'Baronne', 'Lady', 'Miss',
            'Nala', 'Kiara', 'Zara', 'Nina', 'Lola', 'Mia', 'Lea', 'Chloé',
            'Belle', 'Douce', 'Mignonne', 'Gentille', 'Sage', 'Jolie', 'Charmante',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    /**
     * Seed female rabbits with reproduction-ready attributes
     * 
     * Each female has:
     * - Unique code (FEM-XXXX format)
     * - Name, breed, origin
     * - Birth date (4 months to 4 years ago)
     * - State (Active/Gestante/Allaitante/Vide) for reproduction tracking
     * 
     * @return void
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
    
    // =========================================================================
    // SECTION 8: MATING (SAILLIES) SEEDING METHODS
    // =========================================================================
    
    /**
     * Seed mating records with realistic timing
     * 
     * Each saillie has:
     * - Random male and female pairing
     * - Saillie date (1-8 months ago)
     * - Palpation date (10-15 days after saillie, if performed)
     * - Palpation result (+/-/null) with ~60% positive rate
     * - Theoretical birth date (31 days after saillie)
     * 
     * @return void
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
            
            // Palpation result: ~60% positive, ~30% negative, ~10% not done
            $palpationResultat = match(rand(1, 10)) {
                1, 2, 3, 4, 5, 6 => '+',    // 60% positive
                7, 8, 9 => '-',              // 30% negative
                default => null,             // 10% not performed
            };
            
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
    
    // =========================================================================
    // SECTION 9: BIRTH (MISES BAS) SEEDING METHODS
    // =========================================================================
    
    /**
     * Seed birth events from positive palpation saillies
     * 
     * Only creates mises bas for saillies with positive palpation result.
     * Each mise bas has:
     * - Link to femelle and saillie
     * - Actual birth date (±3 days from theoretical)
     * - Weaning date (6 weeks after birth)
     * - Average weaning weight (0.5-1.5 kg)
     * 
     * @return void
     */
    private function seedMisesBas(): void
    {
        $this->command->info('🥚 Seeding Mises Bas (Births)...');
        
        // Only create mises bas for saillies with positive palpation
        $positiveSaillies = array_filter(
            $this->saillies, 
            fn($s) => $s->palpation_resultat === '+'
        );
        
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
    
    // =========================================================================
    // SECTION 10: LITTER (NAISSANCES) SEEDING METHODS - ⭐ FIXED
    // =========================================================================
    
    /**
     * Generate observations for naissance record
     * 
     * Returns random observation or null for realistic variation.
     * 
     * @return string|null Generated observation text
     */
    private function generateNaissanceObservations(): ?string
    {
        return self::NAISSANCE_OBSERVATIONS[array_rand(self::NAISSANCE_OBSERVATIONS)];
    }
    
    /**
     * Seed litter records with verification tracking - ⭐ FIXED: user_id
     * 
     * ⭐ CRITICAL FIX: Now includes user_id field which was causing the error.
     * 
     * Each naissance has:
     * - Link to mise_bas (which links to femelle via foreign key)
     * - Average birth weight (40-80 grams)
     * - Health status for the litter
     * - Observations (optional)
     * - Expected weaning and vaccination dates
     * - Verification tracking (sex_verified, reminders)
     * - ⭐ user_id: Assigned from seeded users array
     * 
     * @return void
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
            $sexVerifiedAt = $sexVerified 
                ? $miseBas->date_mise_bas->copy()->addDays(rand(10, 30)) 
                : null;
            
            // Reminder tracking for unverified births
            $reminderCount = $sexVerified ? 0 : rand(0, 3);
            $firstReminderSentAt = $reminderCount > 0 
                ? $miseBas->date_mise_bas->copy()->addDays(15) 
                : null;
            $lastReminderSentAt = $reminderCount > 0 
                ? now()->subDays(rand(1, 10)) 
                : null;
            
            // ⭐ CRITICAL: Select a random user for this naissance
            $userId = $this->users[array_rand($this->users)]->id;
            
            // Archive some records (10% chance)
            $isArchived = rand(0, 10) === 1;
            $archivedAt = $isArchived ? now() : null;
            
            // ⭐ Create naissance with ALL required fields including user_id
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
                'is_archived' => $isArchived,
                'archived_at' => $archivedAt,
                // ⭐ FIXED: Include user_id which was missing and causing the error
                'user_id' => $userId,
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
    
    // =========================================================================
    // SECTION 11: BABY RABBIT (LAPEREAUX) SEEDING METHODS
    // =========================================================================
    
    /**
     * Generate lapereau name
     * 
     * Creates cute, short names suitable for baby rabbits.
     * 
     * @param int $index Sequential index for name selection
     * @return string Generated rabbit name
     */
    private function generateLapereauName(int $index): string
    {
        $names = [
            'Toto', 'Titi', 'Tutu', 'Coco', 'Lolo', 'Mimi', 'Kiki', 'Doudou',
            'Bibi', 'Gigi', 'Fifi', 'Riri', 'Zizi', 'Nunu', 'Pipi', 'Qiqi',
            'Bunny', 'Fluffy', 'Snowball', 'Cotton', 'Puff', 'Hoppy', 'Thumper',
            'Peter', 'Benjamin', 'Flopsy', 'Mopsy', 'Cottontail', 'Velvet', 'Silky',
            'Pompon', 'Biscotte', 'Caramel', 'Noisette', 'Chocolat', 'Miel', 'Sucre',
        ];
        
        return $names[$index % count($names)] . '-' . $index;
    }
    
    /**
     * Generate lapereau observations
     * 
     * Returns random health observation or null.
     * 
     * @return string|null Generated observation text
     */
    private function generateLapereauObservations(): ?string
    {
        return self::LAPEREAU_OBSERVATIONS[array_rand(self::LAPEREAU_OBSERVATIONS)];
    }
    
    /**
     * Get category based on age in weeks
     * 
     * Categories used for sales and inventory grouping.
     * 
     * @param Carbon|null $dateNaissance Birth date of the rabbit
     * @return string|null Category label
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
    
    /**
     * Seed baby rabbits with individual health and sales data
     * 
     * Each lapereau has:
     * - Unique auto-generated code (LAP-YYYY-XXXX format)
     * - Name, sex (if verified), state (vivant/mort/vendu)
     * - Individual birth weight (35-90 grams)
     * - Individual health status
     * - Observations (optional)
     * - Category based on age
     * - Feeding metrics (daily/weekly)
     * 
     * @return void
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
                
                // Generate unique code using model method
                $code = Lapereau::generateUniqueCode();
                
                // Sex (if verified, otherwise null)
                $sex = $naissance->sex_verified 
                    ? ['male', 'female'][rand(0, 1)] 
                    : null;
                
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
    
    // =========================================================================
    // SECTION 12: SALES SEEDING METHODS
    // =========================================================================
    
    /**
     * Generate sale notes
     * 
     * Returns random transaction note or null.
     * 
     * @return string|null Generated note text
     */
    private function generateSaleNotes(): ?string
    {
        return self::SALE_NOTES[array_rand(self::SALE_NOTES)];
    }
    
    /**
     * Seed sales records with financial tracking
     * 
     * Each sale has:
     * - Link to user who recorded the sale
     * - Sale date (1 day to 6 months ago)
     * - Product type, category, quantity, pricing
     * - Buyer information (name, contact, address)
     * - Payment status and amount tracking
     * - Optional notes
     * 
     * @return void
     */
    private function seedSales(): void
    {
        $this->command->info('💰 Seeding Sales...');
        
        for ($i = 1; $i <= self::TOTAL_SALES; $i++) {
            // Select random user who recorded this sale
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
            
            // Amount paid based on status
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
    
    // =========================================================================
    // SECTION 13: NOTIFICATIONS SEEDING METHODS
    // =========================================================================
    
    /**
     * Generate notification message
     * 
     * Returns random message appropriate for notification type.
     * 
     * @param string $title Notification title for context
     * @return string Generated message text
     */
    private function generateNotificationMessage(string $title): string
    {
        return self::NOTIFICATION_MESSAGES[array_rand(self::NOTIFICATION_MESSAGES)];
    }
    
    /**
     * Seed user notifications with activity tracking
     * 
     * Each notification has:
     * - Link to user recipient
     * - Type (success/warning/info/error) for styling
     * - Title and message content
     * - Optional action URL for deep linking
     * - Icon class for Bootstrap Icons
     * - Read status and email delivery tracking
     * - Timestamps for creation and reading
     * 
     * @return void
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
    
    // =========================================================================
    // SECTION 14: SUMMARY DISPLAY METHODS
    // =========================================================================
    
    /**
     * Display comprehensive seeding summary
     * 
     * Shows counts for all seeded entities plus financial totals.
     * Helps verify seeding completed successfully.
     * 
     * @return void
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
        
        // Calculate financial totals
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
    
    // =========================================================================
    // SECTION 15: LOGIN CREDENTIALS DISPLAY - ⭐ CRITICAL OUTPUT
    // =========================================================================
    
    /**
     * Display login credentials for all created accounts
     * 
     * ⭐ THIS IS THE MOST IMPORTANT OUTPUT FOR THE USER!
     * 
     * Shows:
     * - Admin account (full access)
     * - Manager account (operational access)
     * - Regular user accounts (testing access)
     * - All use password: password123
     * - Welcome page URL for login
     * 
     * @return void
     */
    private function displayLoginCredentials(): void
    {
        $this->command->info('');
        $this->command->info('🔐 LOGIN CREDENTIALS - SAVE THESE!');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('⚠️  IMPORTANT: These credentials are for testing only!');
        $this->command->info('⚠️  Change passwords before using in production!');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('');
        
        // Admin account - highlighted prominently
        $this->command->info('👑 ADMINISTRATOR ACCOUNT (Full Access):');
        $this->command->info('   ┌─────────────────────────────────┐');
        $this->command->info('   │ Email:    admin@cuniapp.com     │');
        $this->command->info('   │ Password: password123           │');
        $this->command->info('   │ URL:      http://localhost:8000/welcome │');
        $this->command->info('   └─────────────────────────────────┘');
        $this->command->info('');
        
        // Manager account
        $this->command->info('📋 MANAGER ACCOUNT (Operational Access):');
        $this->command->info('   ┌─────────────────────────────────┐');
        $this->command->info('   │ Email:    manager@cuniapp.com   │');
        $this->command->info('   │ Password: password123           │');
        $this->command->info('   │ URL:      http://localhost:8000/welcome │');
        $this->command->info('   └─────────────────────────────────┘');
        $this->command->info('');
        
        // Regular users
        $this->command->info('👥 REGULAR USER ACCOUNTS (Testing):');
        $this->command->info('   Password: password123 (for all users)');
        $this->command->info('   URL:      http://localhost:8000/welcome');
        $this->command->info('');
        
        $regularUsers = array_slice($this->users, 2); // Skip admin and manager
        $this->command->info('   Available accounts:');
        foreach ($regularUsers as $index => $user) {
            $this->command->info(sprintf('   %2d. %-35s (%s)', 
                $index + 1, 
                $user->email, 
                $user->name
            ));
        }
        
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ All accounts use password: password123');
        $this->command->info('✅ All emails are verified for immediate login');
        $this->command->info('✅ Visit http://localhost:8000/welcome to login');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}
// ============================================================================
// END OF CuniAppSeeder CLASS - 2000+ LINES OF COMPREHENSIVE SEEDING LOGIC
// ============================================================================