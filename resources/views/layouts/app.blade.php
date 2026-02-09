<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AnyxTech Élevage')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* AnyxTech Color System - Mode Sombre (par défaut) */
            --anyx-dark: #0A1628;
            --anyx-darker: #050B14;
            --anyx-navy: #162B4D;
            --anyx-blue: #0066FF;
            --anyx-cyan: #00D9FF;
            --anyx-purple: #8B5CF6;
            --anyx-pink: #EC4899;
            --anyx-green: #10B981;
            --anyx-orange: #F59E0B;
            
            /* Surfaces */
            --surface-1: #0F1C2E;
            --surface-2: #1A2942;
            --surface-3: #253A5C;
            
            /* Text */
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
            --text-tertiary: #6B7280;
            
            /* Effects */
            --glow-blue: 0 0 20px rgba(0, 102, 255, 0.4);
            --glow-cyan: 0 0 20px rgba(0, 217, 255, 0.4);
            --shadow-1: 0 4px 6px rgba(0, 0, 0, 0.4);
            --shadow-2: 0 10px 25px rgba(0, 0, 0, 0.5);
            
            /* Mode clair - caché par défaut */
            --light-bg: #F9FAFB;
            --light-surface-1: #FFFFFF;
            --light-surface-2: #F3F4F6;
            --light-surface-3: #E5E7EB;
            --light-text-primary: #111827;
            --light-text-secondary: #4B5563;
            --light-text-tertiary: #9CA3AF;
        }

        /* Mode clair activé */
        body.light-mode {
            /* Couleurs de fond */
            --anyx-dark: var(--light-bg);
            --anyx-darker: var(--light-surface-2);
            --surface-1: var(--light-surface-1);
            --surface-2: var(--light-surface-2);
            --surface-3: var(--light-surface-3);
            
            /* Textes */
            --text-primary: var(--light-text-primary);
            --text-secondary: var(--light-text-secondary);
            --text-tertiary: var(--light-text-tertiary);
            
            /* Effets */
            --shadow-1: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-2: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Mode clair pour la navbar */
        body.light-mode .anyxtech-navbar {
            background: var(--surface-1);
            border-bottom: 2px solid rgba(0, 102, 255, 0.1);
        }

        body.light-mode .anyxtech-navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        body.light-mode .nav-link {
            color: var(--text-secondary);
        }

        body.light-mode .nav-link:hover,
        body.light-mode .nav-link.active {
            color: var(--anyx-blue);
        }

        body.light-mode .nav-link.active {
            background: rgba(0, 102, 255, 0.08);
            box-shadow: 0 0 15px rgba(0, 102, 255, 0.1);
        }

        body.light-mode .dropdown-menu-custom {
            background: var(--surface-1);
            border: 1px solid rgba(0, 102, 255, 0.1);
        }

        body.light-mode .nav-btn-secondary {
            background: var(--surface-2);
            border: 1px solid rgba(0, 102, 255, 0.2);
        }

        body.light-mode .anyxtech-footer {
            background: var(--surface-2);
            border-top: 2px solid rgba(0, 102, 255, 0.1);
        }

        body.light-mode .footer-link:hover {
            color: var(--anyx-blue);
        }

        body.light-mode .social-link {
            background: var(--surface-3);
            border: 1px solid rgba(0, 102, 255, 0.1);
        }

        body.light-mode .social-link:hover {
            background: linear-gradient(135deg, var(--anyx-cyan), var(--anyx-blue));
            color: white;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--anyx-darker);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--anyx-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--anyx-cyan), var(--anyx-blue));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--anyx-cyan);
            box-shadow: var(--glow-cyan);
        }

        /* Mode clair pour scrollbar */
        body.light-mode ::-webkit-scrollbar-track {
            background: var(--surface-2);
        }

        body.light-mode ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--anyx-blue), var(--anyx-cyan));
        }

        /* Navbar */
        .anyxtech-navbar {
            background: var(--surface-1);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid rgba(0, 217, 255, 0.1);
            box-shadow: var(--shadow-2);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .anyxtech-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--anyx-cyan) 0%, var(--anyx-blue) 50%, var(--anyx-purple) 100%);
            animation: gradientShift 8s ease infinite;
            background-size: 200% 100%;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .anyxtech-navbar.scrolled {
            background: rgba(10, 22, 40, 0.95);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }

        .nav-container {
            max-width: 1800px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
        }

        /* Logo */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-brand:hover {
            transform: translateY(-2px);
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            position: relative;
        }

        .brand-logo svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(var(--glow-cyan));
            animation: logoRotate 20s linear infinite;
        }

        @keyframes logoRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .brand-name {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--anyx-cyan) 0%, var(--anyx-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 11px;
            color: var(--text-secondary);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.1), rgba(0, 102, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .nav-link:hover::before {
            opacity: 1;
        }

        .nav-link:hover {
            color: var(--anyx-cyan);
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: var(--anyx-cyan);
            background: rgba(0, 217, 255, 0.1);
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.2);
        }

        .nav-link i {
            font-size: 18px;
        }

        /* Mode clair pour les liens */
        body.light-mode .nav-link:hover {
            color: var(--anyx-blue);
        }

        body.light-mode .nav-link.active {
            color: var(--anyx-blue);
            background: rgba(0, 102, 255, 0.08);
            box-shadow: 0 0 15px rgba(0, 102, 255, 0.1);
        }

        /* Bouton mode clair/sombre */
        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--surface-2);
            border: 1px solid rgba(0, 217, 255, 0.2);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s;
            margin-left: 8px;
        }

        .theme-toggle:hover {
            background: var(--surface-3);
            color: var(--anyx-cyan);
            border-color: var(--anyx-cyan);
            transform: rotate(15deg);
        }

        body.light-mode .theme-toggle:hover {
            color: var(--anyx-blue);
        }

        .theme-toggle .bi-sun {
            display: none;
        }

        body.light-mode .theme-toggle .bi-moon {
            display: none;
        }

        body.light-mode .theme-toggle .bi-sun {
            display: block;
        }

        /* Dropdown */
        .nav-dropdown {
            position: relative;
        }

        .dropdown-toggle::after {
            content: '▼';
            font-size: 10px;
            margin-left: 6px;
            transition: transform 0.3s;
        }

        .nav-dropdown:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 12px;
            background: var(--surface-2);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 12px;
            box-shadow: var(--shadow-2);
            min-width: 220px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-dropdown:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .dropdown-item-custom:hover {
            background: rgba(0, 217, 255, 0.1);
            color: var(--anyx-cyan);
            transform: translateX(4px);
        }

        body.light-mode .dropdown-item-custom:hover {
            color: var(--anyx-blue);
            background: rgba(0, 102, 255, 0.08);
        }

        .dropdown-item-custom i {
            font-size: 16px;
        }

        /* Action Buttons */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .nav-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .nav-btn span {
            position: relative;
            z-index: 1;
        }

        .nav-btn-secondary {
            background: var(--surface-2);
            color: var(--text-primary);
            border: 1px solid rgba(0, 217, 255, 0.3);
        }

        .nav-btn-secondary:hover {
            border-color: var(--anyx-cyan);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            color: var(--anyx-cyan);
        }

        body.light-mode .nav-btn-secondary:hover {
            border-color: var(--anyx-blue);
            color: var(--anyx-blue);
        }

        .nav-btn-primary {
            background: linear-gradient(135deg, var(--anyx-cyan) 0%, var(--anyx-blue) 100%);
            color: white;
            box-shadow: var(--glow-blue);
        }

        .nav-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 102, 255, 0.6);
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            width: 30px;
            height: 24px;
            cursor: pointer;
            position: relative;
            z-index: 10;
        }

        .mobile-toggle span {
            display: block;
            width: 100%;
            height: 3px;
            background: var(--anyx-cyan);
            border-radius: 2px;
            transition: all 0.3s;
        }

        body.light-mode .mobile-toggle span {
            background: var(--anyx-blue);
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translateY(10px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translateY(-10px);
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 32px 0;
        }

        .page-container {
            max-width: 1800px;
            margin: 0 auto;
            padding: 0 32px;
        }

        /* Footer */
        .anyxtech-footer {
            background: var(--anyx-dark);
            border-top: 2px solid rgba(0, 217, 255, 0.1);
            padding: 48px 0 24px;
            position: relative;
            overflow: hidden;
        }

        .anyxtech-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--anyx-purple) 0%, var(--anyx-blue) 50%, var(--anyx-cyan) 100%);
        }

        .footer-content {
            max-width: 1800px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 32px;
        }

        .footer-brand-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .footer-logo-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-logo-title svg {
            width: 40px;
            height: 40px;
            filter: drop-shadow(var(--glow-cyan));
        }

        .footer-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 20px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--anyx-cyan) 0%, var(--anyx-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.8;
        }

        .footer-social-links {
            display: flex;
            gap: 12px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-2);
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid rgba(0, 217, 255, 0.1);
        }

        .social-link:hover {
            background: linear-gradient(135deg, var(--anyx-cyan), var(--anyx-blue));
            color: white;
            transform: translateY(-4px);
            box-shadow: var(--glow-blue);
        }

        .footer-section h4 {
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--anyx-cyan);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        body.light-mode .footer-section h4 {
            color: var(--anyx-blue);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-link::before {
            content: '→';
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s;
        }

        .footer-link:hover {
            color: var(--anyx-cyan);
            transform: translateX(8px);
        }

        .footer-link:hover::before {
            opacity: 1;
            transform: translateX(0);
        }

        body.light-mode .footer-link:hover {
            color: var(--anyx-blue);
        }

        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        body.light-mode .footer-bottom {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .copyright {
            color: var(--text-tertiary);
            font-size: 13px;
        }

        .footer-legal {
            display: flex;
            gap: 24px;
        }

        .footer-legal a {
            color: var(--text-tertiary);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }

        .footer-legal a:hover {
            color: var(--anyx-cyan);
        }

        body.light-mode .footer-legal a:hover {
            color: var(--anyx-blue);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        @media (max-width: 768px) {
            .nav-menu,
            .nav-actions {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .nav-menu.mobile-active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--surface-1);
                border-top: 1px solid rgba(0, 217, 255, 0.1);
                padding: 20px;
                gap: 4px;
                box-shadow: var(--shadow-2);
            }

            body.light-mode .nav-menu.mobile-active {
                border-top: 1px solid rgba(0, 102, 255, 0.1);
            }

            .nav-actions.mobile-active {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-top: 12px;
            }

            .nav-btn {
                width: 100%;
                justify-content: center;
            }

            .dropdown-menu-custom {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                margin-top: 8px;
                display: none;
            }

            .nav-dropdown.active .dropdown-menu-custom {
                display: block;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .page-container,
            .nav-container,
            .footer-content {
                padding: 0 20px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="anyxtech-navbar">
        <div class="nav-container">
            <div class="navbar-content">
                <!-- Brand -->
                <a href="{{ url('/') }}" class="nav-brand">
                    <div class="brand-logo">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="url(#logoGrad)"/>
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="#0A1628"/>
                            <defs>
                                <linearGradient id="logoGrad" x1="5" y1="5" x2="35" y2="35">
                                    <stop offset="0%" stop-color="#00D9FF"/>
                                    <stop offset="100%" stop-color="#0066FF"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="brand-name">
                        <div class="brand-title">ANYXTECH</div>
                        <div class="brand-subtitle">Élevage Pro</div>
                    </div>
                </a>

                <!-- Mobile Toggle -->
                <div class="mobile-toggle" id="mobileToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <!-- Navigation Menu -->
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link active">
                            <i class="bi bi-house-door"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link dropdown-toggle">
                            <i class="bi bi-diagram-3"></i>
                            <span>Gestion</span>
                        </a>
                        <div class="dropdown-menu-custom">
                            <a href="/males" class="dropdown-item-custom">
                                <i class="bi bi-gender-male"></i>
                                <span>Mâles</span>
                            </a>
                            <a href="/femelles" class="dropdown-item-custom">
                                <i class="bi bi-gender-female"></i>
                                <span>Femelles</span>
                            </a>
                            <a href="/saillies" class="dropdown-item-custom">
                                <i class="bi bi-heart-pulse"></i>
                                <span>Saillies</span>
                            </a>
                            <a href="{{ route('naissances.index') }}" class="dropdown-item-custom">
                                <i class="bi bi-stars"></i>
                                <span>Naissances</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-clipboard-data"></i>
                            <span>Rapports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-calendar-event"></i>
                            <span>Planning</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-gear"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                </ul>

                <!-- Action Buttons -->
                <div class="nav-actions" id="navActions">
                    <button class="theme-toggle" id="themeToggle" title="Changer de thème">
                        <i class="bi bi-moon"></i>
                        <i class="bi bi-sun"></i>
                    </button>
                    <a href="#" class="nav-btn nav-btn-secondary">
                        <i class="bi bi-bell"></i>
                        <span>Alertes</span>
                    </a>
                    <a href="{{ route('lapin.create') }}" class="nav-btn nav-btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        <span>Nouveau</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="page-container animate-in">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="anyxtech-footer">
        <div class="footer-content">
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand-section">
                    <div class="footer-logo-title">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="url(#footerGrad)"/>
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="#0A1628"/>
                            <defs>
                                <linearGradient id="footerGrad" x1="5" y1="5" x2="35" y2="35">
                                    <stop offset="0%" stop-color="#00D9FF"/>
                                    <stop offset="100%" stop-color="#0066FF"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="footer-title">AnyxTech</span>
                    </div>
                    <p class="footer-description">
                        Solution professionnelle et intelligente pour la gestion moderne de votre élevage cunicole. 
                        Optimisez vos performances avec nos outils technologiques de pointe.
                    </p>
                    <div class="footer-social-links">
                        <a href="#" class="social-link">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-github"></i>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div class="footer-section">
                    <h4>Produit</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Fonctionnalités</a>
                        <a href="#" class="footer-link">Tarifs</a>
                        <a href="#" class="footer-link">Documentation</a>
                        <a href="#" class="footer-link">API</a>
                        <a href="#" class="footer-link">Mises à jour</a>
                    </div>
                </div>

                <!-- Company Links -->
                <div class="footer-section">
                    <h4>Entreprise</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">À propos</a>
                        <a href="#" class="footer-link">Blog</a>
                        <a href="#" class="footer-link">Carrières</a>
                        <a href="#" class="footer-link">Presse</a>
                        <a href="#" class="footer-link">Partenaires</a>
                    </div>
                </div>

                <!-- Support Links -->
                <div class="footer-section">
                    <h4>Support</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Centre d'aide</a>
                        <a href="#" class="footer-link">Contact</a>
                        <a href="#" class="footer-link">Statut</a>
                        <a href="#" class="footer-link">Formation</a>
                        <a href="#" class="footer-link">Communauté</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="copyright">
                    © {{ date('Y') }} AnyxTech. Tous droits réservés. Développé avec ❤️ par yamdev07
                </div>
                <div class="footer-legal">
                    <a href="#">Conditions d'utilisation</a>
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile Menu Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        const navActions = document.getElementById('navActions');

        mobileToggle.addEventListener('click', () => {
            mobileToggle.classList.toggle('active');
            navMenu.classList.toggle('mobile-active');
            navActions.classList.toggle('mobile-active');
        });

        // Dropdown Toggle for Mobile
        document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            toggle.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                }
            });
        });

        // Navbar Scroll Effect
        let lastScroll = 0;
        const navbar = document.querySelector('.anyxtech-navbar');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            lastScroll = currentScroll;
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Active Link Highlight
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });

        // Animate on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });

        // Add ripple effect to buttons
        function createRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            ripple.style.width = ripple.style.height = `${diameter}px`;
            ripple.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
            ripple.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
            ripple.classList.add('ripple');

            const existingRipple = button.querySelector('.ripple');
            if (existingRipple) {
                existingRipple.remove();
            }

            button.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        // Add ripple CSS
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: rippleEffect 0.6s ease-out;
                pointer-events: none;
            }
            
            @keyframes rippleEffect {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        document.querySelectorAll('.nav-btn').forEach(button => {
            button.addEventListener('click', createRipple);
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
        });

        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        // Check for saved theme preference or default to dark
        const currentTheme = localStorage.getItem('theme') || 'dark';
        
        // Apply saved theme on page load
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
        }

        // Theme toggle click event
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            
            // Save theme preference
            const theme = body.classList.contains('light-mode') ? 'light' : 'dark';
            localStorage.setItem('theme', theme);
            
            // Add animation to toggle button
            themeToggle.style.transform = 'rotate(180deg)';
            setTimeout(() => {
                themeToggle.style.transform = 'rotate(0)';
            }, 300);
        });

        // Add keyboard shortcut for theme toggle (Ctrl+Shift+T)
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                themeToggle.click();
            }
        });

        // Detect system theme preference
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        
        // If no theme is saved in localStorage, use system preference
        if (!localStorage.getItem('theme')) {
            if (!prefersDarkScheme.matches) {
                body.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
            }
        }

        // Listen for system theme changes
        prefersDarkScheme.addEventListener('change', (e) => {
            // Only apply if user hasn't manually set a preference
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    body.classList.remove('light-mode');
                } else {
                    body.classList.add('light-mode');
                }
            }
        });
    </script>

    @yield('scripts')
</body>
</html>