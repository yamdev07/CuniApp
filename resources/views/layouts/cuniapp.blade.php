{{-- <!DOCTYPE html>
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

        /* ==================== USER DROPDOWN ==================== */
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

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 200px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: none;
            /* Masqué par défaut */
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

        .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--surface-border);
            background: var(--surface-alt);
        }

        .dropdown-header span {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .dropdown-header small {
            color: var(--text-secondary);
            font-size: 11px;
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

        .dropdown-item-custom.danger:hover {
            background: rgba(239, 68, 68, 0.05);
            color: var(--accent-red);
        }




        .btn-theme-toggle {
            width: 50px;
            height: 26px;
            background: var(--gray-200);
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            border: 1px solid var(--gray-300);
            transition: background 0.3s ease;
            padding: 0;
        }

        .toggle-circle {
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .toggle-circle svg {
            width: 14px;
            height: 14px;
            color: var(--gray-600);
        }

        /* Style quand le mode sombre est actif */
        .dark .btn-theme-toggle {
            background: var(--primary);
        }

        .dark .toggle-circle {
            transform: translateX(24px);
        }

        .dark .toggle-circle svg {
            color: var(--primary);
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

        /* Force l'alignement à droite pour le menu utilisateur */
.user-profile-dropdown .dropdown-menu-custom {
    left: auto;
    right: 0;
}

/* Empêche le menu de déborder sur mobile */
@media (max-width: 768px) {
    .header-nav {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 8px; /* Espace pour la barre de défilement si besoin */
        width: 100%;
        gap: 8px;
    }
    
    .dropdown-menu-custom {
        position: fixed; /* Évite les problèmes de overflow sur mobile */
        top: auto;
        left: 5%;
        right: 5%;
        width: 90%;
    }
}
    </style>
</head>

<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">
    <header class="cuni-header">
        <div class="header-wrapper">
            <div class="brand-identity">
            <div class="brand-identity"
                style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                        <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                    </div>
                </div>

                <button id="theme-toggle" class="btn-theme-toggle">
                    <div class="toggle-circle">
                        <svg id="theme-toggle-dark-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </button>
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
        // --- Gestion du Thème (Dark Mode) ---
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Initialisation du thème au chargement
        function initTheme() {
            const isDark = localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.documentElement.classList.add('dark');
                if (lightIcon) lightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                if (darkIcon) darkIcon.classList.remove('hidden');
            }
        }

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                // Bascule les icônes
                darkIcon.classList.toggle('hidden');
                lightIcon.classList.toggle('hidden');

                // Bascule la classe et sauvegarde la préférence
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });
        }

        // Appliquer le thème immédiatement pour éviter le flash blanc
        initTheme();

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

</html> --}}






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
            --surface-overlay: #1E293B;
            /* <--- AJOUTE CETTE LIGNE (Bleu gris sombre opaque) */
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
            background: var(--surface-card);
            border-bottom: 1px solid var(--surface-border);
            padding: 10px 0;
        }


        /* .header-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        } */

        /* Ligne du haut : Titre et Thème */
        /* .header-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        } */

        /* .brand-identity {
            display: flex;
            align-items: center;
            gap: 16px;
        } */

        /* Ligne du bas : Navigation */
        /* .header-nav-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid var(--surface-border);
        } */

        /* .nav-main-links {
            display: flex;
            align-items: center;
            gap: 25px;
        } */

        .nav-user-side {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* .nav-link {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        } */

        .nav-link.active {
            color: var(--primary-color);
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .nav-link span {
                display: none;
            }

            /* Cache le texte des liens pour ne garder que les icônes */
        }

        @media (max-width: 768px) {
            .header-wrapper {
                flex-direction: column;
                gap: 10px;
            }

            .header-nav {
                width: 100%;
                justify-content: center;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .header-actions {
                border: none;
                margin: 0;
                padding: 0;
            }
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

        /* .brand-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.02em;
        } */

        .brand-title span {
            font-weight: 500;
            color: var(--text-secondary);
        }

        /* .brand-tagline {
            font-size: 12px;
            color: var(--text-tertiary);
            margin-top: 2px;
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
        } */

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


        /* Optionnel : Pousser les éléments de droite si nécessaire */
        .top-actions {
            order: 3;
            /* Envoie le bouton thème après la navigation */
        }

        .nav-user-side {
            order: 4;
        }

        /* 1. Conteneur principal en Flexbox */
        /* 1. Structure globale du Header */
        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            padding: 0 2rem;
            /* Plus d'espace sur les côtés du header */
        }

        /* 2. On force les sous-conteneurs à ne plus faire de lignes */
        .header-top-row,
        .header-nav-bottom {
            display: contents;
            /* Supprime l'effet de "boîte" pour laisser les enfants s'aligner sur la ligne du parent */
        }

        /* 3. Alignement de la marque (Logo + Titre) */
        .brand-identity {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            /* Empêche le logo de s'écraser */
        }

        .brand-title {
            font-size: 1.2rem;
            margin: 0;
            line-height: 1;
        }

        .brand-tagline {
            display: none;
            /* On cache la tagline sur une seule ligne pour gagner de la place */
        }

        /* 4. Navigation centrale */
        .nav-main-links {
            display: flex;
            align-items: center;
            gap: 25px;
            /* Augmente l'espace ENTRE les liens (ajuste à ta guise) */
            margin: 0 30px;
            /* Espace entre le logo et le profil */
        }

        /* 3. Style des liens pour augmenter la zone de clic et l'air */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Espace entre l'icône et le texte du lien */
            padding: 8px 12px;
            /* Espace interne pour ne pas que le texte soit collé */
            text-decoration: none;
            white-space: nowrap;
            /* Empêche le texte de passer à la ligne */
            transition: all 0.2s ease;
        }

        /* 5. Actions de droite (Thème + Notifs + Profil) */
        .top-actions,
        .nav-user-side {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* On regroupe tout à droite */
        .header-right-group {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        /* 6. Responsive : Si l'écran est trop petit, on cache les textes des liens */
        @media (max-width: 1100px) {

            .nav-link span,
            .user-trigger span {
                display: none;
            }

            .brand-title span {
                display: none;
                /* Cache "Élevage" */
            }
        }

        @media (max-width: 1200px) {
            .nav-main-links {
                gap: 15px;
                /* On réduit un peu l'espace sur les écrans moyens */
            }
        }

        @media (max-width: 992px) {
            .nav-link span {
                display: none;
                /* On ne garde que les icônes sur tablette pour éviter la collision */
            }

            .nav-main-links {
                gap: 20px;
            }
        }

        /* .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 240px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
            overflow: hidden;
            animation: slideIn 0.2s ease-out;
            padding: 8px 0;
        } */


        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            /* On augmente la largeur de 200px à 260px pour plus d'espace */
            width: 260px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            /* Un arrondi plus doux */
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
            overflow: hidden;
            animation: slideIn 0.2s ease-out;
            padding: 8px;
            /* Ajout d'un padding interne pour que les items ne touchent pas les bords */
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

        /* .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            margin: 2px 8px;
            border-radius: 6px;
            width: auto;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        } */

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 14px;
            /* Plus d'espace entre l'icône et le texte */
            padding: 12px 16px;
            /* Plus de hauteur pour cliquer facilement */
            margin: 4px 0;
            /* Espace vertical entre chaque ligne */
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

        .dropdown-item-custom i {
            width: 20px;
            display: flex;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
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
            background: var(--surface-overlay) !important;
            border-color: var(--surface-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }



        .theme-dark .dropdown-item-custom {
            color: var(--text-primary);
        }



        .theme-dark .dropdown-header {
            background: var(--surface-elevated) !important;
            padding: 20px 24px;
        }

        .theme-dark .dropdown-item-custom:hover {
            background: var(--surface-overlay) !important;
            transform: translateX(4px);
            /* Petit effet de décalage pour "ouvrir" l'espace */
            transition: all 0.2s ease;
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

        /* ==================== USER DROPDOWN ==================== */
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

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 250px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
            overflow: hidden;
            animation: slideIn 0.2s ease-out;
            padding: 10px;
            /* Ajoute un espace vide tout autour des items */
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



        .dropdown-header {
            padding: 24px 16px !important;
            border-bottom: 1px solid var(--surface-border);
            background: var(--surface-alt);

            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            width: 100% !important;
        }

        .dropdown-header span,
        .dropdown-header small {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .dropdown-header span {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .dropdown-header small {
            font-size: 12px;
            color: var(--text-secondary);
            /* Empêche les longs emails de pousser les bords */
            overflow-wrap: break-word;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            padding: 14px 16px;
            /* Plus d'espace pour la zone de clic */
            margin-bottom: 2px;
        }

        .dropdown-item-custom:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .dropdown-item-custom.danger:hover {
            background: rgba(239, 68, 68, 0.05);
            color: var(--accent-red);
        }




        .btn-theme-toggle {
            width: 50px;
            height: 26px;
            background: var(--gray-200);
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            border: 1px solid var(--gray-300);
            transition: background 0.3s ease;
            padding: 0;
        }

        .toggle-circle {
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .toggle-circle svg {
            width: 14px;
            height: 14px;
            color: var(--gray-600);
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

        /* Force l'alignement à droite pour le menu utilisateur */
        .user-profile-dropdown .dropdown-menu-custom {
            left: auto;
            right: 0;
        }

        /* Empêche le menu de déborder sur mobile */
        @media (max-width: 768px) {
            .header-nav {
                display: flex;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 8px;
                /* Espace pour la barre de défilement si besoin */
                width: 100%;
                gap: 8px;
            }

            .dropdown-menu-custom {
                position: fixed;
                /* Évite les problèmes de overflow sur mobile */
                top: auto;
                left: 5%;
                right: 5%;
                width: 90%;
            }
        }

        /* --- DARK MODE FIX --- */
        .theme-dark body {
            background: #0f172a;
            color: white;
        }

        .theme-dark .cuni-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }

        .theme-dark .nav-link {
            color: #94a3b8;
        }

        /* Style quand le mode sombre est actif */
        .theme-dark .btn-theme-toggle {
            background: var(--primary);
        }

        .theme-dark .toggle-circle {
            transform: translateX(24px);
            /* Déplace le cercle à droite */
        }

        .theme-dark .toggle-circle svg {
            color: var(--primary);
        }

        /* Couleur par défaut (Mode Clair) */
        .brand-title {
            color: var(--gray-800);
        }

        .theme-dark .brand-title {
            color: #FFFFFF !important;
            /* Force le blanc en mode sombre */
        }


        /* --- ADAPTATION MODE SOMBRE --- */
        .theme-dark .page-title {
            color: var(--white);
        }


        .theme-dark .card-header-custom {
            background: var(--surface-elevated);
            border-bottom-color: var(--surface-border);
        }

        .theme-dark .card-title {
            color: var(--white);
        }

        .theme-dark .card-title i {
            color: var(--primary);
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

        .btn-cuni.danger:hover {
            background: var(--accent-red);
            color: var(--white);
            border-color: var(--accent-red);
        }


        /* --- ADAPTATION MODE SOMBRE --- */
        .theme-dark .form-label {
            color: var(--text-secondary);
        }

        .theme-dark .form-control,
        .theme-dark .form-select {
            background-color: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-primary);
            box-shadow: none;
        }

        .theme-dark .form-control:focus,
        .theme-dark .form-select:focus {
            background-color: var(--surface-elevated);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(77, 166, 255, 0.15);
        }

        /* --- CHAMPS DE FORMULAIRE (INPUTS, SELECT, TEXTAREA) --- */

        /* Conteneur pour espacer les blocs (Label + Champ) */
        .form-group {
            margin-bottom: 20px;
        }

        /* Style des labels */
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }

        /* Style de base des champs */
        .form-control,
        .form-select {
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

        /* État Focus (clic dans le champ) */
        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: var(--white);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }

        /* Placeholder (texte d'exemple) */
        .form-control::placeholder {
            color: var(--text-tertiary);
        }

        /* --- ADAPTATION MODE SOMBRE --- */
        .theme-dark .form-label {
            color: var(--text-secondary);
        }

        .theme-dark .form-control,
        .theme-dark .form-select {
            background-color: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-primary);
            box-shadow: none;
        }

        .theme-dark .form-control:focus,
        .theme-dark .form-select:focus {
            background-color: var(--surface-elevated);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(77, 166, 255, 0.15);
        }


        .theme-dark input[type="date"] {
            color-scheme: dark;
        }



        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-transition: background-color 5000s ease-in-out 0s;
            transition: background-color 5000s ease-in-out 0s;
            -webkit-text-fill-color: var(--text-primary) !important;
        }

        .theme-dark input:-webkit-autofill {
            -webkit-text-fill-color: #E6E9F0 !important;
            caret-color: white;
        }
    </style>

</head>

<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">


    {{-- <header class="cuni-header">
        <div class="header-wrapper">

            <div class="header-top-row">
                <div class="brand-identity">
                    <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                        <svg viewBox="0 0 40 40" fill="none" style="width: 40px; height: 40px;">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                        <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                    </div>
                </div>

                <div class="top-actions">
                    <button id="theme-toggle" class="btn-theme-toggle">
                        <div class="toggle-circle">
                            <svg id="theme-toggle-dark-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1z"
                                    fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

            <nav class="header-nav-bottom">
                <div class="nav-main-links">
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
                    <a href="{{ route('lapins.index') }}"
                        class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        Tous les Lapins</a>


                    <div class="dropdown-container">
                        <button class="nav-link" onclick="toggleMoreDropdown()">
                            <i class="bi bi-three-dots"></i> Plus <i class="bi bi-chevron-down"
                                style="font-size: 10px;"></i>
                        </button>
                        <div class="dropdown-menu-custom" id="moreDropdown">
                            <a href="{{ route('saillies.index') }}" class="dropdown-item-custom"><i
                                    class="bi bi-heart"></i> Saillies</a>

                            <a href="{{ route('sales.index') }}" class="dropdown-item-custom">
                                <i class="bi bi-cart"></i> Ventes
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item-custom"><i
                                    class="bi bi-gear"></i> Paramètres</a>
                        </div>
                    </div>
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
                                
                                <a href="{{ route('profile.edit') }}" class="dropdown-item-custom"><i
                                        class="bi bi-person"></i> Profil</a>
                                <hr>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item-custom logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>
    </header> --}}



    <header class="cuni-header">
        <div class="header-wrapper">

            <div class="header-top-row">
                <div class="brand-identity">
                    <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                        <svg viewBox="0 0 40 40" fill="none" style="width: 40px; height: 40px;">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                        <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                    </div>
                </div>

                <div class="top-actions">
                    <button id="theme-toggle" class="btn-theme-toggle">
                        <div class="toggle-circle">
                            <svg id="theme-toggle-dark-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1z"
                                    fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

            <nav class="header-nav-bottom">
                <div class="nav-main-links">
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
                    <a href="{{ route('lapins.index') }}"
                        class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        Tous les Lapins</a>


                    <div class="dropdown-container">
                        <button class="nav-link" onclick="toggleMoreDropdown()">
                            <i class="bi bi-three-dots"></i> Plus <i class="bi bi-chevron-down"
                                style="font-size: 10px;"></i>
                        </button>
                        <div class="dropdown-menu-custom" id="moreDropdown">
                            <a href="{{ route('saillies.index') }}" class="dropdown-item-custom"><i
                                    class="bi bi-heart"></i> Saillies</a>

                            <a href="{{ route('sales.index') }}" class="dropdown-item-custom">
                                <i class="bi bi-cart"></i> Ventes
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item-custom"><i
                                    class="bi bi-gear"></i> Paramètres</a>
                        </div>
                    </div>
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

                                <a href="{{ route('profile.edit') }}" class="dropdown-item-custom"><i
                                        class="bi bi-person"></i> Profil</a>
                                <hr>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item-custom logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
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
        // --- Gestion du Thème (Dark Mode) ---
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        /**
         * Initialisation du thème au chargement
         */
        function initTheme() {
            // Vérifie si la préférence est déjà stockée ou utilise la préférence système
            const isDark = localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.documentElement.classList.add('theme-dark');
                // Affiche l'icône soleil (pour repasser en clair)
                if (lightIcon) lightIcon.classList.remove('hidden');
                if (darkIcon) darkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('theme-dark');
                // Affiche l'icône lune (pour passer en sombre)
                if (darkIcon) darkIcon.classList.remove('hidden');
                if (lightIcon) lightIcon.classList.add('hidden');
            }
        }

        /**
         * Gestion du clic sur le bouton
         */
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                // 1. Bascule la classe principale
                const isCurrentlyDark = document.documentElement.classList.toggle('theme-dark');

                // 2. Bascule les icônes (si elles existent)
                if (darkIcon && lightIcon) {
                    darkIcon.classList.toggle('hidden');
                    lightIcon.classList.toggle('hidden');
                }

                // 3. Sauvegarde la préférence
                localStorage.setItem('color-theme', isCurrentlyDark ? 'dark' : 'light');
            });
        }

        // Appliquer le thème immédiatement
        initTheme();

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
