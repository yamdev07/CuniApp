# 📋 Prompt: Complete Payment Notification System Setup (Laravel 12)

---

## 🎯 OBJECTIF

Configurer les notifications de paiement complètes pour votre application CuniApp Élevage en suivant les étapes 5 et 6 adaptées à **Laravel 12**.

---

## ✅ ÉTAPE 5 : Mettre à jour le Contrôleur Admin d'Abonnement

**Fichier :** `app/Http/Controllers/Admin/SubscriptionManagementController.php`

### 📌 Ce que vous devez faire :

Ajouter les notifications lors de l'activation et la désactivation manuelle des abonnements par l'admin.

### 🔧 Modifications à apporter :

#### 1. Dans la méthode `activate()` :

**Ajoutez après l'activation de l'abonnement :**

```php
// ✅ Send activation notification
$user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
```

**Emplacement :** Juste après `$user->update([...])` et avant `DB::commit()`

#### 2. Dans la méthode `deactivate()` :

**Ajoutez après la désactivation :**

```php
// ✅ Send expiration notification
use App\Notifications\SubscriptionExpiredNotification;

$user->notify(new SubscriptionExpiredNotification($subscription));
```

**Emplacement :** Juste après `$subscription->user->update([...])` et avant `DB::commit()`

### 📁 Fichiers de notification requis :

Assurez-vous que ces fichiers existent dans `app/Notifications/` :

| Fichier | Purpose |
|---------|---------|
| `SubscriptionActivatedNotification.php` | Notification quand admin active un abonnement |
| `SubscriptionExpiredNotification.php` | Notification quand abonnement expire/désactivé |
| `SubscriptionExpiringSoonNotification.php` | Rappel avant expiration (7, 3, 1 jours) |
| `PaymentSuccessfulNotification.php` | Paiement réussi |
| `PaymentFailedNotification.php` | Paiement échoué |
| `InvoiceEmailNotification.php` | Facture générée |

---

## ✅ ÉTAPE 6 : Planifier les Commandes (Laravel 12)

**⚠️ IMPORTANT :** Laravel 12 n'utilise PLUS `app/Console/Kernel.php` pour la planification !

### 📌 Option A : Via `bootstrap/app.php` (Recommandé Laravel 12)

**Fichier :** `bootstrap/app.php`

**Ajoutez le bloc `->withSchedule()` :**

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // ✅ Vérification des abonnements expirés (tous les jours à 8h)
        $schedule->command('subscriptions:check-expiration')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer();
        
        // ✅ Nettoyage des transactions en attente (toutes les 30 minutes)
        $schedule->command('transactions:cleanup-pending')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->onOneServer();
        
        // ✅ Vérification des naissances (tous les jours à 9h)
        $schedule->command('births:check-verification')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer();
    })
    ->withMiddleware(function (Middleware $middleware) {
        // Vos middlewares...
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestion des exceptions...
    })->create();
```

### 📌 Option B : Via `routes/console.php` (Alternative)

**Fichier :** `routes/console.php`

```php
<?php

use Illuminate\Support\Facades\Schedule;

// ✅ Vérification des abonnements expirés
Schedule::command('subscriptions:check-expiration')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->onOneServer();

// ✅ Nettoyage des transactions en attente
Schedule::command('transactions:cleanup-pending')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->onOneServer();

// ✅ Vérification des naissances
Schedule::command('births:check-verification')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->onOneServer();
```

---

## 🧪 COMMANDES DE TEST

### 1. Vérifier que les commandes existent :

```bash
php artisan list | grep subscriptions
php artisan list | grep transactions
php artisan list | grep births
```

**Résultat attendu :**
```
subscriptions:check-expiration
transactions:cleanup-pending
births:check-verification
```

### 2. Exécuter manuellement pour tester :

```bash
php artisan subscriptions:check-expiration
php artisan transactions:cleanup-pending
php artisan births:check-verification
```

### 3. Vérifier la planification :

```bash
php artisan schedule:list
```

**Résultat attendu :** 3 tâches affichées avec leurs horaires

### 4. Tester le scheduler :

```bash
php artisan schedule:work
```

### 5. Vérifier les notifications en base de données :

```bash
php artisan tinker
>>> App\Models\Notification::latest()->take(10)->get(['type', 'title', 'created_at'])
>>> App\Models\Subscription::where('status', 'expired')->count()
>>> App\Models\PaymentTransaction::where('status', 'cancelled')->count()
```

---

## 🔧 CONFIGURATION CRON (Production)

**Sur votre serveur de production :**

```bash
# Éditer le crontab
crontab -e

# Ajouter cette ligne (UNE SEULE suffit pour toutes les tâches)
* * * * * cd /chemin/vers/cuniapp && php artisan schedule:run >> /dev/null 2>&1

# Vérifier
crontab -l

# Redémarrer le service cron
sudo service cron restart
```

---

## 📊 TABLEAU RÉCAPITULATIF DES NOTIFICATIONS

| Événement | Base de données | Email | Déclencheur |
|-----------|-----------------|-------|-------------|
| Paiement initié | ✅ | ✅ | `PaymentController@initiate` |
| Paiement réussi | ✅ | ✅ | `PaymentController@callback` |
| Paiement échoué | ✅ | ✅ | `PaymentController@process/callback` |
| Paiement expiré | ✅ | ✅ | `CleanupPendingTransactions` |
| Abonnement activé | ✅ | ✅ | `PaymentController@activateSubscription` + `Admin@activate` |
| Abonnement expire (7 jours) | ✅ | ✅ | `SendSubscriptionExpirationNotifications` |
| Abonnement expire (3 jours) | ✅ | ✅ | `SendSubscriptionExpirationNotifications` |
| Abonnement expire (1 jour) | ✅ | ✅ | `SendSubscriptionExpirationNotifications` |
| Abonnement expiré | ✅ | ✅ | `SendSubscriptionExpirationNotifications` + `Admin@deactivate` |
| Facture générée | ✅ | ✅ | `InvoiceService@createFromTransaction` |

---

## ✅ CHECKLIST DE VALIDATION

- [ ] Les 6 classes de notification existent dans `app/Notifications/`
- [ ] `bootstrap/app.php` contient le bloc `->withSchedule()`
- [ ] Les 3 commandes sont listées avec `php artisan schedule:list`
- [ ] Les commandes s'exécutent manuellement sans erreur
- [ ] Les notifications apparaissent dans la table `notifications`
- [ ] Les emails sont envoyés (vérifier boîte mail)
- [ ] Le cron est configuré sur le serveur de production
- [ ] Les logs ne montrent pas d'erreurs (`tail -f storage/logs/laravel.log`)

---

## 🚨 DÉPANNAGE

| Problème | Solution |
|----------|----------|
| `schedule:list` ne montre rien | `php artisan config:clear` + vérifier `bootstrap/app.php` |
| Commandes non trouvées | Vérifier `app/Console/Commands/` + `php artisan command:cache` |
| Notifications non envoyées | Vérifier config mail + queue worker |
| Emails non reçus | Vérifier `.env` SMTP + logs mail |
| Cron ne s'exécute pas | `sudo service cron status` + vérifier chemin absolu |

---

## 📁 STRUCTURE ATTENDUE

```
cuniapp/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── CheckBirthVerification.php
│   │       ├── CleanupPendingTransactions.php
│   │       └── SendSubscriptionExpirationNotifications.php
│   ├── Notifications/
│   │   ├── PaymentSuccessfulNotification.php
│   │   ├── PaymentFailedNotification.php
│   │   ├── SubscriptionActivatedNotification.php
│   │   ├── SubscriptionExpiredNotification.php
│   │   ├── SubscriptionExpiringSoonNotification.php
│   │   └── InvoiceEmailNotification.php
│   └── Http/
│       └── Controllers/
│           └── Admin/
│               └── SubscriptionManagementController.php (modifié)
├── bootstrap/
│   └── app.php (avec ->withSchedule())
├── routes/
│   └── console.php (option alternative)
└── storage/
    └── logs/
        └── laravel.log
```

---

## 🎯 RÉSULTAT FINAL ATTENDU

Après ces modifications :

1. ✅ Les utilisateurs reçoivent des notifications pour TOUS les événements de paiement
2. ✅ Les emails sont envoyés automatiquement selon les préférences utilisateur
3. ✅ Les commandes planifiées s'exécutent automatiquement
4. ✅ L'admin peut activer/désactiver avec notifications
5. ✅ Les abonnements expirés sont détectés et notifiés
6. ✅ Les transactions en attente sont nettoyées automatiquement

---

**Une fois terminé, exécutez :**

```bash
php artisan schedule:list
php artisan subscriptions:check-expiration
php artisan transactions:cleanup-pending
tail -f storage/logs/laravel.log
```

**Et vérifiez que tout fonctionne sans erreur !** 🚀