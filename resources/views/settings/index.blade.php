@extends('layouts.cuniapp')

@section('title', 'Paramètres - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-gear"></i>
            Paramètres
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Paramètres</span>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert-cuni error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Erreurs de validation</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- Tabs Navigation -->
<div class="tabs-container">
    <button class="tab-btn active" data-tab="general-tab">
        <i class="bi bi-house"></i>Général
    </button>
    <button class="tab-btn" data-tab="breeding-tab">
        <i class="bi bi-heart-pulse"></i>Élevage
    </button>
    <button class="tab-btn" data-tab="profile-tab">
        <i class="bi bi-person"></i>Profil
    </button>
    <button class="tab-btn" data-tab="notifications-tab">
        <i class="bi bi-bell"></i>Notifications
    </button>
    <button class="tab-btn" data-tab="system-tab">
        <i class="bi bi-hdd"></i>Système
    </button>
</div>

<!-- Tab Content -->
<div class="tab-content active" id="general-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-building"></i>
                Informations de la Ferme
            </h3>
        </div>
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Nom de la ferme</label>
                    <input type="text" name="farm_name" class="form-control" 
                           value="{{ \App\Models\Setting::get('farm_name', '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="farm_email" class="form-control" 
                           value="{{ \App\Models\Setting::get('farm_email', '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="farm_phone" class="form-control" 
                           value="{{ \App\Models\Setting::get('farm_phone', '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="farm_address" class="form-control" 
                           value="{{ \App\Models\Setting::get('farm_address', '') }}">
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tab-content" id="breeding-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-egg"></i>
                Paramètres d'Élevage
            </h3>
        </div>
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Jours de gestation (lapine)</label>
                    <input type="number" name="gestation_days" class="form-control" 
                           value="{{ \App\Models\Setting::get('gestation_days', 31) }}" 
                           min="28" max="35">
                    <small style="color: var(--text-tertiary); font-size: 12px;">Moyenne: 31 jours</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Semaines de sevrage</label>
                    <input type="number" name="weaning_weeks" class="form-control" 
                           value="{{ \App\Models\Setting::get('weaning_weeks', 6) }}" 
                           min="4" max="8">
                    <small style="color: var(--text-tertiary); font-size: 12px;">Recommandé: 6 semaines</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Seuil d'alerte (%)</label>
                    <input type="number" name="alert_threshold" class="form-control" 
                           value="{{ \App\Models\Setting::get('alert_threshold', 80) }}" 
                           min="1" max="100">
                    <small style="color: var(--text-tertiary); font-size: 12px;">Pour les notifications</small>
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tab-content" id="profile-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-person-circle"></i>
                Mon Profil
            </h3>
        </div>
        <form action="{{ route('settings.profile') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ auth()->user()->email }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmer mot de passe</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-save"></i> Mettre à jour le profil
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tab-content" id="notifications-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-bell"></i>
                Préférences de Notifications
            </h3>
        </div>
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Thème de l'application</label>
                    <select name="theme" class="form-control">
                        <option value="dark" {{ \App\Models\Setting::get('theme', 'dark') == 'dark' ? 'selected' : '' }}>Sombre</option>
                        <option value="light" {{ \App\Models\Setting::get('theme', 'dark') == 'light' ? 'selected' : '' }}>Clair</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Langue</label>
                    <select name="language" class="form-control">
                        <option value="fr" {{ \App\Models\Setting::get('language', 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ \App\Models\Setting::get('language', 'fr') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="notifications_email" 
                               {{ \App\Models\Setting::get('notifications_email', '0') == '1' ? 'checked' : '' }}>
                        <span class="form-label" style="margin: 0;">Notifications par email</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="notifications_dashboard" 
                               {{ \App\Models\Setting::get('notifications_dashboard', '0') == '1' ? 'checked' : '' }}>
                        <span class="form-label" style="margin: 0;">Notifications sur le dashboard</span>
                    </label>
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tab-content" id="system-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-hdd"></i>
                Gestion du Système
            </h3>
        </div>
        <div class="settings-grid">
            <div class="cuni-card" style="margin: 0; background: var(--surface-2);">
                <h4 style="font-family: 'Orbitron'; font-size: 16px; margin-bottom: 12px;">
                    <i class="bi bi-download" style="color: var(--cuni-cyan);"></i>
                    Exporter les données
                </h4>
                <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 16px;">
                    Télécharger toutes les données de l'élevage au format JSON
                </p>
                <a href="{{ route('settings.export') }}" class="btn-cuni secondary">
                    <i class="bi bi-download"></i> Exporter
                </a>
            </div>
            <div class="cuni-card" style="margin: 0; background: var(--surface-2);">
                <h4 style="font-family: 'Orbitron'; font-size: 16px; margin-bottom: 12px;">
                    <i class="bi bi-trash" style="color: var(--cuni-orange);"></i>
                    Vider le cache
                </h4>
                <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 16px;">
                    Effacer le cache de l'application pour résoudre les problèmes
                </p>
                <form action="{{ route('settings.clear-cache') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-cuni danger" 
                            onclick="return confirm('Voulez-vous vraiment vider le cache ?')">
                        <i class="bi bi-trash"></i> Vider le cache
                    </button>
                </form>
            </div>
        </div>
        
        <div class="cuni-card" style="margin-top: 24px; background: var(--surface-2);">
            <h4 style="font-family: 'Orbitron'; font-size: 16px; margin-bottom: 16px;">
                <i class="bi bi-info-circle" style="color: var(--cuni-cyan);"></i>
                Informations Système
            </h4>
            <div class="settings-grid">
                <div>
                    <small style="color: var(--text-tertiary);">Version</small>
                    <div style="font-weight: 700;">1.0.0</div>
                </div>
                <div>
                    <small style="color: var(--text-tertiary);">PHP</small>
                    <div style="font-weight: 700;">{{ phpversion() }}</div>
                </div>
                <div>
                    <small style="color: var(--text-tertiary);">Laravel</small>
                    <div style="font-weight: 700;">{{ app()->version() }}</div>
                </div>
                <div>
                    <small style="color: var(--text-tertiary);">Environnement</small>
                    <div style="font-weight: 700;">{{ app()->environment() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection