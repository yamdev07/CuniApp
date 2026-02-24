<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Email - CuniApp Élevage</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f3f4f6; line-height: 1.6; }
        .email-container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .email-header { background: linear-gradient(135deg, #2563EB 0%, #06B6D4 100%); padding: 40px 30px; text-align: center; }
        .logo { width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .logo svg { width: 36px; height: 36px; }
        .email-header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .email-header p { color: rgba(255,255,255,0.9); font-size: 14px; }
        .email-body { padding: 40px 30px; }
        .greeting { font-size: 18px; color: #1f2937; margin-bottom: 16px; font-weight: 600; }
        .message { color: #4b5563; font-size: 15px; margin-bottom: 24px; line-height: 1.7; }
        .code-container { background: #eff6ff; border: 2px dashed #2563eb; border-radius: 12px; padding: 24px; text-align: center; margin: 32px 0; }
        .code-label { color: #6b7280; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; font-weight: 600; }
        .verification-code { font-size: 36px; font-weight: 700; color: #2563eb; letter-spacing: 8px; font-family: 'JetBrains Mono', monospace; }
        .expiry-info { color: #f59e0b; font-size: 13px; margin-top: 12px; font-weight: 500; }
        .info-box { background: #f9fafb; border-left: 4px solid #2563eb; padding: 16px; border-radius: 8px; margin: 24px 0; }
        .info-box p { color: #6b7280; font-size: 13px; line-height: 1.6; }
        .info-box strong { color: #1f2937; }
        .button-container { text-align: center; margin: 32px 0; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: all 0.3s ease; }
        .footer { background: #f9fafb; padding: 24px 30px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { color: #9ca3af; font-size: 12px; margin-bottom: 8px; }
        .footer a { color: #2563eb; text-decoration: none; }
        .social-links { margin-top: 16px; }
        .social-links a { display: inline-block; margin: 0 8px; color: #6b7280; text-decoration: none; font-size: 18px; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">
                <svg viewBox="0 0 40 40" fill="none">
                    <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/>
                    <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/>
                </svg>
            </div>
            <h1>CuniApp Élevage</h1>
            <p>Gestion intelligente de votre cheptel</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Bonjour,</p>
            <p class="message">
                Merci de vous être inscrit sur <strong>CuniApp Élevage</strong> ! 
                Pour finaliser votre inscription et activer votre compte, veuillez utiliser 
                le code de vérification ci-dessous.
            </p>

            <!-- Verification Code -->
            <div class="code-container">
                <div class="code-label">Votre code de vérification</div>
                <div class="verification-code">{{ $code }}</div>
                <div class="expiry-info">⏱️ Ce code expire dans 10 minutes</div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <p>
                    <strong>💡 Conseil :</strong> Pour des raisons de sécurité, ne partagez jamais 
                    ce code avec qui que ce soit. Notre équipe ne vous demandera jamais votre 
                    code de vérification.
                </p>
            </div>

            <!-- Alternative Action - ✅ FIXED: Simple link to welcome page -->
            <div class="button-container">
                <p style="color: #6b7280; font-size: 13px; margin-bottom: 16px;">
                    Ou retournez sur la page d'accueil pour saisir votre code
                </p>
                <a href="{{ route('welcome') }}" class="cta-button">
                    Retour à la vérification
                </a>
            </div>

            <p class="message">
                Si vous n'avez pas demandé ce code, vous pouvez ignorer cet email en toute sécurité.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} CuniApp Élevage - Tous droits réservés</p>
            <p>
                <a href="{{ config('app.url') }}">Visiter notre site</a> • 
                <a href="{{ route('welcome') }}">Se connecter</a> • 
                <a href="mailto:{{ config('mail.from.address') }}">Contact</a>
            </p>
            <div class="social-links">
                <a href="#">📘</a>
                <a href="#">🐦</a>
                <a href="#">📸</a>
                <a href="#">💼</a>
            </div>
        </div>
    </div>
</body>
</html>