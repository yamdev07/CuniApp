<?php
// bootstrap/app.php

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // ✅ Vérification des abonnements expirés (tous les jours à 8h)
        $schedule->command('subscriptions:check-expiration')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer()
            ->emailOutputOnFailure(config('mail.from.address'));

        // ✅ Nettoyage des transactions en attente (toutes les 30 minutes)
        $schedule->command('transactions:cleanup-pending')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->onOneServer()
            ->emailOutputOnFailure(config('mail.from.address'));

        // ✅ Vérification des naissances (tous les jours à 9h)
        $schedule->command('births:check-verification')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer()
            ->emailOutputOnFailure(config('mail.from.address'));
    })
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ Register your custom middleware
        $middleware->alias([
            'check.subscription' => \App\Http\Middleware\CheckSubscription::class,
            'check.admin' => \App\Http\Middleware\CheckAdminRole::class,
        ]);

        // ✅ Trust proxies for production (Cloudflare, Load Balancer, etc.)
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ✅ Global exception handling
        $exceptions->reportable(function (\Throwable $e) {
            Log::error('Global Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })->create();
