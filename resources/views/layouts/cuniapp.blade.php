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

        html,
        body {
            min-width: 100%;
            width: 100%;
        }

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
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
            min-width: 100%;
            box-shadow: var(--shadow-sm);
        }

        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
            padding: 0 2.5rem;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
            gap: 20px;
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
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
            flex-shrink: 0;
        }

        .cuniapp-logo:hover {
            transform: scale(1.05);
        }

        .cuniapp-logo svg {
            width: 28px;
            height: 28px;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .brand-title {
            font-size: 1.15rem;
            margin: 0;
            line-height: 1.1;
            color: var(--gray-800);
            white-space: nowrap;
            font-weight: 700;
        }

        .theme-dark .brand-title {
            color: #FFFFFF !important;
        }

        .brand-tagline {
            display: block !important;
            font-size: 0.72rem;
            color: var(--text-secondary);
            opacity: 0.85;
            margin: 0;
            padding: 4px 0 0 0;
        }

        .nav-main-links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            justify-content: center;
            overflow: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-main-links::-webkit-scrollbar {
            display: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
            color: var(--text-secondary);
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            flex-shrink: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        .nav-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .nav-link i {
            font-size: 16px;
        }

        .nav-user-side {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .notification-trigger {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: var(--radius);
            background: var(--gray-50);
            border: 1px solid var(--surface-border);
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
            text-decoration: none;
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
            top: -5px;
            right: -5px;
            background: var(--accent-red);
            color: white;
            font-size: 10px;
            font-weight: 700;
            min-width: 19px;
            height: 19px;
            border-radius: 10px;
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
            padding: 7px 13px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
            background: var(--gray-50);
            border: 1px solid var(--surface-border);
            white-space: nowrap;
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
            flex-shrink: 0;
        }

        /* ===== DROPDOWN CONTAINER - FIX FOR MORE BUTTON ===== */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

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

        /* More Dropdown Specific Positioning */
        #moreDropdown {
            left: 0;
            right: auto;
            width: 240px;
        }

        .dropdown-menu-custom.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
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

        .theme-switch-row {
            cursor: pointer;
        }

        .theme-status-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.08);
            color: var(--text-secondary);
            text-transform: capitalize;
        }

        .cuni-main {
            max-width: 1600px;
            margin: 0 auto;
            padding: 28px;
            /* min-height: calc(100vh - 220px); */
            flex: 1;
            width: 100%;
            min-width: 320px;
        }

        .mobile-menu-trigger {
            display: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-secondary);
            padding: 8px;
            border-radius: var(--radius);
            transition: all 0.2s;
            flex-shrink: 0;
            background: none;
            border: none;
        }

        .mobile-menu-trigger:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .mobile-nav-overlay {
            position: fixed;
            top: 72px;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--surface);
            z-index: 99;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding: 20px;
        }

        .mobile-nav-overlay.active {
            transform: translateX(0);
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            text-decoration: none;
            color: var(--text-primary);
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .mobile-nav-link:hover {
            background: var(--gray-50);
            color: var(--primary);
        }

        .mobile-nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .mobile-nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .mobile-nav-divider {
            height: 1px;
            background: var(--surface-border);
            margin: 16px 0;
        }

        .cuni-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
            width: 100%;
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

        .theme-dark .card-title {
            color: var(--white);
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

        .btn-cuni.sm {
            padding: 8px 14px;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }

        .theme-dark .form-label {
            color: var(--text-secondary);
        }

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

        .theme-dark .form-control,
        .theme-dark .form-select {
            background-color: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-primary);
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: var(--white);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }

        .theme-dark .form-control:focus,
        .theme-dark .form-select:focus {
            background-color: var(--surface-elevated);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(77, 166, 255, 0.15);
        }

        .form-control::placeholder {
            color: var(--text-tertiary);
        }

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

        .theme-dark .page-title {
            color: var(--white);
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

        .table-responsive {
            overflow-x: auto;
        }

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

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .status-inactive {
            background: rgba(107, 114, 128, 0.1);
            color: var(--gray-500);
        }

        .status-gestante {
            background: rgba(236, 72, 153, 0.1);
            color: var(--accent-pink);
        }

        .status-allaitante {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-purple);
        }

        .status-vide {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
        }

        .status-malade {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
        }

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

        .cuni-footer {
            background: var(--surface);
            border-top: 1px solid var(--surface-border);
            padding: 60px 0 30px 0;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

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

        .footer-logo-icon svg {
            width: 28px;
            height: 28px;
        }

        .footer-logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .footer-logo-text span {
            color: var(--primary);
        }

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

        .footer-section h4 i {
            color: var(--primary);
        }

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

        .footer-links li a:hover i {
            opacity: 1;
        }

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

        .footer-legal a:hover {
            color: var(--primary);
        }

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
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
        }


        .form-section {
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--surface-border);
        }

        .section-subtitle {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-subtitle i {
            color: var(--primary);
        }


        /* Dark mode specific overrides */
        .theme-dark .cuni-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }

        .theme-dark .nav-link {
            color: #94a3b8;
        }

        .theme-dark .nav-link:hover {
            background: var(--surface-elevated);
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
            transition: all 0.2s ease;
        }

        .theme-dark .notification-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .notification-trigger:hover {
            background: var(--surface-overlay);
        }

        .theme-dark .user-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .user-trigger:hover {
            background: var(--surface-overlay);
        }

        .theme-dark .card-header-custom {
            background-color: var(--surface-alt) !important;
            border-bottom-color: var(--surface-border) !important;
        }

        .theme-dark .mobile-nav-overlay {
            background: var(--surface);
        }

        .theme-dark .mobile-nav-link {
            color: var(--text-primary);
        }

        .theme-dark .mobile-nav-link:hover {
            background: var(--surface-elevated);
            color: var(--primary);
        }

        .theme-dark .mobile-nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .d-md-none {
            display: none;
        }

        /* ===== UTILITAIRES RESPONSIVE ===== */
        .d-none {
            display: none !important;
        }

        .d-md-none {
            display: none;
        }

        .d-md-flex {
            display: none;
        }

        @media (max-width: 1100px) {
            .d-md-none {
                display: block !important;
            }

            .d-md-flex {
                display: none !important;
            }

            /* Badge notification dans le menu mobile */
            .mobile-nav-link .notification-badge {
                position: static !important;
                margin-left: auto;
                font-size: 11px;
                min-width: 18px;
                height: 18px;
            }

            /* Avatar utilisateur dans le menu mobile */
            .mobile-nav-link .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 13px;
                margin-right: 8px;
            }

            /* Espacement pour les sous-liens de profil */
            .mobile-nav-link[style*="padding-left: 52px"] {
                padding-left: 52px !important;
            }
        }

        @media (min-width: 1101px) {
            .d-md-none {
                display: none !important;
            }

            .d-md-flex {
                display: flex !important;
            }
        }

        /* ===== RESPONSIVE HEADER ===== */
        @media (max-width: 1280px) {
            .header-wrapper {
                padding: 0 2rem;
            }

            .brand-tagline {
                display: none !important;
            }
        }

        @media (max-width: 1100px) {
            .header-wrapper {
                padding: 0 1.5rem;
                height: 68px;
            }

            .nav-main-links {
                display: none !important;
                /* ✅ Cache le menu desktop */
            }

            .mobile-menu-trigger {
                display: block !important;
                /* ✅ Affiche le burger */
            }

            .user-trigger span {
                display: none;
            }

            .cuni-main {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .header-wrapper {
                padding: 0 1.25rem;
                height: 64px;
            }

            .brand-info {
                min-width: 0;
            }

            .brand-title {
                font-size: 1.05rem;
            }

            .cuni-main {
                padding: 16px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-legal {
                justify-content: center;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title {
                font-size: 20px;
            }

            .card-header-custom {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .header-wrapper {
                gap: 10px;
            }

            .cuniapp-logo {
                width: 40px;
                height: 40px;
            }

            .cuniapp-logo svg {
                width: 24px;
                height: 24px;
            }

            .brand-title {
                font-size: 1rem;
            }

            .notification-trigger {
                width: 38px;
                height: 38px;
            }

            .user-avatar {
                width: 30px;
                height: 30px;
                font-size: 13px;
            }
        }

        /* ===== FOOTER RESPONSIVE FIX ===== */
        @media (max-width: 1100px) {
            .footer-container {
                padding: 0 1.5rem;
            }

            .footer-brand {
                grid-column: 1 / -1;
                text-align: center;
            }

            .footer-logo {
                justify-content: center;
            }

            .footer-tagline {
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .footer-container {
                padding: 0 1.25rem;
            }

            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 24px;
            }

            .footer-brand {
                grid-column: 1 / -1 !important;
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 20px;
                border-bottom: 1px solid var(--surface-border);
            }

            .footer-logo {
                justify-content: center;
            }

            .footer-tagline {
                max-width: 400px;
                margin: 0 auto;
            }

            /* ✅ Les 3 sections en 3 colonnes */
            .footer-section {
                grid-column: span 1;
            }

            /* ✅ Container pour les 3 colonnes */
            .footer-grid>.footer-section {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
            }

            .footer-section h4 {
                font-size: 0.9rem;
                margin-bottom: 12px;
            }

            .footer-links li a {
                font-size: 0.8rem;
                padding: 3px 0;
            }

            .footer-contact-item {
                font-size: 0.8rem;
                margin-bottom: 6px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 24px 0 0 0;
            }

            .footer-copyright {
                font-size: 0.8rem;
                order: 2;
            }

            .footer-legal {
                justify-content: center;
                flex-wrap: wrap;
                gap: 16px;
                order: 1;
            }

            .footer-legal a {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .footer-container {
                padding: 0 1rem;
            }

            .footer-logo-text {
                font-size: 1.1rem;
            }

            .footer-logo-icon {
                width: 40px;
                height: 40px;
            }

            /* ✅ 3 colonnes même sur très petit écran */
            .footer-grid>.footer-section {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }

            .footer-section h4 {
                font-size: 0.85rem;
            }

            .footer-links li a {
                font-size: 0.75rem;
            }

            .footer-contact-item {
                font-size: 0.75rem;
            }
        }

        /* ===== PAGINATION STYLES ===== */
        .cuni-pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 24px;
            border-top: 1px solid var(--surface-border);
            margin-top: 24px;
            background: var(--surface-alt);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }

        .cuni-pagination-info {
            flex: 1;
            min-width: 200px;
        }

        .cuni-pagination-text {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .cuni-pagination-text strong {
            color: var(--primary);
            font-weight: 600;
            margin: 0 4px;
        }

        .cuni-pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .cuni-page-item {
            display: inline-flex;
        }

        .cuni-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius);
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .cuni-page-link:hover {
            background: var(--primary-subtle);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
        }

        .cuni-page-link i {
            font-size: 14px;
            font-weight: 600;
        }

        .cuni-page-item.active .cuni-page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: var(--primary);
            color: var(--white);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            cursor: default;
        }

        .cuni-page-item.active .cuni-page-link:hover {
            transform: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .cuni-page-item.disabled .cuni-page-link {
            background: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-tertiary);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .cuni-page-item.disabled .cuni-page-link:hover {
            background: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-tertiary);
            transform: none;
            box-shadow: none;
        }

        .cuni-page-dots {
            cursor: default;
            background: transparent !important;
            border-color: transparent !important;
        }

        /* Responsive Pagination */
        @media (max-width: 768px) {
            .cuni-pagination-wrapper {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 16px;
            }

            .cuni-pagination-info {
                width: 100%;
                margin-bottom: 12px;
            }

            .cuni-pagination {
                justify-content: center;
                gap: 4px;
            }

            .cuni-page-link {
                min-width: 34px;
                height: 34px;
                padding: 0 8px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .cuni-pagination-text {
                font-size: 12px;
            }

            .cuni-page-link {
                min-width: 32px;
                height: 32px;
                font-size: 11px;
            }

            /* Hide middle page numbers on very small screens */
            .cuni-page-item:not(:first-child):not(:last-child):not(.active) {
                display: none;
            }
        }

        /* Dark Mode Support */
        .theme-dark .cuni-pagination-wrapper {
            background: var(--surface-elevated);
            border-top-color: var(--surface-border);
        }

        .theme-dark .cuni-page-link {
            background: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-secondary);
        }

        .theme-dark .cuni-page-link:hover {
            background: var(--surface-overlay);
            border-color: var(--primary);
            color: var(--primary);
        }

        .theme-dark .cuni-page-item.active .cuni-page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(77, 166, 255, 0.3);
        }

        .theme-dark .cuni-page-item.disabled .cuni-page-link {
            background: var(--surface-alt);
            border-color: var(--surface-border);
            color: var(--text-tertiary);
        }


        /* ===== ENHANCED FOOTER STYLES ===== */
        .cuni-footer {
            background: linear-gradient(135deg, var(--surface) 0%, var(--surface-alt) 100%);
            border-top: 1px solid var(--surface-border);
            padding: 80px 0 30px 0;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }

        .theme-dark .cuni-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-top-color: var(--surface-border);
        }

        .footer-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 50px;
            margin-bottom: 50px;
        }

        /* Brand Section */
        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 6px;
        }

        .footer-logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.3);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .footer-logo-icon:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .footer-logo-icon svg {
            width: 28px;
            height: 28px;
        }

        .footer-logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .footer-logo-text span {
            color: var(--primary);
        }

        .footer-tagline {
            font-size: 0.92rem;
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 320px;
        }

        /* Social Media Links */
        .footer-social {
            display: flex;
            gap: 10px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--surface-alt);
            border: 1px solid var(--surface-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 17px;
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
        }

        /* Simplified App Store Buttons (ICON ONLY) */
        .footer-app-buttons {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .app-store-btn {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: var(--surface-alt);
            border: 1px solid var(--surface-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            flex-shrink: 0;
            font-size: 24px;
        }

        .app-store-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.25);
        }

        /* Footer Sections */
        .footer-section h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .footer-section h4 i {
            color: var(--primary);
            font-size: 18px;
        }

        .section-divider {
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent-cyan));
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links li a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.93rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .footer-links li a:hover {
            color: var(--primary);
            transform: translateX(6px);
        }

        .footer-links li a i {
            font-size: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .footer-links li a:hover i {
            opacity: 1;
        }

        .badge-notification {
            background: var(--accent-red);
            color: var(--white);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 6px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Contact Section */
        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .footer-contact-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .footer-contact-item i {
            font-size: 17px;
            color: var(--primary);
            width: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-contact-item div {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .footer-contact-item strong {
            font-size: 11px;
            color: var(--text-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-contact-item span,
        .footer-contact-item a {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
            line-height: 1.5;
        }

        .footer-contact-item a:hover {
            color: var(--primary);
        }

        /* Quick Stats Widget */
        .footer-quick-stats {
            margin-top: 22px;
            padding: 18px;
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            border: 1px solid var(--surface-border);
        }

        .footer-quick-stats h5 {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-quick-stats h5 i {
            color: var(--accent-green);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--surface-border);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 10px;
            color: var(--text-tertiary);
            margin-top: 3px;
            text-align: center;
        }

        /* Footer Bottom */
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 28px;
            border-top: 1px solid var(--surface-border);
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-copyright {
            flex: 1;
            min-width: 250px;
        }

        .footer-copyright p {
            font-size: 0.88rem;
            color: var(--text-tertiary);
            margin-bottom: 5px;
        }

        .footer-copyright a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-version {
            font-size: 11px !important;
            opacity: 0.7;
        }

        .footer-version .separator {
            margin: 0 6px;
        }

        .footer-legal {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .footer-legal a {
            font-size: 0.88rem;
            color: var(--text-tertiary);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-legal a:hover {
            color: var(--primary);
        }

        .footer-legal a i {
            font-size: 14px;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border: none;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
            font-size: 22px;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(37, 99, 235, 0.5);
        }

        .back-to-top.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        /* ===== MOBILE RESPONSIVE FOOTER ===== */

        /* Tablet (1024px and below) */
        @media (max-width: 1024px) {
            .footer-container {
                padding: 0 2rem;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px;
            }

            .footer-brand {
                grid-column: 1 / -1;
                text-align: center;
                align-items: center;
            }

            .footer-logo {
                justify-content: center;
            }

            .footer-tagline {
                max-width: 450px;
                margin: 0 auto;
            }

            .footer-social {
                justify-content: center;
            }

            .footer-app-buttons {
                justify-content: center;
            }

            .footer-section h4 {
                justify-content: center;
            }

            .section-divider {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links li a {
                justify-content: center;
            }

            .footer-contact {
                align-items: center;
            }

            .footer-contact-item {
                justify-content: center;
            }
        }

        /* Mobile (768px and below) */
        @media (max-width: 768px) {
            .cuni-footer {
                padding: 50px 0 20px 0;
            }

            .footer-container {
                padding: 0 1.5rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 35px;
                margin-bottom: 35px;
            }

            .footer-brand {
                text-align: center;
                align-items: center;
                padding-bottom: 25px;
                border-bottom: 1px solid var(--surface-border);
            }

            .footer-logo {
                justify-content: center;
            }

            .footer-logo-icon {
                width: 45px;
                height: 45px;
            }

            .footer-logo-icon svg {
                width: 26px;
                height: 26px;
            }

            .footer-logo-text {
                font-size: 1.25rem;
            }

            .footer-tagline {
                max-width: 100%;
                font-size: 0.9rem;
            }

            .footer-social {
                justify-content: center;
                gap: 8px;
            }

            .social-link {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }

            .footer-app-buttons {
                justify-content: center;
                gap: 8px;
            }

            .app-store-btn {
                width: 44px;
                height: 44px;
                font-size: 22px;
            }

            .footer-section h4 {
                font-size: 1rem;
                justify-content: flex-start;
            }

            .section-divider {
                left: 0;
                transform: none;
            }

            .footer-links li a {
                justify-content: flex-start;
                font-size: 0.9rem;
            }

            .footer-contact {
                align-items: flex-start;
            }

            .footer-contact-item {
                justify-content: flex-start;
            }

            .footer-quick-stats {
                padding: 15px;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-item {
                padding: 8px;
            }

            .stat-value {
                font-size: 16px;
            }

            .stat-label {
                font-size: 9px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 18px;
                padding-top: 24px;
            }

            .footer-copyright {
                min-width: 100%;
            }

            .footer-legal {
                justify-content: center;
                gap: 18px;
                width: 100%;
            }

            .footer-legal a {
                font-size: 0.85rem;
            }

            .back-to-top {
                width: 44px;
                height: 44px;
                bottom: 20px;
                right: 20px;
                font-size: 20px;
            }
        }

        /* Small Mobile (480px and below) */
        @media (max-width: 480px) {
            .footer-container {
                padding: 0 1rem;
            }

            .footer-logo-text {
                font-size: 1.15rem;
            }

            .footer-logo-icon {
                width: 42px;
                height: 42px;
            }

            .footer-logo-icon svg {
                width: 24px;
                height: 24px;
            }

            .footer-tagline {
                font-size: 0.88rem;
                line-height: 1.6;
            }

            .footer-social {
                gap: 6px;
            }

            .social-link {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }

            .footer-app-buttons {
                gap: 6px;
            }

            .app-store-btn {
                width: 42px;
                height: 42px;
                font-size: 20px;
            }

            .footer-section h4 {
                font-size: 0.95rem;
                margin-bottom: 18px;
            }

            .footer-links {
                gap: 10px;
            }

            .footer-links li a {
                font-size: 0.88rem;
            }

            .footer-contact-item strong {
                font-size: 10px;
            }

            .footer-contact-item span,
            .footer-contact-item a {
                font-size: 12px;
            }

            .footer-quick-stats {
                padding: 12px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }

            .stat-item {
                padding: 8px 6px;
            }

            .stat-value {
                font-size: 15px;
            }

            .stat-label {
                font-size: 8px;
            }

            .footer-bottom {
                padding-top: 20px;
            }

            .footer-copyright p {
                font-size: 0.82rem;
            }

            .footer-version {
                font-size: 10px !important;
            }

            .footer-legal {
                flex-wrap: wrap;
                gap: 14px;
            }

            .footer-legal a {
                font-size: 0.82rem;
                justify-content: center;
            }

            .back-to-top {
                width: 42px;
                height: 42px;
                bottom: 15px;
                right: 15px;
            }
        }

        /* Extra Small Mobile (360px and below) */
        @media (max-width: 360px) {
            .footer-logo-text {
                font-size: 1.1rem;
            }

            .footer-tagline {
                font-size: 0.85rem;
            }

            .social-link {
                width: 34px;
                height: 34px;
            }

            .app-store-btn {
                width: 40px;
                height: 40px;
            }

            .footer-links li a {
                font-size: 0.85rem;
            }

            .footer-legal {
                gap: 10px;
            }

            .footer-legal a {
                font-size: 0.78rem;
            }
        }

        /* Dark Mode Footer */
        .theme-dark .cuni-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .theme-dark .footer-logo-text {
            color: var(--white);
        }

        .theme-dark .social-link {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .app-store-btn {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .footer-quick-stats {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .stat-item {
            background: var(--surface-overlay);
            border-color: var(--surface-border);
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

        .alert-cuni.warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: var(--accent-orange);
        }

        .alert-cuni.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
        }

        /* ✅ SMART HEADER OVERFLOW DETECTION */
        @media (max-width: 1400px) {

            /* Hide subscription from main nav on medium desktop */
            .subscription-nav-link {
                display: none !important;
            }

            /* Show "Plus" dropdown more prominently */
            #moreButton {
                background: var(--primary-subtle) !important;
                color: var(--primary) !important;
            }
        }

        @media (max-width: 1280px) {

            /* Hide more nav items on smaller desktop */
            .nav-main-links .nav-link:nth-child(4),
            .nav-main-links .nav-link:nth-child(5) {
                display: none !important;
            }
        }

        @media (max-width: 1100px) {

            /* Hide most nav items, keep only essentials */
            .nav-main-links .nav-link:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)) {
                display: none !important;
            }

            .header-wrapper {
                padding: 0 1.5rem;
                height: 68px;
            }

            .brand-tagline {
                display: none !important;
            }

            .nav-main-links {
                display: none !important;
            }

            .mobile-menu-trigger {
                display: block !important;
            }

            .user-trigger span {
                display: none;
            }
        }

        /* ✅ Prevent horizontal scroll */
        .cuni-header {
            overflow-x: hidden;
            max-width: 100vw;
        }

        .header-wrapper {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* ✅ Ensure badges don't cause overflow */
        .notification-badge {
            position: absolute;
            min-width: 19px;
            height: 19px;
            font-size: 10px;
            padding: 0 5px;
            white-space: nowrap;
        }

        /* ✅ Dropdown positioning fix */
        .dropdown-menu-custom {
            max-width: 280px;
            overflow-x: hidden;
        }

        /* ✅ FIX DROPDOWN VISIBILITY ISSUES */
        .cuni-header {
            overflow: visible !important;
            position: relative;
            z-index: 999 !important;
        }

        .header-wrapper {
            overflow: visible !important;
            position: relative;
        }

        .nav-main-links {
            overflow: visible !important;
        }

        .dropdown-container {
            position: relative !important;
            display: inline-block;
        }

        .dropdown-menu-custom {
            position: absolute !important;
            top: calc(100% + 8px) !important;
            z-index: 9999 !important;
            min-width: 240px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: none;
            animation: slideIn 0.2s ease-out;
        }

        .dropdown-menu-custom.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* ✅ FIX NOTIFICATION BADGE POSITIONING */
        .notification-trigger {
            position: relative !important;
        }

        .notification-badge {
            position: absolute !important;
            top: -5px !important;
            right: -5px !important;
            z-index: 10000 !important;
        }

        /* ✅ FIX USER DROPDOWN */
        .user-profile-dropdown {
            position: relative !important;
            display: inline-block;
        }

        /* ✅ PREVENT CLIPPING */
        @media (max-width: 1100px) {
            .nav-main-links {
                display: none !important;
            }

            .mobile-menu-trigger {
                display: block !important;
            }
        }

        /* Add to your layout styles */
        .subscription-badge {
            position: absolute !important;
            top: 8px !important;
            right: 12px !important;
            min-width: 18px !important;
            height: 18px !important;
            font-size: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }


        /* ✅ MOBILE NAV OVERLAY - FIXED STYLING */
        .mobile-nav-overlay {
            position: fixed;
            top: 72px;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--surface) !important;
            /* ✅ Force surface color */
            z-index: 99;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding: 20px;
        }

        .mobile-nav-overlay.active {
            transform: translateX(0);
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-bottom: 40px;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            text-decoration: none;
            color: var(--text-primary) !important;
            /* ✅ Force text color */
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s;
            background: transparent !important;
            /* ✅ Prevent white background */
        }

        .mobile-nav-link:hover {
            background: var(--gray-50) !important;
            color: var(--primary) !important;
        }

        .mobile-nav-link.active {
            background: var(--primary-subtle) !important;
            color: var(--primary) !important;
        }

        .mobile-nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            color: inherit;
        }

        .mobile-nav-divider {
            height: 1px;
            background: var(--surface-border);
            margin: 16px 0;
        }

        /* ✅ DARK MODE SUPPORT FOR MOBILE NAV */
        .theme-dark .mobile-nav-overlay {
            background: var(--surface) !important;
        }

        .theme-dark .mobile-nav-link {
            color: var(--text-primary) !important;
        }

        .theme-dark .mobile-nav-link:hover {
            background: var(--surface-elevated) !important;
        }

        .theme-dark .mobile-nav-link.active {
            background: var(--primary-subtle) !important;
        }

        /* ✅ NOTIFICATION BADGE IN MOBILE NAV */
        .mobile-nav-link .notification-badge {
            position: static !important;
            margin-left: auto;
            font-size: 11px;
            min-width: 18px;
            height: 18px;
        }

        /* ✅ THEME BADGE IN MOBILE NAV */
        .theme-status-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.08);
            color: var(--text-secondary);
            text-transform: capitalize;
            margin-left: auto;
        }

        .theme-dark .theme-status-badge {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">



    <header class="cuni-header" style="overflow: visible !important; z-index: 999 !important;">
        <div class="header-wrapper" style="overflow: visible !important;">
            <div class="brand-identity">
                {{-- Logo and Brand --}}
                <a href="{{ route('dashboard') }}" class="cuniapp-logo">
                    <svg viewBox="0 0 40 40" fill="none" style="width: 40px; height: 40px;">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                    </svg>
                </a>
                <div class="brand-info">
                    <a href="{{ route('dashboard') }}">
                        <h1 class="brand-title">
                            CuniApp <span>Élevage</span>
                            @if (auth()->check() && auth()->user()->firm)
                                <span
                                    style="font-size: 14px; font-weight: 400; color: var(--text-secondary); margin-left: 12px;">
                                    | {{ auth()->user()->firm->name }}
                                </span>
                            @endif
                        </h1>
                    </a>
                    <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                </div>
            </div>

            {{-- ✅ MAIN NAVIGATION --}}
            <nav class="nav-main-links" id="navMainLinks" style="overflow: visible !important;">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('males.index') }}"
                    class="nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Mâles</span>
                </a>
                <a href="{{ route('femelles.index') }}"
                    class="nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-right-square"></i>
                    <span>Femelles</span>
                </a>
                <a href="{{ route('lapins.index') }}"
                    class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i>
                    <span>Tous les Lapins</span>
                </a>
                <a href="{{ route('mises-bas.index') }}"
                    class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i>
                    <span>Mises Bas</span>
                </a>

                {{-- Dépenses --}}
                <a href="{{ route('expenses.index') }}"
                    class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
                    title="Gestion des dépenses">
                    <i class="bi bi-wallet2"></i>
                    <span>Dépenses</span>
                </a>

                {{-- ✅ ENTREPRISE LINK (Firm Admins Only) --}}
                @if (auth()->check() && auth()->user()->isFirmAdmin())
                    <a href="{{ route('firm.index') }}"
                        class="nav-link {{ request()->routeIs('firm.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Entreprise</span>
                    </a>
                @endif

                {{-- ✅ SUPER ADMIN LINK (Super Admins Only) --}}
                @if (auth()->check() && auth()->user()->isSuperAdmin())
                    <a href="{{ route('super.admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('super.admin.*') ? 'active' : '' }}"
                        style="color: var(--accent-orange);">
                        <i class="bi bi-star-fill"></i>
                        <span>Super Admin</span>
                    </a>
                @endif


                {{-- ✅ MORE DROPDOWN - FIXED POSITIONING --}}
                <div class="dropdown-container" style="position: relative; display: inline-block;">
                    <button class="nav-link" type="button" onclick="toggleMoreDropdown(event)" id="moreButton"
                        style="position: relative;">
                        <i class="bi bi-three-dots"></i>
                        <span>Plus</span>
                        <i class="bi bi-chevron-down" style="font-size: 10px;"></i>
                    </button>
                    {{-- ✅ DROPDOWN MENU - FIXED Z-INDEX & POSITIONING --}}
                    <div class="dropdown-menu-custom" id="moreDropdown"
                        style="
                    position: absolute;
                    top: calc(100% + 8px);
                    right: 0;
                    min-width: 240px;
                    z-index: 9999 !important;
                    display: none;
                ">
                        <a href="{{ route('saillies.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-heart"></i> Saillies
                        </a>
                        <a href="{{ route('naissances.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-egg-fill"></i> Naissances
                        </a>
                        <a href="{{ route('sales.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-cart"></i> Ventes
                        </a>
                        <a href="{{ route('activites.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-clock-history"></i> Activités
                        </a>

                        <a href="{{ route('invoices.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-receipt"></i>
                            <span>Mes Factures</span>
                        </a>



                        <a href="{{ route('settings.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-gear"></i> Paramètres
                        </a>

                        {{-- ✅ SUBSCRIPTION IN MORE DROPDOWN ONLY (Non-admin users) --}}
                        {{-- ✅ SUBSCRIPTION IN MORE DROPDOWN ONLY (Non-admin users) --}}
                        @if (auth()->check() && auth()->user()->role !== 'admin')
                            <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 8px 0;">
                            <a href="{{ route('subscription.plans') }}" class="dropdown-item-custom"
                                style="position:relative; {{ !auth()->user()->hasActiveSubscription() ? 'color: var(--accent-orange);' : '' }}">
                                <i class="bi bi-credit-card"></i>
                                <span style="flex: 1;">Abonnement</span>
                                @if (!auth()->user()->hasActiveSubscription())
                                    <span class="notification-badge"
                                        style="background: var(--accent-orange); position: absolute; top: 8px; right: 12px; min-width: 18px; height: 18px; font-size: 10px;">!</span>
                                @endif
                            </a>
                        @endif

                        {{-- In the MORE dropdown, after the Abonnement link --}}
                        @if (auth()->check() && auth()->user()->role !== 'admin')
                            <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 8px 0;">

                            {{-- View Plans --}}
                            <a href="{{ route('subscription.plans') }}" class="dropdown-item-custom">
                                <i class="bi bi-credit-card"></i>
                                <span>Nos Offres</span>
                            </a>

                            {{-- ✅ VIEW STATUS (NEW) --}}
                            <a href="{{ route('subscription.status') }}" class="dropdown-item-custom">
                                <i class="bi bi-pie-chart"></i>
                                <span>Mon Abonnement</span>
                                @if (auth()->user()->hasActiveSubscription())
                                    <span class="badge"
                                        style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green); font-size: 10px; margin-left: auto;">
                                        Actif
                                    </span>
                                @else
                                    <span class="badge"
                                        style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red); font-size: 10px; margin-left: auto;">
                                        Inactif
                                    </span>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>

                {{-- ❌ REMOVED: Duplicate subscription link from main nav (was causing duplicate badges) --}}
                {{-- @if (auth()->check() && auth()->user()->role !== 'admin')
                <a href="{{ route('subscription.plans') }}" class="nav-link subscription-nav-link...">
                    ...
                </a>
            @endif --}}
            </nav>

            {{-- ✅ MOBILE MENU TRIGGER --}}
            <button class="mobile-menu-trigger d-md-none" onclick="toggleMobileNav()" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>

            {{-- ✅ USER SIDE NAV - FIXED (NO DUPLICATE BADGES) --}}
            @auth
                <div class="nav-user-side d-none d-md-flex" style="overflow: visible !important;">
                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}" class="notification-trigger"
                        style="position: relative;">
                        <i class="bi bi-bell"></i>
                        @php
                            $unread = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if ($unread > 0)
                            <span class="notification-badge"
                                style="
                            position: absolute;
                            top: -5px;
                            right: -5px;
                            background: var(--accent-red);
                            color: white;
                            font-size: 10px;
                            font-weight: 700;
                            min-width: 19px;
                            height: 19px;
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: 2px solid var(--surface);
                            z-index: 10000;
                        ">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>

                    {{-- ✅ USER PROFILE DROPDOWN - NO SUBSCRIPTION BADGE HERE --}}
                    <div class="user-profile-dropdown" style="position: relative; display: inline-block;">
                        <div class="user-trigger" onclick="toggleUserDropdown(event)" style="position: relative;">
                            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        {{-- ✅ USER DROPDOWN MENU --}}
                        <div class="dropdown-menu-custom" id="userDropdown"
                            style="
                        position: absolute;
                        top: calc(100% + 8px);
                        right: 0;
                        min-width: 280px;
                        z-index: 9999 !important;
                        display: none;
                    ">
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

                            {{-- Theme Selector --}}
                            <a href="{{ route('settings.index') }}#system-tab"
                                class="dropdown-item-custom theme-switch-row" id="theme-selector">
                                <div class="theme-info" style="display: flex; align-items: center; gap: 10px; flex: 1;">
                                    <i class="bi bi-palette" id="theme-icon-main" style="color: var(--primary);"></i>
                                    <span>Thème</span>
                                </div>
                                <div class="theme-status-badge" id="theme-badge">
                                    @php
                                        $themeValue = auth()->user()->theme ?? 'system';
                                        $themeLabel = match ($themeValue) {
                                            'system' => 'Système',
                                            'light' => 'Clair',
                                            'dark' => 'Sombre',
                                            default => 'Système',
                                        };
                                    @endphp
                                    <span id="theme-text">{{ $themeLabel }}</span>
                                </div>
                            </a>

                            {{-- 👑 ADMIN SUBSCRIPTION MANAGEMENT (Admin only) --}}
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 8px 0;">
                                <div class="dropdown-header" style="background: var(--surface-alt);">
                                    <span style="font-size: 12px; color: var(--text-tertiary);">👑 Administration</span>
                                </div>
                                <a href="{{ route('admin.subscriptions.index') }}" class="dropdown-item-custom">
                                    <i class="bi bi-shield-lock"></i> Gestion Abonnements
                                </a>
                                <a href="{{ route('admin.subscriptions.transactions') }}" class="dropdown-item-custom">
                                    <i class="bi bi-receipt"></i> Transactions
                                </a>
                            @endif

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

        {{-- ✅ MOBILE NAV OVERLAY --}}
        @auth
            <div class="mobile-nav-overlay" id="mobileNavOverlay">
                <div class="mobile-nav-links">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Tableau de bord</span>
                    </a>

                    {{-- Mâles --}}
                    <a href="{{ route('males.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('males.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-up-right-square"></i>
                        <span>Mâles</span>
                    </a>

                    {{-- Femelles --}}
                    <a href="{{ route('femelles.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('femelles.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-right-square"></i>
                        <span>Femelles</span>
                    </a>

                    {{-- Tous les Lapins --}}
                    <a href="{{ route('lapins.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        <span>Tous les Lapins</span>
                    </a>

                    {{-- Mises Bas --}}
                    <a href="{{ route('mises-bas.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                        <i class="bi bi-egg"></i>
                        <span>Mises Bas</span>
                    </a>


                    {{-- ✅ MOBILE: ENTREPRISE LINK (Firm Admins Only) --}}
                    @if (auth()->check() && auth()->user()->isFirmAdmin())
                        <div class="mobile-nav-divider"></div>
                        <div
                            style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--primary); text-transform: uppercase;">
                            🏢 Entreprise
                        </div>
                        <a href="{{ route('firm.index') }}"
                            class="mobile-nav-link {{ request()->routeIs('firm.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Gérer l'Entreprise</span>
                        </a>
                    @endif

                    {{-- ✅ MOBILE: SUPER ADMIN LINK (Super Admins Only) --}}
                    @if (auth()->check() && auth()->user()->isSuperAdmin())
                        <div class="mobile-nav-divider"></div>
                        <div
                            style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--accent-orange); text-transform: uppercase;">
                            👑 Super Admin
                        </div>
                        <a href="{{ route('super.admin.dashboard') }}"
                            class="mobile-nav-link {{ request()->routeIs('super.admin.*') ? 'active' : '' }}">
                            <i class="bi bi-star-fill"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    @endif

                    {{-- ✅ MOBILE DIVIDER --}}
                    <div class="mobile-nav-divider"></div>

                    {{-- ✅ MORE SECTION (Saillies, Naissances, Ventes, Activités, Paramètres) --}}
                    <div
                        style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px;">
                        Plus
                    </div>

                    <a href="{{ route('saillies.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('saillies.*') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i>
                        <span>Saillies</span>
                    </a>

                    <a href="{{ route('naissances.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('naissances.*') ? 'active' : '' }}">
                        <i class="bi bi-egg-fill"></i>
                        <span>Naissances</span>
                    </a>

                    <a href="{{ route('sales.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i>
                        <span>Ventes</span>
                    </a>

                    <a href="{{ route('activites.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('activites.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Activités</span>
                    </a>

                    <a href="{{ route('settings.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Paramètres</span>
                    </a>

                    {{-- ✅ SUBSCRIPTION LINKS (Non-admin users only) --}}
                    @if (auth()->check() && auth()->user()->role !== 'admin')
                        <div class="mobile-nav-divider"></div>

                        <div
                            style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--accent-orange); text-transform: uppercase; letter-spacing: 0.5px;">
                            💳 Abonnement
                        </div>

                        <a href="{{ route('subscription.plans') }}"
                            class="mobile-nav-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}"
                            style="{{ !auth()->user()->hasActiveSubscription() ? 'color: var(--accent-orange);' : '' }}">
                            <i class="bi bi-credit-card"></i>
                            <span>Nos Offres</span>
                            @if (!auth()->user()->hasActiveSubscription())
                                <span class="notification-badge"
                                    style="position: static; margin-left: auto; min-width: 18px; height: 18px; font-size: 10px;">!</span>
                            @endif
                        </a>

                        <a href="{{ route('subscription.status') }}" class="mobile-nav-link">
                            <i class="bi bi-pie-chart"></i>
                            <span>Mon Abonnement</span>
                            @if (auth()->user()->hasActiveSubscription())
                                <span class="badge"
                                    style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green); font-size: 10px; margin-left: auto;">Actif</span>
                            @else
                                <span class="badge"
                                    style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red); font-size: 10px; margin-left: auto;">Inactif</span>
                            @endif
                        </a>
                    @endif

                    {{-- ✅ ADMIN LINKS (Admin users only) --}}
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <div class="mobile-nav-divider"></div>

                        <div
                            style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--accent-purple); text-transform: uppercase; letter-spacing: 0.5px;">
                            👑 Administration
                        </div>

                        <a href="{{ route('admin.subscriptions.index') }}" class="mobile-nav-link">
                            <i class="bi bi-shield-lock"></i>
                            <span>Gestion Abonnements</span>
                        </a>

                        <a href="{{ route('admin.subscriptions.transactions') }}" class="mobile-nav-link">
                            <i class="bi bi-receipt"></i>
                            <span>Transactions</span>
                        </a>
                    @endif

                    {{-- ✅ USER PROFILE SECTION --}}
                    <div class="mobile-nav-divider"></div>

                    <div
                        style="padding: 8px 16px; font-size: 11px; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.5px;">
                        Compte
                    </div>

                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}" class="mobile-nav-link">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        @php
                            $unread = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if ($unread > 0)
                            <span class="notification-badge"
                                style="position: static; margin-left: auto; min-width: 18px; height: 18px; font-size: 10px;">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>

                    {{-- Profile --}}
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                        <i class="bi bi-person"></i>
                        <span>Mon Profil</span>
                    </a>

                    {{-- Theme Selector --}}
                    <a href="{{ route('settings.index') }}#system-tab" class="mobile-nav-link">
                        <i class="bi bi-palette"></i>
                        <span>Thème</span>
                        <span class="theme-status-badge" style="font-size: 10px; padding: 2px 6px; margin-left: auto;">
                            {{ auth()->user()->theme ?? 'system' }}
                        </span>
                    </a>

                    {{-- Logout --}}
                    <div class="mobile-nav-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-nav-link"
                            style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                            <i class="bi bi-box-arrow-right" style="color: var(--accent-red);"></i>
                            <span style="color: var(--accent-red);">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </header>

    <main class="cuni-main">
        @yield('content')
    </main>

    <footer class="cuni-footer">
        <div class="footer-container">
            <!-- Main Footer Grid -->
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <svg viewBox="0 0 40 40" fill="none">
                                <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                                <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                            </svg>
                        </div>
                        <div class="footer-logo-text">CuniApp <span>Élevage</span></div>
                    </div>
                    <p class="footer-tagline">
                        La solution complète pour la gestion intelligente de votre élevage de lapins.
                        Suivez vos reproductions, naissances et performances en toute simplicité.
                    </p>

                </div>

                <!-- Navigation Section -->
                <div class="footer-section">
                    <h4>
                        <i class="bi bi-compass"></i>
                        Navigation
                        <span class="section-divider"></span>
                    </h4>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-chevron-right"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('activites.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Historique des activités
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('notifications.index') }}">
                                <i class="bi bi-chevron-right"></i> Notifications
                                @php
                                    $unread = \App\Models\Notification::where('user_id', auth()->id() ?? 0)
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                @if ($unread > 0)
                                    <span class="badge-notification">{{ $unread > 99 ? '99+' : $unread }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Paramètres
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="bi bi-chevron-right"></i>
                                Mon Profil
                            </a>
                        </li>
                        {{-- 💳 SUBSCRIPTION LINK - Footer --}}
                        @if (auth()->check() && auth()->user()->role !== 'admin')
                            <li>
                                <a href="{{ route('subscription.plans') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    Abonnement
                                    @if (!auth()->user()->hasActiveSubscription())
                                        <span class="badge-notification">!</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        {{-- 👑 ADMIN SUBSCRIPTION LINK - Footer --}}
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            <li>
                                <a href="{{ route('admin.subscriptions.index') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    Gestion Abonnements
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.subscriptions.transactions') }}">
                                    <i class="bi bi-chevron-right"></i>
                                    Transactions
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Gestion Section -->
                <div class="footer-section">
                    <h4>
                        <i class="bi bi-briefcase"></i>
                        Gestion d'Élevage
                        <span class="section-divider"></span>
                    </h4>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('males.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Mâles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('femelles.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Femelles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lapins.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Tous les Lapins
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('saillies.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Saillies
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mises-bas.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Mises Bas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('naissances.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Naissances
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('sales.index') }}">
                                <i class="bi bi-chevron-right"></i>
                                Ventes
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="footer-section">
                    <h4>
                        <i class="bi bi-envelope"></i>
                        Contact & Infos
                        <span class="section-divider"></span>
                    </h4>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <strong>Adresse</strong>
                                <span>Houéyiho après le pont devant Volta United, Cotonou, Bénin</span>
                            </div>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-whatsapp"></i>
                            <div>
                                <strong>WhatsApp</strong>
                                <a href="https://www.linkedin.com/company/anyxtech-sarl/" _target>+229 01 52 41 52
                                    41</a>
                            </div>
                        </div>
                        <div class="footer-contact-item">
                            <i class="bi bi-envelope-fill"></i>
                            <div>
                                <strong>Email</strong>
                                <a href="mailto:contact@anyxtech.com">contact@anyxtech.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-linkedin"></i>
                        <div>
                            <a href="https://www.linkedin.com/company/anyxtech-sarl/" target="_blank"
                                rel="noopener noreferrer">
                                <strong>LinkedIn</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                <p>
                    &copy; {{ date('Y') }}
                    <a href="{{ route('dashboard') }}">CuniApp Élevage</a>.
                    Tous droits réservés.
                </p>
                <p class="footer-version">
                    Version {{ config('app.version', '1.0.0') }}
                    <span class="separator">•</span>
                    <span class="build-info">Build {{ date('Y.m.d') }}</span>
                </p>
            </div>

            <div class="footer-legal">
                <a href="{{ route('privacy') }}">
                    <i class="bi bi-shield-check"></i>
                    Confidentialité
                </a>
                <a href="{{ route('terms') }}">
                    <i class="bi bi-file-text"></i>
                    Conditions
                </a>
                <a href="{{ route('contact') }}">
                    <i class="bi bi-headset"></i>
                    Support
                </a>
                <a href="{{ route('health.check') }}" target="_blank">
                    <i class="bi bi-activity"></i>
                    État du système
                </a>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button id="backToTop" class="back-to-top" title="Retour en haut">
            <i class="bi bi-arrow-up-short"></i>
        </button>
        </div>
    </footer>

    <button id="backToTop" class="back-to-top" title="Retour en haut">
        <i class="bi bi-arrow-up-short"></i>
    </button>

    @stack('scripts')
    @include('components.modal-system')
    <script>
        function getSystemTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function initTheme() {
            const userTheme = '{{ auth()->check() ? auth()->user()->theme : 'system' }}';
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
                const themeText = document.getElementById('theme-text');
                const themeIcon = document.getElementById('theme-icon-main');
                if (themeText) themeText.innerText = 'Sombre';
                if (themeIcon) themeIcon.className = 'bi bi-moon-fill';
            } else {
                document.documentElement.classList.remove('theme-dark');
                const themeText = document.getElementById('theme-text');
                const themeIcon = document.getElementById('theme-icon-main');
                if (themeText) themeText.innerText = savedTheme === 'system' ? 'Système' : 'Clair';
                if (themeIcon) themeIcon.className = savedTheme === 'system' ? 'bi bi-display' : 'bi bi-sun';
            }
            const themeBadge = document.getElementById('theme-badge');
            if (themeBadge) {
                const badgeText = themeBadge.querySelector('span');
                if (badgeText) {
                    const themeValue = '{{ auth()->check() ? auth()->user()->theme : 'system' }}';
                    badgeText.innerText = themeValue === 'system' ? 'Système' : (themeValue === 'light' ? 'Clair' :
                        'Sombre');
                }
            }
        }

        function setTheme(theme) {
            localStorage.setItem('color-theme', theme);
            if ({{ auth()->check() ? 'true' : 'false' }}) {
                fetch('{{ route('settings.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            theme: theme
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Theme updated:', data);
                    })
                    .catch(error => {
                        console.error('Theme update failed:', error);
                    });
            }
            let applyTheme = theme;
            if (theme === 'system') {
                applyTheme = getSystemTheme();
            }
            applyThemeVisuals(applyTheme, theme);
            const label = theme === 'system' ? 'Système' : (theme === 'light' ? 'Clair' : 'Sombre');
            showToast('Thème mis à jour: ' + label);
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            const savedTheme = localStorage.getItem('color-theme');
            if (!savedTheme || savedTheme === 'system') {
                initTheme();
            }
        });

        document.addEventListener('click', function(e) {
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            if (userDropdown && !e.target.closest('.user-profile-dropdown')) {
                userDropdown.classList.remove('show');
            }
            if (moreDropdown && !e.target.closest('.dropdown-container')) {
                moreDropdown.classList.remove('show');
            }
        });

        initTheme();

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

        function toggleMoreDropdown(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const moreDropdown = document.getElementById('moreDropdown');
            const userDropdown = document.getElementById('userDropdown');
            const mobileNav = document.getElementById('mobileNavOverlay');

            if (userDropdown) userDropdown.classList.remove('show');
            if (mobileNav) mobileNav.classList.remove('active');

            if (moreDropdown) {
                const isShowing = moreDropdown.classList.contains('show');
                // Close all dropdowns first
                document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('show'));
                // Toggle current dropdown
                if (!isShowing) {
                    moreDropdown.classList.add('show');
                }
            }
        }

        function toggleUserDropdown(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            const mobileNav = document.getElementById('mobileNavOverlay');

            if (moreDropdown) moreDropdown.classList.remove('show');
            if (mobileNav) mobileNav.classList.remove('active');

            if (userDropdown) {
                const isShowing = userDropdown.classList.contains('show');
                document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('show'));
                if (!isShowing) {
                    userDropdown.classList.add('show');
                }
            }
        }

        function toggleMobileNav() {
            const mobileNav = document.getElementById('mobileNavOverlay');
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            if (userDropdown) userDropdown.classList.remove('show');
            if (moreDropdown) moreDropdown.classList.remove('show');
            if (mobileNav) {
                mobileNav.classList.toggle('active');
                document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
            }
        }

        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Update the document click handler
        document.addEventListener('click', function(e) {
            const userDropdown = document.getElementById('userDropdown');
            const moreDropdown = document.getElementById('moreDropdown');
            const mobileNav = document.getElementById('mobileNavOverlay');
            const mobileTrigger = document.querySelector('.mobile-menu-trigger');

            // Close user dropdown if clicking outside
            if (userDropdown && !e.target.closest('.user-profile-dropdown')) {
                userDropdown.classList.remove('show');
            }

            // Close more dropdown if clicking outside
            if (moreDropdown && !e.target.closest('.dropdown-container')) {
                moreDropdown.classList.remove('show');
            }

            // Close mobile nav if clicking outside
            if (mobileNav && mobileTrigger &&
                !e.target.closest('.mobile-menu-trigger') &&
                !e.target.closest('.mobile-nav-overlay')) {
                mobileNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>

</html>
