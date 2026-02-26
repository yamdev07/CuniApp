@section('title', 'Tableau de Bord - CuniApp Élevage')
@section('content')
    <style>
        /* Dashboard Specific Styles - Optimized & Deduplicated */
        .dash-header {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

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
            /* Deepest background (not pure black) */
            --surface-alt: #0F172A;
            /* Card surfaces */
            --surface-elevated: #151E30;
            /* Hover states, modals */
            --surface-overlay: #1A2335;
            /* Dropdowns, tooltips */
            --surface-border: #25324A;
            /* Subtle dividers */

            /* ===== TYPOGRAPHY ===== */
            --text-primary: #E6E9F0;
            /* Headings, critical text (92% white) */
            --text-secondary: #A3B3C6;
            /* Body text (65% white) */
            --text-tertiary: #6B7D95;
            /* Muted text, placeholders (45% white) */
            --text-inverted: #0F172A;
            /* Text on primary colors */

            /* ===== BRAND COLORS (Optimized for dark) ===== */
            --primary: #4DA6FF;
            /* Brighter for visibility */
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
        /* Fixed: "Plus" Dropdown Menu */
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

        /* Fixed: Dashboard Header Control Buttons (Paramètres, Nouvelle entrée) */
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

        /* Fixed: User Profile Trigger Button */
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

        /* Fixed: Notification Badge in Header */
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

        /* Form Controls */
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

        /* Buttons */
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

        /* Tables */
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

        /* Badges */
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

        /* Alerts */
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

        /* Tooltips & Popovers */
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

        /* Calendar */
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

        /* Dropdowns */
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

        /* Toast Notifications */
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

        /* Inputs with Icons */
        .theme-dark .form-input-wrapper i {
            color: var(--text-tertiary);
        }

        .theme-dark .form-input:focus+i,
        .theme-dark .form-input-wrapper:focus-within i {
            color: var(--primary);
        }

        /* Pagination */
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

        /* Verification Modal */
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

        /* Metrics Cards (Dashboard) */
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

        /* Critical: Fix White SVG Icons */
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

        /* Header - Fixed "Plus" dropdown */
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

    <div class="cuniapp-dashboard">
        <!-- Header Section -->
        <header class="dash-header">
            <div class="header-wrapper-dash">
                <div class="brand-identity-dash">
                    <div class="cuniapp-logo-dash">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                        </svg>
                    </div>
                    <div class="brand-text-dash">
                        <h1>CuniApp <span class="subtitle-accent">Élevage</span></h1>
                        <p class="brand-tagline-dash">Gestion intelligente de votre cheptel</p>
                    </div>
                </div>
                <div class="header-controls">
                    <a href="{{ route('settings.index') }}" class="ctrl-btn secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0-.33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0 .33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg> Paramètres
                    </a>
                    <a href="{{ route('lapins.create') }}" class="ctrl-btn primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg> Nouvelle entrée
                    </a>
                </div>
            </div>
            <div class="metrics-grid">
                @php
                    $metricsData = [
                        [
                            'icon' => 'total',
                            'value' => $nbMales + $nbFemelles,
                            'label' => 'Total Lapins',
                            'type' => 'primary',
                            'change' => '+8.2%',
                            'trend' => 'up',
                            'route' => 'lapins.index',
                        ],
                        [
                            'icon' => 'male',
                            'value' => $nbMales,
                            'label' => 'Mâles',
                            'type' => 'blue',
                            'change' => '+5.1%',
                            'trend' => 'up',
                            'route' => 'males.index',
                        ],
                        [
                            'icon' => 'female',
                            'value' => $nbFemelles,
                            'label' => 'Femelles',
                            'type' => 'pink',
                            'change' => '+12%',
                            'trend' => 'up',
                            'route' => 'femelles.index',
                        ],
                        [
                            'icon' => 'breed',
                            'value' => $nbSaillies,
                            'label' => 'Saillies',
                            'type' => 'purple',
                            'change' => '-3.1%',
                            'trend' => 'down',
                            'route' => 'saillies.index',
                        ],
                        [
                            'icon' => 'birth',
                            'value' => $nbMisesBas,
                            'label' => 'Portées',
                            'type' => 'green',
                            'change' => '+15%',
                            'trend' => 'up',
                            'route' => 'mises-bas.index',
                        ],
                        [
                            'icon' => 'alert',
                            'value' => 3,
                            'label' => 'Alertes',
                            'type' => 'orange',
                            'change' => '0%',
                            'trend' => 'neutral',
                            'route' => '',
                        ],
                        [
                            'icon' => 'sales',
                            'value' => number_format($totalRevenue, 0, ',', ' '),
                            'label' => 'CA Total',
                            'type' => 'purple',
                            'change' => '+12%',
                            'trend' => 'up',
                            'route' => 'sales.index',
                        ],
                    ];
                @endphp
                @foreach ($metricsData as $metric)
                    <a href="{{ Route::has($metric['route']) ? route($metric['route']) : '#' }}">
                        <div class="metric-card {{ $metric['type'] }}" data-trend="{{ $metric['trend'] }}">
                            <div class="metric-icon">
                                @if ($metric['icon'] === 'total')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                                        <circle cx="17" cy="7" r="2" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    </svg>
                                @elseif($metric['icon'] === 'male')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="10" cy="14" r="6" />
                                        <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                    </svg>
                                @elseif($metric['icon'] === 'female')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="8" r="6" />
                                        <path d="M12 14v8M9 19h6" />
                                    </svg>
                                @elseif($metric['icon'] === 'breed')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                @elseif($metric['icon'] === 'birth')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                @endif
                            </div>
                            <div class="metric-data">
                                <div class="metric-value">{{ $metric['value'] }}</div>
                                <div class="metric-label">{{ $metric['label'] }}</div>
                                <div class="metric-trend {{ $metric['trend'] }}">
                                    <span
                                        class="trend-arrow">{{ $metric['trend'] === 'up' ? '↗' : ($metric['trend'] === 'down' ? '↘' : '→') }}</span>
                                    {{ $metric['change'] }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </header>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Left Column -->
            <div class="primary-col">
                <!-- Performance Overview -->
                <div class="section-block">
                    <div class="section-title">
                        <h2>Performance</h2>
                    </div>
                    <div class="performance-grid">
                        @php
                            $totalCheptel = max($nbMales + $nbFemelles, 1);
                            $perfCards = [
                                [
                                    'type' => 'blue',
                                    'icon' => 'male',
                                    'value' => $nbMales,
                                    'title' => 'Mâles Reproducteurs',
                                    'progress' => ($nbMales / $totalCheptel) * 100,
                                    'trend' => number_format($malePercent, 1) . '%',
                                    'isUp' => $malePercent >= 0,
                                ],
                                [
                                    'type' => 'pink',
                                    'icon' => 'female',
                                    'value' => $nbFemelles,
                                    'title' => 'Femelles Reproductrices',
                                    'progress' => ($nbFemelles / $totalCheptel) * 100,
                                    'trend' => number_format($femalePercent, 1) . '%',
                                    'isUp' => $femalePercent >= 0,
                                ],
                                [
                                    'type' => 'purple',
                                    'icon' => 'breed',
                                    'value' => $nbSaillies,
                                    'title' => 'Saillies Totales',
                                    'progress' => $nbFemelles > 0 ? min(($nbSaillies / $nbFemelles) * 100, 100) : 0,
                                    'trend' => number_format($sailliePercent, 1) . '%',
                                    'isUp' => $sailliePercent >= 0,
                                ],
                                [
                                    'type' => 'green',
                                    'icon' => 'birth',
                                    'value' => $nbMisesBas,
                                    'title' => 'Mises Bas',
                                    'progress' => $nbSaillies > 0 ? min(($nbMisesBas / $nbSaillies) * 100, 100) : 0,
                                    'trend' => number_format($miseBasPercent, 1) . '%',
                                    'isUp' => $miseBasPercent >= 0,
                                ],
                            ];
                        @endphp
                        @foreach ($perfCards as $card)
                            <div class="perf-card {{ $card['type'] }}">
                                <div class="card-top">
                                    <span class="card-label">{{ $card['title'] }}</span>
                                    <div class="card-badge {{ $card['type'] }}">
                                        @if ($card['icon'] === 'male')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="10" cy="14" r="6" />
                                                <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                            </svg>
                                        @elseif($card['icon'] === 'female')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <circle cx="12" cy="8" r="6" />
                                                <path d="M12 14v8M9 19h6" />
                                            </svg>
                                        @elseif($card['icon'] === 'breed')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path
                                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-number">{{ $card['value'] }}</div>
                                <div class="progress-track">
                                    <div class="progress-bar {{ $card['type'] }}"
                                        style="width: {{ $card['progress'] }}%"></div>
                                </div>
                                <div class="card-footer">
                                    <span class="progress-label">{{ round($card['progress']) }}% du flux</span>
                                    <span class="trend-badge" style="color: {{ $card['isUp'] ? '#10b981' : '#ef4444' }}">
                                        {{ $card['isUp'] ? '↑' : '↓' }} {{ $card['trend'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="section-block">
                    <div class="section-title">
                        <h2>Actions Rapides</h2>
                    </div>
                    <div class="actions-grid">
                        @foreach ([['url' => route('males.index'), 'icon' => 'male', 'title' => 'Gérer Mâles', 'desc' => 'Consulter et modifier', 'color' => 'blue'], ['url' => route('femelles.index'), 'icon' => 'female', 'title' => 'Gérer Femelles', 'desc' => 'Suivi reproduction', 'color' => 'pink'], ['url' => route('saillies.index'), 'icon' => 'breed', 'title' => 'Planifier Saillie', 'desc' => 'Nouveau croisement', 'color' => 'purple']] as $action)
                            <a href="{{ $action['url'] }}" class="action-tile {{ $action['color'] }}">
                                <div class="tile-icon">
                                    @if ($action['icon'] === 'male')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="10" cy="14" r="6" />
                                            <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                        </svg>
                                    @elseif($action['icon'] === 'female')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="8" r="6" />
                                            <path d="M12 14v8M9 19h6" />
                                        </svg>
                                    @elseif($action['icon'] === 'breed')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                    @endif
                                </div>
                                <h3>{{ $action['title'] }}</h3>
                                <p>{{ $action['desc'] }}</p>
                                <div class="tile-arrow">→</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="sidebar-col">
                <!-- Calendar -->
                <div class="widget calendar-widget">
                    <div class="widget-head">
                        <h3>Calendrier</h3>
                        <div class="calendar-controls">
                            <button class="cal-btn" id="prevMonth">‹</button>
                            <span class="cal-month" id="currentMonth">Février 2026</span>
                            <button class="cal-btn" id="nextMonth">›</button>
                        </div>
                    </div>
                    <div class="calendar-body" id="calendarGrid"></div>
                    <div class="calendar-legend">
                        <div class="legend-row">
                            <span class="legend-dot purple"></span>
                            <span>Saillies</span>
                        </div>
                        <div class="legend-row">
                            <span class="legend-dot green"></span>
                            <span>Naissances</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="widget activity-widget">
                    <div class="widget-head">
                        <h3>Activité</h3>
                        <button class="text-link">Tout voir</button>
                    </div>
                    <div class="timeline">
                        @foreach ([['type' => 'green', 'title' => 'Mise bas enregistrée', 'desc' => 'Femelle #245 - 6 lapereaux', 'time' => 'Il y a 2h'], ['type' => 'purple', 'title' => 'Saillie programmée', 'desc' => 'F#245 × M#112', 'time' => 'Hier 15:30'], ['type' => 'orange', 'title' => 'Vaccination requise', 'desc' => '3 lapins concernés', 'time' => '23 août'], ['type' => 'blue', 'title' => 'Rapport généré', 'desc' => 'Stats mensuelles', 'time' => '20 août']] as $item)
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $item['type'] }}"></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">{{ $item['title'] }}</div>
                                    <div class="timeline-desc">{{ $item['desc'] }}</div>
                                    <div class="timeline-time">{{ $item['time'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notifications Widget -->
                <div class="widget alerts-widget">
                    <div class="widget-head">
                        <h3>Notifications</h3>
                        <a href="{{ route('notifications.index') }}" class="text-link flex items-center gap-1">
                            Voir tout <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="alerts-list">
                        @php
                            $recentNotifs = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_read', false)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($recentNotifs as $notif)
                            <a href="{{ route('notifications.read', $notif->id) }}"
                                class="alert-row {{ $notif->type }}">
                                <div class="alert-indicator"></div>
                                <div class="alert-text">
                                    <div class="alert-title flex items-center gap-2">
                                        <i class="bi {{ $notif->icon }} text-sm"></i>
                                        {{ $notif->title }}
                                    </div>
                                    <div class="alert-time">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                                @if (!$notif->is_read)
                                    <span class="badge"
                                        style="background: rgba(239, 68, 68, 0.1); color: #EF4444; font-size: 11px; padding: 2px 8px;">
                                        Nouveau
                                    </span>
                                @endif
                            </a>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="bi bi-bell-slash text-2xl mb-2 opacity-50"></i>
                                <p>Aucune notification non lue</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarGrid = document.getElementById('calendarGrid');
            const currentMonthSpan = document.getElementById('currentMonth');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            let currentDate = new Date();
            const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                'Octobre', 'Novembre', 'Décembre'
            ];
            const weekdays = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];

            function renderCalendar(date) {
                calendarGrid.innerHTML = '';
                weekdays.forEach(day => {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'cal-day header';
                    dayEl.textContent = day;
                    calendarGrid.appendChild(dayEl);
                });

                const year = date.getFullYear();
                const month = date.getMonth();
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const today = new Date();
                const startDay = firstDay === 0 ? 6 : firstDay - 1;
                currentMonthSpan.textContent = `${months[month]} ${year}`;

                for (let i = 0; i < startDay; i++) {
                    calendarGrid.appendChild(document.createElement('div'));
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'cal-day';
                    dayEl.textContent = day;

                    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dayEl.classList.add('today');
                    }

                    if ([5, 12, 18].includes(day)) {
                        dayEl.classList.add('event', 'purple');
                    }
                    if ([8, 15, 22].includes(day)) {
                        dayEl.classList.add('event', 'green');
                    }

                    calendarGrid.appendChild(dayEl);
                }
            }

            prevMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            nextMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

            renderCalendar(currentDate);

            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 500);

            const elements = document.querySelectorAll('.metric-card, .perf-card, .action-tile, .widget');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.4s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endsection
