<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CuniApp Élevage')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        /* ==================== ALL ORIGINAL STYLES PRESERVED ==================== */
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

        /* ==================== PROFESSIONAL DARK MODE ==================== */
        .theme-dark {
            /* ===== CORE FOUNDATION ===== */
            --surface: #0A0F1D;
            --surface-alt: #0F172A;
            --surface-elevated: #151E30;
            --surface-overlay: #1A2335;
            --surface-border: #25324A;
            /* ===== TYPOGRAPHY ===== */
            --text-primary: #E6E9F0;
            --text-secondary: #A3B3C6;
            --text-tertiary: #6B7D95;
            --text-inverted: #0F172A;
            /* ===== BRAND COLORS (Optimized for dark) ===== */
            --primary: #4DA6FF;
            --primary-light: #6EB5FF;
            --primary-dark: #2A88F0;
            --primary-subtle: rgba(77, 166, 255, 0.12);
            --primary-glow: rgba(77, 166, 255, 0.25);
            /* ===== ACCENTS (Increased saturation) ===== */
            --accent-cyan: #22D3EE;
            --accent-purple: #A78BFA;
            --accent-pink: #F472B6;
            --accent-green: #34D399;
            --accent-orange: #FB923C;
            --accent-red: #F87171;
            /* ===== NEUTRALS (Expanded dark spectrum) ===== */
            --gray-50: #080C15;
            --gray-100: #0F172A;
            --gray-200: #1A2335;
            --gray-300: #25324A;
            --gray-400: #3B4B63;
            --gray-500: #54657F;
            --gray-600: #788BA5;
            --gray-700: #A3B3C6;
            --gray-800: #CBD5E1;
            --gray-900: #F1F5F9;
            /* ===== EFFECTS (Dark-optimized) ===== */
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.35);
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.45);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.55);
            --shadow-lg: 0 16px 32px rgba(0, 0, 0, 0.65);
            --shadow-tooltip: 0 4px 16px rgba(0, 0, 0, 0.75);
            --glow-primary: 0 0 0 3px var(--primary-glow);
            --glow-success: 0 0 0 3px rgba(52, 211, 153, 0.3);
            --glow-warning: 0 0 0 3px rgba(251, 146, 60, 0.3);
            /* ===== FORM CONTROLS ===== */
            --input-bg: #151E30;
            --input-border: #25324A;
            --input-focus-border: var(--primary);
            --input-placeholder: var(--text-tertiary);
            --input-disabled-bg: #1A2335;
            --input-disabled-text: #54657F;
            /* ===== INTERACTIVE STATES ===== */
            --hover-subtle: rgba(255, 255, 255, 0.04);
            --hover-primary: rgba(77, 166, 255, 0.15);
            --active-primary: rgba(77, 166, 255, 0.25);
            --focus-ring: var(--glow-primary);
            /* ===== DATA VISUALIZATION ===== */
            --chart-grid: #25324A;
            --chart-axis: #3B4B63;
            --chart-tooltip-bg: var(--surface-overlay);
            --chart-tooltip-border: var(--surface-border);
            /* ===== TOOLTIPS & POPOVERS ===== */
            --tooltip-bg: var(--surface-overlay);
            --tooltip-border: #2D3E58;
            --tooltip-text: var(--text-primary);
            --tooltip-shadow: var(--shadow-tooltip);
            /* ===== BADGES & STATUS ===== */
            --badge-active-bg: rgba(52, 211, 153, 0.15);
            --badge-active-text: #22C55E;
            --badge-gestante-bg: rgba(251, 146, 60, 0.15);
            --badge-gestante-text: #F97316;
            --badge-allaitante-bg: rgba(77, 166, 255, 0.15);
            --badge-allaitante-text: var(--primary);
            --badge-vide-bg: rgba(108, 117, 125, 0.15);
            --badge-vide-text: #6C757D;
            /* ===== ALERTS (Enhanced contrast) ===== */
            --alert-success-bg: rgba(16, 185, 129, 0.12);
            --alert-success-border: rgba(16, 185, 129, 0.3);
            --alert-warning-bg: rgba(245, 158, 11, 0.12);
            --alert-warning-border: rgba(245, 158, 11, 0.3);
            --alert-error-bg: rgba(239, 68, 68, 0.12);
            --alert-error-border: rgba(239, 68, 68, 0.3);
            --alert-info-bg: rgba(59, 130, 246, 0.12);
            --alert-info-border: rgba(59, 130, 246, 0.3);
            /* ===== CALENDAR SPECIFIC ===== */
            --cal-header-bg: var(--surface-alt);
            --cal-day-hover: var(--hover-subtle);
            --cal-day-today-bg: var(--primary);
            --cal-day-today-text: var(--text-inverted);
            --cal-event-purple: var(--accent-purple);
            --cal-event-green: var(--accent-green);
            /* ===== APPLY BASE STYLES ===== */
            background-color: var(--surface);
            color: var(--text-primary);
        }

        /* ==================== DARK MODE FIXES - HEADER COMPONENTS ==================== */
        .theme-dark .header-nav div[x-show="open"] {
            background: var(--surface-overlay) !important;
            border-color: var(--surface-border) !important;
            box-shadow: var(--shadow-lg);
        }

        .theme-dark .header-nav div[x-show="open"] .dropdown-item-custom {
            color: var(--text-primary);
            background: transparent;
        }

        .theme-dark .header-nav div[x-show="open"] .dropdown-item-custom:hover {
            background: var(--hover-subtle) !important;
            color: var(--primary) !important;
            transform: translateX(4px);
        }

        .theme-dark .header-nav div[x-show="open"] hr {
            border-color: var(--surface-border) !important;
        }

        .theme-dark .header-nav div[x-show="open"] .dropdown-item-custom.active {
            background: var(--primary-subtle) !important;
            color: var(--primary) !important;
            font-weight: 500;
        }

        .theme-dark .ctrl-btn.secondary {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
            color: var(--text-primary);
        }

        .theme-dark .ctrl-btn.secondary:hover {
            background: var(--hover-subtle);
            border-color: var(--gray-400);
            color: var(--primary);
        }

        .theme-dark .ctrl-btn.primary {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: var(--shadow-sm);
        }

        .theme-dark .ctrl-btn.primary:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .theme-dark .ctrl-btn i {
            color: currentColor;
        }

        .theme-dark .user-trigger {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .user-trigger:hover {
            background: var(--hover-subtle);
        }

        .theme-dark .user-avatar {
            background: var(--primary);
            color: var(--white);
        }

        .theme-dark .nav-link .badge {
            background: rgba(239, 68, 68, 0.15);
            color: var(--accent-red);
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* ==================== DARK MODE COMPONENT OVERRIDES ==================== */
        .theme-dark .cuni-header {
            background: linear-gradient(180deg, var(--surface) 0%, var(--surface-alt) 100%);
            box-shadow: var(--shadow);
            border-bottom: 1px solid var(--surface-border);
        }

        .theme-dark .brand-tagline {
            color: var(--text-tertiary);
        }

        .theme-dark .nav-link {
            color: var(--text-secondary);
            border-color: transparent;
        }

        .theme-dark .nav-link:hover {
            background: var(--hover-subtle);
            color: var(--text-primary);
        }

        .theme-dark .nav-link.active {
            background: var(--primary-subtle);
            color: var(--primary);
            border-color: var(--primary);
        }

        .theme-dark .nav-link.danger:hover {
            background: rgba(248, 113, 113, 0.1);
        }

        .theme-dark .cuni-card {
            background: var(--surface-alt);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow-md);
        }

        .theme-dark .card-header-custom {
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid var(--surface-border);
        }

        .theme-dark .card-title {
            color: var(--text-primary);
        }

        .theme-dark .form-control,
        .theme-dark .form-select {
            background: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-dark .form-control::placeholder,
        .theme-dark .form-select option {
            color: var(--input-placeholder);
        }

        .theme-dark .form-control:focus,
        .theme-dark .form-select:focus {
            border-color: var(--input-focus-border);
            box-shadow: var(--focus-ring);
        }

        .theme-dark .form-control.is-invalid {
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.2);
        }

        .theme-dark .form-control.is-valid {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2);
        }

        .theme-dark .btn-cuni.primary {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: var(--shadow-sm);
        }

        .theme-dark .btn-cuni.primary:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .theme-dark .btn-cuni.secondary {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
            color: var(--text-primary);
        }

        .theme-dark .btn-cuni.secondary:hover {
            background: var(--hover-subtle);
            border-color: var(--gray-400);
        }

        .theme-dark .btn-cuni.danger {
            background: rgba(248, 113, 113, 0.1);
            border-color: rgba(248, 113, 113, 0.3);
            color: var(--accent-red);
        }

        .theme-dark .btn-cuni.danger:hover {
            background: rgba(248, 113, 113, 0.15);
        }

        .theme-dark .table thead {
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid var(--surface-border);
        }

        .theme-dark .table tbody tr:hover {
            background: var(--hover-subtle);
        }

        .theme-dark .table tbody td {
            color: var(--text-secondary);
            border-color: var(--surface-border);
        }

        .theme-dark .table-empty-state {
            background: var(--surface-elevated);
        }

        .theme-dark .badge {
            border: 1px solid transparent;
        }

        .theme-dark .status-active {
            background: var(--badge-active-bg);
            color: var(--badge-active-text);
            border-color: rgba(52, 211, 153, 0.3);
        }

        .theme-dark .status-gestante {
            background: var(--badge-gestante-bg);
            color: var(--badge-gestante-text);
            border-color: rgba(251, 146, 60, 0.3);
        }

        .theme-dark .status-allaitante {
            background: var(--badge-allaitante-bg);
            color: var(--badge-allaitante-text);
            border-color: rgba(77, 166, 255, 0.3);
        }

        .theme-dark .status-vide {
            background: var(--badge-vide-bg);
            color: var(--badge-vide-text);
            border-color: rgba(108, 117, 125, 0.3);
        }

        .theme-dark .alert-cuni {
            border-width: 1px;
        }

        .theme-dark .alert-cuni.success {
            background: var(--alert-success-bg);
            border-color: var(--alert-success-border);
            color: var(--accent-green);
        }

        .theme-dark .alert-cuni.error {
            background: var(--alert-error-bg);
            border-color: var(--alert-error-border);
            color: var(--accent-red);
        }

        .theme-dark .tooltip-inner {
            background: var(--tooltip-bg);
            color: var(--tooltip-text);
            border: 1px solid var(--tooltip-border);
            box-shadow: var(--tooltip-shadow);
            border-radius: var(--radius);
        }

        .theme-dark .tooltip-arrow::before {
            border-color: var(--tooltip-bg);
        }

        .theme-dark .calendar-body {
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            padding: 8px;
        }

        .theme-dark .cal-day.header {
            color: var(--text-tertiary);
            background: transparent;
        }

        .theme-dark .cal-day:not(.header):hover {
            background: var(--cal-day-hover);
        }

        .theme-dark .cal-day.today {
            background: var(--cal-day-today-bg);
            color: var(--cal-day-today-text);
            font-weight: 600;
        }

        .theme-dark .cal-day.event::after {
            opacity: 0.85;
        }

        .theme-dark .dropdown-menu-custom {
            background: var(--surface-overlay);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow-lg);
        }

        .theme-dark .dropdown-item-custom:hover {
            background: var(--hover-subtle);
            transform: translateX(0);
        }

        .theme-dark .dropdown-header {
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid var(--surface-border);
        }

        .theme-dark #toast-container .pointer-events-auto {
            box-shadow: var(--shadow-lg);
            border-left-width: 3px;
        }

        .theme-dark #toast-container .pointer-events-auto.bg-gradient-to-r {
            background: var(--surface-overlay) !important;
        }

        .theme-dark #toast-container .text-gray-800 {
            color: var(--text-primary) !important;
        }

        .theme-dark #toast-container .text-gray-700 {
            color: var(--text-secondary) !important;
        }

        .theme-dark .form-input-wrapper i {
            color: var(--text-tertiary);
        }

        .theme-dark .form-input:focus+i,
        .theme-dark .form-input-wrapper:focus-within i {
            color: var(--primary);
        }

        .theme-dark .pagination .page-item .page-link {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
            color: var(--text-secondary);
        }

        .theme-dark .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--text-inverted);
        }

        .theme-dark .pagination .page-item:hover .page-link {
            background: var(--hover-subtle);
            border-color: var(--primary);
            color: var(--primary);
        }

        .theme-dark .verification-modal {
            background: var(--surface-alt);
            box-shadow: var(--shadow-lg);
        }

        .theme-dark .verification-header {
            border-bottom: 1px solid var(--surface-border);
            background: rgba(255, 255, 255, 0.03);
        }

        .theme-dark .verification-code-input {
            background: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        .theme-dark .verification-code-input:focus {
            border-color: var(--primary);
            box-shadow: var(--glow-primary);
        }

        .theme-dark .verification-code-input.filled {
            background: var(--primary-subtle);
            border-color: var(--primary);
        }

        .theme-dark .metric-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .theme-dark .perf-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .theme-dark .action-tile:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .theme-dark svg {
            fill: currentColor;
        }

        .theme-dark .cuniapp-logo svg path {
            fill: rgba(255, 255, 255, 0.92) !important;
        }

        .theme-dark .nav-link i,
        .theme-dark .btn-cuni i {
            color: currentColor;
        }

        /* ==================== GLOBAL BASE STYLES ==================== */
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
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            border-radius: var(--radius) var(--radius) 0 0;
            display: flex;
            align-items: center;
            gap: 8px;
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
            font-size: 16px;
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
                padding: 10px 14px;
                font-size: 13px;
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

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .action-buttons .btn-cuni {
            padding: 6px 10px;
            font-size: 12px;
        }

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
            border-radius: var(--radius);
        }

        .dropdown-item-custom:hover {
            background: var(--gray-50);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-item-custom.active {
            background: var(--primary-subtle);
            color: var(--primary);
            font-weight: 500;
        }

        .dropdown-item-custom i {
            width: 20px;
            text-align: center;
        }

        /* ==================== MOBILE MENU ==================== */
        #mobileMenuToggle {
            display: none;
        }

        @media (max-width: 768px) {
            #mobileMenuToggle {
                display: block;
                position: absolute;
                right: 16px;
                top: 16px;
                z-index: 101;
            }

            .header-nav,
            .user-profile-dropdown {
                display: none;
            }

            #mobileMenu {
                display: block;
            }
        }

        .theme-dark .dropdown-menu-custom {
            background: var(--surface-overlay);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow-lg);
        }

        .theme-dark .dropdown-item-custom {
            color: var(--text-primary);
        }

        .theme-dark .dropdown-item-custom:hover {
            background: var(--hover-subtle);
            color: var(--primary);
        }
    </style>
</head>

<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">
    <!-- Header -->
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
                <a href="{{ route('lapins.index') }}"
                    class="nav-link {{ request()->routeIs('lapins.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> Lapins
                </a>
                <a href="{{ route('saillies.index') }}"
                    class="nav-link {{ request()->routeIs('saillies.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Saillies
                </a>
                <a href="{{ route('mises-bas.index') }}"
                    class="nav-link {{ request()->routeIs('mises-bas.*') ? 'active' : '' }}">
                    <i class="bi bi-egg"></i> Mises Bas
                </a>
                <a href="{{ route('sales.index') }}"
                    class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i> Ventes
                </a>
                <a href="{{ route('settings.index') }}"
                    class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Paramètres
                </a>
            </nav>

            <!-- User Profile Dropdown -->
            @auth
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
                        <hr style="border-color: var(--surface-border); margin: 8px 0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item-custom danger">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="cuni-main">
        @yield('content')
    </main>

    <!-- Footer -->
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
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const trigger = document.querySelector('.user-trigger');
            if (dropdown && !trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>

</html>
