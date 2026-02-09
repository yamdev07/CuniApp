{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Paramètres - AnyxTech Élevage')

@section('content')
<div class="settings-page">
    <!-- En-tête des paramètres -->
    <div class="settings-header mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-2 settings-title">
                    <i class="bi bi-gear-fill me-3"></i>Paramètres
                </h1>
                <p class="settings-subtitle">Configurez votre application AnyxTech Élevage</p>
            </div>
            <button type="button" class="btn btn-primary save-all-btn settings-save-btn">
                <i class="bi bi-floppy me-2"></i>Enregistrer tout
            </button>
        </div>
        
        <!-- Barre de progression -->
        <div class="settings-progress mb-4">
            <div class="progress settings-progress-bar">
                <div class="progress-bar settings-progress-fill" style="width: {{ $progress }}%;"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="settings-text-secondary">Configuration à {{ $progress }}%</small>
                <small class="settings-text-secondary">{{ $filledSettings }}/{{ $totalSettings }} paramètres configurés</small>
            </div>
        </div>
    </div>

    <!-- Grille des paramètres -->
    <div class="row">
        <!-- Colonne gauche - Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="settings-sidebar card">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="settingsTab" role="tablist">
                        <button class="nav-link active d-flex align-items-center mb-2 settings-nav-link" id="general-tab" data-bs-toggle="pill" data-bs-target="#general">
                            <i class="bi bi-sliders me-3"></i>
                            <span>Général</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="elevage-tab" data-bs-toggle="pill" data-bs-target="#elevage">
                            <i class="bi bi-house-heart me-3"></i>
                            <span>Élevage</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications">
                            <i class="bi bi-bell me-3"></i>
                            <span>Notifications</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="users-tab" data-bs-toggle="pill" data-bs-target="#users">
                            <i class="bi bi-people me-3"></i>
                            <span>Utilisateurs</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backup">
                            <i class="bi bi-cloud-arrow-up me-3"></i>
                            <span>Sauvegarde</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="api-tab" data-bs-toggle="pill" data-bs-target="#api">
                            <i class="bi bi-code-slash me-3"></i>
                            <span>API & Intégrations</span>
                        </button>
                        <button class="nav-link d-flex align-items-center mb-2 settings-nav-link" id="appearance-tab" data-bs-toggle="pill" data-bs-target="#appearance">
                            <i class="bi bi-palette me-3"></i>
                            <span>Apparence</span>
                        </button>
                        <div class="mt-4 pt-3 border-top settings-danger-section">
                            <button class="nav-link d-flex align-items-center settings-danger-link" id="danger-tab" data-bs-toggle="pill" data-bs-target="#danger">
                                <i class="bi bi-exclamation-triangle me-3"></i>
                                <span>Zone de danger</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Contenu -->
        <div class="col-lg-9">
            <form id="settingsForm" method="POST" action="{{ route('settings.save') }}">
                @csrf
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- Onglet Général -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="card settings-card">
                            <div class="card-body">
                                <h5 class="card-title mb-4 settings-card-title">
                                    <i class="bi bi-sliders me-2"></i>Paramètres généraux
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom de l'élevage</label>
                                        <input type="text" name="general[farm_name]" class="form-control settings-input" 
                                               value="{{ old('general.farm_name', $settings['general']['farm_name'] ?? 'AnyxTech Élevage') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Adresse</label>
                                        <input type="text" name="general[address]" class="form-control settings-input" 
                                               value="{{ old('general.address', $settings['general']['address'] ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Téléphone</label>
                                        <input type="tel" name="general[phone]" class="form-control settings-input" 
                                               value="{{ old('general.phone', $settings['general']['phone'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email principal</label>
                                        <input type="email" name="general[email]" class="form-control settings-input" 
                                               value="{{ old('general.email', $settings['general']['email'] ?? '') }}">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Devise</label>
                                    <select name="general[currency]" class="form-select settings-select">
                                        <option value="EUR" {{ ($settings['general']['currency'] ?? 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        <option value="USD" {{ ($settings['general']['currency'] ?? '') == 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                                        <option value="XOF" {{ ($settings['general']['currency'] ?? '') == 'XOF' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fuseau horaire</label>
                                    <select name="general[timezone]" class="form-select settings-select">
                                        <option value="Europe/Paris" {{ ($settings['general']['timezone'] ?? 'Europe/Paris') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                        <option value="Africa/Casablanca" {{ ($settings['general']['timezone'] ?? '') == 'Africa/Casablanca' ? 'selected' : '' }}>Africa/Casablanca</option>
                                        <option value="UTC" {{ ($settings['general']['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Langue</label>
                                    <select name="general[language]" class="form-select settings-select">
                                        <option value="fr" {{ ($settings['general']['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ ($settings['general']['language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ ($settings['general']['language'] ?? '') == 'es' ? 'selected' : '' }}>Español</option>
                                    </select>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary save-section-btn settings-section-btn" data-section="general">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer cette section
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Élevage -->
                    <div class="tab-pane fade" id="elevage" role="tabpanel">
                        <div class="card settings-card">
                            <div class="card-body">
                                <h5 class="card-title mb-4 settings-card-title">
                                    <i class="bi bi-house-heart me-2"></i>Configuration de l'élevage
                                </h5>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3 settings-subsection-title">Types de lapins</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input settings-checkbox" type="checkbox" name="elevage[rabbit_types][]" value="viande" id="type-viande"
                                               {{ in_array('viande', $settings['elevage']['rabbit_types'] ?? ['viande', 'fourrure']) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-checkbox-label" for="type-viande">Lapins de viande</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input settings-checkbox" type="checkbox" name="elevage[rabbit_types][]" value="fourrure" id="type-fourrure"
                                               {{ in_array('fourrure', $settings['elevage']['rabbit_types'] ?? ['viande', 'fourrure']) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-checkbox-label" for="type-fourrure">Lapins à fourrure</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input settings-checkbox" type="checkbox" name="elevage[rabbit_types][]" value="compagnie" id="type-compagnie"
                                               {{ in_array('compagnie', $settings['elevage']['rabbit_types'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-checkbox-label" for="type-compagnie">Lapins de compagnie</label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Durée de gestation (jours)</label>
                                    <input type="number" name="elevage[gestation_days]" class="form-control settings-input" 
                                           value="{{ old('elevage.gestation_days', $settings['elevage']['gestation_days'] ?? 31) }}" min="28" max="35">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Âge minimal pour la saillie (jours)</label>
                                    <input type="number" name="elevage[min_mating_age]" class="form-control settings-input" 
                                           value="{{ old('elevage.min_mating_age', $settings['elevage']['min_mating_age'] ?? 120) }}" min="90" max="180">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Seuil d'alerte température (°C)</label>
                                    <div class="input-group">
                                        <input type="number" name="elevage[temperature_alert]" class="form-control settings-input" 
                                               value="{{ old('elevage.temperature_alert', $settings['elevage']['temperature_alert'] ?? 30) }}" step="0.1">
                                        <span class="input-group-text settings-input-addon">°C</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Unités de mesure</label>
                                    <select name="elevage[measurement_unit]" class="form-select settings-select">
                                        <option value="metric" {{ ($settings['elevage']['measurement_unit'] ?? 'metric') == 'metric' ? 'selected' : '' }}>Système métrique (kg, cm)</option>
                                        <option value="imperial" {{ ($settings['elevage']['measurement_unit'] ?? '') == 'imperial' ? 'selected' : '' }}>Système impérial (lb, inch)</option>
                                    </select>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary save-section-btn settings-section-btn" data-section="elevage">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer cette section
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Notifications -->
                    <div class="tab-pane fade" id="notifications" role="tabpanel">
                        <div class="card settings-card">
                            <div class="card-body">
                                <h5 class="card-title mb-4 settings-card-title">
                                    <i class="bi bi-bell me-2"></i>Notifications et alertes
                                </h5>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3 settings-subsection-title">Notifications par email</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" id="notif-saillies" checked>
                                        <label class="form-check-label settings-switch-label" for="notif-saillies">Rappels de saillies</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" id="notif-mises-bas" checked>
                                        <label class="form-check-label settings-switch-label" for="notif-mises-bas">Alertes de mises bas</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" id="notif-vaccins" checked>
                                        <label class="form-check-label settings-switch-label" for="notif-vaccins">Rappels de vaccins</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" id="notif-sante">
                                        <label class="form-check-label settings-switch-label" for="notif-sante">Alertes santé</label>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary save-section-btn settings-section-btn" data-section="notifications">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer cette section
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Apparence -->
                    <div class="tab-pane fade" id="appearance" role="tabpanel">
                        <div class="card settings-card">
                            <div class="card-body">
                                <h5 class="card-title mb-4 settings-card-title">
                                    <i class="bi bi-palette me-2"></i>Apparence et thème
                                </h5>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input settings-radio" type="radio" name="appearance[theme]" value="dark" id="theme-dark"
                                                   {{ ($settings['appearance']['theme'] ?? 'dark') == 'dark' ? 'checked' : '' }}>
                                            <label class="form-check-label settings-radio-label" for="theme-dark">
                                                <i class="bi bi-moon me-2"></i>Thème sombre
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input settings-radio" type="radio" name="appearance[theme]" value="light" id="theme-light"
                                                   {{ ($settings['appearance']['theme'] ?? '') == 'light' ? 'checked' : '' }}>
                                            <label class="form-check-label settings-radio-label" for="theme-light">
                                                <i class="bi bi-sun me-2"></i>Thème clair
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input settings-radio" type="radio" name="appearance[theme]" value="auto" id="theme-auto"
                                                   {{ ($settings['appearance']['theme'] ?? '') == 'auto' ? 'checked' : '' }}>
                                            <label class="form-check-label settings-radio-label" for="theme-auto">
                                                <i class="bi bi-circle-half me-2"></i>Automatique (système)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Couleur d'accentuation</label>
                                    <div class="d-flex gap-3">
                                        @php
                                            $accentColor = $settings['appearance']['accent_color'] ?? '#00D9FF';
                                            $colors = ['#00D9FF', '#0066FF', '#8B5CF6', '#10B981', '#F59E0B'];
                                        @endphp
                                        @foreach($colors as $color)
                                            <div class="color-option settings-color-option {{ $color == $accentColor ? 'selected' : '' }}" 
                                                 data-color="{{ $color }}" data-name="appearance[accent_color]"></div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="appearance[accent_color]" value="{{ $accentColor }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Densité d'interface</label>
                                    <select name="appearance[density]" class="form-select settings-select">
                                        <option value="compact" {{ ($settings['appearance']['density'] ?? 'normal') == 'compact' ? 'selected' : '' }}>Compacte</option>
                                        <option value="normal" {{ ($settings['appearance']['density'] ?? 'normal') == 'normal' ? 'selected' : '' }}>Normale</option>
                                        <option value="comfort" {{ ($settings['appearance']['density'] ?? '') == 'comfort' ? 'selected' : '' }}>Confortable</option>
                                    </select>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary save-section-btn settings-section-btn" data-section="appearance">
                                    <i class="bi bi-check-circle me-2"></i>Enregistrer cette section
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Zone de danger -->
                    <div class="tab-pane fade" id="danger" role="tabpanel">
                        <div class="card settings-card-danger">
                            <div class="card-body">
                                <h5 class="card-title mb-4 settings-card-title-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Zone de danger
                                </h5>
                                
                                <div class="alert alert-danger settings-alert-danger">
                                    <i class="bi bi-exclamation-octagon me-2"></i>
                                    Ces actions sont irréversibles. Veuillez procéder avec prudence.
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3 settings-subsection-title">Export des données</h6>
                                    <form method="POST" action="{{ route('settings.export') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger settings-danger-btn me-3">
                                            <i class="bi bi-file-earmark-excel me-2"></i>Exporter toutes les données (CSV)
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="mb-3 settings-subsection-title">Réinitialisation</h6>
                                    <form method="POST" action="{{ route('settings.reset') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser les données de test ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <div class="form-check mb-3">
                                            <input class="form-check-input settings-checkbox-danger" type="checkbox" id="confirm-reset" required>
                                            <label class="form-check-label settings-checkbox-label-danger" for="confirm-reset">
                                                Je comprends que cette action supprimera toutes les données de test
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-danger settings-danger-btn">
                                            <i class="bi bi-trash3 me-2"></i>Supprimer les données de test
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* =========================================== */
    /* STYLES SPÉCIFIQUES POUR LA PAGE PARAMÈTRES */
    /* =========================================== */
    
    /* Titre principal */
    .settings-title {
        font-family: 'Orbitron', sans-serif;
        color: var(--anyx-cyan);
    }
    
    body.light-mode .settings-title {
        color: var(--anyx-blue);
    }
    
    /* Sous-titre */
    .settings-subtitle {
        color: var(--text-secondary);
    }
    
    body.light-mode .settings-subtitle {
        color: var(--light-text-secondary);
    }
    
    /* Bouton d'enregistrement */
    .settings-save-btn {
        background: linear-gradient(135deg, var(--anyx-cyan), var(--anyx-blue));
        border: none;
    }
    
    /* Barre de progression */
    .settings-progress-bar {
        height: 6px;
        background: var(--surface-2);
        border-radius: 3px;
    }
    
    .settings-progress-fill {
        background: linear-gradient(90deg, var(--anyx-cyan), var(--anyx-blue));
    }
    
    /* Texte secondaire */
    .settings-text-secondary {
        color: var(--text-secondary);
    }
    
    body.light-mode .settings-text-secondary {
        color: var(--light-text-secondary);
    }
    
    /* Carte de paramètres */
    .settings-card {
        background: var(--surface-1);
        border: 1px solid rgba(0, 217, 255, 0.1);
    }
    
    body.light-mode .settings-card {
        background: var(--light-surface-1);
        border: 1px solid rgba(0, 102, 255, 0.1);
    }
    
    /* Titre de carte */
    .settings-card-title {
        color: var(--anyx-cyan);
    }
    
    body.light-mode .settings-card-title {
        color: var(--anyx-blue);
    }
    
    /* Liens de navigation */
    .settings-nav-link {
        color: var(--text-secondary);
    }
    
    .settings-nav-link:hover,
    .settings-nav-link.active {
        color: var(--anyx-cyan);
        background: rgba(0, 217, 255, 0.1);
    }
    
    body.light-mode .settings-nav-link:hover,
    body.light-mode .settings-nav-link.active {
        color: var(--anyx-blue);
        background: rgba(0, 102, 255, 0.08);
    }
    
    /* Zone de danger */
    .settings-danger-section {
        border-top-color: rgba(255, 255, 255, 0.1);
    }
    
    body.light-mode .settings-danger-section {
        border-top-color: rgba(0, 0, 0, 0.1);
    }
    
    .settings-danger-link {
        color: #ef4444;
    }
    
    .settings-card-danger {
        background: var(--surface-1);
        border-color: #ef4444;
    }
    
    body.light-mode .settings-card-danger {
        background: var(--light-surface-1);
        border-color: #dc2626;
    }
    
    .settings-card-title-danger {
        color: #ef4444;
    }
    
    body.light-mode .settings-card-title-danger {
        color: #dc2626;
    }
    
    /* Alertes */
    .settings-alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        color: var(--text-primary);
    }
    
    body.light-mode .settings-alert-danger {
        background: rgba(220, 38, 38, 0.08);
        border-color: rgba(220, 38, 38, 0.2);
        color: var(--light-text-primary);
    }
    
    /* Boutons danger */
    .settings-danger-btn {
        background: #ef4444;
        border-color: #ef4444;
    }
    
    .settings-danger-btn:hover {
        background: #dc2626;
        border-color: #dc2626;
    }
    
    /* Checkboxes danger */
    .settings-checkbox-danger:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
    
    .settings-checkbox-label-danger {
        color: #ef4444;
    }
    
    body.light-mode .settings-checkbox-label-danger {
        color: #dc2626;
    }
    
    /* Inputs */
    .settings-input,
    .settings-select {
        background: var(--surface-2);
        border-color: rgba(0, 217, 255, 0.2);
        color: var(--text-primary);
    }
    
    body.light-mode .settings-input,
    body.light-mode .settings-select {
        background: var(--light-surface-1);
        border-color: rgba(0, 102, 255, 0.2);
        color: var(--light-text-primary);
    }
    
    .settings-input:focus,
    .settings-select:focus {
        border-color: var(--anyx-cyan);
        box-shadow: 0 0 0 0.2rem rgba(0, 217, 255, 0.25);
    }
    
    body.light-mode .settings-input:focus,
    body.light-mode .settings-select:focus {
        border-color: var(--anyx-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.25);
    }
    
    /* Input group */
    .settings-input-addon {
        background: var(--surface-3);
        border-color: rgba(0, 217, 255, 0.2);
        color: var(--text-secondary);
    }
    
    body.light-mode .settings-input-addon {
        background: var(--light-surface-2);
        border-color: rgba(0, 102, 255, 0.2);
        color: var(--light-text-secondary);
    }
    
    /* Checkboxes */
    .settings-checkbox {
        border-color: var(--text-secondary);
    }
    
    .settings-checkbox:checked {
        background-color: var(--anyx-cyan);
        border-color: var(--anyx-cyan);
    }
    
    body.light-mode .settings-checkbox:checked {
        background-color: var(--anyx-blue);
        border-color: var(--anyx-blue);
    }
    
    .settings-checkbox-label {
        color: var(--text-primary);
    }
    
    body.light-mode .settings-checkbox-label {
        color: var(--light-text-primary);
    }
    
    /* Radios */
    .settings-radio {
        border-color: var(--text-secondary);
    }
    
    .settings-radio:checked {
        background-color: var(--anyx-cyan);
        border-color: var(--anyx-cyan);
    }
    
    body.light-mode .settings-radio:checked {
        background-color: var(--anyx-blue);
        border-color: var(--anyx-blue);
    }
    
    .settings-radio-label {
        color: var(--text-primary);
    }
    
    body.light-mode .settings-radio-label {
        color: var(--light-text-primary);
    }
    
    /* Switches */
    .settings-switch {
        border-color: var(--text-secondary);
    }
    
    .settings-switch:checked {
        background-color: var(--anyx-cyan);
        border-color: var(--anyx-cyan);
    }
    
    body.light-mode .settings-switch:checked {
        background-color: var(--anyx-blue);
        border-color: var(--anyx-blue);
    }
    
    .settings-switch-label {
        color: var(--text-primary);
    }
    
    body.light-mode .settings-switch-label {
        color: var(--light-text-primary);
    }
    
    /* Sous-titres de section */
    .settings-subsection-title {
        color: var(--text-primary);
    }
    
    body.light-mode .settings-subsection-title {
        color: var(--light-text-primary);
    }
    
    /* Options de couleur */
    .settings-color-option {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    
    .settings-color-option.selected {
        border-color: var(--anyx-cyan);
        transform: scale(1.1);
    }
    
    body.light-mode .settings-color-option.selected {
        border-color: var(--anyx-blue);
    }
    
    /* Boutons de section */
    .settings-section-btn {
        border-color: var(--anyx-cyan);
        color: var(--anyx-cyan);
    }
    
    .settings-section-btn:hover {
        background-color: var(--anyx-cyan);
        color: white;
    }
    
    body.light-mode .settings-section-btn {
        border-color: var(--anyx-blue);
        color: var(--anyx-blue);
    }
    
    body.light-mode .settings-section-btn:hover {
        background-color: var(--anyx-blue);
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Gestion des onglets
    const triggerTabList = document.querySelectorAll('#settingsTab button')
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', event => {
            event.preventDefault()
            tabTrigger.show()
        })
    });

    // Sélection des couleurs
    $('.settings-color-option').click(function() {
        $('.settings-color-option').removeClass('selected');
        $(this).addClass('selected');
        $('input[name="appearance[accent_color]"]').val($(this).data('color'));
        
        // Appliquer immédiatement le changement de couleur
        document.documentElement.style.setProperty('--anyx-cyan', $(this).data('color'));
    });

    // Enregistrement par section
    $('.save-section-btn').click(function() {
        const section = $(this).data('section');
        const formData = $('#settingsForm').serializeArray();
        const sectionData = {};
        
        // Filtrer les données de la section
        formData.forEach(item => {
            if (item.name.startsWith(section + '[')) {
                sectionData[item.name] = item.value;
            }
        });
        
        // Envoyer via AJAX
        $.ajax({
            url: '{{ route("settings.save") }}',
            method: 'POST',
            data: sectionData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', 'Paramètres enregistrés', 'Les paramètres de la section ont été sauvegardés.');
                updateProgress();
            },
            error: function(xhr) {
                showToast('error', 'Erreur', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        });
    });

    // Enregistrement complet
    $('.save-all-btn').click(function() {
        $.ajax({
            url: '{{ route("settings.save") }}',
            method: 'POST',
            data: $('#settingsForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', 'Tous les paramètres enregistrés', 'Vos modifications ont été sauvegardées.');
                updateProgress();
            },
            error: function(xhr) {
                showToast('error', 'Erreur', 'Une erreur est survenue lors de l\'enregistrement.');
            }
        });
    });

    // Mettre à jour la barre de progression
    function updateProgress() {
        const totalFields = $('input, select, textarea').not('[type="hidden"]').length;
        let filledFields = 0;
        
        $('input, select, textarea').not('[type="hidden"]').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                if ($(this).is(':checked')) filledFields++;
            } else if ($(this).val().trim() !== '') {
                filledFields++;
            }
        });
        
        const percentage = Math.round((filledFields / totalFields) * 100);
        $('.settings-progress-fill').css('width', percentage + '%');
        $('.settings-progress small:first-child').text('Configuration à ' + percentage + '%');
        $('.settings-progress small:last-child').text(filledFields + '/' + totalFields + ' paramètres configurés');
    }

    // Toast notification
    function showToast(type, title, message) {
        // Créer le toast
        const toast = $(`
            <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        // Ajouter au conteneur
        $('#toastContainer').append(toast);
        
        // Afficher le toast
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        // Supprimer après fermeture
        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // Initialiser la progression
    updateProgress();
    
    // Mettre à jour la progression lors des changements
    $('input, select, textarea').on('change input', updateProgress);
});
</script>

<!-- Container pour les toasts -->
<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
@endsection