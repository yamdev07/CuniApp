// resources/views/settings/index.blade.php
@extends('layouts.app')

@section('title', 'Paramètres - CuniApp Élevage')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-light mb-2">
                <i class="bi bi-gear me-2"></i>Paramètres
            </h2>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary">Tableau de bord</a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">Paramètres</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
    <div class="alert alert-success border-0 bg-success-soft fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger border-0 bg-danger-soft fade show mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>
                <h5 class="mb-1">Erreurs de validation</h5>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist">
                        <button class="nav-link active text-start mb-2" data-bs-toggle="pill" data-bs-target="#general-tab">
                            <i class="bi bi-house me-2"></i>Général
                        </button>
                        <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#breeding-tab">
                            <i class="bi bi-heart-pulse me-2"></i>Élevage
                        </button>
                        <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#profile-tab">
                            <i class="bi bi-person me-2"></i>Profil
                        </button>
                        <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#notifications-tab">
                            <i class="bi bi-bell me-2"></i>Notifications
                        </button>
                        <button class="nav-link text-start mb-2" data-bs-toggle="pill" data-bs-target="#system-tab">
                            <i class="bi bi-hdd me-2"></i>Système
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
            <div class="tab-content" id="settings-tabs-content">
                
                <!-- Général -->
                <div class="tab-pane fade show active" id="general-tab">
                    <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                        <div class="card-header bg-transparent border-bottom border-secondary pb-3">
                            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Informations de la Ferme</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom de la ferme</label>
                                        <input type="text" name="farm_name" class="form-control" 
                                               value="{{ \App\Models\Setting::get('farm_name', '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="farm_email" class="form-control" 
                                               value="{{ \App\Models\Setting::get('farm_email', '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone</label>
                                        <input type="text" name="farm_phone" class="form-control" 
                                               value="{{ \App\Models\Setting::get('farm_phone', '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Adresse</label>
                                        <input type="text" name="farm_address" class="form-control" 
                                               value="{{ \App\Models\Setting::get('farm_address', '') }}">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Élevage -->
                <div class="tab-pane fade" id="breeding-tab">
                    <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                        <div class="card-header bg-transparent border-bottom border-secondary pb-3">
                            <h5 class="mb-0"><i class="bi bi-egg me-2"></i>Paramètres d'Élevage</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Jours de gestation (lapine)</label>
                                        <input type="number" name="gestation_days" class="form-control" 
                                               value="{{ \App\Models\Setting::get('gestation_days', 31) }}" 
                                               min="28" max="35">
                                        <small class="text-muted">Moyenne: 31 jours</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Semaines de sevrage</label>
                                        <input type="number" name="weaning_weeks" class="form-control" 
                                               value="{{ \App\Models\Setting::get('weaning_weeks', 6) }}" 
                                               min="4" max="8">
                                        <small class="text-muted">Recommandé: 6 semaines</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Seuil d'alerte (%)</label>
                                        <input type="number" name="alert_threshold" class="form-control" 
                                               value="{{ \App\Models\Setting::get('alert_threshold', 80) }}" 
                                               min="1" max="100">
                                        <small class="text-muted">Pour les notifications</small>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Profil -->
                <div class="tab-pane fade" id="profile-tab">
                    <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                        <div class="card-header bg-transparent border-bottom border-secondary pb-3">
                            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Mon Profil</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.profile') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom complet</label>
                                        <input type="text" name="name" class="form-control" 
                                               value="{{ auth()->user()->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ auth()->user()->email }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mot de passe actuel</label>
                                        <input type="password" name="current_password" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" name="new_password" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirmer mot de passe</label>
                                        <input type="password" name="new_password_confirmation" class="form-control">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Mettre à jour le profil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications-tab">
                    <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                        <div class="card-header bg-transparent border-bottom border-secondary pb-3">
                            <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Préférences de Notifications</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Thème de l'application</label>
                                        <select name="theme" class="form-select">
                                            <option value="dark" {{ \App\Models\Setting::get('theme', 'dark') == 'dark' ? 'selected' : '' }}>Sombre</option>
                                            <option value="light" {{ \App\Models\Setting::get('theme', 'dark') == 'light' ? 'selected' : '' }}>Clair</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Langue</label>
                                        <select name="language" class="form-select">
                                            <option value="fr" {{ \App\Models\Setting::get('language', 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                            <option value="en" {{ \App\Models\Setting::get('language', 'fr') == 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notifications_email" 
                                                   id="notifications_email" 
                                                   {{ \App\Models\Setting::get('notifications_email', '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notifications_email">
                                                Notifications par email
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="notifications_dashboard" 
                                                   id="notifications_dashboard" 
                                                   {{ \App\Models\Setting::get('notifications_dashboard', '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notifications_dashboard">
                                                Notifications sur le dashboard
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Système -->
                <div class="tab-pane fade" id="system-tab">
                    <div class="card border-0 shadow-none bg-surface-1 rounded-3">
                        <div class="card-header bg-transparent border-bottom border-secondary pb-3">
                            <h5 class="mb-0"><i class="bi bi-hdd me-2"></i>Gestion du Système</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card bg-surface-2 border-0">
                                        <div class="card-body">
                                            <h6><i class="bi bi-download me-2"></i>Exporter les données</h6>
                                            <p class="text-muted small">Télécharger toutes les données de l'élevage au format JSON</p>
                                            <a href="{{ route('settings.export') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-download me-1"></i>Exporter
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-surface-2 border-0">
                                        <div class="card-body">
                                            <h6><i class="bi bi-trash me-2"></i>Vider le cache</h6>
                                            <p class="text-muted small">Effacer le cache de l'application pour résoudre les problèmes</p>
                                            <form action="{{ route('settings.clear-cache') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm" 
                                                        onclick="return confirm('Voulez-vous vraiment vider le cache ?')">
                                                    <i class="bi bi-trash me-1"></i>Vider le cache
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card bg-surface-2 border-0">
                                        <div class="card-body">
                                            <h6><i class="bi bi-info-circle me-2"></i>Informations Système</h6>
                                            <div class="row g-2 mt-2">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Version</small>
                                                    <div class="fw-semibold">1.0.0</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">PHP</small>
                                                    <div class="fw-semibold">{{ phpversion() }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Laravel</small>
                                                    <div class="fw-semibold">{{ app()->version() }}</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Environnement</small>
                                                    <div class="fw-semibold">{{ app()->environment() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.bg-surface-1 {
    background: var(--surface-1, #0F1C2E);
}
.bg-surface-2 {
    background: var(--surface-2, #1A2942);
}
.nav-pills .nav-link {
    color: var(--text-secondary, #9CA3AF);
    border-radius: 8px;
    transition: all 0.3s;
}
.nav-pills .nav-link:hover {
    background: rgba(0, 217, 255, 0.1);
    color: var(--anyx-cyan, #00D9FF);
}
.nav-pills .nav-link.active {
    background: linear-gradient(135deg, var(--anyx-cyan, #00D9FF), var(--anyx-blue, #0066FF));
    color: white;
}
.card {
    border: 1px solid rgba(255, 255, 255, 0.05);
}
</style>
@endsection