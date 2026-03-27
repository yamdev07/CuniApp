<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaleController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\FemelleController;
use App\Http\Controllers\SaillieController;
use App\Http\Controllers\MiseBasController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LapinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\NaissanceController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\SubscriptionManagementController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckFirmAdmin;
use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================================================================
// 🔓 PUBLIC ROUTES (No authentication required)
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
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Google Social Login
    Route::get('/customer/social-login/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])
        ->name('social-login.google.redirect');
    Route::get('/customer/social-login/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
        ->name('social-login.google.callback');

    // Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Password Reset Flow
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // Email Verification Code System
    Route::post('/verification/code/verify', [EmailVerificationCodeController::class, 'verify'])->name('verification.code.verify');
    Route::post('/verification/code/resend', [EmailVerificationCodeController::class, 'resend'])->name('verification.code.resend');
    Route::post('/verification/send-code', [EmailVerificationCodeController::class, 'sendCode'])->name('verification.send-code');
});

// ========================================================================
// 🪝 WEBHOOK ROUTES (Outside auth, CSRF disabled)
// ========================================================================
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    // FedaPay webhook - NO auth, signature verified in controller
    Route::post('/fedapay', [PaymentController::class, 'handleWebhook'])
        ->name('fedapay')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

// ========================================================================
// 🔐 AUTHENTICATED ROUTES (Login required)
// ========================================================================
Route::middleware('auth')->group(function () {
    // --- Routes accessibles à tous les connectés (vérifiés ou non) ---
    Route::get('/verify-email', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', function () {
        if (auth()->check() && auth()->user()) {
            auth()->user()->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        }
        return redirect()->route('login');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ====================================================================
    // 🛡️ FULLY VERIFIED ROUTES (Login + Email Verification Required)
    // ====================================================================
    Route::middleware('verified')->group(function () {
        // Dashboard & Profile
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Subscription Routes
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans');
            Route::get('/subscribe', [SubscriptionController::class, 'create'])->name('subscribe');
            Route::post('/purchase', [SubscriptionController::class, 'store'])->name('purchase');
            Route::get('/status', [SubscriptionController::class, 'show'])->name('status');
            Route::post('/renew', [SubscriptionController::class, 'renew'])->name('renew');
            Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        });

        // Payment Routes
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/initiate/{transaction_id}', [PaymentController::class, 'initiate'])->name('initiate');
            Route::post('/process', [PaymentController::class, 'process'])->name('process');
            // ✅ FEDAPAY CALLBACK (user redirect after payment)
            Route::get('/callback/{provider}', [PaymentController::class, 'callback'])->name('callback');
            Route::get('/verify/{transaction_id}', [PaymentController::class, 'verify'])->name('verify');
            // ⚠️ REMOVED: manualConfirm method doesn't exist in PaymentController
            // Route::post('/manual-confirm', [PaymentController::class, 'manualConfirm'])->name('manual-confirm');
        });

        // Protected CRUD Routes (Require active subscription)
        Route::middleware('check.subscription')->group(function () {
            // MÂLES
            Route::prefix('males')->name('males.')->group(function () {
                Route::get('/check-code', [MaleController::class, 'checkCode'])->name('check-code');
                Route::get('/', [MaleController::class, 'index'])->name('index');
                Route::get('/create', [MaleController::class, 'create'])->name('create');
                Route::post('/', [MaleController::class, 'store'])->name('store');
                Route::get('/{male}', [MaleController::class, 'show'])->name('show');
                Route::get('/{male}/edit', [MaleController::class, 'edit'])->name('edit');
                Route::put('/{male}', [MaleController::class, 'update'])->name('update');
                Route::delete('/{male}', [MaleController::class, 'destroy'])->name('destroy');
                Route::patch('/{male}/toggle-etat', [MaleController::class, 'toggleEtat'])->name('toggleEtat');
                // ⚠️ REMOVED: search method doesn't exist in MaleController
                // Route::get('/search/males', [MaleController::class, 'search'])->name('males.search');
            });

            // FEMELLES
            Route::prefix('femelles')->name('femelles.')->group(function () {
                Route::get('/check-code', [FemelleController::class, 'checkCode'])->name('check-code');
                Route::get('/', [FemelleController::class, 'index'])->name('index');
                Route::get('/create', [FemelleController::class, 'create'])->name('create');
                Route::post('/', [FemelleController::class, 'store'])->name('store');
                Route::get('/{femelle}', [FemelleController::class, 'show'])->name('show');
                Route::get('/{femelle}/edit', [FemelleController::class, 'edit'])->name('edit');
                Route::put('/{femelle}', [FemelleController::class, 'update'])->name('update');
                Route::delete('/{femelle}', [FemelleController::class, 'destroy'])->name('destroy');
                Route::patch('/{femelle}/toggle-etat', [FemelleController::class, 'toggleEtat'])->name('toggleEtat');
                // ⚠️ REMOVED: search method doesn't exist in FemelleController
                // Route::get('/search/femelles', [FemelleController::class, 'search'])->name('femelles.search');
            });

            // SAILLIES
            Route::prefix('saillies')->name('saillies.')->group(function () {
                Route::get('/', [SaillieController::class, 'index'])->name('index');
                Route::get('/create', [SaillieController::class, 'create'])->name('create');
                Route::post('/', [SaillieController::class, 'store'])->name('store');
                Route::get('/{saillie}', [SaillieController::class, 'show'])->name('show');
                Route::get('/{saillie}/edit', [SaillieController::class, 'edit'])->name('edit');
                Route::put('/{saillie}', [SaillieController::class, 'update'])->name('update');
                Route::delete('/{saillie}', [SaillieController::class, 'destroy'])->name('destroy');
                Route::patch('/{saillie}/palpation', [SaillieController::class, 'updatePalpation'])->name('palpation.update');
            });

            // MISES BAS
            Route::prefix('mises-bas')->name('mises-bas.')->group(function () {
                Route::get('/', [MiseBasController::class, 'index'])->name('index');
                Route::get('/create', [MiseBasController::class, 'create'])->name('create');
                Route::post('/', [MiseBasController::class, 'store'])->name('store');
                Route::get('/{miseBas}', [MiseBasController::class, 'show'])->name('show');
                Route::get('/{miseBas}/edit', [MiseBasController::class, 'edit'])->name('edit');
                Route::put('/{miseBas}', [MiseBasController::class, 'update'])->name('update');
                Route::delete('/{miseBas}', [MiseBasController::class, 'destroy'])->name('destroy');
            });

            // LAPINS
            Route::prefix('lapins')->name('lapins.')->group(function () {
                Route::get('/', [LapinController::class, 'index'])->name('index');
                Route::get('/create', [LapinController::class, 'create'])->name('create');
                Route::post('/', [LapinController::class, 'store'])->name('store');
                Route::get('/{id}', [LapinController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [LapinController::class, 'edit'])->name('edit');
                Route::put('/{id}', [LapinController::class, 'update'])->name('update');
                Route::delete('/{id}', [LapinController::class, 'destroy'])->name('destroy');
                Route::get('/check-code', [LapinController::class, 'checkCode'])->name('check-code');
            });

            // NAISSANCES
            Route::prefix('naissances')->name('naissances.')->group(function () {
                Route::get('/', [NaissanceController::class, 'index'])->name('index');
                Route::get('/create', [NaissanceController::class, 'create'])->name('create');
                Route::post('/', [NaissanceController::class, 'store'])->name('store');
                Route::get('/{naissance}', [NaissanceController::class, 'show'])->name('show');
                Route::get('/{naissance}/edit', [NaissanceController::class, 'edit'])->name('edit');
                Route::put('/{naissance}', [NaissanceController::class, 'update'])->name('update');
                Route::delete('/{naissance}', [NaissanceController::class, 'destroy'])->name('destroy');
            });

            // SALES
            Route::prefix('sales')->name('sales.')->group(function () {
                Route::get('/', [SaleController::class, 'index'])->name('index');
                Route::get('/create', [SaleController::class, 'create'])->name('create');
                Route::post('/', [SaleController::class, 'store'])->name('store');
                Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
                Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('edit');
                Route::put('/{sale}', [SaleController::class, 'update'])->name('update');
                Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
                Route::patch('/{sale}/mark-paid', [SaleController::class, 'markAsPaid'])->name('mark-paid');
                Route::post('/{sale}/partial-payment', [SaleController::class, 'recordPartialPayment'])->name('partial-payment');
                Route::post('/{sale}/change-status', [SaleController::class, 'changePaymentStatus'])->name('change-status');
                Route::delete('/bulk-delete', [SaleController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/export', [SaleController::class, 'export'])->name('export');
                Route::post('/load-rabbits', [SaleController::class, 'loadRabbits'])->name('load-rabbits');
            });
        });

        // Settings & Notifications (Require active subscription)
        Route::middleware('check.subscription')->group(function () {
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', [SettingsController::class, 'index'])->name('index');
                Route::post('/', [SettingsController::class, 'update'])->name('update');
                Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('updateProfile');
                Route::get('/export', [SettingsController::class, 'exportData'])->name('export');
                Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
            });

            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
                Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
                Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('activites')->name('activites.')->group(function () {
                Route::get('/', [ActiviteController::class, 'index'])->name('index');
                Route::delete('/{type}/{id}', [ActiviteController::class, 'destroy'])->name('destroy');
            });
        });

        // Invoice Routes (Require verification, optionally add check.subscription)
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('download');
            Route::post('/{invoice}/regenerate', [InvoiceController::class, 'regeneratePdf'])->name('regenerate');
            Route::post('/{invoice}/email', [InvoiceController::class, 'email'])->name('email');
        });

        // ========================================================================
        // 👑 FIRM ADMIN ROUTES (Inside auth group for consistency)
        // ========================================================================
        Route::middleware(['verified', 'check.firm.admin'])->prefix('firm')->name('firm.')->group(function () {
            Route::get('/', [FirmController::class, 'index'])->name('index');
            Route::post('/employee', [FirmController::class, 'storeEmployee'])->name('employee.store');
            Route::patch('/employee/{userId}', [FirmController::class, 'updateEmployee'])->name('employee.update');
            Route::patch('/employee/{userId}/deactivate', [FirmController::class, 'deactivateEmployee'])->name('employee.deactivate');
            Route::delete('/employee/{userId}', [FirmController::class, 'deleteEmployee'])->name('employee.delete');
            Route::patch('/update', [FirmController::class, 'updateFirm'])->name('update');
        });

        // ========================================================================
        // 🌟 SUPER ADMIN ROUTES (Inside auth group for consistency)
        // ========================================================================
        Route::middleware(['verified', 'check.super.admin'])->prefix('super-admin')->name('super.admin.')->group(function () {
            Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/firms', [SuperAdminController::class, 'firms'])->name('firms');
            Route::get('/firms/{id}', [SuperAdminController::class, 'showFirm'])->name('firms.show');
            Route::post('/firms/{id}/ban', [SuperAdminController::class, 'banFirm'])->name('firms.ban');
            Route::post('/firms/{id}/activate', [SuperAdminController::class, 'activateFirm'])->name('firms.activate');
        });


        // ====================================================================
        // EXPENSE ROUTES (Require active subscription)
        // ====================================================================
        Route::middleware('check.subscription')->prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', [ExpenseController::class, 'index'])->name('index');
            Route::get('/create', [ExpenseController::class, 'create'])->name('create');
            Route::post('/', [ExpenseController::class, 'store'])->name('store');
            Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        });
    }); // <--- FIN DU GROUPE VERIFIED

    // ========================================================================
    // 👑 ADMIN ROUTES (HORS GROUPE VERIFIED - For subscription management)
    // ========================================================================
    Route::middleware('check.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            // 1. ROUTES FIXES (Doivent être en PREMIER)
            Route::get('/', [SubscriptionManagementController::class, 'index'])->name('index');
            Route::get('/archives', [SubscriptionManagementController::class, 'archives'])->name('archives');
            Route::get('/transactions', [SubscriptionManagementController::class, 'transactions'])->name('transactions');
            Route::get('/export', [SubscriptionManagementController::class, 'export'])->name('export');

            // 2. ROUTES DYNAMIQUES (Doivent être APRÈS les fixes)
            Route::get('/{userId}', [SubscriptionManagementController::class, 'show'])->name('show');

            // 3. ROUTES POST/DELETE (Peuvent être à la fin)
            Route::post('/activate', [SubscriptionManagementController::class, 'activate'])->name('activate');
            Route::post('/deactivate', [SubscriptionManagementController::class, 'deactivate'])->name('deactivate');
            Route::post('/extend', [SubscriptionManagementController::class, 'extend'])->name('extend');
            Route::post('/{id}/archive', [SubscriptionManagementController::class, 'archive'])->name('archive');
            Route::post('/{id}/restore', [SubscriptionManagementController::class, 'restore'])->name('restore');
            Route::delete('/{id}/destroy', [SubscriptionManagementController::class, 'destroy'])->name('destroy');
        });
    });
}); // <--- FIN DU GROUPE AUTH

// ========================================================================
// 🔍 UTILITY ROUTES
// ========================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // ⚠️ REMOVED: search methods don't exist in controllers
    // Route::get('/search/males', [MaleController::class, 'search'])->name('males.search');
    // Route::get('/search/femelles', [FemelleController::class, 'search'])->name('femelles.search');
});

// ========================================================================
// 🌐 LOCALIZATION ROUTES
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
// 🤖 SYSTEM & SEO ROUTES
// ========================================================================
Route::get('/robots.txt', function () {
    return response("User-agent: *
Disallow: /admin/
Disallow: /settings/
Sitemap: " . url('/sitemap.xml'), 200, ['Content-Type' => 'text/plain']);
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
    return response($xml, 200, ['Content-Type' => 'application/xml']);
});

// ========================================================================
// 🔁 LEGACY ROUTE ALIASES
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
// 🚨 SYSTEM HEALTH CHECK ROUTES
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
    return response()->view('errors.404', ['path' => request()->path()], 404);
})->name('fallback');
