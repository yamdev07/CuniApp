<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de Bord Élevage - CuniApp')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            /* Primary Colors */
            --primary: #2563EB;
            --primary-light: #3B82F6;
            --primary-dark: #1D4ED8;
            --primary-subtle: #EFF6FF;
            
            /* Accent Colors */
            --accent-cyan: #06B6D4;
            --accent-purple: #8B5CF6;
            --accent-pink: #EC4899;
            --accent-green: #10B981;
            --accent-orange: #F59E0B;
            --accent-red: #EF4444;
            
            /* Neutral Colors */
            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            
            /* Semantic Colors */
            --surface: #FFFFFF;
            --surface-alt: #F9FAFB;
            --surface-border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-tertiary: #9CA3AF;
            
            /* Effects */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ==================== HEADER ==================== */
        .cuni-header {
            background: var(--surface);
            border-bottom: 1px solid var(--surface-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .cuniapp-logo {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: var(--radius-md);
            transition: transform 0.2s ease;
        }

        .cuniapp-logo:hover {
            transform: scale(1.05);
        }

        .cuniapp-logo svg {
            width: 28px;
            height: 28px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.02em;
        }

        .brand-title span {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .brand-tagline {
            font-size: 12px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* Navigation */
        .header-nav {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius);
            text-decoration: none;
            transition: all 0.2s ease;
            color: var(--text-secondary);
            background: transparent;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: var(--gray-100);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
            border-color: var(--primary-subtle);
        }

        .nav-link i {
            font-size: 16px;
        }

        .nav-link.danger {
            color: var(--accent-red);
        }

        .nav-link.danger:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* ==================== MAIN CONTENT ==================== */
        .cuni-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            min-height: calc(100vh - 200px);
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.02em;
        }

        .page-title i {
            color: var(--primary);
            font-size: 26px;
        }

        .breadcrumb {
            display: flex;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 8px;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .breadcrumb span {
            color: var(--text-tertiary);
        }

        /* ==================== CARDS ==================== */
        .cuni-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--surface-border);
            background: var(--surface-alt);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
        }

        .card-body {
            padding: 24px;
        }

        /* ==================== BUTTONS ==================== */
        .btn-cuni {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-cuni.primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .btn-cuni.primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .btn-cuni.secondary {
            background: var(--white);
            color: var(--text-primary);
            border: 1px solid var(--surface-border);
        }

        .btn-cuni.secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .btn-cuni.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-cuni.danger:hover {
            background: rgba(239, 68, 68, 0.15);
        }

        .btn-cuni.sm {
            padding: 8px 14px;
            font-size: 13px;
        }

        /* ==================== FORMS ==================== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            background: var(--white);
            color: var(--text-primary);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }

        .form-control::placeholder {
            color: var(--text-tertiary);
        }

        /* ==================== ALERTS ==================== */
        .alert-cuni {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
        }

        .alert-cuni i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-cuni.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--accent-green);
        }

        .alert-cuni.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
        }

        /* ==================== TABS ==================== */
        .tabs-container {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--surface-border);
            padding-bottom: 0;
            overflow-x: auto;
            background: var(--surface);
            border-radius: var(--radius-md) var(--radius-md) 0 0;
            padding: 8px 8px 0 8px;
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .tab-btn:hover {
            color: var(--text-primary);
            background: var(--gray-50);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: var(--primary-subtle);
        }

        .tab-btn i {
            margin-right: 6px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ==================== GRID ==================== */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        /* ==================== FOOTER ==================== */
        .cuni-footer {
            background: var(--surface);
            border-top: 1px solid var(--surface-border);
            padding: 24px;
            margin-top: 40px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-content p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .footer-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--gray-100);
        }

        .footer-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-tertiary);
        }

        .footer-stat i {
            color: var(--primary);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .header-wrapper {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }

            .header-nav {
                width: 100%;
                justify-content: flex-start;
            }

            .cuni-main {
                padding: 16px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .tabs-container {
                padding: 4px 4px 0 4px;
            }

            .tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .brand-title {
                font-size: 18px;
            }

            .nav-link {
                padding: 8px 12px;
                font-size: 13px;
            }

            .footer-links {
                flex-direction: column;
                gap: 12px;
            }

            .footer-stats {
                flex-direction: column;
                gap: 8px;
            }
        }

        /* ==================== ENHANCED TABLES ==================== */
.table-responsive {
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--surface-border);
}

.table {
    margin-bottom: 0;
    width: 100%;
}

.table thead {
    background: var(--surface-alt);
    border-bottom: 2px solid var(--surface-border);
}

.table thead th {
    padding: 14px 16px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
    border: none;
}

.table tbody tr {
    border-bottom: 1px solid var(--surface-border);
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: var(--primary-subtle);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    font-size: 14px;
    color: var(--text-primary);
}

/* Empty State */
.table-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--surface-alt);
}

.table-empty-state i {
    font-size: 3rem;
    color: var(--text-tertiary);
    margin-bottom: 16px;
    display: block;
}

.table-empty-state p {
    color: var(--text-secondary);
    font-size: 14px;
    margin-bottom: 20px;
}

.table-empty-state .btn-cuni {
    margin-top: 8px;
}

/* Badge Improvements */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Status Colors */
.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.status-gestante {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.status-allaitante {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.status-vide {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.action-buttons .btn-cuni {
    padding: 6px 10px;
    font-size: 12px;
}

/* Pagination */
.pagination {
    display: flex;
    gap: 4px;
    justify-content: center;
    margin-top: 20px;
}

.pagination .page-item .page-link {
    padding: 8px 14px;
    border: 1px solid var(--surface-border);
    border-radius: var(--radius);
    color: var(--text-secondary);
    background: var(--white);
    transition: all 0.2s ease;
}

.pagination .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: var(--white);
}

.pagination .page-item:hover .page-link {
    background: var(--primary-subtle);
    border-color: var(--primary);
    color: var(--primary);
}

/* Responsive Table */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        min-width: 800px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
    </style>
</head>
<body>
    <!-- Header -->
    <header class="cuni-header">
        <div class="header-wrapper">
            <div class="brand-identity">
                <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                    <svg viewBox="0 0 40 40" fill="none">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/>
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/>
                    </svg>
                </a>
                <div>
                    <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                    <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                </div>
            </div>
            
            <nav class="header-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('males.index') }}" class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Mâles</span>
                </a>
                <a href="{{ route('femelles.index') }}" class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-right-square"></i>
                    <span>Femelles</span>
                </a>
                <a href="{{ route('saillies.index') }}" class="nav-link {{ request()->routeIs('saillies.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span>Saillies</span>
                </a>
                <a href="{{ route('mises-bas.index') }}" class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i>
                    <span>Mises Bas</span>
                </a>
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Paramètres</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link danger" style="border: none; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="cuni-main">
        @if(session('success'))
        <div class="alert-cuni success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="cuni-footer">
        <div class="footer-content">
            <div>
                <p>&copy; {{ date('Y') }} CuniApp Élevage - Tous droits réservés</p>
                <div class="footer-stats">
                    <div class="footer-stat">
                        <i class="bi bi-clock"></i>
                        <span>Dernière connexion: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="footer-stat">
                        <i class="bi bi-server"></i>
                        <span>Version: {{ config('app.version', '1.0.0') }}</span>
                    </div>
                </div>
            </div>
            <div class="footer-links">
                <a href="#"><i class="bi bi-book"></i> Documentation</a>
                <a href="#"><i class="bi bi-headset"></i> Support</a>
                <a href="#"><i class="bi bi-shield-check"></i> Confidentialité</a>
            </div>
        </div>
    </footer>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>

    <!-- Global Modal System -->
@include('components.modal-system')

@if(session('verification_pending'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        openVerificationModal('{{ session('verification_email') }}');
    }, 500);
});
</script>
@endif
</body>
</html>