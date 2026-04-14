@extends('layouts.cuniapp')

@section('title', __('Page non trouvée'))

@section('content')
    <div class="page-header">
        <h2 class="page-title">{{ __('Erreur 404') }}</h2>
    </div>

    <div class="cuni-card">
        <div class="card-body text-center py-16">
            <div class="text-6xl mb-4 opacity-20" style="color: var(--primary);">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">{{ __('Page non trouvée') }}</h3>
            <p class="text-gray-500 mb-2">{{ __('La page que vous recherchez n\'existe pas ou a été déplacée.') }}</p>
            <p class="text-gray-400 mb-6 text-sm">
                <span id="countdown">5</span> {{ __('secondes avant la redirection automatique...') }}
            </p>

            <a href="{{ route('dashboard') }}" class="btn-cuni primary" id="redirect-btn">
                <i class="bi bi-arrow-left"></i> {{ __('Retour au tableau de bord') }}
            </a>
        </div>
    </div>

    @auth
    <script>
        // Auto-redirect authenticated users to their dashboard
        (function() {
            let seconds = 5;
            const countdownEl = document.getElementById('countdown');
            const timer = setInterval(function() {
                seconds--;
                if (countdownEl) countdownEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    // Redirect based on user role
                    window.location.href = '{{ auth()->user()->isSuperAdmin() ? route("super.admin.dashboard") : route("dashboard") }}';
                }
            }, 1000);
        })();
    </script>
    @endauth
@endsection