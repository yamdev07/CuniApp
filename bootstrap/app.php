<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Redirect guests to welcome page when trying to access protected routes
        $middleware->redirectGuestsTo(fn () => route('welcome'));
        
        // Redirect authenticated users away from auth pages to dashboard
        $middleware->redirectUsersTo(fn ($request) => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();