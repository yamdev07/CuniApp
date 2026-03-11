<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'admin' => \App\Http\Middleware\CheckAdminRole::class,
            'check.subscription' => \App\Http\Middleware\CheckSubscription::class,
            'check.admin' => \App\Http\Middleware\CheckAdminRole::class,
            'webhook.ip' => \App\Http\Middleware\VerifyWebhookIP::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
