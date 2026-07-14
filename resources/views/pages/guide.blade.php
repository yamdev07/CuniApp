<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-x:hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Guide d\'utilisation') }} - CuniApp {{ __('Élevage') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #2563EB; --primary-light: #3B82F6; --primary-dark: #1D4ED8; --primary-subtle: #EFF6FF;
            --accent-cyan: #06B6D4; --accent-green: #10B981; --accent-orange: #F59E0B; --accent-red: #EF4444;
            --white: #FFFFFF; --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB; --gray-300: #D1D5DB;
            --gray-400: #9CA3AF; --gray-500: #6B7280; --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937; --gray-900: #111827;
            --surface: #FFFFFF; --surface-alt: #F9FAFB; --surface-border: #E5E7EB;
            --text-primary: #1F2937; --text-secondary: #6B7280; --text-tertiary: #9CA3AF;
            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05); --shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1); --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --radius: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-xl: 20px; --radius-2xl: 24px;
            --sidebar-w: 280px;
        }
        .theme-dark {
            --surface: #0A0F1D; --surface-alt: #0F172A; --surface-elevated: #151E30; --surface-overlay: #1E293B;
            --surface-border: #25324A; --text-primary: #E6E9F0; --text-secondary: #A3B3C6; --text-tertiary: #6B7D95;
            --primary: #4DA6FF; --primary-subtle: rgba(77,166,255,0.12); --accent-green: #34D399;
            --accent-orange: #FB923C; --accent-red: #F87171; --gray-50: #080C15; --gray-100: #0F172A;
            --gray-200: #1A2335; --gray-300: #25324A; --gray-400: #4A5568; --gray-500: #718096;
            --gray-600: #A0AEC0; --gray-700: #CBD5E0; --gray-800: #E2E8F0; --gray-900: #F7FAFC;
        }
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; overflow-x:hidden; }
        body { font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif; background:var(--gray-50); color:var(--text-primary); line-height:1.6; padding-top:64px; }
        .theme-dark body { background:var(--gray-50); color:var(--text-primary); }

        /* Top Bar */
        .guide-topbar {
            position:fixed; top:0; left:0; right:0; z-index:100; height:64px;
            background:var(--surface); border-bottom:1px solid var(--surface-border);
            backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
        }
        .topbar-inner {
            max-width:100%; margin:0 auto; padding:0 24px; height:64px;
            display:flex; align-items:center; justify-content:space-between; gap:20px;
        }
        .topbar-brand { display:flex; align-items:center; gap:12px; text-decoration:none; flex-shrink:0; }
        .topbar-logo {
            width:38px; height:38px; background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center;
            box-shadow:0 4px 12px rgba(37,99,235,0.3);
        }
        .topbar-logo svg { width:20px; height:20px; }
        .topbar-brand-text { font-size:18px; font-weight:700; color:var(--text-primary); letter-spacing:-0.02em; }
        .topbar-brand-text span { color:var(--primary); }

        /* Search */
        .search-wrapper {
            flex:1; max-width:520px; position:relative;
        }
        .search-input {
            width:100%; padding:10px 16px 10px 42px; font-size:14px; font-family:inherit;
            border:2px solid var(--surface-border); border-radius:var(--radius-lg);
            background:var(--surface-alt); color:var(--text-primary);
            transition:all 0.3s ease; outline:none;
        }
        .search-input:focus { border-color:var(--primary); background:var(--surface); box-shadow:0 0 0 4px var(--primary-subtle); }
        .search-input::placeholder { color:var(--text-tertiary); }
        .search-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-tertiary); font-size:16px; pointer-events:none; }
        .search-kbd {
            position:absolute; right:12px; top:50%; transform:translateY(-50%);
            font-size:11px; font-family:'JetBrains Mono',monospace; color:var(--text-tertiary);
            background:var(--surface); border:1px solid var(--surface-border); border-radius:4px;
            padding:2px 6px; pointer-events:none;
        }

        .topbar-actions { display:flex; align-items:center; gap:12px; flex-shrink:0; }
        .topbar-link {
            font-size:13px; font-weight:500; color:var(--text-secondary); text-decoration:none;
            padding:8px 14px; border-radius:var(--radius); transition:all 0.2s ease;
            display:flex; align-items:center; gap:6px;
        }
        .topbar-link:hover { color:var(--primary); background:var(--primary-subtle); }
        .mobile-menu-btn {
            display:none; background:none; border:none; font-size:22px; color:var(--text-secondary);
            cursor:pointer; padding:8px; border-radius:var(--radius);
        }
        .mobile-menu-btn:hover { background:var(--gray-100); color:var(--primary); }

        /* Layout */
        .guide-layout {
            box-sizing:border-box;
            width:100%;
            display:block;
            min-height:calc(100vh - 64px);
            padding-left:var(--sidebar-w);
        }

        /* Sidebar */
        .guide-sidebar {
            width:var(--sidebar-w);
            border-right:1px solid var(--surface-border);
            background:var(--surface);
            position:fixed; top:64px; left:0; bottom:0;
            overflow-x:hidden; overflow-y:auto;
            padding:24px 0;
            transition:transform 0.3s ease;
            z-index:50;
        }

        /* Main Content + Footer wrapper */
        .guide-body {
            width:100%;
            box-sizing:border-box;
        }

        /* Main Content */
        .guide-main {
            padding:40px 48px 80px;
        }

        .sidebar-section { margin-bottom:8px; }
        .sidebar-section-title {
            font-size:11px; font-weight:700; color:var(--text-tertiary); text-transform:uppercase;
            letter-spacing:0.08em; padding:8px 24px; display:flex; align-items:center; gap:8px;
        }
        .sidebar-section-title i { font-size:13px; color:var(--primary); }
        .sidebar-link {
            display:flex; align-items:center; gap:10px; padding:9px 24px; font-size:13px; font-weight:500;
            color:var(--text-secondary); text-decoration:none; transition:all 0.2s ease; border-left:3px solid transparent;
        }
        .sidebar-link:hover { color:var(--primary); background:var(--primary-subtle); }
        .sidebar-link.active { color:var(--primary); background:var(--primary-subtle); border-left-color:var(--primary); font-weight:600; }
        .sidebar-link i { font-size:15px; width:20px; text-align:center; flex-shrink:0; }
        .sidebar-badge {
            font-size:10px; font-weight:700; padding:2px 7px; border-radius:10px;
            background:rgba(16,185,129,0.12); color:var(--accent-green); margin-left:auto;
        }
        .guide-hero {
            text-align:center; padding:48px 0 40px; border-bottom:1px solid var(--surface-border); margin-bottom:40px;
        }
        .guide-hero-badge {
            display:inline-flex; align-items:center; gap:8px; background:var(--primary-subtle);
            border:1px solid rgba(37,99,235,0.2); border-radius:100px; padding:6px 16px;
            font-size:12px; font-weight:600; color:var(--primary); margin-bottom:16px;
        }
        .guide-hero-badge i { font-size:14px; }
        .guide-hero h1 {
            font-size:36px; font-weight:700; color:var(--text-primary); margin-bottom:12px; letter-spacing:-0.02em;
        }
        .guide-hero p { font-size:16px; color:var(--text-secondary); max-width:560px; margin:0 auto; }

        /* Guide Sections */
        .guide-section { margin-bottom:48px; scroll-margin-top:80px; }
        .guide-section-header {
            display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:12px;
            border-bottom:2px solid var(--primary-subtle);
        }
        .guide-section-icon {
            width:40px; height:40px; border-radius:var(--radius-md); background:var(--primary-subtle);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .guide-section-icon i { font-size:18px; color:var(--primary); }
        .guide-section-header h2 { font-size:22px; font-weight:700; color:var(--text-primary); }
        .guide-card {
            background:var(--surface); border:1px solid var(--surface-border); border-radius:var(--radius-lg);
            padding:24px; margin-bottom:16px; transition:all 0.2s ease;
        }
        .guide-card:hover { box-shadow:var(--shadow-md); border-color:rgba(37,99,235,0.15); }
        .guide-card h3 {
            font-size:16px; font-weight:600; color:var(--text-primary); margin-bottom:12px;
            display:flex; align-items:center; gap:8px;
        }
        .guide-card h3 i { color:var(--primary); font-size:16px; }
        .guide-card p { font-size:14px; color:var(--text-secondary); line-height:1.7; margin-bottom:12px; }
        .guide-card p:last-child { margin-bottom:0; }
        .guide-steps { list-style:none; padding:0; margin:12px 0; }
        .guide-steps li {
            display:flex; align-items:flex-start; gap:12px; padding:10px 0;
            font-size:14px; color:var(--text-secondary); line-height:1.6;
        }
        .guide-steps li .step-num {
            width:24px; height:24px; border-radius:50%; background:var(--primary-subtle);
            color:var(--primary); font-size:12px; font-weight:700;
            display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;
        }
        .guide-tip {
            background:linear-gradient(135deg,rgba(16,185,129,0.06),rgba(6,182,212,0.04));
            border:1px solid rgba(16,185,129,0.15); border-radius:var(--radius-md);
            padding:14px 18px; margin:12px 0; font-size:13px; color:var(--text-secondary);
            display:flex; align-items:flex-start; gap:10px;
        }
        .guide-tip i { color:var(--accent-green); font-size:16px; margin-top:1px; flex-shrink:0; }
        .guide-tip strong { color:var(--text-primary); }
        .guide-code {
            font-family:'JetBrains Mono',monospace; font-size:13px; background:var(--gray-100);
            border:1px solid var(--surface-border); border-radius:var(--radius); padding:12px 16px;
            margin:12px 0; overflow-x:auto; color:var(--text-primary); line-height:1.5;
        }
        .theme-dark .guide-code { background:var(--surface-elevated); }

        /* Search Results Highlight */
        .search-highlight { background:rgba(245,158,11,0.2); border-radius:2px; padding:0 2px; }
        .guide-section.search-hidden { display:none; }
        .guide-card.search-hidden { display:none; }
        .no-results { text-align:center; padding:60px 20px; color:var(--text-tertiary); }
        .no-results i { font-size:48px; margin-bottom:16px; display:block; opacity:0.4; }

        /* Back to top */
        .back-to-top {
            position:fixed; bottom:30px; right:30px; width:44px; height:44px; border-radius:50%;
            background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white; border:none;
            box-shadow:0 8px 24px rgba(37,99,235,0.35); cursor:pointer; display:none;
            align-items:center; justify-content:center; z-index:1000; transition:all 0.3s ease; font-size:20px;
        }
        .back-to-top:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(37,99,235,0.5); }
        .back-to-top.show { display:flex; animation:fadeIn 0.3s ease; }

        @keyframes fadeIn { from{opacity:0;transform:scale(0.8)} to{opacity:1;transform:scale(1)} }

        /* Mobile Sidebar */
        .sidebar-overlay {
            display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:200;
            backdrop-filter:blur(4px);
        }
        .sidebar-overlay.active { display:block; }

        @media(max-width:1024px) {
            .guide-sidebar {
                position:fixed; top:64px; left:0; z-index:201;
                transform:translateX(-100%); height:auto; bottom:0;
                box-shadow:var(--shadow-lg);
            }
            .guide-sidebar.open { transform:translateX(0); }
            .mobile-menu-btn { display:block; }
            .guide-layout { padding-left:0; }
            .guide-main { padding:24px 20px 60px; }
            .guide-hero h1 { font-size:28px; }
            .search-kbd { display:none; }
        }
        @media(max-width:768px) {
            .topbar-link { display:none; }
            .topbar-brand-text { display:none; }
        }
        @media(max-width:640px) {
            .guide-hero h1 { font-size:24px; }
            .guide-hero p { font-size:14px; }
        }

        /* Scrollbar */
        .guide-sidebar::-webkit-scrollbar { width:4px; }
        .guide-sidebar::-webkit-scrollbar-track { background:transparent; }
        .guide-sidebar::-webkit-scrollbar-thumb { background:var(--gray-300); border-radius:4px; }
        .theme-dark .guide-sidebar::-webkit-scrollbar-thumb { background:var(--gray-600); }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <header class="guide-topbar">
        <div class="topbar-inner">
            <a href="{{ route('home') }}" class="topbar-brand">
                <div class="topbar-logo">
                    <svg viewBox="0 0 40 40" fill="none"><path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/><path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/></svg>
                </div>
                <span class="topbar-brand-text">CuniApp <span>{{ __('Guide') }}</span></span>
            </a>
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" id="guideSearch" placeholder="{{ __('Rechercher dans la documentation...') }}" autocomplete="off">
                <span class="search-kbd">Ctrl+K</span>
            </div>
            <div class="topbar-actions">
                <div class="lang-switcher" style="display:flex;align-items:center;gap:0;border:1px solid var(--surface-border);border-radius:var(--radius);overflow:hidden;">
                    <a href="{{ route('lang.switch', 'fr') }}" class="lang-btn {{ app()->getLocale() === 'fr' ? 'active' : '' }}" style="padding:6px 10px;font-size:12px;font-weight:600;text-decoration:none;color:{{ app()->getLocale() === 'fr' ? 'white' : 'var(--text-secondary)' }};background:{{ app()->getLocale() === 'fr' ? 'var(--primary)' : 'transparent' }};transition:all 0.2s;">FR</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" style="padding:6px 10px;font-size:12px;font-weight:600;text-decoration:none;color:{{ app()->getLocale() === 'en' ? 'white' : 'var(--text-secondary)' }};background:{{ app()->getLocale() === 'en' ? 'var(--primary)' : 'transparent' }};transition:all 0.2s;">EN</a>
                </div>
                <a href="{{ route('home') }}" class="topbar-link"><i class="bi bi-arrow-left"></i> {{ __('Accueil') }}</a>
                <a href="{{ route('connect') }}" class="topbar-link" style="background:var(--primary);color:white;border-radius:var(--radius);"><i class="bi bi-box-arrow-in-right"></i> {{ __('Connexion') }}</a>
                <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="guide-layout">
        <!-- Sidebar -->
        <aside class="guide-sidebar" id="guideSidebar">
                <div class="sidebar-section">
                    <div class="sidebar-section-title"><i class="bi bi-rocket-takeoff"></i> {{ __('Pour commencer') }}</div>
                <a href="#introduction" class="sidebar-link active" data-section="introduction"><i class="bi bi-info-circle"></i> {{ __('Introduction') }}</a>
                <a href="#getting-started" class="sidebar-link" data-section="getting-started"><i class="bi bi-play-circle"></i> {{ __('Premiers pas') }}</a>
                <a href="#account-setup" class="sidebar-link" data-section="account-setup"><i class="bi bi-person-gear"></i> {{ __('Configuration du compte') }}</a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title"><i class="bi bi-speedometer2"></i> {{ __('Tableau de bord') }}</div>
                <a href="#dashboard" class="sidebar-link" data-section="dashboard"><i class="bi bi-speedometer2"></i> {{ __('Vue d\'ensemble') }}</a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title"><i class="bi bi-egg"></i> {{ __('Gestion du cheptel') }}</div>
                <a href="#males" class="sidebar-link" data-section="males"><i class="bi bi-arrow-up-right-square"></i> {{ __('Mâles') }}</a>
                <a href="#femelles" class="sidebar-link" data-section="femelles"><i class="bi bi-arrow-down-right-square"></i> {{ __('Femelles') }}</a>
                <a href="#all-rabbits" class="sidebar-link" data-section="all-rabbits"><i class="bi bi-collection"></i> {{ __('Tous les Lapins') }}</a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title"><i class="bi bi-heart"></i> {{ __('Reproduction') }}</div>
                <a href="#saillies" class="sidebar-link" data-section="saillies"><i class="bi bi-heart-fill"></i> {{ __('Saillies') }}</a>
                <a href="#naissances" class="sidebar-link" data-section="naissances"><i class="bi bi-egg-fill"></i> {{ __('Naissances') }}</a>
                <a href="#mises-bas" class="sidebar-link" data-section="mises-bas"><i class="bi bi-clipboard2-pulse"></i> {{ __('Mises Bas') }}</a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title"><i class="bi bi-cart"></i> {{ __('Commercial') }}</div>
                <a href="#sales" class="sidebar-link" data-section="sales"><i class="bi bi-cart-check"></i> {{ __('Ventes') }}</a>
                <a href="#invoices" class="sidebar-link" data-section="invoices"><i class="bi bi-receipt"></i> {{ __('Factures') }}</a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title"><i class="bi bi-gear"></i> {{ __('Paramètres & Compte') }}</div>
                <a href="#profile" class="sidebar-link" data-section="profile"><i class="bi bi-person"></i> {{ __('Profil') }}</a>
                <a href="#settings" class="sidebar-link" data-section="settings"><i class="bi bi-sliders"></i> {{ __('Paramètres') }}</a>
                <a href="#notifications" class="sidebar-link" data-section="notifications"><i class="bi bi-bell"></i> {{ __('Notifications') }}</a>
                <a href="#activities" class="sidebar-link" data-section="activities"><i class="bi bi-clock-history"></i> {{ __('Activités') }}</a>
                <a href="#subscription" class="sidebar-link" data-section="subscription"><i class="bi bi-credit-card"></i> {{ __('Abonnement') }}</a>
                <a href="#firm" class="sidebar-link" data-section="firm"><i class="bi bi-building"></i> {{ __('Entreprise') }}</a>
            </div>
        </aside>

        <!-- Body (main content + footer) -->
        <div class="guide-body">
            <!-- Main Content -->
            <main class="guide-main" id="guideContent">
            <!-- Hero -->
            <div class="guide-hero">
                <div class="guide-hero-badge"><i class="bi bi-book-half"></i> {{ __('Documentation Officielle') }}</div>
                <h1>{{ __('Guide d\'utilisation CuniApp') }}</h1>
                <p>{{ __('Tout ce que vous devez savoir pour gérer efficacement votre élevage de lapins avec CuniApp.') }}</p>
            </div>

            <!-- Introduction -->
            <section class="guide-section" id="introduction" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-info-circle"></i></div>
                    <h2>{{ __('Introduction') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-book"></i> {{ __('Qu\'est-ce que CuniApp ?') }}</h3>
                    <p>{{ __('CuniApp est une plateforme SaaS de gestion professionnelle d\'élevages cunicoles (lapins). Elle vous permet de suivre vos reproductions, gérer votre cheptel, émettre des factures et monitorer la performance de votre élevage depuis un tableau de bord unique.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-lightning"></i> {{ __('Fonctionnalités principales') }}</h3>
                    <ul class="guide-steps">
                        <li><span class="step-num">1</span> <div><strong>{{ __('Gestion du cheptel') }}</strong> — {{ __('Enregistrez et suivez tous vos lapins mâles et femelles avec leurs caractéristiques.') }}</div></li>
                        <li><span class="step-num">2</span> <div><strong>{{ __('Suivi des reproductions') }}</strong> {{ __('— Enregistrez les saillies, palpations et suivez les gestations en temps réel.') }}</div></li>
                        <li><span class="step-num">3</span> <div><strong>{{ __('Gestion des naissances') }}</strong> — {{ __('Enregistrez les mises bas, suivez la mortalité et monitorer la croissance.') }}</div></li>
                        <li><span class="step-num">4</span> <div><strong>{{ __('Ventes & Facturation') }}</strong> — {{ __('Gérez vos ventes, émettez des factures PDF et suivez les paiements.') }}</div></li>
                        <li><span class="step-num">5</span> <div><strong>{{ __('Tableau de bord') }}</strong> — {{ __('Visualisez les statistiques et indicateurs clés de votre élevage.') }}</div></li>
                    </ul>
                </div>
            </section>

            <!-- Dashboard -->
            <section class="guide-section" id="dashboard" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-speedometer2"></i></div>
                    <h2>{{ __('Tableau de bord') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-graph-up"></i> {{ __('Métriques et indicateurs') }}</h3>
                    <p>{{ __('Le tableau de bord affiche des cartes de métriques en haut de page avec les compteurs de votre cheptel et les tendances :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Total des lapins avec tendance (hausse/baisse).') }}</li>
                        <li><span class="step-num">•</span> {{ __('Nombre de mâles et femelles.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Nombre de saillies et portées.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Alertes en attente.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-lightning-charge"></i> {{ __('Actions rapides') }}</h3>
                    <p>{{ __('La section "Actions Rapides" vous donne un accès direct aux opérations les plus courantes :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Enregistrer une saillie.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Déclarer une naissance.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Enregistrer une vente.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Ajouter un lapin.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-calendar3"></i> {{ __('Calendrier') }}</h3>
                    <p>{{ __('Le calendrier du tableau de bord affiche les événements importants de votre élevage :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Saillies') }}</strong> — {{ __('Dates de saillies enregistrées.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Naissances') }}</strong> — {{ __('Dates de mises bas prévues ou passées.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Détermination du sexe') }}</strong> — {{ __('Rappel J+10 après naissance pour déterminer le sexe des lapereaux.') }}</li>
                    </ul>
                    <div class="guide-tip">
                        <i class="bi bi-info-circle"></i>
                        <div>{{ __('Naviguez entre les mois avec les flèches pour voir les événements à venir ou passés.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-bar-chart-line"></i> {{ __('Graphiques et performances') }}</h3>
                    <p>{{ __('Le tableau de bord inclut des graphiques pour visualiser :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Ventes annuelles') }}</strong> — {{ __('Évolution du chiffre d\'affaires sur l\'année en cours.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Saillies & Naissances') }}</strong> — {{ __('Comparaison du nombre de saillies et naissances.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Taux de survie') }}</strong> — {{ __('Pourcentage de lapereaux survivants par portée.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-bell"></i> {{ __('Widgets latéraux') }}</h3>
                    <p>{{ __('La barre latérale du tableau de bord contient :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Derniers lapins') }}</strong> — {{ __('Les mâles et femelles récemment ajoutés.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Dernières ventes') }}</strong> — {{ __('Les ventes les plus récentes avec montant.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Activité récente') }}</strong> — {{ __('Timeline des dernières actions effectuées.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Notifications') }}</strong> — {{ __('Alertes et notifications non lues.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-building"></i> {{ __('Vue entreprise (administrateurs)') }}</h3>
                    <p>{{ __('Si vous êtes administrateur de ferme, le tableau de bord affiche également :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Revenus totaux de l\'entreprise.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Statut de l\'abonnement actuel.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Bouton d\'accès rapide à la gestion de l\'entreprise.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Getting Started -->
            <section class="guide-section" id="getting-started" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-play-circle"></i></div>
                    <h2>{{ __('Premiers pas') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-person-plus"></i> {{ __('Créer votre compte') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Rendez-vous sur la page d\'accueil et cliquez sur "Commencer".') }}</li>
                        <li><span class="step-num">2</span> {{ __('Remplissez le formulaire d\'inscription avec votre nom, email et mot de passe.') }}</li>
                        <li><span class="step-num">3</span> {{ __('Acceptez les conditions d\'utilisation et cliquez sur "Créer mon compte".') }}</li>
                        <li><span class="step-num">4</span> {{ __('Un code de vérification vous sera envoyé par email. Entrez-le dans le formulaire pour activer votre compte.') }}</li>
                        <li><span class="step-num">5</span> {{ __('Configurez votre ferme (nom, description) lors de la première connexion.') }}</li>
                    </ol>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Astuce') }}:</strong> {{ __('Un essai gratuit de 14 jours est offert à l\'inscription. Aucune carte bancaire n\'est requise.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-key"></i> {{ __('Vérification par email') }}</h3>
                    <p>{{ __('Après l\'inscription, un email contenant un code de vérification vous est envoyé. Ce code est valide pendant un temps limité. Si vous ne l\'avez pas reçu :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Vérifiez votre dossier spam / courriers indésirables.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Cliquez sur "Renvoyer le code" pour en recevoir un nouveau.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Vous pouvez aussi demander un nouveau lien de vérification depuis la page de connexion.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-google"></i> {{ __('Connexion avec Google') }}</h3>
                    <p>{{ __('Vous pouvez également vous connecter ou vous inscrire via votre compte Google en cliquant sur le bouton "Continuer avec Google" sur la page de connexion.') }}</p>
                    <p>{{ __('Si c\'est votre première connexion via Google, vous serez redirigé vers un formulaire pour compléter votre inscription :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Choisissez un mot de passe pour votre compte CuniApp.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Acceptez les conditions d\'utilisation.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Configurez votre ferme lors de la première connexion.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-key"></i> {{ __('Changement de mot de passe forcé') }}</h3>
                    <p>{{ __('Si un administrateur vous a attribué un mot de passe temporaire, vous serez obligé de le changer lors de votre première connexion. Le nouveau mot de passe doit contenir au moins 8 caractères.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-key"></i> {{ __('Mot de passe oublié') }}</h3>
                    <p>{{ __('Si vous avez oublié votre mot de passe :') }}</p>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Cliquez sur "Mot de passe oublié ?" sur la page de connexion.') }}</li>
                        <li><span class="step-num">2</span> {{ __('Entrez votre adresse email.') }}</li>
                        <li><span class="step-num">3</span> {{ __('Vous recevrez un lien de réinitialisation par email.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Cliquez sur le lien et créez un nouveau mot de passe.') }}</li>
                    </ol>
                </div>
            </section>

            <!-- Account Setup -->
            <section class="guide-section" id="account-setup" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-person-gear"></i></div>
                    <h2>{{ __('Configuration du compte') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-building"></i> {{ __('Configuration de la ferme') }}</h3>
                    <p>{{ __('Lors de votre première connexion, vous serez redirigé vers la page de configuration de votre ferme. Renseignez :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">1</span> <div><strong>{{ __('Nom de la ferme') }}</strong> — {{ __('Le nom de votre exploitation.') }}</div></li>
                        <li><span class="step-num">2</span> <div><strong>{{ __('Description') }}</strong> — {{ __('Une brève description de votre élevage (optionnel).') }}</div></li>
                    </ul>
                    <div class="guide-tip">
                        <i class="bi bi-info-circle"></i>
                        <div>{{ __('Vous pouvez modifier ces informations plus tard depuis la page "Mon Entreprise" dans les paramètres.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-upc-scan"></i> {{ __('Codes uniques') }}</h3>
                    <p>{{ __('Chaque lapin, mâle ou femelle doit avoir un code unique. Lors de la création, le système vérifie automatiquement que le code n\'est pas déjà utilisé. Si le code existe déjà, un message d\'erreur s\'affiche et vous devez en choisir un autre.') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Astuce') }}:</strong> {{ __('Utilisez un format de code cohérent (ex: ML-001, FL-001) pour faciliter la recherche et l\'identification.') }}</div>
                    </div>
                </div>
            </section>

            <!-- Males -->
            <section class="guide-section" id="males" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-arrow-up-right-square"></i></div>
                    <h2>{{ __('Gestion des Mâles') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-plus-circle"></i> {{ __('Ajouter un mâle') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à la section "Mâles" depuis le menu.') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Nouveau mâle".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Remplissez les informations : code unique, nom, race, date de naissance, couleur, état de santé.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Cliquez sur "Enregistrer" pour ajouter le mâle à votre cheptel.') }}</li>
                    </ol>
                    <div class="guide-tip">
                        <i class="bi bi-info-circle"></i>
                        <div>{{ __('Vous pouvez également ajouter un lapin directement depuis la section "Tous les Lapins" via le bouton "Nouveau lapin".') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-list-ul"></i> {{ __('Gérer vos mâles') }}</h3>
                    <p>{{ __('Depuis la liste des mâles, vous pouvez :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Consulter les détails d\'un mâle en cliquant sur son nom.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Modifier les informations d\'un mâle existant.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Basculer l\'état') }}</strong> — {{ __('Passez un mâle de "Reproducteur" à "Retiré" (ou inversement) selon son statut dans votre élevage.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Supprimer un mâle (action irréversible).') }}</li>
                    </ul>
                    <p>{{ __('Filtres disponibles : recherche par nom/code, filtre par état (Actif, Inactif, Malade, Vendu) et par origine (Interne, Achat).') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-info-circle"></i>
                        <div>{{ __('Les mâles retirés ne seront plus proposés dans les listes déroulantes lors de l\'enregistrement de nouvelles saillies.') }}</div>
                    </div>
                </div>
            </section>

            <!-- Females -->
            <section class="guide-section" id="femelles" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-arrow-down-right-square"></i></div>
                    <h2>{{ __('Gestion des Femelles') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-plus-circle"></i> {{ __('Ajouter une femelle') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à la section "Femelles" depuis le menu.') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Nouvelle femelle".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Remplissez les informations : code unique, nom, race, date de naissance, couleur, lignée.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Cliquez sur "Enregistrer" pour ajouter la femelle à votre cheptel.') }}</li>
                    </ol>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-clipboard2-pulse"></i> {{ __('Suivi reproductif des femelles') }}</h3>
                    <p>{{ __('Chaque femelle dispose d\'un historique reproductif complet montrant ses saillies, gestations, naissances et performances. Accédez-y en cliquant sur le nom d\'une femelle.') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Fiche détaillée') }}</strong> — {{ __('Code, nom, race, date de naissance, couleur, lignée, état.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Historique reproductif') }}</strong> — {{ __('Toutes les saillies, palpations, naissances avec dates et résultats.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Basculer l\'état') }}</strong> — {{ __('Même fonctionnalité que les mâles pour gérer le statut reproducteur.') }}</li>
                    </ul>
                    <p>{{ __('Filtres disponibles : recherche par nom/code, filtre par état (Actif, Inactif, Gestante, Allaitante, Vide) et par origine.') }}</p>
                </div>
            </section>

            <!-- All Rabbits -->
            <section class="guide-section" id="all-rabbits" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-collection"></i></div>
                    <h2>{{ __('Tous les Lapins') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-search"></i> {{ __('Vue d\'ensemble du cheptel') }}</h3>
                    <p>{{ __('La section "Tous les Lapins" vous donne une vue complète de l\'ensemble de votre cheptel, mâles et femelles confondus.') }}</p>
                    <p>{{ __('Barre de recherche et filtres avancés :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Recherche') }}</strong> — {{ __('Recherchez par nom, code ou race.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Type') }}</strong> — {{ __('Filtrez par mâles ou femelles.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('État') }}</strong> — {{ __('Filtrez par état : Actif, Inactif, Malade, Vendu, Gestante, Allaitante, Vide.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Origine') }}</strong> — {{ __('Filtrez par origine : Interne (né dans l\'élevage) ou Achat.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-info-circle"></i> {{ __('Détails d\'un lapereau') }}</h3>
                    <p>{{ __('En cliquant sur un lapereau issu d\'une naissance, vous accédez à sa fiche détaillée contenant :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Informations générales : code, nom, sexe, poids à la naissance.') }}</li>
                        <li><span class="step-num">•</span> {{ __('État de santé et état actuel (vivant, vendu, décédé).') }}</li>
                        <li><span class="step-num">•</span> {{ __('Historique de vaccination complet avec rappels.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Traçabilité : mère (femelle), père (mâle), date de naissance.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Observations et notes personnelles.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Saillies -->
            <section class="guide-section" id="saillies" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-heart-fill"></i></div>
                    <h2>{{ __('Gestion des Saillies') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-plus-circle"></i> {{ __('Enregistrer une saillie') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à la section "Saillies".') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Nouvelle saillie".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Sélectionnez le mâle et la femelle concernés.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Indiquez la date de la saillie.') }}</li>
                        <li><span class="step-num">5</span> {{ __('Enregistrez. La saillie apparaîtra dans l\'historique de la femelle.') }}</li>
                    </ol>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-hand-index"></i> {{ __('Palpation') }}</h3>
                    <p>{{ __('Après environ 14 jours, vous pouvez enregistrer le résultat de la palpation (vérification de la gestation) directement depuis la liste des saillies ou depuis la page de détail de la saillie.') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Ouvrez le détail de la saillie concernée.') }}</li>
                        <li><span class="step-num">2</span> {{ __('Indiquez la date de palpation et le résultat (Positif = Gestante, ou Négatif).') }}</li>
                        <li><span class="step-num">3</span> {{ __('Si positif, la date de mise bas théorique sera automatiquement calculée (~31 jours après la saillie).') }}</li>
                    </ul>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Conseil') }}:</strong> {{ __('Effectuez la palpation entre le 12ème et le 15ème jour après la saillie pour un résultat fiable.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-clock-history"></i> {{ __('Timeline de reproduction') }}</h3>
                    <p>{{ __('Chaque saillie dispose d\'une timeline visuelle montrant les étapes de la reproduction :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Saillie réalisée') }}</strong> — {{ __('Date et croisement (femelle × mâle).') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Palpation') }}</strong> — {{ __('Résultat positif ou négatif avec date.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Mise bas prévue') }}</strong> — {{ __('Date théorique calculée automatiquement.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Naissance') }}</strong> — {{ __('Lien vers la mise bas enregistrée si elle a eu lieu.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Naissances -->
            <section class="guide-section" id="naissances" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-egg-fill"></i></div>
                    <h2>{{ __('Gestion des Naissances') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-plus-circle"></i> {{ __('Enregistrer une naissance') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à la section "Naissances".') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Nouvelle naissance".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Sélectionnez la femelle et la date de la mise bas.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Indiquez le nombre de lapereaux nés et le nombre de morts-nés.') }}</li>
                        <li><span class="step-num">5</span> {{ __('Les lapereaux seront automatiquement ajoutés à votre cheptel.') }}</li>
                    </ol>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-pencil-square"></i> {{ __('Modifier une naissance') }}</h3>
                    <p>{{ __('Vous pouvez modifier une naissance existante pour mettre à jour les informations des lapereaux : poids à la naissance, état de santé, sexe, et vaccinations. Chaque lapereau peut recevoir plusieurs vaccins avec dates, doses et rappels.') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Astuce') }}:</strong> {{ __('Le sexe des lapereaux peut être vérifié et défini lors de la modification d\'une naissance, généralement après quelques semaines.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-shield-plus"></i> {{ __('Vaccination des lapereaux') }}</h3>
                    <p>{{ __('Lors de l\'enregistrement ou de la modification d\'une naissance, vous pouvez ajouter des vaccins pour chaque lapereau :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Sélectionnez le type de vaccin (Myxomatose, VHD, Pasteurellose, etc.).') }}</li>
                        <li><span class="step-num">•</span> {{ __('Indiquez la date d\'administration et le numéro de dose.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Planifiez une date de rappel pour la prochaine dose.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Ajoutez des notes si nécessaire.') }}</li>
                    </ul>
                    <div class="guide-tip">
                        <i class="bi bi-info-circle"></i>
                        <div>{{ __('Les lapereaux sans vaccin sont affichés avec un badge "Non vacciné". Un compteur de vaccination s\'affiche dans le détail de chaque portée.') }}</div>
                    </div>
                </div>
            </section>

            <!-- Mises Bas -->
            <section class="guide-section" id="mises-bas" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                    <h2>{{ __('Mises Bas') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-list-check"></i> {{ __('Suivi des mises bas') }}</h3>
                    <p>{{ __('La section "Mises Bas" affiche toutes les naissances enregistrées avec leurs détails. Chaque mise bas contient :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Informations principales') }}</strong> — {{ __('Femelle, date, nb vivants, morts-nés, total.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Date de sevrage') }}</strong> — {{ __('Date prévue ou passée du sevrage, avec compte à rebours.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Poids moyen au sevrage') }}</strong> — {{ __('Poids moyen des lapereaux au moment du sevrage.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-bar-chart"></i> {{ __('Statistiques de la portée') }}</h3>
                    <p>{{ __('Chaque détail de mise bas affiche des statistiques en temps réel :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Vivants') }}</strong> — {{ __('Nombre de lapereaux vivants.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Morts-nés') }}</strong> — {{ __('Nombre de morts-nés.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Taux de survie') }}</strong> — {{ __('Pourcentage calculé automatiquement.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Jours d\'âge') }}</strong> — {{ __('Âge de la portée en jours depuis la naissance.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Sales -->
            <section class="guide-section" id="sales" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-cart-check"></i></div>
                    <h2>{{ __('Gestion des Ventes') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-plus-circle"></i> {{ __('Enregistrer une vente') }}</h3>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à la section "Ventes".') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Nouvelle vente".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Sélectionnez le(s) lapin(s) à vendre depuis la liste déroulante.') }}</li>
                        <li><span class="step-num">4</span> {{ __('Renseignez le prix, le mode de paiement et les informations du client.') }}</li>
                        <li><span class="step-num">5</span> {{ __('Enregistrez la vente. Une facture sera générée automatiquement.') }}</li>
                    </ol>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-cash-stack"></i> {{ __('Suivi des paiements') }}</h3>
                    <p>{{ __('Vous pouvez gérer les paiements de différentes manières :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Marquer une vente comme payée intégralement.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Enregistrer des paiements partiels avec le montant versé.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Changer le statut de paiement (en attente, partiellement payé, payé).') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-list-check"></i> {{ __('Opérations groupées') }}</h3>
                    <p>{{ __('La section ventes propose des actions groupées pour gagner du temps :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Suppression multiple') }}</strong> — {{ __('Sélectionnez plusieurs ventes et supprimez-les en une seule action.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Exportation') }}</strong> — {{ __('Exportez la liste de vos ventes au format CSV pour analyse externe.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Invoices -->
            <section class="guide-section" id="invoices" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-receipt"></i></div>
                    <h2>{{ __('Factures') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-file-earmark-pdf"></i> {{ __('Détail d\'une facture') }}</h3>
                    <p>{{ __('La page de détail d\'une facture affiche :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('En-tête') }}</strong> — {{ __('Numéro de facture, date d\'émission, statut (payée/en attente).') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Informations client') }}</strong> — {{ __('Nom, email, nom de la ferme.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Détail des prestations') }}</strong> — {{ __('Lignes de facture avec descriptions et montants.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Récapitulatif') }}</strong> — {{ __('Sous-total, taxes le cas échéant, total.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-gear"></i> {{ __('Actions sur une facture') }}</h3>
                    <p>{{ __('Depuis le détail d\'une facture, vous pouvez :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Télécharger le PDF') }}</strong> — {{ __('Exportez la facture au format PDF pour impression ou archivage.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Régénérer le PDF') }}</strong> {{ __('— Recréez le fichier PDF si des modifications ont été apportées.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Envoyer par email') }}</strong> — {{ __('Envoyez la facture directement par email au client.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Profile -->
            <section class="guide-section" id="profile" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-person"></i></div>
                    <h2>{{ __('Profil') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-pencil-square"></i> {{ __('Modifier votre profil') }}</h3>
                    <p>{{ __('Accédez à votre profil depuis le menu utilisateur (coin supérieur droit) puis "Profil". Vous pouvez modifier :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Votre nom et photo de profil.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Votre adresse email.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Votre mot de passe actuel et nouveau mot de passe.') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-trash"></i> {{ __('Supprimer mon compte') }}</h3>
                    <p>{{ __('Vous pouvez supprimer votre compte depuis la page profil. Cette action est irréversible : toutes vos données seront définitivement effacées après un délai de 30 jours.') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div><strong>{{ __('Attention') }}:</strong> {{ __('Avant de supprimer votre compte, assurez-vous d\'avoir exporté vos données si nécessaire.') }}</div>
                    </div>
                </div>
            </section>

            <!-- Settings -->
            <section class="guide-section" id="settings" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-sliders"></i></div>
                    <h2>{{ __('Paramètres') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-palette"></i> {{ __('Thème et préférences') }}</h3>
                    <p>{{ __('Depuis les paramètres, vous pouvez personnaliser l\'apparence de l\'application :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Thème clair / sombre / système.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Langue de l\'interface (Français / Anglais).') }}</li>
                    </ul>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-download"></i> {{ __('Exportation des données') }}</h3>
                    <p>{{ __('Vous pouvez exporter l\'ensemble de vos données au format JSON depuis les paramètres. Cette fonctionnalité est utile pour les sauvegardes ou la migration.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-trash3"></i> {{ __('Vider le cache') }}</h3>
                    <p>{{ __('Si l\'application semble lente ou affiche des données obsolètes, vous pouvez vider le cache depuis les paramètres. Cela force le rechargement des données depuis le serveur.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-bell"></i> {{ __('Préférences de notification') }}</h3>
                    <p>{{ __('L\'onglet "Notifications" des paramètres vous permet de choisir comment recevoir vos alertes :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Notifications par email') }}</strong> — {{ __('Recevez des alertes par email (palpations, abonnements, etc.).') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Notifications du tableau de bord') }}</strong> — {{ __('Affichage des alertes visuelles dans l\'application.') }}</li>
                    </ul>
                    <p>{{ __('Vous pouvez activer ou désactiver chaque type de notification indépendamment.') }}</p>
                </div>
            </section>

            <!-- Notifications -->
            <section class="guide-section" id="notifications" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-bell"></i></div>
                    <h2>{{ __('Notifications') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-bell-ring"></i> {{ __('Centre de notifications') }}</h3>
                    <p>{{ __('Le centre de notifications vous alerte des événements importants : rappels de palpation, abonnement expiration, activités récentes.') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Marquez une notification comme lue en cliquant dessus.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Supprimez une notification en cliquant sur l\'icône de suppression.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Utilisez "Marquer toutes comme lues" pour marquer toutes les notifications d\'un seul clic.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Filtrez par notifications non lues depuis le lien dans le widget du tableau de bord.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Activities -->
            <section class="guide-section" id="activities" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-clock-history"></i></div>
                    <h2>{{ __('Activités') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-journal-text"></i> {{ __('Journal d\'activités') }}</h3>
                    <p>{{ __('La section "Activités" affiche un historique chronologique de toutes les actions effectuées dans l\'application : ajouts, modifications, suppressions de lapins, saillies, naissances, ventes, etc.') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Consultez les activités récentes depuis le tableau de bord ou la section dédiée.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Filtrez par type d\'activité pour retrouver une action spécifique.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Supprimez une entrée du journal si nécessaire.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Subscription -->
            <section class="guide-section" id="subscription" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-credit-card"></i></div>
                    <h2>{{ __('Abonnement') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-star"></i> {{ __('Plans et tarifs') }}</h3>
                    <p>{{ __('CuniApp propose plusieurs plans d\'abonnement adaptés à la taille de votre élevage. Consultez la page "Tarifs" depuis l\'accueil pour comparer les offres.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-arrow-repeat"></i> {{ __('Renouvellement et paiement') }}</h3>
                    <p>{{ __('Les abonnements payants sont renouvelés automatiquement. Les paiements sont sécurisés via FedaPay et acceptent les moyens de paiement suivants :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('MTN MoMo') }}</strong> — {{ __('Mobile Money MTN.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Celtis Cash') }}</strong> — {{ __('Mobile Money Celtis.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Moov Pay') }}</strong> — {{ __('Mobile Money Moov.') }}</li>
                    </ul>
                    <p>{{ __('Vous pouvez gérer votre abonnement depuis la section "Mon Abonnement".') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Astuce') }}:</strong> {{ __('Un badge orange s\'affiche sur le lien "Abonnement" si votre abonnement est expiré ou inactif.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-clock-history"></i> {{ __('Historique des paiements') }}</h3>
                    <p>{{ __('La page "Mon Abonnement" affiche l\'historique complet de vos paiements avec le statut (complété, en attente, échoué), la date, le montant et la méthode utilisée. Vous pouvez réessayer un paiement échoué directement depuis cette page.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-arrow-repeat"></i> {{ __('Renouvellement anticipé') }}</h3>
                    <p>{{ __('Si votre abonnement est actif, vous pouvez le renouveler anticipément depuis la page "Mon Abonnement". Cliquez sur "Renouveler", choisissez votre moyen de paiement et confirmez. Votre abonnement sera prolongé de la durée du plan choisi.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-x-circle"></i> {{ __('Annulation d\'abonnement') }}</h3>
                    <p>{{ __('Vous pouvez annuler votre abonnement à tout moment depuis la page "Mon Abonnement". Après annulation :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> {{ __('Votre abonnement restera actif jusqu\'à la fin de la période payée.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Le renouvellement automatique sera désactivé.') }}</li>
                        <li><span class="step-num">•</span> {{ __('Vous pourrez souscrire à un nouveau plan à tout moment.') }}</li>
                    </ul>
                    <div class="guide-tip">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div><strong>{{ __('Attention') }}:</strong> {{ __('L\'annulation ne rembourse pas la période en cours. Aucune donnée ne sera supprimée.') }}</div>
                    </div>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-exclamation-triangle"></i> {{ __('Page d\'abonnement requis') }}</h3>
                    <p>{{ __('Si vous essayez d\'accéder à une fonctionnalité nécessitant un abonnement actif (gestion du cheptel, ventes, etc.) sans abonnement, vous serez redirigé vers une page vous invitant à souscrire. Cette page s\'affiche également si votre abonnement a expiré.') }}</p>
                    <div class="guide-tip">
                        <i class="bi bi-lightbulb"></i>
                        <div><strong>{{ __('Astuce') }}:</strong> {{ __('Vous pouvez quand même consulter votre profil, vos paramètres et vos notifications même sans abonnement actif.') }}</div>
                    </div>
                </div>
            </section>

            <!-- Firm -->
            <section class="guide-section" id="firm" data-searchable>
                <div class="guide-section-header">
                    <div class="guide-section-icon"><i class="bi bi-building"></i></div>
                    <h2>{{ __('Gestion de l\'entreprise') }}</h2>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-people"></i> {{ __('Gestion des employés') }}</h3>
                    <p>{{ __('En tant qu\'administrateur de ferme, vous pouvez inviter des collaborateurs à rejoindre votre entreprise. Chaque employé disposera de son propre compte avec accès aux données de la ferme.') }}</p>
                    <ol class="guide-steps">
                        <li><span class="step-num">1</span> {{ __('Accédez à "Mon Entreprise" depuis le menu.') }}</li>
                        <li><span class="step-num">2</span> {{ __('Cliquez sur "Ajouter un employé".') }}</li>
                        <li><span class="step-num">3</span> {{ __('Renseignez le nom, l\'email et le mot de passe de l\'employé.') }}</li>
                        <li><span class="step-num">4</span> {{ __('L\'employé pourra se connecter immédiatement avec les identifiants fournis.') }}</li>
                    </ol>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-pencil"></i> {{ __('Modifier les informations de la ferme') }}</h3>
                    <p>{{ __('L\'administrateur peut modifier le nom et la description de la ferme depuis la page "Mon Entreprise". Ces informations s\'affichent dans l\'en-tête de l\'application et sur les factures.') }}</p>
                </div>
                <div class="guide-card" data-searchable>
                    <h3><i class="bi bi-shield-check"></i> {{ __('Permissions et gestion') }}</h3>
                    <p>{{ __('L\'administrateur de la ferme peut :') }}</p>
                    <ul class="guide-steps">
                        <li><span class="step-num">•</span> <strong>{{ __('Activer/Désactiver') }}</strong> — {{ __('Désactiver temporairement un compte employé sans le supprimer.') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Supprimer') }}</strong> — {{ __('Supprimer définitivement un employé (action irréversible).') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Suivi d\'activité') }}</strong> — {{ __('Consultez le graphique d\'activité de chaque employé sur différentes périodes (7j, 30j, 90j).') }}</li>
                        <li><span class="step-num">•</span> <strong>{{ __('Statut en ligne') }}</strong> — {{ __('Voyez qui est actuellement en ligne et la dernière connexion de chaque employé.') }}</li>
                    </ul>
                </div>
            </section>

        </main>

        <!-- Footer (inside body wrapper, next to sidebar) -->
        @include('components.public-footer')
        </div> <!-- end guide-body -->
    </div>

    <!-- Back to Top -->
    <button id="backToTop" class="back-to-top" title="{{ __('Retour en haut') }}"><i class="bi bi-arrow-up-short"></i></button>

    <script>
        // Search functionality
        const searchInput = document.getElementById('guideSearch');
        const sections = document.querySelectorAll('.guide-section');
        const cards = document.querySelectorAll('.guide-card');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            if (!query) {
                sections.forEach(s => s.classList.remove('search-hidden'));
                cards.forEach(c => c.classList.remove('search-hidden'));
                document.querySelectorAll('.no-results').forEach(n => n.remove());
                return;
            }
            let hasResults = false;
            sections.forEach(section => {
                const sectionText = section.textContent.toLowerCase();
                const sectionCards = section.querySelectorAll('.guide-card');
                let sectionHasMatch = false;
                sectionCards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    if (cardText.includes(query)) {
                        card.classList.remove('search-hidden');
                        sectionHasMatch = true;
                        hasResults = true;
                    } else {
                        card.classList.add('search-hidden');
                    }
                });
                if (sectionText.includes(query) || sectionHasMatch) {
                    section.classList.remove('search-hidden');
                    hasResults = true;
                } else {
                    section.classList.add('search-hidden');
                }
            });
            const existing = document.querySelector('.no-results');
            if (existing) existing.remove();
            if (!hasResults) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = '<i class="bi bi-search"></i><p style="font-size:16px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">{{ __("Aucun résultat trouvé") }}</p><p style="font-size:14px;">{{ __("Essayez avec d\'autres mots-clés") }}</p>';
                document.getElementById('guideContent').appendChild(noResults);
            }
        });

        // Ctrl+K shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // Active sidebar link on scroll
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const observerOptions = { rootMargin: '-80px 0px -60% 0px', threshold: 0 };
        const sectionObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    sidebarLinks.forEach(link => {
                        link.classList.toggle('active', link.getAttribute('data-section') === id);
                    });
                }
            });
        }, observerOptions);
        sections.forEach(section => sectionObserver.observe(section));

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('guideSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        // Close sidebar on link click (mobile)
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1024) toggleSidebar();
            });
        });

        // Back to top
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('show', window.scrollY > 300);
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Theme
        const savedTheme = localStorage.getItem('cuniapp_theme') || 'system';
        function setTheme(theme) {
            localStorage.setItem('cuniapp_theme', theme);
            applyTheme(theme);
        }
        window.setTheme = setTheme;

        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('theme-dark', isDark);
            document.querySelectorAll('.footer-theme-btn').forEach(b => {
                const isActive = b.dataset.theme === theme;
                b.style.background = isActive ? 'var(--primary)' : 'transparent';
                b.style.color = isActive ? 'white' : 'var(--text-tertiary)';
            });
        }
        applyTheme(savedTheme);
        document.querySelectorAll('.footer-theme-btn').forEach(btn => {
            btn.addEventListener('click', () => { const t = btn.dataset.theme; setTheme(t); });
        });
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            applyTheme(localStorage.getItem('cuniapp_theme') || 'system');
        });
    </script>
</body>
</html>
