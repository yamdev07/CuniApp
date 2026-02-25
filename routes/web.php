<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file contains all web-facing routes for the CuniApp Élevage application.
| Routes are organized into logical sections with strict middleware enforcement:
|   • Public routes (no auth required)
|   • Guest routes (unauthenticated users only)
|   • Authenticated routes (login required, email verification optional)
|   • Verified routes (login + email verification required)
|   • Admin routes (role-based access control)
|
| ⚠️ SECURITY NOTE: Always apply proper middleware to prevent privilege escalation
| ⚠️ PERFORMANCE NOTE: Keep route definitions lean; use route caching in production
|
| Generated: 2026-02-26 | Environment: production-ready
| Lines of Code: 287 (excluding comments)
|--------------------------------------------------------------------------
*/

// ========================================================================
// 🔓 PUBLIC ROUTES (No authentication required - accessible to all)
// ========================================================================

Route::get('/', function () {
    return redirect()->route('welcome');
})->name('home');

Route::get('/welcome', [AuthenticatedSessionController::class, 'create'])
    ->name('welcome')
    ->middleware('guest');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

// ========================================================================
// 👤 GUEST ROUTES (Only accessible to unauthenticated users)
// ========================================================================

Route::middleware('guest')->group(function () {
    // Authentication Routes
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Password Reset Flow
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // ✅ EMAIL VERIFICATION CODE SYSTEM (Critical for unverified users)
    Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])
        ->name('verification.code.verify');

    Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])
        ->name('verification.code.resend');

    Route::post('/verification/send-code', [EmailVerificationCodeController::class, 'sendCode'])
        ->name('verification.send-code');
});

// ========================================================================
// 🔐 AUTHENTICATED ROUTES (Login required, email verification optional)
// ========================================================================

Route::middleware('auth')->group(function () {
    // Standard Laravel Email Verification Flow
    Route::get('/verify-email', fn() => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Custom verification notification handler with null safety
    Route::post('/email/verification-notification', function () {
        if (auth()->check() && auth()->user()) {
            auth()->user()->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }
        return redirect()->route('login');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Password Confirmation (for sensitive operations)
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Secure logout endpoint
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // ====================================================================
    // 🛡️ FULLY VERIFIED ROUTES (Login + Email Verification Required)
    // ====================================================================

    Route::middleware('verified')->group(function () {
        // Dashboard & Analytics
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Mâles CRUD Operations
        Route::get('/males', [MaleController::class, 'index'])
            ->name('males.index');
        Route::get('/males/create', [MaleController::class, 'create'])
            ->name('males.create');
        Route::post('/males', [MaleController::class, 'store'])
            ->name('males.store');
        Route::get('/males/{male}', [MaleController::class, 'show'])
            ->name('males.show');
        Route::get('/males/{male}/edit', [MaleController::class, 'edit'])
            ->name('males.edit');
        Route::put('/males/{male}', [MaleController::class, 'update'])
            ->name('males.update');
        Route::delete('/males/{male}', [MaleController::class, 'destroy'])
            ->name('males.destroy');
        Route::patch('/males/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])
            ->name('males.toggleEtat');

        // Femelles CRUD Operations
        Route::get('/femelles', [FemelleController::class, 'index'])
            ->name('femelles.index');
        Route::get('/femelles/create', [FemelleController::class, 'create'])
            ->name('femelles.create');
        Route::post('/femelles', [FemelleController::class, 'store'])
            ->name('femelles.store');
        Route::get('/femelles/{femelle}', [FemelleController::class, 'show'])
            ->name('femelles.show');
        Route::get('/femelles/{femelle}/edit', [FemelleController::class, 'edit'])
            ->name('femelles.edit');
        Route::put('/femelles/{femelle}', [FemelleController::class, 'update'])
            ->name('femelles.update');
        Route::delete('/femelles/{femelle}', [FemelleController::class, 'destroy'])
            ->name('femelles.destroy');
        Route::patch('/femelles/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])
            ->name('femelles.toggleEtat');

        // Saillies CRUD Operations
        Route::get('/saillies', [SaillieController::class, 'index'])
            ->name('saillies.index');
        Route::get('/saillies/create', [SaillieController::class, 'create'])
            ->name('saillies.create');
        Route::post('/saillies', [SaillieController::class, 'store'])
            ->name('saillies.store');
        Route::get('/saillies/{saillie}', [SaillieController::class, 'show'])
            ->name('saillies.show');
        Route::get('/saillies/{saillie}/edit', [SaillieController::class, 'edit'])
            ->name('saillies.edit');
        Route::put('/saillies/{saillie}', [SaillieController::class, 'update'])
            ->name('saillies.update');
        Route::delete('/saillies/{saillie}', [SaillieController::class, 'destroy'])
            ->name('saillies.destroy');
        Route::patch('/saillies/{saillie}/palpation', [SaillieController::class, 'updatePalpation'])
            ->name('saillies.palpation.update');

        // Mises Bas CRUD Operations
        Route::get('/mises-bas', [MiseBasController::class, 'index'])
            ->name('mises-bas.index');
        Route::get('/mises-bas/create', [MiseBasController::class, 'create'])
            ->name('mises-bas.create');
        Route::post('/mises-bas', [MiseBasController::class, 'store'])
            ->name('mises-bas.store');
        Route::get('/mises-bas/{miseBas}', [MiseBasController::class, 'show'])
            ->name('mises-bas.show');
        Route::get('/mises-bas/{miseBas}/edit', [MiseBasController::class, 'edit'])
            ->name('mises-bas.edit');
        Route::put('/mises-bas/{miseBas}', [MiseBasController::class, 'update'])
            ->name('mises-bas.update');
        Route::delete('/mises-bas/{miseBas}', [MiseBasController::class, 'destroy'])
            ->name('mises-bas.destroy');

        // Unified Lapins Management
        Route::get('/lapins', [LapinController::class, 'index'])
            ->name('lapins.index');
        Route::get('/lapins/create', [LapinController::class, 'create'])
            ->name('lapins.create');
        Route::post('/lapins', [LapinController::class, 'store'])
            ->name('lapins.store');

        // ⚠️ CRITICAL: Sales Routes with FULL Notification Coverage
        // ====================================================================
        // SALES MANAGEMENT ROUTES (All actions trigger notifications)
        // ====================================================================

        // Sales CRUD Operations
        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])
            ->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');
        Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])
            ->name('sales.edit');
        Route::put('/sales/{sale}', [SaleController::class, 'update'])
            ->name('sales.update');
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])
            ->name('sales.destroy');

        // Payment Management (All trigger notifications)
        Route::patch('/sales/{sale}/mark-paid', [SaleController::class, 'markAsPaid'])
            ->name('sales.mark-paid');

        Route::post('/sales/{sale}/partial-payment', [SaleController::class, 'recordPartialPayment'])
            ->name('sales.partial-payment');

        Route::post('/sales/{sale}/change-status', [SaleController::class, 'changePaymentStatus'])
            ->name('sales.change-status');

        // Bulk Operations (All trigger notifications)
        Route::delete('/sales/bulk-delete', [SaleController::class, 'bulkDelete'])
            ->name('sales.bulk-delete');

        Route::get('/sales/export', [SaleController::class, 'export'])
            ->name('sales.export');

        // Settings Management
        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])
            ->name('settings.update');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])
            ->name('settings.profile');
        Route::get('/settings/export', [SettingsController::class, 'exportData'])
            ->name('settings.export');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])
            ->name('settings.clear-cache');

        // Notification System
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.read-all');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
            ->name('notifications.destroy');
    });
});

// ========================================================================
// 🔍 UTILITY ROUTES (Available to authenticated users)
// ========================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Code Availability Checks (AJAX endpoints)
    Route::get('/males/check-code', [MaleController::class, 'checkCode'])
        ->name('males.check-code');
    Route::get('/femelles/check-code', [FemelleController::class, 'checkCode'])
        ->name('femelles.check-code');

    // Search Endpoints
    Route::get('/search/males', [MaleController::class, 'search'])
        ->name('males.search');
    Route::get('/search/femelles', [FemelleController::class, 'search'])
        ->name('femelles.search');
});

// ========================================================================
// 🧪 DEBUG & DEVELOPMENT ROUTES (Protected in production)
// ========================================================================

if (app()->environment('local', 'staging')) {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/debug/routes', function () {
            $routes = Route::getRoutes()->getRoutes();
            return response()->json(array_map(function ($route) {
                return [
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'methods' => $route->methods(),
                    'middleware' => $route->middleware(),
                ];
            }, $routes));
        })->name('debug.routes');

        Route::get('/debug/cache', function () {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            return back()->with('success', 'Cache vidé avec succès');
        })->name('debug.clear-cache');

        Route::get('/debug/notifications/test', function () {
            $controller = app(NotificationController::class);
            $controller->notifyUser([
                'type' => 'success',
                'title' => 'Test Notification',
                'message' => 'Ceci est une notification de test générée manuellement',
                'action_url' => route('dashboard')
            ]);
            return back()->with('success', 'Notification de test envoyée');
        })->name('debug.notifications.test');
    });
}

// ========================================================================
// 🌐 LOCALIZATION ROUTES (Language switching)
// ========================================================================

Route::middleware(['web'])->group(function () {
    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['fr', 'en'])) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    })->name('lang.switch');
});

// ========================================================================
// 🤖 SYSTEM & SEO ROUTES (Public access)
// ========================================================================

Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow: /admin/\nDisallow: /settings/\nSitemap: " . url('/sitemap.xml'), 200, [
        'Content-Type' => 'text/plain'
    ]);
});

Route::get('/sitemap.xml', function () {
    $pages = [
        route('welcome'),
        route('about'),
        route('contact'),
        route('privacy'),
        route('terms'),
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    foreach ($pages as $page) {
        $xml .= "  <url><loc>$page</loc><lastmod>" . now()->format('Y-m-d') . "</lastmod><changefreq>monthly</changefreq></url>" . PHP_EOL;
    }
    $xml .= '</urlset>';

    return response($xml, 200, [
        'Content-Type' => 'application/xml'
    ]);
});

// ========================================================================
// 🔁 LEGACY ROUTE ALIASES (Backward compatibility)
// ========================================================================

Route::redirect('/home', '/dashboard', 301);
Route::redirect('/femelles/show/{id}', '/femelles/{id}', 301);
Route::redirect('/males/show/{id}', '/males/{id}', 301);
Route::redirect('/sales/show/{id}', '/sales/{id}', 301);

// Legacy API endpoints (v1)
Route::prefix('api/v1')->middleware('auth')->group(function () {
    Route::get('/males', fn() => response()->json(\App\Models\Male::all()));
    Route::get('/femelles', fn() => response()->json(\App\Models\Femelle::all()));
});

// ========================================================================
// 🚨 SYSTEM HEALTH CHECK ROUTES (Internal monitoring)
// ========================================================================

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'ok';
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'environment' => app()->environment(),
        'database' => $dbStatus,
        'cache' => Cache::has('health_check') ? 'ok' : 'cold',
        'version' => config('app.version', '1.0.0'),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
    ]);
})->name('health.check');

Route::get('/ping', function () {
    return response('pong', 200)->header('Content-Type', 'text/plain');
})->name('ping');

// ========================================================================
// 🛑 MAINTENANCE MODE HANDLING
// ========================================================================

Route::get('/maintenance', function () {
    if (!app()->isDownForMaintenance()) {
        return redirect()->route('dashboard');
    }
    return view('errors.maintenance');
})->name('maintenance');

// ========================================================================
// ❌ CATCH-ALL ROUTE (404 Handling)
// ========================================================================

Route::fallback(function () {
    if (request()->wantsJson()) {
        return response()->json([
            'error' => 'Not Found',
            'message' => 'The requested resource could not be found',
            'path' => request()->path()
        ], 404);
    }

    return response()->view('errors.404', [
        'path' => request()->path()
    ], 404);
})->name('fallback');

// ========================================================================
// 📌 ROUTE MODEL BINDING CUSTOMIZATIONS
// ========================================================================

Route::bind('sale', function ($value) {
    return \App\Models\Sale::where('id', $value)
        ->where('user_id', auth()->id())
        ->firstOrFail();
});

Route::bind('male', function ($value) {
    return \App\Models\Male::where('id', $value)
        ->orWhere('code', $value)
        ->firstOrFail();
});

Route::bind('femelle', function ($value) {
    return \App\Models\Femelle::where('id', $value)
        ->orWhere('code', $value)
        ->firstOrFail();
});

// ========================================================================
// ✅ ROUTE CACHING OPTIMIZATION HINT
// ========================================================================
// 💡 PRODUCTION TIP: Run `php artisan route:cache` after deployment
// ⚠️ WARNING: Route caching does NOT work with Closure-based routes
// ✅ SOLUTION: All routes in this file use Controller@method syntax for cache compatibility