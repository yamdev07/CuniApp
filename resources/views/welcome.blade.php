<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CuniApp Élevage') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
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

            0%,
            100% {
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

        .brand-section {
            position: relative;
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

            0%,
            100% {
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

        .features-list li:nth-child(1) {
            animation-delay: 0.2s;
        }

        .features-list li:nth-child(2) {
            animation-delay: 0.4s;
        }

        .features-list li:nth-child(3) {
            animation-delay: 0.6s;
        }

        .features-list li:nth-child(4) {
            animation-delay: 0.8s;
        }

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

        .auth-section {
            position: relative;
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

        .form-input:focus+i,
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

        .password-strength-text.weak {
            color: var(--accent-red);
        }

        .password-strength-text.fair {
            color: var(--accent-orange);
        }

        .password-strength-text.good {
            color: var(--accent-cyan);
        }

        .password-strength-text.strong {
            color: var(--accent-green);
        }

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

        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* ==================== VERIFICATION MODAL ==================== */
        .verification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .verification-overlay.active {
            display: flex;
        }

        .verification-modal {
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            max-width: 450px;
            width: 90%;
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .verification-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .verification-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .verification-header h3 i {
            color: var(--primary);
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
            color: var(--gray-600);
        }

        .modal-close:hover {
            background: var(--gray-200);
            color: var(--gray-800);
        }

        .verification-body {
            padding: 24px;
        }

        .verification-message {
            text-align: center;
            color: var(--gray-600);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .verification-message strong {
            color: var(--primary);
        }

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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Illustrations */
        .steps-illustration {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out backwards;
            animation-delay: 0.2s;
        }

        .step-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-lg);
            padding: 24px 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .step-item:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .step-image-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: var(--radius-xl);
            background: var(--white);
            padding: 6px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .step-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: calc(var(--radius-xl) - 6px);
        }

        .step-item h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        .step-item p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            margin: 0;
        }

        .farmer-popout {
            position: absolute;
            bottom: -50px;
            right: -35px;
            width: 100px;
            height: 100px;
            z-index: 100;
            animation: bounceIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) 1s backwards;
            pointer-events: none;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(50px);
            }

            50% {
                opacity: 1;
                transform: scale(1.05) translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .farmer-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 6px solid var(--white);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            background: var(--white);
        }

        .farmer-badge {
            position: absolute;
            top: 5px;
            right: -25px;
            background: linear-gradient(135deg, var(--accent-orange) 0%, #d97706 100%);
            color: var(--white);
            font-weight: 700;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
            transform: rotate(12deg);
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.5);
            border: 2px solid var(--white);
        }

        @media (max-width: 968px) {
            .farmer-popout {
                display: none;
            }

            .steps-illustration {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }

            .step-item {
                padding: 16px 10px;
            }

            .step-image-wrapper {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 640px) {
            .steps-illustration {
                grid-template-columns: 1fr;
            }
        }

        /* ============================================
✅ REGISTRATION STEP STYLES - ADD THIS
============================================ */
        .form-step {
            animation: fadeInStep 0.4s ease;
        }

        @keyframes fadeInStep {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .step-indicator {
            padding: 16px;
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            margin-bottom: 24px;
        }

        .step-circle {
            transition: all 0.3s ease;
        }

        .step-progress {
            transition: width 0.3s ease;
        }

        .form-input.error {
            border-color: var(--accent-red) !important;
            background-color: rgba(239, 68, 68, 0.05) !important;
        }

        /* Hide social login on step 2 for cleaner look */
        .form-step[data-step="2"] .divider,
        .form-step[data-step="2"] .social-login {
            display: none;
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
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                    </svg>
                </div>
                <h1 class="brand-title">CuniApp <span>Élevage</span></h1>
                <p class="brand-tagline">Gestion intelligente de votre cheptel lapin. Suivez vos reproductions,
                    naissances et performances en toute simplicité.</p>
                <!-- 3 Step Illustration -->
                <div class="steps-illustration">
                    <div class="step-item">
                        <div class="step-image-wrapper">
                            <img src="{{ asset('images/step_1.png') }}" alt="Étape 1">
                        </div>
                        <h4>1. Identifier</h4>
                        <p>Suivi complet des lapins</p>
                    </div>
                    <div class="step-item">
                        <div class="step-image-wrapper">
                            <img src="{{ asset('images/step_2.png') }}" alt="Étape 2">
                        </div>
                        <h4>2. Reproduire</h4>
                        <p>Gestion des saillies</p>
                    </div>
                    <div class="step-item" style="position: relative;">
                        <div class="step-image-wrapper">
                            <img src="{{ asset('images/step_3.png') }}" alt="Étape 3">
                        </div>
                        <h4>3. Analyser</h4>
                        <p>Tableau de bord intelligent</p>

                        <!-- Rabbit Farmer Pop-out -->
                        <div class="farmer-popout">
                            <img src="{{ asset('images/rabbit_farmer.png') }}" alt="Éleveur CuniApp" class="farmer-img">
                            <div class="farmer-badge">Rejoignez-nous!</div>
                        </div>
                    </div>
                </div>
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

                        <!-- Success Message (After Verification) -->
                        @if (session('success'))
                            <div class="alert-box success">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form active" id="form-login">
                            @csrf

                            @if ($errors->has('email') || $errors->has('password'))
                                <div class="alert-box error">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div>
                                        <strong>Erreurs de connexion</strong>
                                        <ul class="validation-summary-list">
                                            @foreach ($errors->all() as $error)
                                                <li>
                                                    <i class="bi bi-x-circle-fill"></i>
                                                    <span>
                                                        @if ($error === 'auth.failed' || str_contains($error, 'auth.failed'))
                                                            Ces identifiants ne correspondent pas à nos enregistrements.
                                                            Veuillez vérifier votre email et mot de passe.
                                                        @elseif(str_contains($error, 'throttle'))
                                                            Trop de tentatives de connexion. Veuillez réessayer plus
                                                            tard.
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
                                    <input type="email" name="email"
                                        class="form-input @error('email') error @enderror" placeholder="votre@email.com"
                                        required autofocus value="{{ old('email') }}" id="loginEmail">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                    <div class="validation-message error">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>
                                            @if (str_contains($message, 'validation.'))
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
                                    <input type="password" name="password"
                                        class="form-input @error('password') error @enderror" placeholder="••••••••"
                                        required id="loginPassword">
                                    <i class="bi bi-lock"></i>
                                    <i class="bi bi-eye toggle-password" data-target="loginPassword" style="left: auto; right: 14px; cursor: pointer; pointer-events: auto; z-index: 5;"></i>
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
                                    <a class="forgot-password" href="{{ route('password.request') }}">Mot de passe
                                        oublié ?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn-submit">
                                <span>Se connecter</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <div class="divider"><span>ou continuer avec</span></div>

                            <div class="social-login">
                                <button type="button"
                                    onclick="window.location='{{ route('social-login.google.redirect') }}'"
                                    class="btn-social google">
                                    <i class="bi bi-google"></i>
                                    <span>Google</span>
                                </button>
                            </div>
                        </form>

                        <!-- Register Form (2-Step Process) -->
                        <form method="POST" action="{{ route('register') }}" class="auth-form" id="form-register">
                            @csrf

                            @if ($errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('password_confirmation'))
                                <div class="alert-box error">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div>
                                        <strong>Erreurs de validation</strong>
                                        <ul class="validation-summary-list">
                                            @foreach ($errors->all() as $error)
                                                <li>
                                                    <i class="bi bi-x-circle-fill"></i>
                                                    <span>
                                                        @if (str_contains($error, 'validation.unique'))
                                                            Cette adresse email est déjà utilisée. Veuillez en choisir
                                                            une autre.
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

                            <!-- ✅ STEP INDICATOR -->
                            <div class="step-indicator"
                                style="display: flex; align-items: center; gap: 12px; margin-bottom: 32px;">
                                <div class="step" data-step="1"
                                    style="display: flex; align-items: center; gap: 8px;">
                                    <div class="step-circle active"
                                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                        1</div>
                                    <span
                                        style="font-size: 13px; font-weight: 500; color: var(--text-primary);">Compte</span>
                                </div>
                                <div style="flex: 1; height: 2px; background: var(--gray-200);">
                                    <div class="step-progress"
                                        style="height: 100%; width: 0%; background: var(--primary); transition: width 0.3s ease;">
                                    </div>
                                </div>
                                <div class="step" data-step="2"
                                    style="display: flex; align-items: center; gap: 8px;">
                                    <div class="step-circle"
                                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--gray-200); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                        2</div>
                                    <span
                                        style="font-size: 13px; font-weight: 500; color: var(--text-secondary);">Entreprise</span>
                                </div>
                            </div>

                            <!-- ✅ STEP 1: USER BIO DATA -->
                            <div class="form-step" data-step="1" style="display: block;">
                                <h2 class="form-title">Créer votre compte</h2>
                                <p class="form-subtitle">Commençons par vos informations personnelles</p>

                                <div class="form-group">
                                    <label class="form-label">Nom complet *</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" name="name" class="form-input step1-required"
                                            placeholder="Jean Dupont" required autofocus value="{{ old('name') }}"
                                            id="registerName" minlength="2" maxlength="50">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="char-counter" id="nameCharCounter">0/50</div>
                                    @error('name')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Adresse email *</label>
                                    <div class="form-input-wrapper">
                                        <input type="email" name="email" class="form-input step1-required"
                                            placeholder="votre@email.com" required value="{{ old('email') }}"
                                            id="registerEmail" autocomplete="off">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    @error('email')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Mot de passe *</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" name="password" class="form-input step1-required"
                                            placeholder="••••••••" required id="registerPassword" minlength="8"
                                            autocomplete="new-password">
                                        <i class="bi bi-lock"></i>
                                        <i class="bi bi-eye toggle-password" data-target="registerPassword" style="left: auto; right: 14px; cursor: pointer; pointer-events: auto; z-index: 5;"></i>
                                    </div>
                                    <div class="password-strength-container">
                                        <div class="password-strength-bar">
                                            <div class="password-strength-fill" id="passwordStrengthFill"></div>
                                        </div>
                                        <div class="password-strength-text" id="passwordStrengthText">Faible</div>
                                        
                                        <div class="password-requirements">
                                            <div class="password-requirements-title">
                                                <i class="bi bi-shield-lock" style="font-size: 14px; position: static; transform: none; display: inline; color: inherit;"></i>
                                                Critères du mot de passe
                                            </div>
                                            <div class="password-requirement" id="req-length">
                                                <i class="bi bi-x-circle" style="position: static; transform: none; display: inline;"></i>
                                                <span>Au moins 8 caractères</span>
                                            </div>
                                            <div class="password-requirement" id="req-upper">
                                                <i class="bi bi-x-circle" style="position: static; transform: none; display: inline;"></i>
                                                <span>Une majuscule</span>
                                            </div>
                                            <div class="password-requirement" id="req-lower">
                                                <i class="bi bi-x-circle" style="position: static; transform: none; display: inline;"></i>
                                                <span>Une minuscule</span>
                                            </div>
                                            <div class="password-requirement" id="req-number">
                                                <i class="bi bi-x-circle" style="position: static; transform: none; display: inline;"></i>
                                                <span>Un chiffre</span>
                                            </div>
                                            <div class="password-requirement" id="req-special">
                                                <i class="bi bi-x-circle" style="position: static; transform: none; display: inline;"></i>
                                                <span>Un caractère spécial (!@#$...)</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Confirmer le mot de passe *</label>
                                    <div class="form-input-wrapper">
                                        <input type="password" name="password_confirmation"
                                            class="form-input step1-required" placeholder="••••••••" required
                                            id="passwordConfirmation">
                                        <i class="bi bi-lock-fill"></i>
                                        <i class="bi bi-eye toggle-password" data-target="passwordConfirmation" style="left: auto; right: 14px; cursor: pointer; pointer-events: auto; z-index: 5;"></i>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Step 1 Navigation -->
                                <div style="display: flex; gap: 12px; margin-top: 32px;">
                                    <button type="button" class="btn-submit" onclick="nextStep(2)"
                                        style="flex: 1;">
                                        <span>Suivant</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ✅ STEP 2: FIRM DATA -->
                            <div class="form-step" data-step="2" style="display: none;">
                                <h2 class="form-title">Informations de l'Entreprise</h2>
                                <p class="form-subtitle">Configurez votre espace de travail</p>

                                <div class="form-group">
                                    <label class="form-label">Nom de l'entreprise *</label>
                                    <div class="form-input-wrapper">
                                        <input type="text" name="firm_name" class="form-input step2-required"
                                            placeholder="Ex: Ferme Lapin d'Or" required
                                            value="{{ old('firm_name') }}">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    @error('firm_name')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Description (optionnel)</label>
                                    <div class="form-input-wrapper">
                                        <textarea name="firm_description" class="form-input" placeholder="Décrivez votre entreprise..." rows="3">{{ old('firm_description') }}</textarea>
                                        <i class="bi bi-card-text"></i>
                                    </div>
                                    <div class="char-counter" id="descriptionCharCounter">0/1000</div>
                                    @error('firm_description')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                                        <input type="checkbox" name="terms" required
                                            style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                                        <span style="font-size: 13px; color: var(--gray-600);">
                                            J'accepte les <a href="#" style="color: var(--primary);">Conditions
                                                d'utilisation</a> et la <a href="#"
                                                style="color: var(--primary);">Politique de confidentialité</a>
                                        </span>
                                    </label>
                                    @error('terms')
                                        <div class="validation-message error">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Step 2 Navigation -->
                                <div style="display: flex; gap: 12px; margin-top: 32px;">
                                    <button type="button" class="btn-submit" onclick="previousStep(1)"
                                        style="flex: 1; background: var(--gray-200); color: var(--text-primary);">
                                        <i class="bi bi-arrow-left"></i>
                                        <span>Retour</span>
                                    </button>
                                    <button type="submit" class="btn-submit" style="flex: 1;">
                                        <span>Créer mon compte</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="divider"><span>ou s'inscrire avec</span></div>
                            <div class="social-login">
                                <button type="button"
                                    onclick="window.location='{{ route('social-login.google.redirect') }}'"
                                    class="btn-social google">
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

    <!-- ==================== VERIFICATION MODAL ==================== -->
    @if (session('verification_pending'))
        <div class="verification-overlay active" id="verificationOverlay" style="display: flex;">
            <div class="verification-modal">
                <div class="verification-header">
                    <h3>
                        <i class="bi bi-shield-check"></i>
                        Vérification Email
                    </h3>
                    <button type="button" class="modal-close" onclick="closeVerificationModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="verification-body">
                    <p class="verification-message">
                        Un code de vérification a été envoyé à<br>
                        <strong id="verificationEmailDisplay">{{ session('verification_email') }}</strong>
                    </p>
                    <form id="verificationForm" method="POST" action="{{ route('verification.code.verify') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('verification_email') }}">
                        <div class="verification-code-inputs">
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="0" required autofocus>
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="1" required>
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="2" required>
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="3" required>
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="4" required>
                            <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]"
                                inputmode="numeric" data-index="5" required>
                        </div>
                        <input type="hidden" name="code" id="verificationCodeInput">
                        <button type="submit" class="btn-submit">
                            <span>Vérifier</span>
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>
                    <div class="verification-info">
                        <p>Vous n'avez pas reçu le code ? <a href="#" id="resendCode"
                                onclick="resendVerificationCode(event)">Renvoyer</a></p>
                    </div>
                    <div class="resend-timer disabled" id="resendTimer">
                        Renvoyer dans <span id="timerCount">60</span>s
                    </div>
                    @if ($errors->has('code'))
                        <div class="alert-box error" style="margin-top: 16px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $errors->first('code') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ✅ FORCE MODAL TO STAY OPEN WITH JAVASCRIPT --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('verificationOverlay');
                if (overlay && {{ session('verification_pending') ? 'true' : 'false' }}) {
                    // Force display
                    overlay.style.display = 'flex';
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling

                    // Focus first input after short delay
                    setTimeout(() => {
                        const firstInput = document.querySelector('.verification-code-input');
                        if (firstInput) firstInput.focus();
                    }, 500);

                    // Start resend timer
                    startResendTimer();
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Welcome page loaded successfully');

            // ==================== NETWORK STATUS ====================
            function updateNetworkStatus() {
                const networkStatus = document.getElementById('networkStatus');
                if (navigator.onLine) {
                    networkStatus.style.display = 'none';
                } else {
                    networkStatus.style.display = 'flex';
                }
            }

            window.addEventListener('online', updateNetworkStatus);
            window.addEventListener('offline', updateNetworkStatus);
            updateNetworkStatus();

            // Auto-hide validation errors after 8 seconds
            setTimeout(() => {
                const hideElements = (selector) => {
                    document.querySelectorAll(selector).forEach(el => {
                        el.style.transition = 'opacity 0.5s ease';
                        el.style.opacity = '0';
                        setTimeout(() => el.style.display = 'none', 500);
                    });
                };
                hideElements('.alert-box.error');
                hideElements('.validation-message.error');
                document.querySelectorAll('.form-input.error').forEach(el => el.classList.remove('error'));
            }, 8000);

            // ==================== TAB SWITCHING ====================
            function switchTab(tabName) {
                const tabs = document.querySelectorAll('.auth-tab');
                const forms = document.querySelectorAll('.auth-form');

                tabs.forEach(tab => tab.classList.remove('active'));
                forms.forEach(form => form.classList.remove('active'));

                const selectedTab = document.querySelector(`.auth-tab[data-tab="${tabName}"]`);
                const selectedForm = document.querySelector(`#form-${tabName}`);

                if (selectedTab) selectedTab.classList.add('active');
                if (selectedForm) selectedForm.classList.add('active');

                sessionStorage.setItem('cuniapp_current_tab', tabName);

                // Clear validation errors when switching tabs
                document.querySelectorAll('.alert-box.error').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.validation-message.error').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.form-input.error').forEach(el => el.classList.remove('error'));
            }

            const tabs = document.querySelectorAll('.auth-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    switchTab(this.getAttribute('data-tab'));
                });
            });

            // Restore saved tab
            @if ($errors->has('email') || $errors->has('password'))
                switchTab('login');
            @elseif ($errors->has('name') || $errors->has('password_confirmation'))
                switchTab('register');
            @else
                const savedTab = sessionStorage.getItem('cuniapp_current_tab');
                if (savedTab) switchTab(savedTab);
            @endif

            // ==================== PASSWORD STRENGTH ====================
            const registerPassword = document.getElementById('registerPassword');
            const passwordStrengthFill = document.getElementById('passwordStrengthFill');
            const passwordStrengthText = document.getElementById('passwordStrengthText');

            if (registerPassword) {
                registerPassword.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    updatePasswordStrengthUI(strength);
                    validatePasswordCriteria(password);
                });
            }

            function calculatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
                return score;
            }

            function updatePasswordStrengthUI(strength) {
                if (!passwordStrengthFill || !passwordStrengthText) return;

                const classes = ['weak', 'fair', 'good', 'strong'];
                const labels = ['Faible', 'Moyen', 'Bon', 'Excellent'];
                const widths = ['25%', '50%', '75%', '100%'];

                const index = Math.min(strength - 1, 3);
                if (index >= 0) {
                    passwordStrengthFill.className = 'password-strength-fill ' + classes[index];
                    passwordStrengthFill.style.width = widths[index];
                    passwordStrengthText.className = 'password-strength-text ' + classes[index];
                    passwordStrengthText.textContent = labels[index];
                }
            }

            function validatePasswordCriteria(password) {
                const criteria = [
                    { id: 'req-length', regex: /.{8,}/ },
                    { id: 'req-upper', regex: /[A-Z]/ },
                    { id: 'req-lower', regex: /[a-z]/ },
                    { id: 'req-number', regex: /[0-9]/ },
                    { id: 'req-special', regex: /[!@#$%^&*(),.?":{}|<>]/ }
                ];

                criteria.forEach(c => {
                    const el = document.getElementById(c.id);
                    if (el) {
                        const isValid = c.regex.test(password);
                        const icon = el.querySelector('i');
                        if (isValid) {
                            el.classList.add('met');
                            icon.className = 'bi bi-check-circle-fill';
                            icon.style.color = 'var(--accent-green)';
                        } else {
                            el.classList.remove('met');
                            icon.className = 'bi bi-x-circle';
                            icon.style.color = 'var(--gray-400)';
                        }
                    }
                });
            }

            // ==================== TOGGLE PASSWORD VISIBILITY ====================
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', function() {
                    const inputId = this.getAttribute('data-target');
                    const input = document.getElementById(inputId);
                    if (input) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            this.classList.remove('bi-eye');
                            this.classList.add('bi-eye-slash');
                        } else {
                            input.type = 'password';
                            this.classList.remove('bi-eye-slash');
                            this.classList.add('bi-eye');
                        }
                    }
                });
            });

            // ==================== FORM LOADING STATE ====================
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.querySelector('span').textContent = 'Chargement...';
                    }
                });
            });

            // ==================== VERIFICATION MODAL ====================
            let resendTimerInterval;

            window.closeVerificationModal = function() {
                const overlay = document.getElementById('verificationOverlay');
                if (overlay) {
                    overlay.classList.remove('active');
                    setTimeout(() => {
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                    }, 300);
                }
            };

            // Verification Code Input Handling
            document.querySelectorAll('.verification-code-input').forEach((input, index, inputs) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1) {
                        this.classList.add('filled');
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    updateVerificationCode();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        inputs[index - 1].classList.remove('filled');
                        updateVerificationCode();
                    }
                });
            });

            function updateVerificationCode() {
                let code = '';
                document.querySelectorAll('.verification-code-input').forEach(input => {
                    code += input.value;
                });
                const codeInput = document.getElementById('verificationCodeInput');
                if (codeInput) codeInput.value = code;
            }

            function startResendTimer() {
                const timerElement = document.getElementById('resendTimer');
                const timerCount = document.getElementById('timerCount');
                const resendLink = document.getElementById('resendCode');

                if (!timerElement || !timerCount || !resendLink) return;

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

            window.resendVerificationCode = function(e) {
                e.preventDefault();
                const emailDisplay = document.getElementById('verificationEmailDisplay');
                if (!emailDisplay) return;

                const email = emailDisplay.textContent;
                fetch('{{ route('verification.code.resend') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Un nouveau code a été envoyé à votre adresse email.');
                            startResendTimer();
                        } else {
                            alert('Une erreur est survenue. Veuillez réessayer.');
                        }
                    })
                    .catch(error => {
                        alert('Une erreur est survenue. Veuillez réessayer.');
                    });
            };

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeVerificationModal();
                }
            });

            // Start timer if verification pending
            @if (session('verification_pending'))
                startResendTimer();
                setTimeout(() => {
                    const firstInput = document.querySelector('.verification-code-input');
                    if (firstInput) firstInput.focus();
                }, 500);
            @endif

            // ==================== REGISTRATION STEP NAVIGATION ====================
            let currentStep = 1;

            window.nextStep = function(step) {
                if (!validateStep(currentStep)) {
                    return;
                }

                const currentStepEl = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                const nextStepEl = document.querySelector(`.form-step[data-step="${step}"]`);

                if (currentStepEl) currentStepEl.style.display = 'none';
                if (nextStepEl) nextStepEl.style.display = 'block';

                updateStepIndicator(step);
                currentStep = step;

                const form = document.getElementById('form-register');
                if (form) {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            };

            window.previousStep = function(step) {
                const currentStepEl = document.querySelector(`.form-step[data-step="${currentStep}"]`);
                const prevStepEl = document.querySelector(`.form-step[data-step="${step}"]`);

                if (currentStepEl) currentStepEl.style.display = 'none';
                if (prevStepEl) prevStepEl.style.display = 'block';

                updateStepIndicator(step);
                currentStep = step;
            };

            function updateStepIndicator(step) {
                const circles = document.querySelectorAll('.step-circle');
                const progress = document.querySelector('.step-progress');
                const stepTexts = document.querySelectorAll('.step span');

                circles.forEach((circle, index) => {
                    if (index < step) {
                        circle.style.background = 'var(--primary)';
                        circle.style.color = 'white';
                    } else {
                        circle.style.background = 'var(--gray-200)';
                        circle.style.color = 'var(--text-secondary)';
                    }
                });

                stepTexts.forEach((text, index) => {
                    if (index < step) {
                        text.style.color = 'var(--text-primary)';
                        text.style.fontWeight = '600';
                    } else {
                        text.style.color = 'var(--text-secondary)';
                        text.style.fontWeight = '500';
                    }
                });

                if (progress) {
                    progress.style.width = step === 1 ? '0%' : '100%';
                }
            }

            function validateStep(step) {
                let isValid = true;
                const requiredInputs = document.querySelectorAll(`.step${step}-required`);

                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');

                        const wrapper = input.closest('.form-group');
                        let errorMsg = wrapper.querySelector('.validation-message.error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.className = 'validation-message error';
                            errorMsg.innerHTML =
                                '<i class="bi bi-exclamation-circle-fill"></i><span>Ce champ est obligatoire</span>';
                            wrapper.appendChild(errorMsg);
                        }
                        errorMsg.style.display = 'flex';
                    } else {
                        input.classList.remove('error');
                        const wrapper = input.closest('.form-group');
                        const errorMsg = wrapper.querySelector('.validation-message.error');
                        if (errorMsg) errorMsg.style.display = 'none';
                    }
                });

                // Prevent moving to step 2 if password criteria are not met
                if (step === 1 && isValid) {
                    const password = document.getElementById('registerPassword').value;
                    const criteria = [/.{8,}/, /[A-Z]/, /[a-z]/, /[0-9]/, /[!@#$%^&*(),.?":{}|<>]/];
                    const passwordValid = criteria.every(regex => regex.test(password));
                    
                    if (!passwordValid) {
                        isValid = false;
                        showToast('⚠️ Le mot de passe ne respecte pas tous les critères requis', 'error');
                        document.getElementById('registerPassword').classList.add('error');
                    }
                }

                if (!isValid && step !== 1) {
                    showToast('⚠️ Veuillez remplir tous les champs obligatoires', 'error');
                } else if (!isValid && step === 1 && document.getElementById('registerPassword').value.trim() === '') {
                    showToast('⚠️ Veuillez remplir tous les champs obligatoires', 'error');
                }

                return isValid;
            }

            // Clear error on input
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('error');
                        const wrapper = this.closest('.form-group');
                        const errorMsg = wrapper.querySelector('.validation-message.error');
                        if (errorMsg) errorMsg.style.display = 'none';
                    }
                });
            });

            // Toast notification helper
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.style.cssText = `
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)'};
            padding: 16px 24px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease;
        `;

                const icon = type === 'success' ? 'check-circle-fill' : 'x-circle-fill';
                const color = type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)';

                toast.innerHTML = `
            <i class="bi bi-${icon}" style="color: ${color}; font-size: 20px;"></i>
            <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
        `;

                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Add animation styles
            if (!document.getElementById('cuniapp-animations-style')) {
                const style = document.createElement('style');
                style.id = 'cuniapp-animations-style';
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
            }
        });
    </script>
</body>

</html>
