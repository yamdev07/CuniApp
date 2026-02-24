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

        .form-input.error {
            border-color: var(--accent-red);
        }

        .form-input.success {
            border-color: var(--accent-green);
        }

        /* Validation Messages */
        .validation-message {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            margin-top: 6px;
            padding: 8px 12px;
            border-radius: var(--radius);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .validation-message.error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .validation-message.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .validation-message.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-orange);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .validation-message i {
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Password Strength Meter */
        .password-strength-container {
            margin-top: 8px;
        }

        .password-strength-bar {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .password-strength-fill {
            height: 100%;
            border-radius: 3px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .password-strength-fill.weak {
            width: 25%;
            background: var(--accent-red);
        }

        .password-strength-fill.fair {
            width: 50%;
            background: var(--accent-orange);
        }

        .password-strength-fill.good {
            width: 75%;
            background: var(--accent-cyan);
        }

        .password-strength-fill.strong {
            width: 100%;
            background: var(--accent-green);
        }

        .password-strength-text {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .password-strength-text.weak { color: var(--accent-red); }
        .password-strength-text.fair { color: var(--accent-orange); }
        .password-strength-text.good { color: var(--accent-cyan); }
        .password-strength-text.strong { color: var(--accent-green); }

        /* Password Requirements */
        .password-requirements {
            margin-top: 12px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: var(--radius);
            font-size: 12px;
        }

        .password-requirements-title {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            color: var(--gray-600);
            transition: all 0.3s ease;
        }

        .password-requirement:last-child {
            margin-bottom: 0;
        }

        .password-requirement.met {
            color: var(--accent-green);
        }

        .password-requirement i {
            font-size: 14px;
            width: 16px;
        }

        .password-requirement.met i {
            color: var(--accent-green);
        }

        .password-requirement:not(.met) i {
            color: var(--gray-400);
        }

        /* Form Options */
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

        /* Submit Button */
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

        /* Divider */
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

        /* Social Login */
        .social-login {
            display: grid;
            grid-template-columns: 1fr;
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

        .btn-social.google i {
            color: #DB4437;
        }

        /* Network Status */
        .network-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .network-status.offline {
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .network-status i {
            font-size: 16px;
        }

        /* Alert Boxes */
        .alert-box {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            animation: slideDown 0.3s ease;
        }

        .alert-box i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-box.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--accent-green);
        }

        .alert-box.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
        }

        .alert-box.warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: var(--accent-orange);
        }

        .alert-box.info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--primary);
        }

        /* Validation Summary */
        .validation-summary-list {
            list-style: none;
            padding-left: 0;
            margin: 8px 0 0 20px;
        }

        .validation-summary-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--gray-700);
        }

        .validation-summary-list li:last-child {
            margin-bottom: 0;
        }

        .validation-summary-list li i {
            color: var(--accent-red);
            font-size: 14px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Character Counter */
        .char-counter {
            font-size: 11px;
            color: var(--gray-500);
            text-align: right;
            margin-top: 4px;
        }

        .char-counter.exceeded {
            color: var(--accent-red);
            font-weight: 600;
        }

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

        /* ==================== CUSTOM MODAL ==================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container {
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            max-width: 450px;
            width: 90%;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modal-overlay.active .modal-container {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--surface-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-800);
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: var(--gray-100);
            border-radius: var(--radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: var(--gray-200);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--surface-border);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn.primary {
            background: var(--primary);
            color: var(--white);
        }

        .modal-btn.primary:hover {
            background: var(--primary-dark);
        }

        .modal-btn.secondary {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .modal-btn.secondary:hover {
            background: var(--gray-50);
        }

        .modal-btn.danger {
            background: var(--accent-red);
            color: var(--white);
        }

        .modal-btn.danger:hover {
            background: #DC2626;
        }

        /* Verification Code Input */
        .verification-code-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 24px 0;
        }

        .verification-code-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            border: 2px solid var(--gray-300);
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .verification-code-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-subtle);
        }

        .verification-code-input.filled {
            border-color: var(--primary);
            background: var(--primary-subtle);
        }

        .verification-info {
            text-align: center;
            color: var(--gray-600);
            font-size: 13px;
            margin-top: 16px;
        }

        .verification-info a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .verification-info a:hover {
            text-decoration: underline;
        }

        .resend-timer {
            text-align: center;
            color: var(--gray-500);
            font-size: 12px;
            margin-top: 12px;
        }

        .resend-timer.disabled {
            color: var(--gray-400);
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
                        <!-- Network Status -->
                        <div class="network-status offline" id="networkStatus" style="display: none;">
                            <i class="bi bi-wifi-off"></i>
                            <span>Aucune connexion réseau</span>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form active" id="form-login">
                            @csrf

                            @if(session('success'))
                            <div class="alert-box success">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                            @endif

                            @if($errors->has('email') || $errors->has('password'))
                            <div class="alert-box error">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <div>
                                    <strong>Erreurs de connexion</strong>
                                    <ul class="validation-summary-list">
                                        @foreach($errors->all() as $error)
                                        <li>
                                            <i class="bi bi-x-circle-fill"></i>
                                            <span>
                                                @if($error === 'auth.failed' || str_contains($error, 'auth.failed'))
                                                    Ces identifiants ne correspondent pas à nos enregistrements. Veuillez vérifier votre email et mot de passe.
                                                @elseif(str_contains($error, 'throttle'))
                                                    Trop de tentatives de connexion. Veuillez réessayer plus tard.
                                                @elseif(str_contains($error, 'validation.'))
                                                    {{ str_replace('validation.', '', $error) }}
                                                @else
                                                    {{ $error }}
                                                @endif
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            <h2 class="form-title">Bon retour !</h2>
                            <p class="form-subtitle">Connectez-vous à votre compte</p>

                            <div class="form-group">
                                <label class="form-label">Adresse email</label>
                                <div class="form-input-wrapper">
                                    <input type="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" required autofocus value="{{ old('email') }}" id="loginEmail">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>
                                        @if(str_contains($message, 'validation.'))
                                            {{ str_replace('validation.', '', $message) }}
                                        @else
                                            {{ $message }}
                                        @endif
                                    </span>
                                </div>
                                @enderror
                                <div class="validation-message" id="loginEmailValidation" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password" class="form-input @error('password') error @enderror" placeholder="••••••••" required id="loginPassword">
                                    <i class="bi bi-lock"></i>
                                </div>
                                @error('password')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            <div class="form-options">
                                <label class="remember-me">
                                    <input type="checkbox" name="remember">
                                    <span>Se souvenir de moi</span>
                                </label>
                                @if (Route::has('password.request'))
                                <a class="forgot-password" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
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
                            </div>
                        </form>

                        <!-- Register Form -->
                        <form method="POST" action="{{ route('register') }}" class="auth-form" id="form-register">
                            @csrf

                            @if(session('registration_success'))
                            <div class="alert-box success">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>{{ session('registration_success') }}</div>
                            </div>
                            @endif

                            @if($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
                            <div class="alert-box error">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <div>
                                    <strong>Erreurs de validation</strong>
                                    <ul class="validation-summary-list">
                                        @foreach($errors->all() as $error)
                                        <li>
                                            <i class="bi bi-x-circle-fill"></i>
                                            <span>
                                                @if(str_contains($error, 'validation.unique'))
                                                    Cette adresse email est déjà utilisée. Veuillez en choisir une autre.
                                                @elseif(str_contains($error, 'validation.email'))
                                                    Format d'email invalide.
                                                @elseif(str_contains($error, 'validation.min'))
                                                    Le champ est trop court.
                                                @elseif(str_contains($error, 'validation.required'))
                                                    Ce champ est obligatoire.
                                                @elseif(str_contains($error, 'validation.confirmed'))
                                                    Les mots de passe ne correspondent pas.
                                                @elseif(str_contains($error, 'validation.'))
                                                    {{ str_replace('validation.', '', $error) }}
                                                @else
                                                    {{ $error }}
                                                @endif
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            <h2 class="form-title">Créer un compte</h2>
                            <p class="form-subtitle">Rejoignez CuniApp dès aujourd'hui</p>

                            <div class="form-group">
                                <label class="form-label">Nom complet</label>
                                <div class="form-input-wrapper">
                                    <input type="text" name="name" class="form-input @error('name') error @enderror" placeholder="Jean Dupont" required autofocus value="{{ old('name') }}" id="registerName" minlength="2" maxlength="255">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="char-counter" id="nameCharCounter">0/255</div>
                                @error('name')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>
                                        @if(str_contains($message, 'validation.'))
                                            {{ str_replace('validation.', '', $message) }}
                                        @else
                                            {{ $message }}
                                        @endif
                                    </span>
                                </div>
                                @enderror
                                <div class="validation-message" id="nameValidation" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Adresse email</label>
                                <div class="form-input-wrapper">
                                    <input type="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" required value="{{ old('email') }}" id="registerEmail">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>
                                        @if(str_contains($message, 'validation.unique'))
                                            Cette adresse email est déjà utilisée. Veuillez en choisir une autre.
                                        @elseif(str_contains($message, 'validation.email'))
                                            Format d'email invalide.
                                        @elseif(str_contains($message, 'validation.'))
                                            {{ str_replace('validation.', '', $message) }}
                                        @else
                                            {{ $message }}
                                        @endif
                                    </span>
                                </div>
                                @enderror
                                <div class="validation-message" id="emailValidation" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password" class="form-input @error('password') error @enderror" placeholder="••••••••" required id="registerPassword" minlength="8">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <div class="password-strength-container">
                                    <div class="password-strength-bar">
                                        <div class="password-strength-fill" id="passwordStrengthFill"></div>
                                    </div>
                                    <div class="password-strength-text" id="passwordStrengthText">Faible</div>
                                </div>
                                <div class="password-requirements">
                                    <div class="password-requirements-title">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Le mot de passe doit contenir :</span>
                                    </div>
                                    <div class="password-requirement" id="req-length">
                                        <i class="bi bi-circle"></i>
                                        <span>Au moins 8 caractères</span>
                                    </div>
                                    <div class="password-requirement" id="req-uppercase">
                                        <i class="bi bi-circle"></i>
                                        <span>Une majuscule</span>
                                    </div>
                                    <div class="password-requirement" id="req-lowercase">
                                        <i class="bi bi-circle"></i>
                                        <span>Une minuscule</span>
                                    </div>
                                    <div class="password-requirement" id="req-number">
                                        <i class="bi bi-circle"></i>
                                        <span>Un chiffre</span>
                                    </div>
                                    <div class="password-requirement" id="req-special">
                                        <i class="bi bi-circle"></i>
                                        <span>Un caractère spécial (!@#$%^&*)</span>
                                    </div>
                                </div>
                                @error('password')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>
                                        @if(str_contains($message, 'validation.'))
                                            {{ str_replace('validation.', '', $message) }}
                                        @else
                                            {{ $message }}
                                        @endif
                                    </span>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <div class="form-input-wrapper">
                                    <input type="password" name="password_confirmation" class="form-input @error('password_confirmation') error @enderror" placeholder="••••••••" required id="passwordConfirmation">
                                    <i class="bi bi-lock-fill"></i>
                                </div>
                                <div class="validation-message" id="passwordMatchValidation" style="display: none;"></div>
                                @error('password_confirmation')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>
                                        @if(str_contains($message, 'validation.'))
                                            {{ str_replace('validation.', '', $message) }}
                                        @else
                                            {{ $message }}
                                        @endif
                                    </span>
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="terms" required style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                                    <span style="font-size: 13px; color: var(--gray-600);">
                                        J'accepte les <a href="#" style="color: var(--primary);">Conditions d'utilisation</a> et la <a href="#" style="color: var(--primary);">Politique de confidentialité</a>
                                    </span>
                                </label>
                                @error('terms')
                                <div class="validation-message error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Modal for Email Verification -->
    <div class="modal-overlay" id="verificationModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="bi bi-shield-check" style="color: var(--primary);"></i>
                    Vérification Email
                </h3>
                <button class="modal-close" onclick="closeVerificationModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center; color: var(--gray-600); margin-bottom: 16px;">
                    Un code de vérification a été envoyé à<br>
                    <strong id="verificationEmail" style="color: var(--primary);"></strong>
                </p>
                
                <form id="verificationForm" method="POST" action="{{ route('verification.verify') }}">
                    @csrf
                    <input type="hidden" name="email" id="verificationEmailInput">
                    
                    <div class="verification-code-inputs">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="0">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="1">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="2">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="3">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="4">
                        <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="5">
                    </div>
                    
                    <input type="hidden" name="code" id="verificationCodeInput">
                    
                    <button type="submit" class="btn-submit" style="width: 100%;">
                        <span>Vérifier</span>
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>
                
                <div class="verification-info">
                    <p>Vous n'avez pas reçu le code ? <a href="#" id="resendCode" onclick="resendVerificationCode(event)">Renvoyer</a></p>
                </div>
                
                <div class="resend-timer disabled" id="resendTimer">
                    Renvoyer dans <span id="timerCount">60</span>s
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Alert Modal -->
    <div class="modal-overlay" id="alertModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title" id="alertModalTitle">Alerte</h3>
                <button class="modal-close" onclick="closeAlertModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="alertModalMessage" style="color: var(--gray-700);"></p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" onclick="closeAlertModal()">OK</button>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title" id="confirmModalTitle">Confirmation</h3>
                <button class="modal-close" onclick="closeConfirmModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage" style="color: var(--gray-700);"></p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn secondary" onclick="closeConfirmModal()">Annuler</button>
                <button class="modal-btn primary" id="confirmModalConfirm">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Welcome page loaded successfully');

            // Network Status Check
            function updateNetworkStatus() {
                const networkStatus = document.getElementById('networkStatus');
                if (navigator.onLine) {
                    networkStatus.style.display = 'none';
                } else {
                    networkStatus.style.display = 'flex';
                    networkStatus.className = 'network-status offline';
                    networkStatus.innerHTML = '<i class="bi bi-wifi-off"></i><span>Aucune connexion réseau</span>';
                }
            }

            window.addEventListener('online', updateNetworkStatus);
            window.addEventListener('offline', updateNetworkStatus);
            updateNetworkStatus();

            // Tab Switching Function - Clear errors when switching
            function switchTab(tabName) {
                const tabs = document.querySelectorAll('.auth-tab');
                const forms = document.querySelectorAll('.auth-form');
                
                tabs.forEach(tab => tab.classList.remove('active'));
                forms.forEach(form => form.classList.remove('active'));
                
                const selectedTab = document.querySelector(`.auth-tab[data-tab="${tabName}"]`);
                if (selectedTab) selectedTab.classList.add('active');
                
                const selectedForm = document.querySelector(`#form-${tabName}`);
                if (selectedForm) selectedForm.classList.add('active');
                
                // Clear all validation messages when switching tabs
                clearAllValidations();
                
                // Store current tab in session storage
                sessionStorage.setItem('cuniapp_current_tab', tabName);
            }

            function clearAllValidations() {
                document.querySelectorAll('.validation-message').forEach(msg => {
                    msg.style.display = 'none';
                    msg.className = 'validation-message';
                });
                document.querySelectorAll('.form-input').forEach(input => {
                    input.classList.remove('error', 'success');
                });
            }

            const tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    switchTab(this.getAttribute('data-tab'));
                });
            });

            // Restore tab from session storage or show based on errors
            @if ($errors->has('email') || $errors->has('password'))
                switchTab('login');
            @elseif ($errors->has('name') || $errors->has('password_confirmation'))
                switchTab('register');
            @else
                const savedTab = sessionStorage.getItem('cuniapp_current_tab');
                if (savedTab) {
                    switchTab(savedTab);
                }
            @endif

            // Password Strength Checker
            const registerPassword = document.getElementById('registerPassword');
            const passwordStrengthFill = document.getElementById('passwordStrengthFill');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const passwordConfirmation = document.getElementById('passwordConfirmation');
            const passwordMatchValidation = document.getElementById('passwordMatchValidation');

            if (registerPassword) {
                registerPassword.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    checkPasswordMatch();
                });
            }

            if (passwordConfirmation) {
                passwordConfirmation.addEventListener('input', checkPasswordMatch);
            }

            function checkPasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };

                updateRequirement('req-length', requirements.length);
                updateRequirement('req-uppercase', requirements.uppercase);
                updateRequirement('req-lowercase', requirements.lowercase);
                updateRequirement('req-number', requirements.number);
                updateRequirement('req-special', requirements.special);

                const strength = Object.values(requirements).filter(Boolean).length;
                
                passwordStrengthFill.className = 'password-strength-fill';
                passwordStrengthText.className = 'password-strength-text';
                
                if (strength <= 2) {
                    passwordStrengthFill.classList.add('weak');
                    passwordStrengthText.classList.add('weak');
                    passwordStrengthText.textContent = 'Faible';
                } else if (strength === 3) {
                    passwordStrengthFill.classList.add('fair');
                    passwordStrengthText.classList.add('fair');
                    passwordStrengthText.textContent = 'Moyen';
                } else if (strength === 4) {
                    passwordStrengthFill.classList.add('good');
                    passwordStrengthText.classList.add('good');
                    passwordStrengthText.textContent = 'Bon';
                } else {
                    passwordStrengthFill.classList.add('strong');
                    passwordStrengthText.classList.add('strong');
                    passwordStrengthText.textContent = 'Excellent';
                }
            }

            function updateRequirement(id, met) {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.toggle('met', met);
                    element.querySelector('i').className = met ? 'bi bi-check-circle-fill' : 'bi bi-circle';
                }
            }

            function checkPasswordMatch() {
                if (!passwordConfirmation || !registerPassword) return;
                
                const password = registerPassword.value;
                const confirmation = passwordConfirmation.value;
                
                if (confirmation.length === 0) {
                    passwordMatchValidation.style.display = 'none';
                    return;
                }

                if (password === confirmation) {
                    passwordMatchValidation.className = 'validation-message success';
                    passwordMatchValidation.innerHTML = '<i class="bi bi-check-circle-fill"></i><span>Les mots de passe correspondent</span>';
                    passwordMatchValidation.style.display = 'flex';
                    passwordConfirmation.classList.remove('error');
                    passwordConfirmation.classList.add('success');
                } else {
                    passwordMatchValidation.className = 'validation-message error';
                    passwordMatchValidation.innerHTML = '<i class="bi bi-x-circle-fill"></i><span>Les mots de passe ne correspondent pas</span>';
                    passwordMatchValidation.style.display = 'flex';
                    passwordConfirmation.classList.remove('success');
                    passwordConfirmation.classList.add('error');
                }
            }

            // Name Character Counter
            const registerName = document.getElementById('registerName');
            const nameCharCounter = document.getElementById('nameCharCounter');
            const nameValidation = document.getElementById('nameValidation');

            if (registerName && nameCharCounter) {
                registerName.addEventListener('input', function() {
                    const length = this.value.length;
                    nameCharCounter.textContent = `${length}/255`;
                    
                    if (length > 255) {
                        nameCharCounter.classList.add('exceeded');
                        nameValidation.className = 'validation-message error';
                        nameValidation.innerHTML = '<i class="bi bi-x-circle-fill"></i><span>Le nom est trop long (max 255 caractères)</span>';
                        nameValidation.style.display = 'flex';
                        this.classList.add('error');
                    } else if (length > 0 && length < 2) {
                        nameValidation.className = 'validation-message warning';
                        nameValidation.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i><span>Le nom est trop court (min 2 caractères)</span>';
                        nameValidation.style.display = 'flex';
                        this.classList.add('error');
                    } else if (length >= 2) {
                        nameCharCounter.classList.remove('exceeded');
                        nameValidation.style.display = 'none';
                        this.classList.remove('error');
                        this.classList.add('success');
                    } else {
                        nameCharCounter.classList.remove('exceeded');
                        nameValidation.style.display = 'none';
                        this.classList.remove('error', 'success');
                    }
                });
            }

            // Email Validation
            const loginEmail = document.getElementById('loginEmail');
            const registerEmail = document.getElementById('registerEmail');
            const loginEmailValidation = document.getElementById('loginEmailValidation');
            const emailValidation = document.getElementById('emailValidation');

            function validateEmail(input, validationElement) {
                if (!input || !validationElement) return;
                
                input.addEventListener('blur', function() {
                    const email = this.value;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (email.length === 0) {
                        validationElement.style.display = 'none';
                        return;
                    }

                    if (!emailRegex.test(email)) {
                        validationElement.className = 'validation-message error';
                        validationElement.innerHTML = '<i class="bi bi-x-circle-fill"></i><span>Format d\'email invalide</span>';
                        validationElement.style.display = 'flex';
                        this.classList.add('error');
                        this.classList.remove('success');
                    } else {
                        validationElement.style.display = 'none';
                        this.classList.remove('error');
                        this.classList.add('success');
                    }
                });
            }

            validateEmail(loginEmail, loginEmailValidation);
            validateEmail(registerEmail, emailValidation);

            // Form Loading State
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.querySelector('span').textContent = 'Chargement...';
                        submitBtn.querySelector('i').className = 'bi bi-hourglass-split';
                    }
                });
            });

            // ==================== CUSTOM MODAL FUNCTIONS ====================
            
            // Verification Modal
            function openVerificationModal(email) {
                document.getElementById('verificationEmail').textContent = email;
                document.getElementById('verificationEmailInput').value = email;
                document.getElementById('verificationModal').classList.add('active');
                
                // Focus first input
                setTimeout(() => {
                    document.querySelector('.verification-code-input').focus();
                }, 300);
                
                // Start resend timer
                startResendTimer();
            }

            function closeVerificationModal() {
                document.getElementById('verificationModal').classList.remove('active');
            }

            // Verification Code Inputs
            const codeInputs = document.querySelectorAll('.verification-code-input');
            codeInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1) {
                        this.classList.add('filled');
                        if (index < codeInputs.length - 1) {
                            codeInputs[index + 1].focus();
                        }
                    }
                    
                    // Update hidden input with full code
                    updateVerificationCode();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        codeInputs[index - 1].focus();
                        codeInputs[index - 1].value = '';
                        codeInputs[index - 1].classList.remove('filled');
                        updateVerificationCode();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').slice(0, 6);
                    pasteData.split('').forEach((char, i) => {
                        if (codeInputs[i]) {
                            codeInputs[i].value = char;
                            codeInputs[i].classList.add('filled');
                        }
                    });
                    updateVerificationCode();
                });
            });

            function updateVerificationCode() {
                let code = '';
                codeInputs.forEach(input => {
                    code += input.value;
                });
                document.getElementById('verificationCodeInput').value = code;
                
                // Auto-submit if all fields filled
                if (code.length === 6) {
                    document.getElementById('verificationForm').dispatchEvent(new Event('submit'));
                }
            }

            // Resend Timer
            let resendTimerInterval;
            function startResendTimer() {
                const timerElement = document.getElementById('resendTimer');
                const timerCount = document.getElementById('timerCount');
                const resendLink = document.getElementById('resendCode');
                let seconds = 60;

                timerElement.classList.remove('disabled');
                resendLink.style.pointerEvents = 'none';
                resendLink.style.opacity = '0.5';

                clearInterval(resendTimerInterval);
                resendTimerInterval = setInterval(() => {
                    seconds--;
                    timerCount.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(resendTimerInterval);
                        timerElement.classList.add('disabled');
                        resendLink.style.pointerEvents = 'auto';
                        resendLink.style.opacity = '1';
                    }
                }, 1000);
            }

            function resendVerificationCode(e) {
                e.preventDefault();
                const email = document.getElementById('verificationEmailInput').value;
                
                // Send resend request
                fetch('{{ route("verification.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Succès', 'Un nouveau code a été envoyé à votre adresse email.');
                        startResendTimer();
                    } else {
                        showAlert('Erreur', data.message || 'Une erreur est survenue.');
                    }
                })
                .catch(error => {
                    showAlert('Erreur', 'Une erreur est survenue. Veuillez réessayer.');
                });
            }

            // Alert Modal
            function showAlert(title, message) {
                document.getElementById('alertModalTitle').textContent = title;
                document.getElementById('alertModalMessage').textContent = message;
                document.getElementById('alertModal').classList.add('active');
            }

            function closeAlertModal() {
                document.getElementById('alertModal').classList.remove('active');
            }

            // Confirm Modal
            let confirmCallback = null;

            function showConfirm(title, message, callback) {
                document.getElementById('confirmModalTitle').textContent = title;
                document.getElementById('confirmModalMessage').textContent = message;
                confirmCallback = callback;
                document.getElementById('confirmModal').classList.add('active');
            }

            function closeConfirmModal() {
                document.getElementById('confirmModal').classList.remove('active');
                confirmCallback = null;
            }

            document.getElementById('confirmModalConfirm').addEventListener('click', function() {
                if (confirmCallback) {
                    confirmCallback();
                }
                closeConfirmModal();
            });

            // Close modals on overlay click
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            });

            // Close modals on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeVerificationModal();
                    closeAlertModal();
                    closeConfirmModal();
                }
            });

            // Make functions globally available
            window.openVerificationModal = openVerificationModal;
            window.closeVerificationModal = closeVerificationModal;
            window.resendVerificationCode = resendVerificationCode;
            window.showAlert = showAlert;
            window.closeAlertModal = closeAlertModal;
            window.showConfirm = showConfirm;
            window.closeConfirmModal = closeConfirmModal;
        });
    </script>
</body>
</html>