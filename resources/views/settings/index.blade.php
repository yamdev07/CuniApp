@extends('layouts.cuniapp')

@section('title', 'Paramètres - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-gear"></i>
            Paramètres de l'Application
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Paramètres</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert-cuni success">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

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
        <i class="bi bi-house"></i>
        Général
    </button>
    <button class="tab-btn" data-tab="breeding-tab">
        <i class="bi bi-heart-pulse"></i>
        Élevage
    </button>
    <button class="tab-btn" data-tab="profile-tab">
        <i class="bi bi-person"></i>
        Profil
    </button>
    <button class="tab-btn" data-tab="system-tab">
        <i class="bi bi-hdd"></i>
        Système
    </button>
</div>

<!-- Tab Content: Général -->
<div class="tab-content active" id="general-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-building"></i>
                Informations de la Ferme
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Nom de la ferme *</label>
                        <input type="text" name="farm_name" class="form-control" value="{{ \App\Models\Setting::get('farm_name', '') }}" placeholder="Ex: Ferme Lapin d'Or">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="farm_email" class="form-control" value="{{ \App\Models\Setting::get('farm_email', '') }}" placeholder="contact@ferme.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="farm_phone" class="form-control" value="{{ \App\Models\Setting::get('farm_phone', '') }}" placeholder="+33 6 00 00 00 00">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="farm_address" class="form-control" value="{{ \App\Models\Setting::get('farm_address', '') }}" placeholder="Adresse complète">
                    </div>
                </div>
                
                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tab Content: Élevage -->
<div class="tab-content" id="breeding-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-egg"></i>
                Paramètres d'Élevage
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Jours de gestation (lapine)</label>
                        <input type="number" name="gestation_days" class="form-control" value="{{ \App\Models\Setting::get('gestation_days', 31) }}" min="28" max="35">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Moyenne: 31 jours
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Semaines de sevrage</label>
                        <input type="number" name="weaning_weeks" class="form-control" value="{{ \App\Models\Setting::get('weaning_weeks', 6) }}" min="4" max="8">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Recommandé: 6 semaines
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Seuil d'alerte (%)</label>
                        <input type="number" name="alert_threshold" class="form-control" value="{{ \App\Models\Setting::get('alert_threshold', 80) }}" min="1" max="100">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Pour les notifications
                        </small>
                    </div>
                </div>
                
                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tab Content: Profil -->
<div class="tab-content" id="profile-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-person-circle"></i>
                Mon Profil Utilisateur
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.profile') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Nom complet *</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Obligatoire pour changer le mot de passe
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirmer mot de passe</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="••••••••">
                    </div>
                </div>
                
                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-save"></i>
                        Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tab Content: Système -->
<div class="tab-content" id="system-tab">
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-gear"></i>
                Préférences Système
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Thème de l'application</label>
                        <select name="theme" class="form-select">
                            <option value="dark" {{ \App\Models\Setting::get('theme', 'dark') == 'dark' ? 'selected' : '' }}>
                                Sombre
                            </option>
                            <option value="light" {{ \App\Models\Setting::get('theme', 'dark') == 'light' ? 'selected' : '' }}>
                                Clair
                            </option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Langue</label>
                        <select name="language" class="form-select">
                            <option value="fr" {{ \App\Models\Setting::get('language', 'fr') == 'fr' ? 'selected' : '' }}>
                                Français
                            </option>
                            <option value="en" {{ \App\Models\Setting::get('language', 'fr') == 'en' ? 'selected' : '' }}>
                                English
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Notification Preferences Section -->
                <div class="form-section" style="margin-top: 20px; border-top: 1px solid var(--surface-border); padding-top: 20px;">
                    <h4 class="section-subtitle">
                        <i class="bi bi-bell"></i>
                        Préférences de Notifications
                    </h4>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 12px; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--surface-border);">
                            <input type="checkbox" name="notifications_email" {{ auth()->user()->notifications_email ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                            <div>
                                <span class="form-label" style="margin: 0; font-weight: 600;">📧 Notifications par email</span>
                                <p style="color: var(--text-secondary); font-size: 13px; margin-top: 4px;">
                                    Recevez un email pour chaque activité importante (saillies, naissances, etc.)
                                </p>
                            </div>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 12px; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--surface-border);">
                            <input type="checkbox" name="notifications_dashboard" {{ auth()->user()->notifications_dashboard ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                            <div>
                                <span class="form-label" style="margin: 0; font-weight: 600;">🔔 Notifications tableau de bord</span>
                                <p style="color: var(--text-secondary); font-size: 13px; margin-top: 4px;">
                                    Affichez les notifications dans le widget du tableau de bord
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-save"></i>
                        Enregistrer les préférences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Enhanced Tab Styling */
    .tabs-container {
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--surface-border);
        padding-bottom: 0;
        overflow-x: auto;
        background: var(--surface);
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        padding: 8px 8px 0 8px;
    }

    .tab-btn {
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        border-radius: var(--radius) var(--radius) 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn:hover {
        color: var(--text-primary);
        background: var(--gray-50);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: var(--primary-subtle);
    }

    .tab-btn i {
        font-size: 16px;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Enhancements */
    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-subtle);
    }

    /* Checkbox Styling */
    input[type="checkbox"] {
        accent-color: var(--primary);
        cursor: pointer;
    }

    /* Notification Section */
    .form-section {
        background: var(--surface-alt);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--surface-border);
    }

    .section-subtitle {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-subtitle i {
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tabs-container {
            padding: 4px 4px 0 4px;
        }
        
        .tab-btn {
            padding: 10px 14px;
            font-size: 13px;
        }
        
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
            
            // Save to localStorage
            localStorage.setItem('cuniapp_active_tab', tabId);
        });
    });

    // Restore active tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTab = localStorage.getItem('cuniapp_active_tab');
        if (savedTab) {
            const tabBtn = document.querySelector(`.tab-btn[data-tab="${savedTab}"]`);
            if (tabBtn) {
                tabBtn.click();
            }
        }

        // Add fade-in animation to cards
        const cards = document.querySelectorAll('.cuni-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(10px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>
@endsection