<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de Bord Élevage - CuniApp')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --cuni-light: #FFFFFF;
            --cuni-lighter: #F8FAFC;
            --cuni-gray: #F1F5F9;
            --cuni-blue: #0066FF;
            --cuni-cyan: #00D9FF;
            --cuni-purple: #8B5CF6;
            --cuni-pink: #EC4899;
            --cuni-green: #10B981;
            --cuni-orange: #F59E0B;
            --grad-primary: linear-gradient(135deg, #00D9FF 0%, #0066FF 100%);
            --surface-1: #FFFFFF;
            --surface-2: #F8FAFC;
            --surface-3: #F1F5F9;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --text-tertiary: #94A3B8;
            --shadow-1: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-2: 0 10px 25px rgba(0, 0, 0, 0.1);
            --border-light: 1px solid rgba(0, 0, 0, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Space Mono', monospace;
            background: var(--cuni-gray);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header Styles */
        .cuni-header {
            background: var(--surface-1);
            border-radius: 0 0 24px 24px;
            margin-bottom: 32px;
            border: var(--border-light);
            box-shadow: var(--shadow-1);
            position: relative;
        }

        .cuni-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--grad-primary);
            border-radius: 24px 24px 0 0;
        }

        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 32px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cuniapp-logo {
            width: 48px;
            height: 48px;
        }

        .cuniapp-logo svg {
            width: 100%;
            height: 100%;
        }

        .brand-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            font-weight: 900;
            background: var(--grad-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .header-nav {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s;
            color: var(--text-primary);
            background: var(--surface-2);
            border: var(--border-light);
        }

        .nav-link:hover {
            background: var(--surface-3);
            border-color: var(--cuni-cyan);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: var(--grad-primary);
            color: white;
            border-color: transparent;
        }

        .nav-link.danger {
            color: var(--cuni-orange);
        }

        .nav-link.danger:hover {
            background: rgba(245, 158, 11, 0.1);
        }

        /* Main Content */
        .cuni-main {
            max-width: 1800px;
            margin: 0 auto;
            padding: 0 32px 32px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Footer */
        .cuni-footer {
            background: var(--surface-1);
            border-radius: 24px 24px 0 0;
            padding: 24px 32px;
            margin-top: 32px;
            border: var(--border-light);
            box-shadow: var(--shadow-1);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            max-width: 1800px;
            margin: 0 auto;
        }

        .footer-content p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--cuni-blue);
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            color: var(--cuni-cyan);
        }

        .breadcrumb {
            display: flex;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 8px;
        }

        .breadcrumb a {
            color: var(--cuni-blue);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Cards */
        .cuni-card {
            background: var(--surface-1);
            border-radius: 20px;
            padding: 32px;
            border: var(--border-light);
            box-shadow: var(--shadow-1);
            margin-bottom: 24px;
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: var(--border-light);
        }

        .card-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Buttons */
        .btn-cuni {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-cuni.primary {
            background: var(--grad-primary);
            color: white;
            box-shadow: 0 0 20px rgba(0, 102, 255, 0.2);
        }

        .btn-cuni.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 102, 255, 0.4);
        }

        .btn-cuni.secondary {
            background: var(--surface-2);
            color: var(--text-primary);
            border: var(--border-light);
        }

        .btn-cuni.secondary:hover {
            border-color: var(--cuni-cyan);
            background: var(--surface-3);
        }

        .btn-cuni.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-cuni.danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            border: var(--border-light);
            border-radius: 10px;
            background: var(--surface-2);
            color: var(--text-primary);
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--cuni-cyan);
            box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.1);
        }

        .form-select {
            @extend .form-control;
        }

        /* Alerts */
        .alert-cuni {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-cuni.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--cuni-green);
        }

        .alert-cuni.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }

        /* Tabs */
        .tabs-container {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            border-bottom: var(--border-light);
            padding-bottom: 0;
            overflow-x: auto;
        }

        .tab-btn {
            padding: 12px 20px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--cuni-cyan);
        }

        .tab-btn.active {
            color: var(--cuni-blue);
            border-bottom-color: var(--cuni-blue);
        }

        .tab-btn i {
            margin-right: 8px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        /* Grid */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-nav {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .cuni-main {
                padding: 0 20px 20px;
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
                gap: 12px;
            }

            .tab-btn {
                padding: 10px 16px;
                font-size: 12px;
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
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="url(#logoGrad)"/>
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="#FFFFFF"/>
                        <defs>
                            <linearGradient id="logoGrad" x1="5" y1="5" x2="35" y2="35">
                                <stop offset="0%" stop-color="#00D9FF"/>
                                <stop offset="100%" stop-color="#0066FF"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </a>
                <div>
                    <h1 class="brand-title">CuniApp <span style="font-weight: 500; opacity: 0.8;">Élevage</span></h1>
                    <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('males.index') }}" class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-right-square"></i> Mâles
                </a>
                <a href="{{ route('femelles.index') }}" class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-right-square"></i> Femelles
                </a>
                <a href="{{ route('saillies.index') }}" class="nav-link {{ request()->routeIs('saillies.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Saillies
                </a>
                <a href="{{ route('mises-bas.index') }}" class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i> Mises Bas
                </a>
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Paramètres
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link danger" style="border: none; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
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
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-cuni error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="cuni-footer">
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} CuniApp Élevage - Tous droits réservés</p>
            <div class="footer-links">
                <a href="#">Documentation</a>
                <a href="#">Support</a>
                <a href="#">Confidentialité</a>
            </div>
        </div>
    </footer>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>