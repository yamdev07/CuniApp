<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurisez votre compte — {{ config('app.name', 'CuniApp') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-subtle: #EFF6FF;
            --accent-cyan: #06B6D4;
            --accent-green: #10B981;
            --accent-red: #EF4444;
            --accent-orange: #F59E0B;
            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --radius: 8px;
            --radius-md: 12px;
            --radius-xl: 20px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563EB 50%, #06B6D4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
        }

        /* Logo */
        .logo-row {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-bubble {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--accent-cyan));
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }
        .logo-bubble i { font-size: 32px; color: white; }
        .logo-name {
            font-size: 22px;
            font-weight: 700;
            color: white;
            letter-spacing: -0.02em;
        }

        /* Card */
        .card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 60px -10px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(135deg, #F0F9FF, #E0F2FE);
            padding: 28px 36px 20px;
            border-bottom: 1px solid var(--gray-200);
        }
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .card-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 6px;
        }
        .card-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            line-height: 1.5;
        }

        .card-body { padding: 32px 36px; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i.field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 18px;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 12px 44px;
            font-size: 14px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            background: white;
            color: var(--gray-800);
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        /* Password Strength */
        .strength-container { margin-top: 12px; }
        .strength-bar {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .strength-fill {
            height: 100%;
            width: 0;
            transition: all 0.4s ease;
            border-radius: 3px;
        }
        .strength-text {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .requirements {
            margin-top: 16px;
            background: var(--gray-50);
            border-radius: var(--radius);
            padding: 14px;
            font-size: 11px;
        }
        .req-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-500);
            margin-bottom: 8px;
        }
        .req-item:last-child { margin-bottom: 0; }
        .req-item.met { color: var(--accent-green); }
        .req-item.met i { color: var(--accent-green); }

        .error-alert {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
            padding: 12px;
            border-radius: var(--radius);
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
            transition: all 0.2s;
            margin-top: 24px;
        }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-primary:not(:disabled):hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        .logout-link button {
            background: none;
            border: none;
            color: var(--gray-500);
            font-size: 13px;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="logo-row">
            <div class="logo-bubble"><i class="bi bi-shield-lock"></i></div>
            <div class="logo-name">CuniApp Security</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="security-badge"><i class="bi bi-shield-check"></i> Première connexion</div>
                <h1 class="card-title">Sécurisez votre compte</h1>
                <p class="card-subtitle">
                    Votre administrateur a créé votre compte. Pour continuer, vous devez définir votre propre mot de passe personnel.
                </p>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="error-alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.change.update') }}" id="passwordChangeForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="password">Nouveau mot de passe</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock field-icon"></i>
                            <input type="password" id="password" name="password" class="form-input" required autofocus placeholder="••••••••">
                            <button type="button" class="toggle-password" onclick="togglePass('password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <div class="strength-container">
                            <div class="strength-bar"><div class="strength-fill" id="strengthBar"></div></div>
                            <div class="strength-text" id="strengthText">Trop faible</div>
                        </div>

                        <div class="requirements">
                            <div class="req-item" id="req-len"><i class="bi bi-circle"></i> Au moins 8 caractères</div>
                            <div class="req-item" id="req-up"><i class="bi bi-circle"></i> Une majuscule</div>
                            <div class="req-item" id="req-low"><i class="bi bi-circle"></i> Une minuscule</div>
                            <div class="req-item" id="req-num"><i class="bi bi-circle"></i> Un chiffre</div>
                            <div class="req-item" id="req-spec"><i class="bi bi-circle"></i> Un caractère spécial</div>
                            <div class="req-item" id="req-diff"><i class="bi bi-circle"></i> Différent du mot de passe admin</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                        <div class="input-wrapper">
                            <i class="bi bi-shield-lock field-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" id="submitBtn" disabled>
                        <i class="bi bi-check2-circle"></i>
                        Enregistrer mon mot de passe
                    </button>
                </form>

                <div class="logout-link">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Se déconnecter et revenir plus tard</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        const reqs = {
            len: document.getElementById('req-len'),
            up: document.getElementById('req-up'),
            low: document.getElementById('req-low'),
            num: document.getElementById('req-num'),
            spec: document.getElementById('req-spec')
        };

        function togglePass(id) {
            const input = document.getElementById(id);
            const icon = event.currentTarget.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        password.addEventListener('input', () => {
            const val = password.value;
            let score = 0;

            const met = {
                len: val.length >= 8,
                up: /[A-Z]/.test(val),
                low: /[a-z]/.test(val),
                num: /[0-9]/.test(val),
                spec: /[^A-Za-z0-9]/.test(val)
            };

            Object.keys(met).forEach(k => {
                const item = reqs[k];
                const icon = item.querySelector('i');
                if (met[k]) {
                    item.classList.add('met');
                    icon.className = 'bi bi-check-circle-fill';
                    score++;
                } else {
                    item.classList.remove('met');
                    icon.className = 'bi bi-circle';
                }
            });

            // Update UI
            const configs = [
                { color: '#EF4444', text: 'Trop faible', width: '20%' },
                { color: '#EF4444', text: 'Trop faible', width: '20%' },
                { color: '#F59E0B', text: 'Moyen', width: '40%' },
                { color: '#3B82F6', text: 'Bon', width: '60%' },
                { color: '#06B6D4', text: 'Très bon', width: '80%' },
                { color: '#10B981', text: 'Excellent', width: '100%' }
            ];

            const cfg = configs[score];
            strengthBar.style.width = cfg.width;
            strengthBar.style.backgroundColor = cfg.color;
            strengthText.innerText = cfg.text;
            strengthText.style.color = cfg.color;

            submitBtn.disabled = (score < 5);
        });
    </script>
</body>
</html>
