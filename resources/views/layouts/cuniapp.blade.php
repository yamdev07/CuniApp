<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CuniApp Élevage')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --surface-overlay: #1E293B;
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

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-width: 100%; width: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            min-width: 320px;
        }

        .cuni-header {
            background: var(--surface);
            border-bottom: 1px solid var(--surface-border);
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            min-width: 100%;
        }

        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
            padding: 0 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 12px;
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

        .cuniapp-logo:hover { transform: scale(1.05); }
        .cuniapp-logo svg { width: 28px; height: 28px; }

        .brand-info { display: flex; flex-direction: column; }
        .brand-title {
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.1;
            color: var(--gray-800);
        }
        .theme-dark .brand-title { color: #FFFFFF !important; }
        .brand-tagline {
            display: block !important;
            font-size: 0.7rem;
            color: var(--text-secondary);
            opacity: 0.8;
            margin: 0;
            padding: 5px;
        }

        .nav-main-links {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
            color: var(--text-secondary);
            border-radius: var(--radius);
        }

        .nav-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .nav-user-side {
            display: flex;
            align-items: center;
            gap: 15px;
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

        .user-profile-dropdown { position: relative; display: inline-block; }

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

        .user-trigger:hover { background: var(--gray-100); }

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

        .dropdown-container { position: relative; }

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 280px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
            overflow: hidden;
            animation: slideIn 0.2s ease-out;
            padding: 8px;
        }

        #moreDropdown { left: 0; right: auto; }
        .dropdown-menu-custom.show { display: block !important; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--surface-border);
            background: var(--surface-alt);
            margin-bottom: 4px;
        }

        .dropdown-header span {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .dropdown-header small {
            font-size: 12px;
            opacity: 0.7;
            display: block;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            margin: 4px 0;
            border-radius: var(--radius);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
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

        .dropdown-item-custom.danger:hover {
            background: rgba(239, 68, 68, 0.05);
            color: var(--accent-red);
        }

        .theme-switch-row { cursor: pointer; }

        .theme-status-badge {
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.1);
            color: var(--text-secondary);
        }

        .cuni-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            min-height: calc(100vh - 200px);
            flex: 1;
            width: 100%;
            min-width: 320px;
        }

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

        .theme-dark .card-title { color: var(--white); }
        .card-title i { color: var(--primary); }

        .card-body { padding: 24px; }

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

        .theme-dark .btn-cuni.secondary {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
            color: var(--text-secondary);
        }

        .theme-dark .btn-cuni.secondary:hover {
            background: var(--gray-200);
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-cuni.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-cuni.danger:hover {
            background: var(--accent-red);
            color: var(--white);
            border-color: var(--accent-red);
        }

        .btn-cuni.sm { padding: 8px 14px; font-size: 13px; }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }

        .theme-dark .form-label { color: var(--text-secondary); }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-primary);
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .theme-dark .form-control, .theme-dark .form-select {
            background-color: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-primary);
            box-shadow: none;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: var(--white);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }

        .theme-dark .form-control:focus, .theme-dark .form-select:focus {
            background-color: var(--surface-elevated);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(77, 166, 255, 0.15);
        }

        .form-control::placeholder { color: var(--text-tertiary); }

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

        .theme-dark .page-title { color: var(--white); }
        .page-title i { color: var(--primary); font-size: 26px; }

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

        .breadcrumb span { color: var(--text-tertiary); }

        .table-responsive { overflow-x: auto; }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--surface-border);
        }

        .table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--surface-border);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: var(--surface-alt);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            border: none;
        }

        .status-active { background: rgba(16, 185, 129, 0.1); color: var(--accent-green); }
        .status-inactive { background: rgba(107, 114, 128, 0.1); color: var(--gray-500); }
        .status-gestante { background: rgba(236, 72, 153, 0.1); color: var(--accent-pink); }
        .status-allaitante { background: rgba(139, 92, 246, 0.1); color: var(--accent-purple); }
        .status-vide { background: rgba(59, 130, 246, 0.1); color: var(--primary-light); }
        .status-malade { background: rgba(239, 68, 68, 0.1); color: var(--accent-red); }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .table-empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-tertiary);
        }

        .table-empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .table-empty-state p {
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-cuni {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
        }

        .alert-cuni i { font-size: 18px; flex-shrink: 0; margin-top: 2px; }
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

        /* ==================== FOOTER ==================== */
        .cuni-footer {
            background: var(--surface);
            border-top: 1px solid var(--surface-border);
            padding: 60px 0 30px 0;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand { display: flex; flex-direction: column; gap: 16px; }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .footer-logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-logo-icon svg { width: 28px; height: 28px; }

        .footer-logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .footer-logo-text span { color: var(--primary); }

        .footer-tagline {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.6;
            max-width: 300px;
        }

        .footer-section h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-section h4 i { color: var(--primary); }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links li a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links li a:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        .footer-links li a i {
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .footer-links li a:hover i { opacity: 1; }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 1px solid var(--surface-border);
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-copyright {
            font-size: 0.85rem;
            color: var(--text-tertiary);
        }

        .footer-copyright a {
            color: var(--primary);
            text-decoration: none;
        }

        .footer-legal {
            display: flex;
            gap: 24px;
        }

        .footer-legal a {
            font-size: 0.85rem;
            color: var(--text-tertiary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-legal a:hover { color: var(--primary); }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            background: var(--primary-dark);
        }

        .back-to-top.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 30px; }
        }

        @media (max-width: 768px) {
            .header-wrapper {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }
            .nav-main-links { display: none; }
            .footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            .footer-legal { justify-content: center; }
            .cuni-main { padding: 16px; }
            .dropdown-menu-custom {
                position: fixed !important;
                top: 75px !important;
                left: 15px !important;
                right: 15px !important;
                width: auto !important;
            }
        }

        /* Dark mode specific overrides */
        .theme-dark .cuni-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }

        .theme-dark .nav-link { color: #94a3b8; }
        .theme-dark .nav-link:hover {
            background: var(--hover-subtle);
            color: var(--text-primary);
        }
        .theme-dark .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .theme-dark .dropdown-menu-custom {
            background: var(--surface-overlay) !important;
            border-color: var(--surface-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }

        .theme-dark .dropdown-item-custom { color: var(--text-primary); }

        .theme-dark .dropdown-header {
            background: var(--surface-elevated) !important;
            padding: 20px 24px;
        }

        .theme-dark .dropdown-item-custom:hover {
            background: var(--surface-overlay) !important;
            transform: translateX(4px);
            transition: all 0.2s ease;
        }

        .theme-dark .notification-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .notification-trigger:hover { background: var(--hover-subtle); }

        .theme-dark .user-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .user-trigger:hover { background: var(--hover-subtle); }

        .theme-dark .card-header-custom {
            background-color: var(--surface-alt) !important;
            border-bottom-color: var(--surface-border) !important;
        }

        .d-md-none { display: none; }

        @media (max-width: 1024px) {
            .d-md-none { display: block; }
        }
    </style>
</head>
<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">
    <header class="cuni-header">
        <div class="header-wrapper">
            <div class="brand-identity">
                <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                    <svg viewBox="0 0 40 40" fill="none" style="width: 40px; height: 40px;">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                    </svg>
                </a>
                <div class="brand-info">
                    <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                    <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                </div>
            </div>

            <nav class="nav-main-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('males.index') }}" class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Mâles</span>
                </a>
                <a href="{{ route('femelles.index') }}" class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-right-square"></i>
                    <span>Femelles</span>
                </a>
                <a href="{{ route('lapins.index') }}" class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i>
                    <span>Tous les Lapins</span>
                </a>
                <a href="{{ route('mises-bas.index') }}" class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i>
                    <span>Mises Bas</span>
                </a>
                <div class="dropdown-container">
                    <button class="nav-link" onclick="toggleMoreDropdown()">
                        <i class="bi bi-three-dots"></i>
                        <span>Plus</span>
                        <i class="bi bi-chevron-down" style="font-size: 10px;"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="moreDropdown">
                        <a href="{{ route('saillies.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-heart"></i> Saillies
                        </a>
                        <a href="{{ route('sales.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-cart"></i> Ventes
                        </a>
                        <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-gear"></i> Paramètres
                        </a>
                    </div>
                </div>
            </nav>

            <div class="mobile-menu-trigger d-md-none" onclick="toggleMoreDropdown()" style="cursor:pointer; font-size: 24px; color: var(--text-secondary); margin-right: 15px;">
                <i class="bi bi-list"></i>
            </div>

            @auth
            <div class="nav-user-side">
                <a href="{{ route('notifications.index') }}" class="notification-trigger">
                    <i class="bi bi-bell"></i>
                    @php
                        $unread = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if ($unread > 0)
                        <span class="notification-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                    @endif
                </a>

                <div class="user-profile-dropdown">
                    <div class="user-trigger" onclick="toggleUserDropdown()">
                        <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="dropdown-menu-custom" id="userDropdown">
                        <div class="dropdown-header">
                            <span>{{ auth()->user()->name }}</span>
                            <small>{{ auth()->user()->email }}</small>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item-custom">
                            <i class="bi bi-person"></i> Profil
                        </a>
                        <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-gear"></i> Paramètres
                        </a>

                        <!-- Theme Selection -->
                        <div class="dropdown-item-custom theme-switch-row" id="theme-selector" style="cursor: pointer;">
                            <div class="theme-info" style="display: flex; align-items: center; gap: 10px; flex: 1;">
                                <i class="bi bi-palette" id="theme-icon-main" style="color: var(--primary);"></i>
                                <span>Thème</span>
                            </div>
                            <div class="theme-status-badge" id="theme-badge">
                                <span id="theme-text">{{ ucfirst(auth()->user()->theme ?? 'system') }}</span>
                            </div>
                        </div>

                        <!-- Theme Submenu -->
                        <div class="dropdown-menu-custom" id="themeSubmenu" style="display: none; right: 100%; top: 0; margin-right: 8px; width: 180px;">
                            <button class="dropdown-item-custom" onclick="setTheme('system')" data-theme="system">
                                <i class="bi bi-display"></i> Système
                            </button>
                            <button class="dropdown-item-custom" onclick="setTheme('light')" data-theme="light">
                                <i class="bi bi-sun"></i> Clair
                            </button>
                            <button class="dropdown-item-custom" onclick="setTheme('dark')" data-theme="dark">
                                <i class="bi bi-moon-stars"></i> Sombre
                            </button>
                        </div>

                        <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 8px 0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item-custom logout-btn" style="width: 100%;">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    <main class="cuni-main">
        @yield('content')
    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="cuni-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <svg viewBox="0 0 40 40" fill="none">
                                <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/>
                                <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/>
                            </svg>
                        </div>
                        <div class="footer-logo-text">CuniApp <span>Élevage</span></div>
                    </div>
                    <p class="footer-tagline">
                        La solution complète pour la gestion intelligente de votre élevage de lapins. Suivez vos reproductions, naissances et performances en toute simplicité.
                    </p>
                </div>
                <div class="footer-section">
                    <h4><i class="bi bi-compass"></i> Navigation</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}"><i class="bi bi-chevron-right"></i> Tableau de bord</a></li>
                        <li><a href="{{ route('males.index') }}"><i class="bi bi-chevron-right"></i> Mâles</a></li>
                        <li><a href="{{ route('femelles.index') }}"><i class="bi bi-chevron-right"></i> Femelles</a></li>
                        <li><a href="{{ route('lapins.index') }}"><i class="bi bi-chevron-right"></i> Tous les Lapins</a></li>
                        <li><a href="{{ route('mises-bas.index') }}"><i class="bi bi-chevron-right"></i> Mises Bas</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4><i class="bi bi-briefcase"></i> Gestion</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('saillies.index') }}"><i class="bi bi-chevron-right"></i> Saillies</a></li>
                        <li><a href="{{ route('sales.index') }}"><i class="bi bi-chevron-right"></i> Ventes</a></li>
                        <li><a href="{{ route('notifications.index') }}"><i class="bi bi-chevron-right"></i> Notifications</a></li>
                        <li><a href="{{ route('settings.index') }}"><i class="bi bi-chevron-right"></i> Paramètres</a></li>
                        <li><a href="{{ route('profile.edit') }}"><i class="bi bi-chevron-right"></i> Mon Profil</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4><i class="bi bi-envelope"></i> Contact</h4>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ \App\Models\Setting::get('farm_address', 'Adresse non renseignée') }}</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-telephone"></i>
                            <a href="tel:{{ \App\Models\Setting::get('farm_phone', '') }}">{{ \App\Models\Setting::get('farm_phone', 'Non renseigné') }}</a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:{{ \App\Models\Setting::get('farm_email', config('mail.from.address')) }}">{{ \App\Models\Setting::get('farm_email', config('mail.from.address')) }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">CuniApp Élevage</a>. Tous droits réservés.
                </div>
                <div class="footer-legal">
                    <a href="{{ route('privacy') }}">Confidentialité</a>
                    <a href="{{ route('terms') }}">Conditions</a>
                    <a href="{{ route('contact') }}">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <button id="backToTop" class="back-to-top" title="Retour en haut">
        <i class="bi bi-arrow-up-short"></i>
    </button>

    @stack('scripts')
    <script>
        // ==================== THEME MANAGEMENT (SYSTEM, LIGHT, DARK) ====================
        const themeSelector = document.getElementById('theme-selector');
        const themeSubmenu = document.getElementById('themeSubmenu');
        const themeText = document.getElementById('theme-text');
        const themeBadge = document.getElementById('theme-badge');
        const themeIcon = document.getElementById('theme-icon-main');

        function getSystemTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function initTheme() {
            const userTheme = '{{ auth()->check() ? auth()->user()->theme : "system" }}';
            const savedTheme = localStorage.getItem('color-theme') || userTheme || 'system';
            let currentTheme = savedTheme;

            if (currentTheme === 'system') {
                currentTheme = getSystemTheme();
            }

            applyThemeVisuals(currentTheme, savedTheme);
        }

        function applyThemeVisuals(actualTheme, savedTheme) {
            if (actualTheme === 'dark') {
                document.documentElement.classList.add('theme-dark');
                if (themeText) themeText.innerText = 'Sombre';
                if (themeIcon) themeIcon.className = 'bi bi-moon-fill';
            } else {
                document.documentElement.classList.remove('theme-dark');
                if (themeText) themeText.innerText = savedTheme === 'system' ? 'Système' : 'Clair';
                if (themeIcon) themeIcon.className = savedTheme === 'system' ? 'bi bi-display' : 'bi bi-sun';
            }

            if (themeBadge) {
                themeBadge.querySelector('span').innerText = ucfirst(savedTheme);
            }
        }

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function setTheme(theme) {
            // Save to localStorage
            localStorage.setItem('color-theme', theme);

            // Update user preference if logged in
            if ({{ auth()->check() ? 'true' : 'false' }}) {
                fetch('{{ route("settings.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ theme: theme })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Theme updated:', data);
                })
                .catch(error => {
                    console.error('Theme update failed:', error);
                });
            }

            // Apply theme
            let applyTheme = theme;
            if (theme === 'system') {
                applyTheme = getSystemTheme();
            }

            applyThemeVisuals(applyTheme, theme);

            // Hide submenu
            if (themeSubmenu) {
                themeSubmenu.style.display = 'none';
            }

            // Show toast
            showToast('Thème mis à jour: ' + ucfirst(theme));

            // Reload page to apply theme everywhere
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }

        // Toggle theme submenu
        if (themeSelector) {
            themeSelector.addEventListener('click', function(e) {
                e.stopPropagation();
                if (themeSubmenu) {
                    themeSubmenu.style.display = themeSubmenu.style.display === 'none' ? 'block' : 'none';
                }
            });
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const savedTheme = localStorage.getItem('color-theme');
            if (!savedTheme || savedTheme === 'system') {
                initTheme();
            }
        });

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            const themeSubmenu = document.getElementById('themeSubmenu');

            if (userDropdown && !e.target.closest('.user-profile-dropdown')) {
                userDropdown.classList.remove('show');
            }
            if (moreDropdown && !e.target.closest('.dropdown-container')) {
                moreDropdown.classList.remove('show');
            }
            if (themeSubmenu && !e.target.closest('#theme-selector')) {
                themeSubmenu.style.display = 'none';
            }
        });

        initTheme();

        // ==================== TOAST NOTIFICATION ====================
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                bottom: 100px;
                right: 30px;
                background: var(--surface);
                border: 1px solid var(--surface-border);
                border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--primary)'};
                padding: 16px 24px;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-lg);
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.3s ease;
                max-width: 400px;
            `;

            toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}" style="color: ${type === 'success' ? 'var(--accent-green)' : 'var(--primary)'}; font-size: 20px;"></i>
                <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // ==================== DROPDOWN MANAGEMENT ====================
        function toggleMoreDropdown() {
            const moreDropdown = document.getElementById('moreDropdown');
            const userDropdown = document.getElementById('userDropdown');
            const themeSubmenu = document.getElementById('themeSubmenu');

            if (userDropdown) userDropdown.classList.remove('show');
            if (themeSubmenu) themeSubmenu.style.display = 'none';
            if (moreDropdown) moreDropdown.classList.toggle('show');
        }

        function toggleUserDropdown() {
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            const themeSubmenu = document.getElementById('themeSubmenu');

            if (moreDropdown) moreDropdown.classList.remove('show');
            if (themeSubmenu) themeSubmenu.style.display = 'none';
            if (userDropdown) userDropdown.classList.toggle('show');
        }

        // ==================== BACK TO TOP ====================
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>