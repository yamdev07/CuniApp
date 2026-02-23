<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CuniApp Élevage') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex items-center justify-center bg-gray-900">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-cyan-400 mb-4">CuniApp Élevage</h1>
            <p class="text-gray-300 mb-6">Gestion intelligente de votre cheptel</p>
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-cyan-400 hover:text-cyan-300">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300">Inscription</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</body>
</html>
