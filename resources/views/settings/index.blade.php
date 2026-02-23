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
                                        <input class="form-check-input settings-switch" type="checkbox" 
                                               name="notifications[saillies_reminder]" 
                                               id="notif-saillies"
                                               {{ ($settings['notifications']['saillies_reminder'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-switch-label" for="notif-saillies">Rappels de saillies</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" 
                                               name="notifications[birth_alerts]" 
                                               id="notif-mises-bas"
                                               {{ ($settings['notifications']['birth_alerts'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-switch-label" for="notif-mises-bas">Alertes de mises bas</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" 
                                               name="notifications[vaccine_reminders]" 
                                               id="notif-vaccins"
                                               {{ ($settings['notifications']['vaccine_reminders'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-switch-label" for="notif-vaccins">Rappels de vaccins</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" 
                                               name="notifications[health_alerts]" 
                                               id="notif-sante"
                                               {{ ($settings['notifications']['health_alerts'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-switch-label" for="notif-sante">Alertes santé</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input settings-switch" type="checkbox" 
                                               name="notifications[email_enabled]" 
                                               id="notif-email"
                                               {{ ($settings['notifications']['email_enabled'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label settings-switch-label" for="notif-email">Activer les notifications email</label>
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
                                                 style="background-color: {{ $color }};"
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
                                            <i class="bi bi-file-earmark-excel me-2"></i>Exporter toutes les données (JSON)
                                        </button>
                                        <small class="text-muted">Télécharge un fichier JSON contenant tous vos paramètres.</small>
                                    </form>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="mb-3 settings-subsection-title">Réinitialisation des paramètres</h6>
                                    <form method="POST" action="{{ route('settings.reset') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres aux valeurs par défaut ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <div class="form-check mb-3">
                                            <input class="form-check-input settings-checkbox-danger" type="checkbox" id="confirm-reset" required>
                                            <label class="form-check-label settings-checkbox-label-danger" for="confirm-reset">
                                                Je comprends que cette action réinitialisera tous les paramètres aux valeurs par défaut
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-danger settings-danger-btn">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser aux paramètres par défaut
                                        </button>
                                        <small class="text-muted d-block mt-2">Cela n'affecte pas vos données d'élevage (lapins, saillies, naissances).</small>
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
        transition: all 0.3s;
    }
    
    .settings-save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 102, 255, 0.3);
    }
    
    /* Barre de progression */
    .settings-progress-bar {
        height: 6px;
        background: var(--surface-2);
        border-radius: 3px;
        overflow: hidden;
    }
    
    .settings-progress-fill {
        background: linear-gradient(90deg, var(--anyx-cyan), var(--anyx-blue));
        transition: width 0.5s ease;
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
        border-radius: 12px;
        transition: all 0.3s;
    }
    
    .settings-card:hover {
        border-color: rgba(0, 217, 255, 0.3);
        box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
    }
    
    body.light-mode .settings-card {
        background: var(--light-surface-1);
        border: 1px solid rgba(0, 102, 255, 0.1);
    }
    
    body.light-mode .settings-card:hover {
        border-color: rgba(0, 102, 255, 0.3);
        box-shadow: 0 5px 20px rgba(0, 102, 255, 0.1);
    }
    
    /* Titre de carte */
    .settings-card-title {
        color: var(--anyx-cyan);
        border-bottom: 2px solid rgba(0, 217, 255, 0.2);
        padding-bottom: 10px;
    }
    
    body.light-mode .settings-card-title {
        color: var(--anyx-blue);
        border-bottom: 2px solid rgba(0, 102, 255, 0.2);
    }
    
    /* Liens de navigation */
    .settings-nav-link {
        color: var(--text-secondary);
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 5px;
        transition: all 0.3s;
        border: none;
        background: transparent;
        text-align: left;
        width: 100%;
    }
    
    .settings-nav-link:hover,
    .settings-nav-link.active {
        color: var(--anyx-cyan);
        background: rgba(0, 217, 255, 0.1);
        transform: translateX(5px);
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
    
    .settings-danger-link:hover {
        color: #dc2626;
        background: rgba(239, 68, 68, 0.1);
    }
    
    .settings-card-danger {
        background: var(--surface-1);
        border: 2px solid #ef4444;
        border-radius: 12px;
    }
    
    body.light-mode .settings-card-danger {
        background: var(--light-surface-1);
        border: 2px solid #dc2626;
    }
    
    .settings-card-title-danger {
        color: #ef4444;
        border-bottom: 2px solid rgba(239, 68, 68, 0.3);
        padding-bottom: 10px;
    }
    
    body.light-mode .settings-card-title-danger {
        color: #dc2626;
        border-bottom: 2px solid rgba(220, 38, 38, 0.3);
    }
    
    /* Alertes */
    .settings-alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        color: var(--text-primary);
    }
    
    body.light-mode .settings-alert-danger {
        background: rgba(220, 38, 38, 0.08);
        border: 1px solid rgba(220, 38, 38, 0.2);
        color: var(--light-text-primary);
    }
    
    /* Boutons danger */
    .settings-danger-btn {
        background: #ef4444;
        border-color: #ef4444;
        transition: all 0.3s;
    }
    
    .settings-danger-btn:hover {
        background: #dc2626;
        border-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
    }
    
    /* Checkboxes danger */
    .settings-checkbox-danger {
        border-color: var(--text-secondary);
    }
    
    .settings-checkbox-danger:checked {
        background-color: #ef4444;
        border-color: #ef4444;
    }
    
    body.light-mode .settings-checkbox-danger:checked {
        background-color: #dc2626;
        border-color: #dc2626;
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
        border: 1px solid rgba(0, 217, 255, 0.2);
        border-radius: 8px;
        color: var(--text-primary);
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .settings-input:focus,
    .settings-select:focus {
        border-color: var(--anyx-cyan);
        box-shadow: 0 0 0 0.2rem rgba(0, 217, 255, 0.25);
        background: var(--surface-3);
    }
    
    body.light-mode .settings-input,
    body.light-mode .settings-select {
        background: var(--light-surface-1);
        border: 1px solid rgba(0, 102, 255, 0.2);
        color: var(--light-text-primary);
    }
    
    body.light-mode .settings-input:focus,
    body.light-mode .settings-select:focus {
        border-color: var(--anyx-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.25);
        background: var(--light-surface-2);
    }
    
    /* Input group */
    .settings-input-addon {
        background: var(--surface-3);
        border: 1px solid rgba(0, 217, 255, 0.2);
        color: var(--text-secondary);
        border-radius: 0 8px 8px 0;
    }
    
    body.light-mode .settings-input-addon {
        background: var(--light-surface-2);
        border: 1px solid rgba(0, 102, 255, 0.2);
        color: var(--light-text-secondary);
    }
    
    /* Checkboxes */
    .settings-checkbox {
        border-color: var(--text-secondary);
        width: 18px;
        height: 18px;
        cursor: pointer;
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
        cursor: pointer;
        padding-left: 5px;
    }
    
    body.light-mode .settings-checkbox-label {
        color: var(--light-text-primary);
    }
    
    /* Radios */
    .settings-radio {
        border-color: var(--text-secondary);
        width: 18px;
        height: 18px;
        cursor: pointer;
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
        cursor: pointer;
        padding-left: 5px;
    }
    
    body.light-mode .settings-radio-label {
        color: var(--light-text-primary);
    }
    
    /* Switches */
    .settings-switch {
        border-color: var(--text-secondary);
        width: 48px;
        height: 24px;
        cursor: pointer;
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
        cursor: pointer;
        padding-left: 10px;
    }
    
    body.light-mode .settings-switch-label {
        color: var(--light-text-primary);
    }
    
    /* Sous-titres de section */
    .settings-subsection-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid rgba(0, 217, 255, 0.1);
    }
    
    body.light-mode .settings-subsection-title {
        color: var(--light-text-primary);
        border-bottom: 1px solid rgba(0, 102, 255, 0.1);
    }
    
    /* Options de couleur */
    .settings-color-option {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    .settings-color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .settings-color-option.selected {
        border-color: var(--anyx-cyan);
        transform: scale(1.15);
        box-shadow: 0 0 15px var(--anyx-cyan);
    }
    
    body.light-mode .settings-color-option.selected {
        border-color: var(--anyx-blue);
        box-shadow: 0 0 15px var(--anyx-blue);
    }
    
    /* Boutons de section */
    .settings-section-btn {
        border: 2px solid var(--anyx-cyan);
        color: var(--anyx-cyan);
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
        background: transparent;
    }
    
    .settings-section-btn:hover {
        background-color: var(--anyx-cyan);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
    }
    
    body.light-mode .settings-section-btn {
        border: 2px solid var(--anyx-blue);
        color: var(--anyx-blue);
    }
    
    body.light-mode .settings-section-btn:hover {
        background-color: var(--anyx-blue);
        color: white;
        box-shadow: 0 5px 15px rgba(0, 102, 255, 0.3);
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
        
        // Si on est en mode clair, mettre à jour aussi
        if (document.body.classList.contains('light-mode')) {
            document.documentElement.style.setProperty('--anyx-blue', $(this).data('color'));
        }
    });

    // Enregistrement par section
    $('.save-section-btn').click(function() {
        const section = $(this).data('section');
        let url = '';
        
        // Déterminer l'URL en fonction de la section
        switch(section) {
            case 'general':
                url = '{{ route("settings.save.general") }}';
                break;
            case 'elevage':
                url = '{{ route("settings.save.elevage") }}';
                break;
            case 'appearance':
                url = '{{ route("settings.save.appearance") }}';
                break;
            case 'notifications':
                url = '{{ route("settings.save.notifications") }}';
                break;
            default:
                url = '{{ route("settings.save") }}';
        }
        
        // Préparer les données
        const formData = new FormData();
        const sectionData = {};
        
        // Collecter les données de la section
        $(`#${section} input, #${section} select, #${section} textarea`).each(function() {
            if ($(this).attr('name')) {
                const name = $(this).attr('name');
                const value = $(this).is(':checkbox') ? $(this).is(':checked') : $(this).val();
                
                if ($(this).is(':checkbox') && $(this).attr('name').endsWith('[]')) {
                    // Pour les checkboxes multiples
                    if (!sectionData[name]) sectionData[name] = [];
                    if ($(this).is(':checked')) {
                        sectionData[name].push(value);
                    }
                } else {
                    sectionData[name] = value;
                }
            }
        });
        
        // Ajouter le token CSRF
        sectionData['_token'] = $('meta[name="csrf-token"]').attr('content');
        
        // Envoyer via AJAX
        $.ajax({
            url: url,
            method: 'POST',
            data: sectionData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', '✅ Paramètres enregistrés', 'Les paramètres de la section ont été sauvegardés avec succès.');
                updateProgress();
                
                // Recharger la page si c'est l'apparence pour appliquer les changements
                if (section === 'appearance') {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                showToast('error', '❌ Erreur', 'Une erreur est survenue lors de l\'enregistrement.');
                console.error('Erreur:', xhr.responseText);
            }
        });
    });

    // Enregistrement complet
    $('.save-all-btn').click(function() {
        const formData = $('#settingsForm').serialize();
        
        $.ajax({
            url: '{{ route("settings.save") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', '✅ Tous les paramètres enregistrés', 'Vos modifications ont été sauvegardées avec succès.');
                updateProgress();
                
                // Recharger la page pour appliquer les changements
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                showToast('error', '❌ Erreur', 'Une erreur est survenue lors de l\'enregistrement.');
                console.error('Erreur:', xhr.responseText);
            }
        });
    });

    // Mettre à jour la barre de progression
    function updateProgress() {
        const totalFields = $('input:not([type="hidden"]), select, textarea').not('[type="submit"]').length;
        let filledFields = 0;
        
        $('input:not([type="hidden"]), select, textarea').not('[type="submit"]').each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                if ($(this).is(':checked')) filledFields++;
            } else if ($(this).is('select')) {
                if ($(this).val() !== '') filledFields++;
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
        const bsToast = new bootstrap.Toast(toast[0], {
            autohide: true,
            delay: 5000
        });
        bsToast.show();
        
        // Supprimer après fermeture
        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // Initialiser la progression
    updateProgress();
    
    // Mettre à jour la progression lors des changements
    $('input, select, textarea').on('change input', function() {
        setTimeout(updateProgress, 100);
    });
    
    // Gestion des checkboxes multiples (types de lapins)
    $('input[name="elevage[rabbit_types][]"]').on('change', updateProgress);
    
    // Gestion des switches de notifications
    $('input[type="checkbox"][name^="notifications"]').on('change', updateProgress);
    
    // Gestion des radios du thème
    $('input[type="radio"][name="appearance[theme]"]').on('change', updateProgress);
});
</script>

<!-- Container pour les toasts -->
<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
@endsection