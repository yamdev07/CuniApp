<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CuniApp Élevage')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --primary: #2563EB;
            --primary-light: #3B82F6;
            --primary-dark: #1D4ED8;
            --primary-subtle: #EFF6FF;
            --accent-cyan: #06B6D4;
            --accent-purple: #8B5CF6;
            --accent-pink: #EC4899;
            --accent-green: #10B981;
            --accent-orange: #F59E0B;
            --accent-red: #EF4444;
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
            --surface: #FFFFFF;
            --surface-alt: #F9FAFB;
            --surface-border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-tertiary: #9CA3AF;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        .theme-dark {
            --surface: #0A0F1D;
            --surface-alt: #0F172A;
            --surface-elevated: #151E30;
            --surface-border: #25324A;
            --text-primary: #E6E9F0;
            --text-secondary: #A3B3C6;
            --text-tertiary: #6B7D95;
            --primary: #4DA6FF;
            --primary-subtle: rgba(77, 166, 255, 0.12);
            --accent-green: #34D399;
            --accent-orange: #FB923C;
            --accent-red: #F87171;
            --gray-50: #080C15;
            --gray-100: #0F172A;
            --gray-200: #1A2335;
            background-color: var(--surface);
            color: var(--text-primary);
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

        .header-nav {
            display: flex;
            gap: 4px;
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

        .dropdown-container {
            position: relative;
        }

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 200px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
            overflow: hidden;
            animation: slideIn 0.2s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item-custom:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .dropdown-item-custom i {
            width: 20px;
            text-align: center;
        }

        .notification-trigger {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            background: var(--gray-50);
            border: 1px solid var(--surface-border);
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-trigger:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }

        .notification-trigger i {
            font-size: 18px;
            color: var(--text-secondary);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--accent-red);
            color: white;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--surface);
        }

        .user-profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
            background: var(--gray-50);
            border: 1px solid var(--surface-border);
        }

        .user-trigger:hover {
            background: var(--gray-100);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .cuni-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            min-height: calc(100vh - 200px);
        }

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

        @media (max-width: 768px) {
            .header-wrapper {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }

            .header-nav {
                width: 100%;
                justify-content: flex-start;
                overflow-x: auto;
            }

            .cuni-main {
                padding: 16px;
            }
        }

        .theme-dark .header-nav div[x-show="open"] {
            background: var(--surface-overlay) !important;
            border-color: var(--surface-border) !important;
        }

        .theme-dark .nav-link {
            color: var(--text-secondary);
        }

        .theme-dark .nav-link:hover {
            background: var(--hover-subtle);
            color: var(--text-primary);
        }

        .theme-dark .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .theme-dark .dropdown-menu-custom {
            background: var(--surface-overlay);
            border: 1px solid var(--surface-border);
        }

        .theme-dark .dropdown-item-custom {
            color: var(--text-primary);
        }

        .theme-dark .dropdown-item-custom:hover {
            background: var(--hover-subtle);
            color: var(--primary);
        }

        .theme-dark .notification-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .notification-trigger:hover {
            background: var(--hover-subtle);
        }

        .theme-dark .user-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .user-trigger:hover {
            background: var(--hover-subtle);
        }
    </style>
</head>

<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">
    <header class="cuni-header">
        <div class="header-wrapper">
            <div class="brand-identity">
                <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                    <svg viewBox="0 0 40 40" fill="none">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                    </svg>
                </a>
                <div>
                    <div class="brand-title">CuniApp <span>Élevage</span></div>
                    <div class="brand-tagline">Gestion intelligente de votre cheptel</div>
                </div>
            </div>
            <nav class="header-nav">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('males.index') }}"
                    class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-right-square"></i> Mâles
                </a>
                <a href="{{ route('femelles.index') }}"
                    class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-right-square"></i> Femelles
                </a>
                <a href="{{ route('mises-bas.index') }}"
                    class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i> Mises Bas
                </a>
                <a href="{{ route('sales.index') }}"
                    class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i> Ventes
                </a>
                <div class="dropdown-container">
                    <button class="nav-link" onclick="toggleMoreDropdown()">
                        <i class="bi bi-three-dots"></i> Plus
                        <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="moreDropdown">
                        <a href="{{ route('saillies.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-heart"></i> Saillies
                        </a>
                        <a href="{{ route('lapins.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-collection"></i> Tous les Lapins
                        </a>
                        <a href="{{ route('notifications.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-bell"></i> Notifications
                        </a>
                        <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-gear"></i> Paramètres
                        </a>
                    </div>
                </div>
                @auth
                    <a href="{{ route('notifications.index') }}" class="notification-trigger" style="margin-left: 8px;">
                        <i class="bi bi-bell"></i>
                        @php
                            $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if ($unreadNotifications > 0)
                            <span
                                class="notification-badge">{{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}</span>
                        @endif
                    </a>
                    <div class="user-profile-dropdown">
                        <div class="user-trigger" onclick="toggleUserDropdown()">
                            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu-custom" id="userDropdown">
                            <div style="padding: 12px 16px; border-bottom: 1px solid var(--surface-border);">
                                <span
                                    style="display: block; font-size: 13px; font-weight: 600; color: var(--gray-800);">{{ auth()->user()->name }}</span>
                                <small
                                    style="color: var(--text-secondary); font-size: 11px;">{{ auth()->user()->email }}</small>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item-custom">
                                <i class="bi bi-person"></i> Profil
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                                <i class="bi bi-gear"></i> Paramètres
                            </a>
                            <hr style="border-color: var(--surface-border); margin: 8px 0;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item-custom" style="color: var(--accent-red);">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>
    </header>
    <main class="cuni-main">
        @yield('content')
    </main>
    <footer class="cuni-footer">
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} CuniApp Élevage - Tous droits réservés</p>
            <div class="footer-links">
                <a href="{{ route('settings.index') }}">Paramètres</a>
                <a href="{{ route('profile.edit') }}">Profil</a>
            </div>
        </div>
    </footer>
    @stack('scripts')
    <script>
        function toggleMoreDropdown() {
            const dropdown = document.getElementById('moreDropdown');
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) userDropdown.classList.remove('show');
            dropdown.classList.toggle('show');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            if (moreDropdown) moreDropdown.classList.remove('show');
            dropdown.classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            const moreDropdown = document.getElementById('moreDropdown');
            const userDropdown = document.getElementById('userDropdown');
            const moreTrigger = e.target.closest('.dropdown-container');
            const userTrigger = e.target.closest('.user-profile-dropdown');
            if (moreDropdown && !moreTrigger) {
                moreDropdown.classList.remove('show');
            }
            if (userDropdown && !userTrigger) {
                userDropdown.classList.remove('show');
            }
        });
    </script>
</body>

</html>