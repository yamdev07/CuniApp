<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CuniApp') }} - {{ __('Gestion intelligente de votre cheptel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <meta name="description" content="{{ __('La solution complète pour la gestion intelligente de votre élevage de lapins.') }}">
    <meta property="og:title" content="CuniApp {{ __('Élevage') }}">
    <meta property="og:description" content="{{ __('La solution complète pour la gestion intelligente de votre élevage de lapins.') }}">
    <meta property="og:image" content="{{ asset('images/thumbnail.png') }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="CuniApp {{ __('Élevage') }}">
    <meta name="twitter:description" content="{{ __('La solution complète pour la gestion intelligente de votre élevage de lapins.') }}">
    <meta name="twitter:image" content="{{ asset('images/thumbnail.png') }}">
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
        }
        .theme-dark {
            --surface: #0A0F1D; --surface-alt: #0F172A; --surface-elevated: #151E30; --surface-overlay: #1E293B;
            --surface-border: #25324A; --text-primary: #E6E9F0; --text-secondary: #A3B3C6; --text-tertiary: #6B7D95;
            --primary: #4DA6FF; --primary-subtle: rgba(77,166,255,0.12); --accent-green: #34D399;
            --accent-orange: #FB923C; --accent-red: #F87171; --gray-50: #080C15; --gray-100: #0F172A;
            --gray-200: #1A2335; --gray-300: #25324A; --gray-400: #4A5568; --gray-500: #718096;
            --gray-600: #A0AEC0; --gray-700: #CBD5E0; --gray-800: #E2E8F0; --gray-900: #F7FAFC;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif; background:var(--gray-50); color:var(--text-primary); line-height:1.6; overflow-x:hidden; }
        .theme-dark body, .theme-dark { background:var(--gray-50); color:var(--text-primary); }

        /* Animations */
        @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-20px) rotate(2deg)} }
        @keyframes bounceSubtle { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
        @keyframes slideShow { 0%{opacity:0;transform:scale(1.05)} 8%{opacity:1;transform:scale(1)} 25%{opacity:1;transform:scale(1)} 33%{opacity:0;transform:scale(0.98)} 100%{opacity:0;transform:scale(0.98)} }
        @keyframes glowPulse { 0%,100%{opacity:0.3} 50%{opacity:0.8} }
        .animate-float { animation:float 6s ease-in-out infinite; }
        .animate-float-delayed { animation:float 6s ease-in-out infinite; animation-delay:-3s; }
        .animate-bounce-subtle { animation:bounceSubtle 2s ease-in-out infinite; }

        /* Navbar */
        .landing-nav { position:sticky; top:0; z-index:50; transition:all 0.5s ease; }
        .landing-nav.scrolled { background:var(--surface); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border-bottom:1px solid var(--surface-border); box-shadow:0 4px 30px rgba(37,99,235,0.06); }
        .nav-container { max-width:1280px; margin:0 auto; padding:0 24px; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .nav-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .nav-logo { width:40px; height:40px; background:linear-gradient(135deg,var(--primary),var(--primary-dark)); border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
        .nav-logo svg { width:22px; height:22px; }
        .nav-brand-text { font-size:20px; font-weight:700; color:var(--text-primary); letter-spacing:-0.02em; }
        .nav-links { display:flex; align-items:center; gap:4px; }
        .nav-link { font-size:14px; font-weight:500; color:var(--text-secondary); text-decoration:none; padding:8px 16px; border-radius:var(--radius); transition:all 0.3s ease; }
        .nav-link:hover { color:var(--primary); background:var(--primary-subtle); }
        .nav-actions { display:flex; align-items:center; gap:12px; }
        .btn-nav-login { font-size:14px; font-weight:500; color:var(--text-secondary); text-decoration:none; padding:8px 12px; transition:color 0.3s ease; }
        .btn-nav-login:hover { color:var(--primary); }
        .btn-nav-cta { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; font-size:14px; font-weight:600; color:var(--white); background:linear-gradient(135deg,var(--primary),var(--primary-dark)); border:none; border-radius:var(--radius); text-decoration:none; cursor:pointer; transition:all 0.3s ease; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
        .btn-nav-cta:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(37,99,235,0.4); }
        @media(max-width:768px) { .nav-links{display:none;} }

        /* Hero */
        .hero-section { position:relative; overflow:hidden; isolation:isolate; }
        .hero-bg { position:fixed; inset:0; z-index:-10; }
        .hero-bg-gradient { width:100%; height:100%; background:linear-gradient(135deg,rgba(37,99,235,0.08),rgba(6,182,212,0.05) 50%,rgba(59,130,246,0.08)); }
        .theme-dark .hero-bg-gradient { background:linear-gradient(135deg,rgba(37,99,235,0.15),rgba(6,182,212,0.08) 50%,rgba(59,130,246,0.12)); }
        .hero-bg-overlay { position:absolute; inset:0; background:linear-gradient(135deg,rgba(37,99,235,0.12),transparent 50%,rgba(6,182,212,0.08)); }
        .hero-particles { position:absolute; inset:0; overflow:hidden; pointer-events:none; }
        .particle { position:absolute; border-radius:50%; opacity:0.15; pointer-events:none; }
        .hero-svg { position:absolute; inset:0; width:100%; height:100%; pointer-events:none; opacity:0.08; }
        .theme-dark .hero-svg { opacity:0.14; }
        .hero-content { max-width:1280px; margin:0 auto; padding:80px 24px 120px; display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center; }
        @media(max-width:1024px) { .hero-content{grid-template-columns:1fr;padding:60px 24px 80px;text-align:center;} }
        .hero-left { transition:all 0.7s ease; transition-delay:200ms; }
        .hero-badge { display:inline-flex; align-items:center; gap:8px; background:var(--primary-subtle); border:1px solid rgba(37,99,235,0.2); border-radius:100px; padding:8px 16px; margin-bottom:24px; backdrop-filter:blur(10px); }
        .hero-badge-dot { width:8px; height:8px; background:var(--primary); border-radius:50%; animation:pulse 2s infinite; }
        .hero-badge-text { font-size:12px; font-weight:600; color:var(--primary); }
        .hero-title { font-size:56px; font-weight:700; color:var(--text-primary); line-height:1.1; letter-spacing:-0.02em; margin-bottom:20px; }
        @media(max-width:768px) { .hero-title{font-size:36px;} }
        .hero-gradient-text { background:linear-gradient(135deg,var(--primary),var(--accent-cyan)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .hero-description { font-size:18px; color:var(--text-secondary); max-width:520px; line-height:1.7; margin-bottom:32px; }
        @media(max-width:1024px) { .hero-description{margin:0 auto 32px;} }
        .hero-buttons { display:flex; flex-wrap:wrap; gap:16px; }
        @media(max-width:1024px) { .hero-buttons{justify-content:center;} }
        .btn-hero-primary { display:inline-flex; align-items:center; gap:8px; padding:16px 28px; font-size:16px; font-weight:600; color:var(--white); background:linear-gradient(135deg,var(--primary),var(--primary-dark)); border:none; border-radius:var(--radius-lg); text-decoration:none; cursor:pointer; transition:all 0.3s ease; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
        .btn-hero-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(37,99,235,0.4); }
        .btn-hero-primary i { transition:transform 0.3s ease; }
        .btn-hero-primary:hover i { transform:translateX(4px); }
        .btn-hero-secondary { display:inline-flex; align-items:center; gap:8px; padding:16px 28px; font-size:16px; font-weight:600; color:var(--gray-700); background:transparent; border:2px solid var(--gray-200); border-radius:var(--radius-lg); text-decoration:none; cursor:pointer; transition:all 0.3s ease; }
        .theme-dark .btn-hero-secondary { color:var(--gray-700); border-color:var(--gray-200); }
        .btn-hero-secondary:hover { border-color:var(--primary); color:var(--primary); background:var(--primary-subtle); }
        .hero-trust { display:flex; align-items:center; gap:24px; margin-top:32px; }
        @media(max-width:1024px) { .hero-trust{justify-content:center;} }
        .hero-trust-item { display:flex; align-items:center; gap:8px; font-size:14px; color:var(--text-secondary); }
        .hero-trust-item i { font-size:16px; }

        /* Hero Right - Slideshow */
        .hero-right { position:relative; transition:all 0.7s ease; transition-delay:500ms; }
        .hero-illustration { position:relative; }
        .hero-illustration-bg { position:absolute; top:-40px; right:-40px; width:160px; height:160px; background:rgba(37,99,235,0.1); border-radius:50%; filter:blur(60px); }
        .hero-illustration-bg-2 { position:absolute; bottom:-40px; left:-40px; width:192px; height:192px; background:rgba(59,130,246,0.1); border-radius:50%; filter:blur(60px); }
        .hero-card { position:relative; background:var(--surface); border:1px solid var(--surface-border); border-radius:var(--radius-2xl); padding:8px; backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); box-shadow:0 12px 48px rgba(0,0,0,0.1),0 4px 16px rgba(37,99,235,0.08); overflow:hidden; }
        .theme-dark .hero-card { background:var(--surface-alt); border-color:var(--surface-border); }
        .hero-slideshow { position:relative; width:100%; aspect-ratio:4/3; border-radius:var(--radius-xl); overflow:hidden; }
        .hero-slideshow img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0; transition:opacity 1s ease-in-out; }
        .hero-slideshow img.active { opacity:1; }
        .hero-slideshow-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(0,0,0,0.4),transparent 40%); z-index:1; }
        .hero-slideshow-text { position:absolute; bottom:20px; left:20px; right:20px; z-index:2; color:white; }
        .hero-slideshow-text h3 { font-size:18px; font-weight:700; margin-bottom:4px; text-shadow:0 2px 8px rgba(0,0,0,0.3); }
        .hero-slideshow-text p { font-size:13px; opacity:0.9; text-shadow:0 1px 4px rgba(0,0,0,0.3); }
        .hero-float-stat { position:absolute; backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border-radius:var(--radius-lg); padding:16px; display:flex; align-items:center; gap:12px; border:1px solid var(--surface-border); background:var(--surface); box-shadow:0 12px 48px rgba(0,0,0,0.1); }
        .hero-float-stat-left { left:-48px; top:25%; }
        .hero-float-stat-right { right:-32px; bottom:25%; }
        @media(max-width:1024px) { .hero-float-stat{display:none;} }
        .float-stat-icon { width:40px; height:40px; border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; }
        .float-stat-icon.green { background:rgba(16,185,129,0.15); }
        .float-stat-icon.green i { color:var(--accent-green); font-size:20px; }
        .float-stat-icon.blue { background:rgba(37,99,235,0.15); }
        .float-stat-icon.blue i { color:var(--primary); font-size:20px; }
        .float-stat-label { font-size:12px; color:var(--text-tertiary); }
        .float-stat-value { font-size:18px; font-weight:700; color:var(--text-primary); }

        /* Section Divider */
        .section-divider { display:flex; align-items:center; justify-content:center; padding:8px 0; }
        .section-divider-line { width:64px; height:1px; background:linear-gradient(to right,transparent,rgba(37,99,235,0.2),transparent); }

        /* Features */
        .features-section { position:relative; max-width:1280px; margin:0 auto; padding:96px 24px; border-radius:var(--radius-2xl); overflow:hidden; background:linear-gradient(135deg,rgba(37,99,235,0.04),var(--surface-alt) 100%); }
        .theme-dark .features-section { background:linear-gradient(135deg,rgba(37,99,235,0.08),var(--surface-alt) 100%); }
        @media(max-width:1024px) { .features-section{margin:0 24px;} }
        .section-header { text-align:center; margin-bottom:64px; position:relative; }
        .section-badge { font-size:12px; font-weight:600; color:var(--primary); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:12px; display:block; }
        .section-title { font-size:36px; font-weight:700; color:var(--text-primary); margin-bottom:16px; }
        @media(max-width:768px) { .section-title{font-size:28px;} }
        .section-description { font-size:16px; color:var(--text-secondary); max-width:640px; margin:0 auto; }
        .features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; position:relative; }
        @media(max-width:1024px) { .features-grid{grid-template-columns:repeat(2,1fr);} }
        @media(max-width:640px) { .features-grid{grid-template-columns:1fr;} }
        .feature-card { background:var(--surface); border:1px solid var(--surface-border); border-radius:var(--radius-lg); padding:24px; transition:all 0.3s ease; }
        .feature-card:hover { transform:translateY(-4px); box-shadow:0 12px 48px rgba(0,0,0,0.1); border-color:rgba(37,99,235,0.15); }
        .feature-icon { width:48px; height:48px; border-radius:var(--radius-md); background:var(--primary-subtle); display:flex; align-items:center; justify-content:center; margin-bottom:16px; transition:background 0.3s ease; }
        .feature-card:hover .feature-icon { background:rgba(37,99,235,0.15); }
        .feature-icon i { font-size:24px; color:var(--primary); }
        .feature-title { font-size:18px; font-weight:600; color:var(--text-primary); margin-bottom:8px; }
        .feature-description { font-size:14px; color:var(--text-secondary); line-height:1.6; }

        /* Pricing */
        .pricing-section { position:relative; max-width:1280px; margin:0 auto; padding:96px 24px; border-radius:var(--radius-2xl); overflow:hidden; background:linear-gradient(135deg,var(--surface),rgba(37,99,235,0.05)); }
        .theme-dark .pricing-section { background:linear-gradient(135deg,var(--surface-alt),rgba(37,99,235,0.08)); }
        @media(max-width:1024px) { .pricing-section{margin:0 24px;} }
        .pricing-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:20px; position:relative; }
        @media(max-width:1280px) { .pricing-grid{grid-template-columns:repeat(3,1fr);} }
        @media(max-width:768px) { .pricing-grid{grid-template-columns:1fr;max-width:400px;margin:0 auto;} }
        .pricing-card { border-radius:var(--radius-lg); padding:24px; display:flex; flex-direction:column; transition:all 0.3s ease; position:relative; }
        .pricing-card:hover { transform:translateY(-4px); }
        .pricing-card.popular { border:2px solid var(--primary); background:var(--primary-subtle); box-shadow:0 12px 48px rgba(0,0,0,0.1); }
        .pricing-card:not(.popular) { border:1px solid var(--surface-border); background:var(--surface); box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        .pricing-card:not(.popular):hover { box-shadow:0 12px 48px rgba(0,0,0,0.1); border-color:rgba(37,99,235,0.15); }
        .pricing-popular-badge { position:absolute; top:-12px; left:50%; transform:translateX(-50%); background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white; font-size:12px; font-weight:700; padding:4px 16px; border-radius:100px; display:flex; align-items:center; gap:4px; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
        .pricing-popular-badge i { font-size:12px; }
        .pricing-name { font-size:16px; font-weight:700; margin-bottom:12px; }
        .pricing-card.popular .pricing-name { color:var(--primary); }
        .pricing-card:not(.popular) .pricing-name { color:var(--text-secondary); }
        .pricing-price { margin-bottom:20px; }
        .pricing-amount { font-size:32px; font-weight:700; color:var(--text-primary); }
        .pricing-period { font-size:14px; color:var(--text-tertiary); margin-left:4px; }
        .pricing-features { list-style:none; padding:0; margin:0 0 24px 0; flex:1; }
        .pricing-feature { display:flex; align-items:flex-start; gap:8px; padding:8px 0; font-size:14px; color:var(--text-secondary); }
        .pricing-feature i { color:rgba(37,99,235,0.6); font-size:15px; flex-shrink:0; margin-top:2px; }
        .pricing-cta { display:block; text-align:center; padding:10px 0; border-radius:var(--radius-md); font-size:14px; font-weight:600; text-decoration:none; transition:all 0.3s ease; cursor:pointer; border:none; width:100%; }
        .pricing-card.popular .pricing-cta { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white; }
        .pricing-card.popular .pricing-cta:hover { box-shadow:0 8px 20px rgba(37,99,235,0.4); transform:scale(1.02); }
        .pricing-card:not(.popular) .pricing-cta { background:var(--surface-alt); border:1px solid var(--surface-border); color:var(--gray-700); }
        .theme-dark .pricing-card:not(.popular) .pricing-cta { color:var(--text-secondary); }
        .pricing-card:not(.popular) .pricing-cta:hover { background:var(--primary-subtle); border-color:rgba(37,99,235,0.3); color:var(--primary); }

        /* CTA */
        .cta-section { max-width:1280px; margin:0 auto; padding:0 24px 96px; }
        .cta-card { position:relative; background:linear-gradient(135deg,var(--primary),var(--primary-dark) 50%,var(--primary-light)); border-radius:var(--radius-2xl); padding:64px; overflow:hidden; }
        @media(max-width:768px) { .cta-card{padding:48px 24px;} }
        .cta-bg-blob-1 { position:absolute; top:0; right:0; width:288px; height:288px; background:rgba(255,255,255,0.1); border-radius:50%; filter:blur(60px); transform:translateY(-50%) translateX(33%); }
        .cta-bg-blob-2 { position:absolute; bottom:0; left:0; width:224px; height:224px; background:rgba(255,255,255,0.1); border-radius:50%; filter:blur(60px); transform:translateY(50%) translateX(-33%); }
        .cta-content { position:relative; z-index:10; text-align:center; }
        .cta-title { font-size:36px; font-weight:700; color:white; margin-bottom:16px; }
        @media(max-width:768px) { .cta-title{font-size:28px;} }
        .cta-description { font-size:16px; color:rgba(255,255,255,0.75); max-width:640px; margin:0 auto 32px; line-height:1.7; }
        .cta-buttons { display:flex; flex-wrap:wrap; justify-content:center; gap:16px; }
        .btn-cta-primary { display:inline-flex; align-items:center; gap:8px; padding:16px 32px; font-size:16px; font-weight:600; color:var(--primary); background:white; border:none; border-radius:var(--radius-lg); text-decoration:none; cursor:pointer; transition:all 0.3s ease; }
        .btn-cta-primary:hover { background:rgba(255,255,255,0.9); box-shadow:0 8px 20px rgba(0,0,0,0.15); transform:translateY(-2px); }
        .btn-cta-secondary { display:inline-flex; align-items:center; gap:8px; padding:16px 32px; font-size:16px; font-weight:600; color:white; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); border-radius:var(--radius-lg); text-decoration:none; cursor:pointer; transition:all 0.3s ease; backdrop-filter:blur(10px); }
        .btn-cta-secondary:hover { background:rgba(255,255,255,0.2); }

        /* Footer */
        .landing-footer { background:var(--surface-alt); border-top:1px solid var(--surface-border); position:relative; z-index:10; }
        .footer-grid { display:grid; grid-template-columns:1.5fr 1fr 1fr 1.5fr; gap:40px; }
        @media(max-width:1024px) { .footer-grid{grid-template-columns:1fr 1fr;} }
        @media(max-width:640px) { .footer-grid{grid-template-columns:1fr;} }
        .footer-brand .footer-logo { display:flex; align-items:center; gap:12px; margin-bottom:16px; }
        .footer-brand .footer-logo-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--primary),var(--primary-dark)); border-radius:var(--radius); display:flex; align-items:center; justify-content:center; }
        .footer-brand .footer-logo-icon svg { width:20px; height:20px; }
        .footer-brand .footer-logo-text { font-size:18px; font-weight:700; color:var(--text-primary); }
        .footer-brand .footer-logo-text span { color:var(--primary); }
        .footer-tagline { font-size:13px; color:var(--text-secondary); line-height:1.7; margin-bottom:20px; }
        .footer-section h4 { font-size:14px; font-weight:600; color:var(--text-primary); margin-bottom:16px; display:flex; align-items:center; gap:8px; cursor:pointer; }
        .footer-links { list-style:none; padding:0; margin:0; }
        .footer-links li { margin-bottom:8px; }
        .footer-links a { font-size:13px; color:var(--text-secondary); text-decoration:none; display:flex; align-items:center; gap:8px; transition:color 0.2s ease; padding:4px 0; cursor:pointer; }
        .footer-links a:hover { color:var(--primary); }
        .footer-links a i { font-size:10px; color:var(--text-tertiary); pointer-events:none; }
        .footer-contact-item { display:flex; align-items:flex-start; gap:12px; margin-bottom:16px; }
        .footer-contact-item i { color:var(--primary); font-size:16px; margin-top:2px; flex-shrink:0; pointer-events:none; }
        .footer-contact-item strong { display:block; font-size:13px; color:var(--text-primary); margin-bottom:2px; pointer-events:none; }
        .footer-contact-item span, .footer-contact-item a { font-size:12px; color:var(--text-secondary); text-decoration:none; cursor:pointer; }
        .footer-contact-item a:hover { color:var(--primary); }
        .footer-bottom { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; padding:24px; border-top:1px solid var(--surface-border); background:var(--surface); }
        @media(max-width:768px) { .footer-bottom{flex-direction:column;text-align:center;} }
        .footer-copyright p { font-size:13px; color:var(--text-secondary); }
        .footer-copyright a { color:var(--primary); text-decoration:none; font-weight:600; }
        .footer-version { font-size:11px; color:var(--text-tertiary); margin-top:4px; }
        .footer-version .separator { margin:0 6px; }
        .footer-legal { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
        .footer-legal a { font-size:13px; color:var(--text-secondary); text-decoration:none; display:flex; align-items:center; gap:6px; transition:color 0.2s ease; }
        .footer-legal a:hover { color:var(--primary); }
        .footer-legal a i { font-size:14px; }

        /* Theme & Language Toggles in Footer */
        .footer-toggles { display:flex; align-items:center; gap:12px; margin-top:16px; }
        .toggle-group { display:flex; align-items:center; background:var(--surface); border:1px solid var(--surface-border); border-radius:var(--radius); }
        .toggle-btn { padding:6px 10px; font-size:13px; border:none; background:transparent; color:var(--text-secondary); cursor:pointer; transition:all 0.2s ease; display:flex; align-items:center; gap:4px; text-decoration:none; position:relative; z-index:1; }
        .toggle-btn:hover { color:var(--primary); background:rgba(37,99,235,0.05); }
        .toggle-btn.active { background:var(--primary); color:white; }
        .toggle-btn.active:hover { background:var(--primary-dark); color:white; }
        .toggle-btn i { font-size:14px; pointer-events:none; }

        /* Section reveal */
        .section-reveal { opacity:0; transform:translateY(32px); transition:all 0.7s ease; }
        .section-reveal.visible { opacity:1; transform:translateY(0); }

        @media(max-width:640px) {
            .hero-content{padding:40px 16px 60px;}
            .hero-title{font-size:32px;}
            .hero-description{font-size:16px;}
            .hero-buttons{flex-direction:column;}
            .btn-hero-primary,.btn-hero-secondary{width:100%;justify-content:center;}
            .features-section,.pricing-section{padding:64px 16px;}
            .section-title{font-size:24px;}
        }

        /* Guide CTA Section */
        .guide-cta-section {
            position: relative;
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 24px;
            border-radius: var(--radius-2xl);
            overflow: hidden;
            background: linear-gradient(135deg, var(--surface) 0%, rgba(37,99,235,0.04) 50%, var(--surface-alt) 100%);
            border: 1px solid var(--surface-border);
        }
        .theme-dark .guide-cta-section {
            background: linear-gradient(135deg, var(--surface-alt) 0%, rgba(37,99,235,0.08) 50%, var(--surface) 100%);
        }
        .guide-cta-glow {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(37,99,235,0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: glowPulse 4s ease-in-out infinite;
        }
        .guide-cta-glow-2 {
            top: auto;
            right: auto;
            bottom: -60px;
            left: -60px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(6,182,212,0.1) 0%, transparent 70%);
            animation-delay: -2s;
        }
        .guide-cta-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.6;
        }
        .theme-dark .guide-cta-svg { opacity: 0.8; }
        .guide-cta-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 640px;
            margin: 0 auto;
        }
        .guide-cta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-subtle);
            border: 1px solid rgba(37,99,235,0.2);
            border-radius: 100px;
            padding: 8px 18px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            animation: glowPulse 3s ease-in-out infinite;
        }
        .guide-cta-badge i { font-size: 16px; }
        .guide-cta-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        @media(max-width:768px) { .guide-cta-title { font-size: 24px; } }
        .guide-cta-desc {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .guide-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            font-size: 16px;
            font-weight: 600;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: var(--radius-lg);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(37,99,235,0.3);
        }
        .guide-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(37,99,235,0.45);
        }
        .guide-cta-btn i { transition: transform 0.3s ease; font-size: 18px; }
        .guide-cta-btn:hover i { transform: translateX(4px); }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="landing-nav" id="landingNav">
        <div class="nav-container">
            <a href="/" class="nav-brand">
                <div class="nav-logo">
                    <svg viewBox="0 0 40 40" fill="none"><path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/><path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/></svg>
                </div>
                <span class="nav-brand-text">CuniApp</span>
            </a>
            <div class="nav-links">
                <a href="#features" class="nav-link">{{ __('Fonctionnalités') }}</a>
                <a href="#pricing" class="nav-link">{{ __('Tarifs') }}</a>
                <a href="{{ route('guide') }}" class="nav-link" style="position:relative;">
                    <i class="bi bi-book-half" style="font-size:14px;"></i>
                    {{ __('Guide') }}
                    <span style="position:absolute;top:2px;right:2px;width:6px;height:6px;background:var(--accent-green);border-radius:50%;box-shadow:0 0 6px var(--accent-green),0 0 12px rgba(16,185,129,0.4);animation:pulse 2s infinite;"></span>
                </a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('connect') }}" class="btn-nav-login">{{ __('Connexion') }}</a>
                <a href="{{ route('connect') }}#register" class="btn-nav-cta">
                    {{ __('Commencer') }}
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section">
        <div class="hero-bg"><div class="hero-bg-gradient"></div><div class="hero-bg-overlay"></div></div>
        <div class="hero-particles">
            <div class="particle animate-float" style="width:288px;height:288px;background:rgba(37,99,235,0.08);top:25%;left:-144px;"></div>
            <div class="particle animate-float-delayed" style="width:384px;height:384px;background:rgba(59,130,246,0.06);top:-96px;right:25%;"></div>
            <div class="particle animate-float" style="width:224px;height:224px;background:rgba(37,99,235,0.05);bottom:25%;right:33%;"></div>
            <div class="particle animate-float-delayed" style="width:320px;height:320px;background:rgba(59,130,246,0.04);bottom:0;left:33%;"></div>
        </div>

        <!-- Animated SVG Grid with Glowing Lines -->
        <svg class="hero-svg" style="mask-image:linear-gradient(to bottom,black 0%,black 60%,transparent 100%);-webkit-mask-image:linear-gradient(to bottom,black 0%,black 60%,transparent 100%);">
            <defs>
                <pattern id="hero-grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="var(--primary)" stroke-width="0.5"/></pattern>
                <pattern id="hero-grid-lg" width="240" height="240" patternUnits="userSpaceOnUse"><path d="M 240 0 L 0 0 0 240" fill="none" stroke="var(--primary)" stroke-width="1"/></pattern>
                <radialGradient id="dot-glow" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="var(--primary)" stop-opacity="1"/><stop offset="30%" stop-color="var(--primary)" stop-opacity="0.7"/><stop offset="100%" stop-color="var(--primary)" stop-opacity="0"/></radialGradient>
                <radialGradient id="dot-glow-light" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="var(--primary-light)" stop-opacity="1"/><stop offset="30%" stop-color="var(--primary-light)" stop-opacity="0.7"/><stop offset="100%" stop-color="var(--primary-light)" stop-opacity="0"/></radialGradient>
                <radialGradient id="dot-glow-strong" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="var(--primary)" stop-opacity="1"/><stop offset="25%" stop-color="var(--primary)" stop-opacity="0.8"/><stop offset="100%" stop-color="var(--primary)" stop-opacity="0"/></radialGradient>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-grid)"/>
            <rect width="100%" height="100%" fill="url(#hero-grid-lg)"/>

            <!-- Glowing line traversals - vertical -->
            <line x1="120" y1="0" x2="120" y2="900" stroke="url(#dot-glow)" stroke-width="1.5" opacity="0">
                <animate attributeName="opacity" values="0;0;0.5;0.5;0" dur="8s" repeatCount="indefinite"/>
                <animate attributeName="y1" values="0;900" dur="8s" repeatCount="indefinite"/>
                <animate attributeName="y2" values="200;1100" dur="8s" repeatCount="indefinite"/>
            </line>
            <line x1="400" y1="0" x2="400" y2="900" stroke="url(#dot-glow-light)" stroke-width="1.2" opacity="0">
                <animate attributeName="opacity" values="0;0;0.4;0.4;0" dur="10s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="y1" values="0;900" dur="10s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="y2" values="200;1100" dur="10s" repeatCount="indefinite" begin="2s"/>
            </line>
            <line x1="680" y1="0" x2="680" y2="900" stroke="url(#dot-glow)" stroke-width="1" opacity="0">
                <animate attributeName="opacity" values="0;0;0.35;0.35;0" dur="12s" repeatCount="indefinite" begin="4s"/>
                <animate attributeName="y1" values="0;900" dur="12s" repeatCount="indefinite" begin="4s"/>
                <animate attributeName="y2" values="200;1100" dur="12s" repeatCount="indefinite" begin="4s"/>
            </line>
            <line x1="960" y1="0" x2="960" y2="900" stroke="url(#dot-glow-light)" stroke-width="1.3" opacity="0">
                <animate attributeName="opacity" values="0;0;0.3;0.3;0" dur="9s" repeatCount="indefinite" begin="6s"/>
                <animate attributeName="y1" values="900;0" dur="9s" repeatCount="indefinite" begin="6s"/>
                <animate attributeName="y2" values="1100;200" dur="9s" repeatCount="indefinite" begin="6s"/>
            </line>
            <line x1="1200" y1="0" x2="1200" y2="900" stroke="url(#dot-glow)" stroke-width="1.1" opacity="0">
                <animate attributeName="opacity" values="0;0;0.25;0.25;0" dur="11s" repeatCount="indefinite" begin="1s"/>
                <animate attributeName="y1" values="0;900" dur="11s" repeatCount="indefinite" begin="1s"/>
                <animate attributeName="y2" values="200;1100" dur="11s" repeatCount="indefinite" begin="1s"/>
            </line>

            <!-- Glowing line traversals - horizontal -->
            <line x1="0" y1="80" x2="1600" y2="80" stroke="url(#dot-glow)" stroke-width="1.5" opacity="0">
                <animate attributeName="opacity" values="0;0;0.4;0.4;0" dur="9s" repeatCount="indefinite"/>
                <animate attributeName="x1" values="0;1600" dur="9s" repeatCount="indefinite"/>
                <animate attributeName="x2" values="200;1800" dur="9s" repeatCount="indefinite"/>
            </line>
            <line x1="0" y1="200" x2="1600" y2="200" stroke="url(#dot-glow-light)" stroke-width="1.2" opacity="0">
                <animate attributeName="opacity" values="0;0;0.35;0.35;0" dur="11s" repeatCount="indefinite" begin="3s"/>
                <animate attributeName="x1" values="1600;0" dur="11s" repeatCount="indefinite" begin="3s"/>
                <animate attributeName="x2" values="1800;200" dur="11s" repeatCount="indefinite" begin="3s"/>
            </line>
            <line x1="0" y1="340" x2="1600" y2="340" stroke="url(#dot-glow)" stroke-width="1" opacity="0">
                <animate attributeName="opacity" values="0;0;0.3;0.3;0" dur="13s" repeatCount="indefinite" begin="5s"/>
                <animate attributeName="x1" values="0;1600" dur="13s" repeatCount="indefinite" begin="5s"/>
                <animate attributeName="x2" values="200;1800" dur="13s" repeatCount="indefinite" begin="5s"/>
            </line>

            <!-- Glowing line traversals - diagonal -->
            <line x1="0" y1="0" x2="1600" y2="900" stroke="url(#dot-glow)" stroke-width="0.8" opacity="0">
                <animate attributeName="opacity" values="0;0;0.2;0.2;0" dur="14s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="x1" values="0;1600" dur="14s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="y1" values="0;900" dur="14s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="x2" values="200;1800" dur="14s" repeatCount="indefinite" begin="2s"/>
                <animate attributeName="y2" values="200;1100" dur="14s" repeatCount="indefinite" begin="2s"/>
            </line>
            <line x1="1600" y1="0" x2="0" y2="900" stroke="url(#dot-glow-light)" stroke-width="0.8" opacity="0">
                <animate attributeName="opacity" values="0;0;0.18;0.18;0" dur="16s" repeatCount="indefinite" begin="7s"/>
                <animate attributeName="x1" values="1600;0" dur="16s" repeatCount="indefinite" begin="7s"/>
                <animate attributeName="y1" values="0;900" dur="16s" repeatCount="indefinite" begin="7s"/>
                <animate attributeName="x2" values="1800;200" dur="16s" repeatCount="indefinite" begin="7s"/>
                <animate attributeName="y2" values="200;1100" dur="16s" repeatCount="indefinite" begin="7s"/>
            </line>

            <!-- Curve-following glow dots -->
            <path id="curve1" d="M0,100 Q200,60 400,120 T800,80 T1200,110 T1600,70" fill="none" stroke="var(--primary)" stroke-width="0.8" opacity="0.25"/>
            <circle r="8" fill="url(#dot-glow-strong)" opacity="0.4"><animateMotion dur="10s" repeatCount="indefinite" rotate="auto"><mpath href="#curve1"/></animateMotion><animate attributeName="opacity" values="0;0.45;0.45;0" dur="10s" repeatCount="indefinite"/></circle>
            <path id="curve2" d="M0,200 Q300,160 600,220 T1200,180 T1800,210" fill="none" stroke="var(--primary-light)" stroke-width="0.6" opacity="0.18"/>
            <circle r="7" fill="url(#dot-glow-strong)" opacity="0.35"><animateMotion dur="13s" repeatCount="indefinite" rotate="auto" begin="3s"><mpath href="#curve2"/></animateMotion><animate attributeName="opacity" values="0;0.38;0.38;0" dur="13s" repeatCount="indefinite" begin="3s"/></circle>
            <path id="curve3" d="M0,320 Q250,280 500,330 T1000,300 T1500,320" fill="none" stroke="var(--primary)" stroke-width="0.5" opacity="0.15"/>
            <circle r="6.5" fill="url(#dot-glow-strong)" opacity="0.3"><animateMotion dur="15s" repeatCount="indefinite" rotate="auto" begin="6s"><mpath href="#curve3"/></animateMotion><animate attributeName="opacity" values="0;0.32;0.32;0" dur="15s" repeatCount="indefinite" begin="6s"/></circle>

            <!-- Pulsing data-point dots -->
            <circle cx="400" cy="100" r="2.5" fill="var(--primary)" opacity="0.3"><animate attributeName="opacity" values="0.1;0.35;0.1" dur="8s" repeatCount="indefinite"/></circle>
            <circle cx="800" cy="60" r="3" fill="var(--primary-light)" opacity="0.25"><animate attributeName="opacity" values="0.1;0.3;0.1" dur="10s" repeatCount="indefinite" begin="2s"/></circle>
            <circle cx="1200" cy="90" r="2" fill="var(--primary)" opacity="0.25"><animate attributeName="opacity" values="0.08;0.28;0.08" dur="9s" repeatCount="indefinite" begin="4s"/></circle>
        </svg>

        <!-- Decorative blobs -->
        <div style="position:absolute;top:-128px;right:-128px;width:500px;height:500px;border-radius:50%;pointer-events:none;background:radial-gradient(circle,rgba(37,99,235,0.08) 0%,transparent 70%);"></div>
        <div style="position:absolute;bottom:-64px;left:0;width:400px;height:400px;border-radius:50%;pointer-events:none;background:radial-gradient(circle,rgba(37,99,235,0.05) 0%,transparent 70%);"></div>

        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    <div class="hero-badge-dot"></div>
                    <span class="hero-badge-text">{{ __('Gestion de cheptel moderne') }}</span>
                </div>
                <h1 class="hero-title">
                    {{ __('La plateforme complète de') }}
                    <span class="hero-gradient-text">{{ __('gestion cunicole') }}</span>
                </h1>
                <p class="hero-description">
                    {{ __('Gérez intelligemment votre cheptel lapin. Suivez vos reproductions, naissances, et performances en toute simplicité depuis un seul tableau de bord.') }}
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('connect') }}#register" class="btn-hero-primary">
                        {{ __('Essai gratuit 14 jours') }}
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-hero-secondary">{{ __('En savoir plus') }}</a>
                </div>
                <div class="hero-trust">
                    <div class="hero-trust-item"><i class="bi bi-shield-check" style="color:var(--accent-green);"></i><span>{{ __('Essai 14 jours') }}</span></div>
                    <div class="hero-trust-item"><i class="bi bi-lightning" style="color:var(--accent-orange);"></i><span>{{ __('Sans carte bancaire') }}</span></div>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-illustration">
                    <div class="hero-illustration-bg"></div>
                    <div class="hero-illustration-bg-2"></div>
                    <div class="hero-card">
                        <div class="hero-slideshow" id="heroSlideshow">
                            <img src="{{ asset('images/rabbits.png') }}" alt="CuniApp" class="active">
                            <img src="{{ asset('images/rabbits_2.png') }}" alt="CuniApp">
                            <img src="{{ asset('images/rabbits_3.png') }}" alt="CuniApp">
                            <img src="{{ asset('images/rabbits_4.png') }}" alt="CuniApp">
                            <div class="hero-slideshow-overlay"></div>
                            <div class="hero-slideshow-text">
                                <h3>{{ __("Votre Hub de Gestion d'Élevage") }}</h3>
                                <p>{{ __('Gestion intelligente de votre cheptel') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="hero-float-stat hero-float-stat-left animate-float">
                        <div class="float-stat-icon green"><i class="bi bi-people-fill"></i></div>
                        <div><div class="float-stat-label">{{ __('Éleveurs actifs') }}</div><div class="float-stat-value">500+</div></div>
                    </div>
                    <div class="hero-float-stat hero-float-stat-right animate-float-delayed">
                        <div class="float-stat-icon blue"><i class="bi bi-graph-up-arrow"></i></div>
                        <div><div class="float-stat-label">{{ __('Satisfaction') }}</div><div class="float-stat-value">98%</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"><div class="section-divider-line"></div></div>

    <!-- Features -->
    <div class="section-reveal" id="features">
        <div class="features-section">
            <div class="section-header">
                <span class="section-badge">{{ __('Fonctionnalités') }}</span>
                <h2 class="section-title">{{ __('Tout ce dont votre élevage a besoin') }}</h2>
                <p class="section-description">{{ __('Des fonctionnalités puissantes conçues pour simplifier chaque aspect de la gestion de votre cheptel lapin.') }}</p>
            </div>
            <div class="features-grid">
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-egg-fried"></i></div><h3 class="feature-title">{{ __('Suivi des Reproductions') }}</h3><p class="feature-description">{{ __('Gérez les saillies, palpations et suivez les gestations en temps réel avec des alertes automatiques.') }}</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-clipboard2-pulse"></i></div><h3 class="feature-title">{{ __('Gestion des Naissances') }}</h3><p class="feature-description">{{ __('Enregistrez les mises bas, suivez la mortalité et monitorer la croissance de vos lapereaux.') }}</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-clipboard2-data"></i></div><h3 class="feature-title">{{ __('Inventaire Complet') }}</h3><p class="feature-description">{{ __('Base de données détaillée de tous vos lapins avec codes uniques, photos et historique médical.') }}</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div><h3 class="feature-title">{{ __('Tableau de Bord Intelligent') }}</h3><p class="feature-description">{{ __('Statistiques en temps réel, graphiques de performance et indicateurs clés pour vos décisions.') }}</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-currency-exchange"></i></div><h3 class="feature-title">{{ __('Gestion des Ventes') }}</h3><p class="feature-description">{{ __("Suivez vos ventes, générez des factures et gérez les paiements en un clin d'œil.") }}</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="bi bi-shield-lock"></i></div><h3 class="feature-title">{{ __('Sécurisé & Fiable') }}</h3><p class="feature-description">{{ __('Sécurité de niveau entreprise avec sauvegardes automatiques et vérification par email.') }}</p></div>
            </div>
        </div>
    </div>

    <div class="section-divider"><div class="section-divider-line"></div></div>

    <!-- Pricing -->
    <div class="section-reveal" id="pricing">
        <div class="pricing-section">
            <div class="section-header">
                <span class="section-badge">{{ __('Tarifs') }}</span>
                <h2 class="section-title">{{ __('Des prix simples et transparents') }}</h2>
                <p class="section-description">{{ __('Choisissez le plan qui correspond à votre élevage. Tous les plans incluent un essai gratuit de 14 jours.') }}</p>
            </div>
            <div class="pricing-grid">
                @php $plans = \App\Models\SubscriptionPlan::where('is_active', true)->orderBy('duration_months')->get(); @endphp
                @foreach ($plans as $plan)
                    <div class="pricing-card {{ $plan->duration_months === 12 ? 'popular' : '' }}">
                            @if ($plan->duration_months === 12)
                            <div class="pricing-popular-badge"><i class="bi bi-star-fill"></i> {{ __('Meilleure Offre') }}</div>
                            @endif
                        <h3 class="pricing-name">{{ __($plan->name) }}</h3>
                        <div class="pricing-price">
                            <span class="pricing-amount">@if ($plan->price <= 0){{ __('Gratuit') }}@else{{ number_format($plan->price, 0, ',', ' ') }}@endif</span>
                            @if ($plan->price > 0)<span class="pricing-period">FCFA</span>@endif
                        </div>
                        <ul class="pricing-features">
                            @if ($plan->duration_months > 0)
                            <li class="pricing-feature"><i class="bi bi-check-circle-fill"></i><span>{{ $plan->duration_months }} {{ __("mois d'accès") }}</span></li>
                            @else
                            <li class="pricing-feature"><i class="bi bi-check-circle-fill"></i><span>14 {{ __("jours d'essai") }}</span></li>
                            @endif
                            <li class="pricing-feature"><i class="bi bi-check-circle-fill"></i><span>{{ __("Jusqu'à") }} {{ $plan->max_users ?? 5 }} {{ __('utilisateurs') }}</span></li>
                            @if (is_array($plan->features))
                            @foreach (array_slice($plan->features, 0, 3) as $f)<li class="pricing-feature"><i class="bi bi-check-circle-fill"></i><span>{{ __($f) }}</span></li>
                            @endforeach
                            @endif
                        </ul>
                        <a href="{{ route('connect') }}#register" class="pricing-cta">
                            @if ($plan->price <= 0)
                                {{ __("Commencer l'essai") }}
                            @else
                                {{ __("S'abonner") }}
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="section-divider"><div class="section-divider-line"></div></div>

    <!-- Guide CTA -->
    <div class="section-reveal">
        <div class="guide-cta-section">
            <div class="guide-cta-glow"></div>
            <div class="guide-cta-glow guide-cta-glow-2"></div>
            <svg class="guide-cta-svg" viewBox="0 0 1200 300" fill="none" preserveAspectRatio="xMidYMid slice">
                <defs>
                    <linearGradient id="guide-line-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="var(--primary)" stop-opacity="0"/>
                        <stop offset="50%" stop-color="var(--primary)" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="var(--accent-cyan)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <line x1="0" y1="80" x2="1200" y2="80" stroke="url(#guide-line-grad)" stroke-width="1">
                    <animate attributeName="x1" values="-200;1400" dur="6s" repeatCount="indefinite"/>
                    <animate attributeName="x2" values="0;1600" dur="6s" repeatCount="indefinite"/>
                </line>
                <line x1="0" y1="150" x2="1200" y2="150" stroke="url(#guide-line-grad)" stroke-width="0.8">
                    <animate attributeName="x1" values="1400;-200" dur="8s" repeatCount="indefinite"/>
                    <animate attributeName="x2" values="1600;0" dur="8s" repeatCount="indefinite"/>
                </line>
                <line x1="0" y1="220" x2="1200" y2="220" stroke="url(#guide-line-grad)" stroke-width="0.6">
                    <animate attributeName="x1" values="-300;1500" dur="7s" repeatCount="indefinite"/>
                    <animate attributeName="x2" values="-100;1300" dur="7s" repeatCount="indefinite"/>
                </line>
                <circle r="3" fill="var(--primary)" opacity="0.3"><animate attributeName="cx" values="0;1200" dur="5s" repeatCount="indefinite"/><animate attributeName="cy" values="80;80" dur="5s" repeatCount="indefinite"/><animate attributeName="opacity" values="0;0.5;0" dur="5s" repeatCount="indefinite"/></circle>
                <circle r="2.5" fill="var(--accent-cyan)" opacity="0.25"><animate attributeName="cx" values="1200;0" dur="7s" repeatCount="indefinite"/><animate attributeName="cy" values="150;150" dur="7s" repeatCount="indefinite"/><animate attributeName="opacity" values="0;0.4;0" dur="7s" repeatCount="indefinite"/></circle>
            </svg>
            <div class="guide-cta-content">
                <div class="guide-cta-badge">
                    <i class="bi bi-book-half"></i>
                    <span>{{ __('Documentation') }}</span>
                </div>
                <h2 class="guide-cta-title">{{ __('Besoin d\'aide ? Consultez notre guide') }}</h2>
                <p class="guide-cta-desc">{{ __('Découvrez comment tirer le meilleur parti de CuniApp avec notre documentation complète. Tutoriels, guides pas à pas et conseils d\'utilisation.') }}</p>
                <a href="{{ route('guide') }}" class="guide-cta-btn">
                    <i class="bi bi-arrow-right-circle"></i>
                    <span>{{ __('Accéder au Guide') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="section-divider"><div class="section-divider-line"></div></div>

    <!-- CTA -->
    <div class="section-reveal">
        <div class="cta-section">
            <div class="cta-card">
                <div class="cta-bg-blob-1"></div>
                <div class="cta-bg-blob-2"></div>
                <div class="cta-content">
                    <h2 class="cta-title">{{ __('Prêt à moderniser votre élevage ?') }}</h2>
                    <p class="cta-description">{{ __("Rejoignez des centaines d'éleveurs qui utilisent déjà CuniApp pour optimiser leurs opérations. Commencez votre essai gratuit de 14 jours dès aujourd'hui.") }}</p>
                    <div class="cta-buttons">
                        <a href="{{ route('connect') }}#register" class="btn-cta-primary">{{ __('Essai gratuit 14 jours') }}</a>
                        <a href="{{ route('connect') }}" class="btn-cta-secondary">{{ __('Se connecter') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.public-footer')

    <script>
        // Navbar scroll
        const nav = document.getElementById('landingNav');
        window.addEventListener('scroll', () => { nav.classList.toggle('scrolled', window.scrollY > 20); });

        // Section reveal
        const obs = new IntersectionObserver(entries => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } }); }, { threshold: 0.1 });
        document.querySelectorAll('.section-reveal').forEach(el => obs.observe(el));

        // Slideshow
        const slides = document.querySelectorAll('#heroSlideshow img');
        let currentSlide = 0;
        if (slides.length > 0) {
            setInterval(() => {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }, 3500);
        }

        // Theme toggle
        const savedTheme = localStorage.getItem('cuniapp_theme') || 'system';
        function setTheme(theme) {
            localStorage.setItem('cuniapp_theme', theme);
            applyTheme(theme);
        }
        function applyTheme(theme) {
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('theme-dark', isDark);
            document.querySelectorAll('#themeToggle .toggle-btn').forEach(b => b.classList.toggle('active', b.dataset.theme === theme));
            document.querySelectorAll('.footer-theme-btn').forEach(b => {
                const isActive = b.dataset.theme === theme;
                b.style.background = isActive ? 'var(--primary)' : 'transparent';
                b.style.color = isActive ? 'white' : 'var(--text-tertiary)';
            });
        }
        applyTheme(savedTheme);
        document.querySelectorAll('#themeToggle .toggle-btn, .footer-theme-btn').forEach(btn => {
            btn.addEventListener('click', () => { const t = btn.dataset.theme; localStorage.setItem('cuniapp_theme', t); applyTheme(t); });
        });
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => applyTheme(localStorage.getItem('cuniapp_theme') || 'system'));
    </script>
</body>
</html>
