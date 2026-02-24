<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CuniApp Élevage') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            --surface: #FFFFFF;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
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
            background: linear-gradient(135deg, #1e3a5f 0%, #2563EB 50%, #06B6D4 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background Elements */
        .bg-particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }

        .bg-particle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .bg-particle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 50%;
            right: -50px;
            animation-delay: -5s;
        }

        .bg-particle:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: -50px;
            left: 30%;
            animation-delay: -10s;
        }

        .bg-particle:nth-child(4) {
            width: 250px;
            height: 250px;
            bottom: 20%;
            right: 20%;
            animation-delay: -15s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0.1;
            }
            25% {
                transform: translate(30px, -30px) rotate(90deg);
                opacity: 0.15;
            }
            50% {
                transform: translate(-20px, 20px) rotate(180deg);
                opacity: 0.1;
            }
            75% {
                transform: translate(20px, 30px) rotate(270deg);
                opacity: 0.15;
            }
        }

        /* Main Container */
        .welcome-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .welcome-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            width: 100%;
            align-items: center;
        }

        /* Left Side - Branding */
        .brand-section {
            color: var(--white);
            animation: slideInLeft 1s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: var(--shadow-lg);
            animation: logoPulse 3s infinite ease-in-out;
        }

        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: var(--shadow-lg);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.4);
            }
        }

        .logo-container svg {
            width: 48px;
            height: 48px;
        }

        .brand-title {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-title span {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-green) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 32px;
            font-weight: 400;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
            margin-bottom: 40px;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .features-list li:nth-child(1) { animation-delay: 0.2s; }
        .features-list li:nth-child(2) { animation-delay: 0.4s; }
        .features-list li:nth-child(3) { animation-delay: 0.6s; }
        .features-list li:nth-child(4) { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .features-list li i {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Right Side - Auth Cards */
        .auth-section {
            animation: slideInRight 1s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .auth-container {
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .auth-container:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(-5px);
        }

        .auth-tabs {
            display: flex;
            background: var(--gray-100);
            padding: 8px;
        }

        .auth-tab {
            flex: 1;
            padding: 14px 24px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-500);
            background: transparent;
            border: none;
            cursor: pointer;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-tab.active {
            background: var(--white);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .auth-tab:hover:not(.active) {
            color: var(--gray-700);
            background: rgba(255, 255, 255, 0.5);
        }

        .auth-forms {
            padding: 40px;
        }

        .auth-form {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
        }

        .auth-form.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 8px;
            text-align: center;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            font-size: 14px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            background: var(--white);
            color: var(--gray-800);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }

        .form-input:focus + i,
        .form-input-wrapper:focus-within i {
            color: var(--primary);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-600);
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: var(--shadow-md);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit i {
            transition: transform 0.3s ease;
        }

        .btn-submit:hover i {
            transform: translateX(4px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--gray-400);
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .divider span {
            padding: 0 16px;
        }

        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            font-size: 13px;
            font-weight: 500;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            border-color: var(--gray-300);
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        .btn-social i {
            font-size: 16px;
        }

        .btn-social.google i { color: #DB4437; }
        .btn-social.facebook i { color: #4267B2; }

        /* Responsive */
        @media (max-width: 968px) {
            .welcome-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .brand-section {
                order: 2;
            }

            .auth-section {
                order: 1;
            }

            .features-list li {
                justify-content: center;
            }

            .brand-title {
                font-size: 36px;
            }
        }

        @media (max-width: 480px) {
            .auth-forms {
                padding: 24px;
            }

            .brand-title {
                font-size: 28px;
            }

            .brand-tagline {
                font-size: 16px;
            }
        }

        /* Loading State */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Error States */
        .form-input.error {
            border-color: #EF4444;
        }

        .error-message {
            color: #EF4444;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body>
    <!-- Background Particles -->
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>
    <div class="bg-particle"></div>

    <div class="welcome-container">
        <div class="welcome-content">
            <!-- Left Side - Branding -->
            <div class="brand-section">
                <div class="logo-container">
                    <svg viewBox="0 0 40 40" fill="none">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/>
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/>
                    </svg>
                </div>
                <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                <p class="brand-tagline">Gestion intelligente de votre cheptel lapin. Suivez vos reproductions, naissances et performances en toute simplicité.</p>
                
                <ul class="features-list">
                    <li>
                        <i><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></i>
                        <span>Suivi complet des mâles et femelles</span>
                    </li>
                    <li>
                        <i><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></i>
                        <span>Gestion des saillies et mises bas</span>
                    </li>
                    <li>
                        <i><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></i>
                        <span>Tableau de bord analytique</span>
                    </li>
                    <li>
                        <i><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></i>
                        <span>Alertes et notifications intelligentes</span>
                    </li>
                </ul>
            </div>

            <!-- Right Side - Auth Forms -->
            <div class="auth-section">
                <div class="auth-container">
                    <div class="auth-tabs">
                        <button type="button" class="auth-tab active" data-tab="login" id="tab-login">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </button>
                        <button type="button" class="auth-tab" data-tab="register" id="tab-register">
                            <i class="bi bi-person-plus"></i> Inscription
                        </button>
                    </div>

                    <div class="auth-forms">
                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form active" id="form-login">
                            @csrf
                            <h2 class="form-title">Bon retour !</h2>
                            <p class="form-subtitle">Connectez-vous à votre compte</p>

                            <div class="form-group">
                                <label class="form-label">Adresse email</label>
                                <div class="form-input-wrapper">
                                    <input type="email" name="email" class="form-input" placeholder="votre@email.com" required autofocus>
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                    <div class="error-message">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                                    <i class="bi bi-lock"></i>
                                </div>
                                @error('password')
                                    <div class="error-message">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-options">
                                <label class="remember-me">
                                    <input type="checkbox" name="remember">
                                    <span>Se souvenir de moi</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="forgot-password" href="{{ route('password.request') }}">
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn-submit">
                                <span>Se connecter</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <div class="divider">
                                <span>ou continuer avec</span>
                            </div>

                            <div class="social-login">
                                <button type="button" class="btn-social google">
                                    <i class="bi bi-google"></i>
                                    <span>Google</span>
                                </button>
                                <button type="button" class="btn-social facebook">
                                    <i class="bi bi-facebook"></i>
                                    <span>Facebook</span>
                                </button>
                            </div>
                        </form>

                        <!-- Register Form -->
                        <form method="POST" action="{{ route('register') }}" class="auth-form" id="form-register">
                            @csrf
                            <h2 class="form-title">Créer un compte</h2>
                            <p class="form-subtitle">Rejoignez CuniApp dès aujourd'hui</p>

                            <div class="form-group">
                                <label class="form-label">Nom complet</label>
                                <div class="form-input-wrapper">
                                    <input type="text" name="name" class="form-input" placeholder="Jean Dupont" required autofocus>
                                    <i class="bi bi-person"></i>
                                </div>
                                @error('name')
                                    <div class="error-message">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Adresse email</label>
                                <div class="form-input-wrapper">
                                    <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                    <div class="error-message">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                                    <i class="bi bi-lock"></i>
                                </div>
                                @error('password')
                                    <div class="error-message">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                                    <i class="bi bi-lock-fill"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                <span>Créer mon compte</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <div class="divider">
                                <span>ou s'inscrire avec</span>
                            </div>

                            <div class="social-login">
                                <button type="button" class="btn-social google">
                                    <i class="bi bi-google"></i>
                                    <span>Google</span>
                                </button>
                                <button type="button" class="btn-social facebook">
                                    <i class="bi bi-facebook"></i>
                                    <span>Facebook</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Welcome page loaded successfully');
            
            // Tab Switching Function
            function switchTab(tabName) {
                console.log('🔄 Switching to tab:', tabName);
                
                // Get all tabs and forms
                const tabs = document.querySelectorAll('.auth-tab');
                const forms = document.querySelectorAll('.auth-form');
                
                // Remove active class from all tabs
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                    console.log('Tab:', tab.getAttribute('data-tab'), 'Active:', tab.classList.contains('active'));
                });
                
                // Remove active class from all forms
                forms.forEach(form => {
                    form.classList.remove('active');
                    console.log('Form:', form.id, 'Active:', form.classList.contains('active'));
                });
                
                // Add active class to selected tab
                const selectedTab = document.querySelector(`.auth-tab[data-tab="${tabName}"]`);
                if (selectedTab) {
                    selectedTab.classList.add('active');
                    console.log('✅ Tab activated:', tabName);
                }
                
                // Add active class to selected form
                const selectedForm = document.querySelector(`#form-${tabName}`);
                if (selectedForm) {
                    selectedForm.classList.add('active');
                    console.log('✅ Form activated:', `form-${tabName}`);
                }
            }
            
            // Add click event listeners to tabs
            const tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetTab = this.getAttribute('data-tab');
                    console.log('🖱️ Tab clicked:', targetTab);
                    switchTab(targetTab);
                });
            });
            
            // Check URL for errors to switch to appropriate tab
            @if ($errors->has('email') || $errors->has('password'))
                console.log('⚠️ Login errors detected, switching to login tab');
                switchTab('login');
            @endif
            
            @if ($errors->has('name') || $errors->has('password_confirmation'))
                console.log('⚠️ Register errors detected, switching to register tab');
                switchTab('register');
            @endif
            
            // Form Loading State
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.querySelector('span').textContent = 'Chargement...';
                        submitBtn.querySelector('i').className = 'bi bi-hourglass-split';
                    }
                });
            });
            
            // Input Focus Effects
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.classList.remove('focused');
                });
            });
            
            console.log('✅ All event listeners attached');
        });
    </script>
</body>
</html>