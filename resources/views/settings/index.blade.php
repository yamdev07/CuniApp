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
        <button class="tab-btn" data-tab="notifications-tab">
            <i class="bi bi-bell"></i>
            Notifications
        </button>
        <button class="tab-btn" data-tab="system-tab">
            <i class="bi bi-hdd"></i>
            Système
        </button>
    </div>

     @if (session('success'))
        <div class="alert-custom alert-custom-success">
            <i class="bi bi-check-circle alert-icon"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any() && session('active_tab') !== 'profile-tab')
        <div class="alert-custom alert-custom-danger">
            <i class="bi bi-exclamation-octagon alert-icon"></i>
            <div>
                <strong>Erreurs détectées :</strong>
                <ul style="margin: 5px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
                            <input type="text" name="farm_name" class="form-control"
                                value="{{ \App\Models\Setting::get('farm_name', '') }}" placeholder="Ex: Ferme Lapin d'Or">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="farm_email" class="form-control"
                                value="{{ \App\Models\Setting::get('farm_email', '') }}" placeholder="contact@ferme.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="farm_phone" class="form-control"
                                value="{{ \App\Models\Setting::get('farm_phone', '') }}" placeholder="+33 6 00 00 00 00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="farm_address" class="form-control"
                                value="{{ \App\Models\Setting::get('farm_address', '') }}" placeholder="Adresse complète">
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
                            <input type="number" name="gestation_days" class="form-control"
                                value="{{ \App\Models\Setting::get('gestation_days', 31) }}" min="28" max="35">
                            <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                                <i class="bi bi-info-circle"></i> Moyenne: 31 jours
                            </small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semaines de sevrage</label>
                            <input type="number" name="weaning_weeks" class="form-control"
                                value="{{ \App\Models\Setting::get('weaning_weeks', 6) }}" min="4" max="8">
                            <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                                <i class="bi bi-info-circle"></i> Recommandé: 6 semaines
                            </small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seuil d'alerte (%)</label>
                            <input type="number" name="alert_threshold" class="form-control"
                                value="{{ \App\Models\Setting::get('alert_threshold', 80) }}" min="1"
                                max="100">
                            <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                                <i class="bi bi-info-circle"></i> Pour les notifications
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
                @if (session('success') && session('active_tab') == 'profile-tab')
                    <div class="alert-custom alert-custom-success">
                        <i class="bi bi-check-circle alert-icon"></i>
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Bloc d'erreurs global --}}
                @if ($errors->any())
                    <div class="alert-custom alert-custom-danger">
                        <i class="bi bi-exclamation-octagon alert-icon"></i>
                        <div>
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('settings.updateProfile') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer nouveau mot de passe</label>
                            <input type="password" name="new_password_confirmation" class="form-control"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-save"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tab Content: Notifications -->
    <div class="tab-content" id="notifications-tab">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-bell"></i>
                    Préférences de Notifications
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Thème de l'application</label>
                            <select name="theme" class="form-select">
                                <option value="dark"
                                    {{ \App\Models\Setting::get('theme', 'dark') == 'dark' ? 'selected' : '' }}>
                                    Sombre
                                </option>
                                <option value="light"
                                    {{ \App\Models\Setting::get('theme', 'dark') == 'light' ? 'selected' : '' }}>
                                    Clair
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Langue</label>
                            <select name="language" class="form-select">
                                <option value="fr"
                                    {{ \App\Models\Setting::get('language', 'fr') == 'fr' ? 'selected' : '' }}>
                                    Français
                                </option>
                                <option value="en"
                                    {{ \App\Models\Setting::get('language', 'fr') == 'en' ? 'selected' : '' }}>
                                    English
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="notifications_email"
                                    {{ \App\Models\Setting::get('notifications_email', '0') == '1' ? 'checked' : '' }}
                                    style="width: 18px; height: 18px;">
                                <span class="form-label" style="margin: 0;">Notifications par email</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="notifications_dashboard"
                                    {{ \App\Models\Setting::get('notifications_dashboard', '0') == '1' ? 'checked' : '' }}
                                    style="width: 18px; height: 18px;">
                                <span class="form-label" style="margin: 0;">Notifications sur le dashboard</span>
                            </label>
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

    <!-- Tab Content: Système -->
    <div class="tab-content" id="system-tab">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-hdd"></i>
                    Gestion du Système
                </h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <!-- Export Data -->
                    <div class="cuni-card" style="margin: 0; background: var(--surface-alt);">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div
                                style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-download" style="color: var(--accent-cyan); font-size: 20px;"></i>
                            </div>
                            <h4 style="font-size: 15px; font-weight: 600; color: var(--gray-800); margin: 0;">
                                Exporter les données
                            </h4>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 16px;">
                            Télécharger toutes les données de l'élevage au format JSON
                        </p>
                        <a href="{{ route('settings.export') }}" class="btn-cuni secondary">
                            <i class="bi bi-download"></i>
                            Exporter
                        </a>
                    </div>

                    <!-- Clear Cache -->
                    <div class="cuni-card" style="margin: 0; background: var(--surface-alt);">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div
                                style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-trash" style="color: var(--accent-orange); font-size: 20px;"></i>
                            </div>
                            <h4 style="font-size: 15px; font-weight: 600; color: var(--gray-800); margin: 0;">
                                Vider le cache
                            </h4>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 13px; margin-bottom: 16px;">
                            Effacer le cache de l'application pour résoudre les problèmes
                        </p>
                        <form action="{{ route('settings.clear-cache') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-cuni danger"
                                onclick="return confirm('Voulez-vous vraiment vider le cache ?')">
                                <i class="bi bi-trash"></i>
                                Vider le cache
                            </button>
                        </form>
                    </div>
                </div>

                <!-- System Info -->
                <div class="cuni-card" style="margin-top: 24px; background: var(--surface-alt);">
                    <div class="card-header-custom" style="background: transparent; padding: 0 0 16px 0; border: none;">
                        <h3 class="card-title">
                            <i class="bi bi-info-circle"></i>
                            Informations Système
                        </h3>
                    </div>
                    <div class="settings-grid">
                        <div>
                            <small style="color: var(--text-tertiary); font-size: 12px;">Version</small>
                            <div style="font-weight: 600; color: var(--gray-800);">1.0.0</div>
                        </div>
                        <div>
                            <small style="color: var(--text-tertiary); font-size: 12px;">PHP</small>
                            <div style="font-weight: 600; color: var(--gray-800);">{{ phpversion() }}</div>
                        </div>
                        <div>
                            <small style="color: var(--text-tertiary); font-size: 12px;">Laravel</small>
                            <div style="font-weight: 600; color: var(--gray-800);">{{ app()->version() }}</div>
                        </div>
                        <div>
                            <small style="color: var(--text-tertiary); font-size: 12px;">Environnement</small>
                            <div style="font-weight: 600; color: var(--gray-800);">{{ app()->environment() }}</div>
                        </div>
                    </div>
                </div>
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


        /* Alertes personnalisées avec bordure gauche */
        .alert-custom {
            border: none;
            border-left: 5px solid;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .alert-custom-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #22c55e;
        }

        .alert-custom-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #ef4444;
        }

        .alert-icon {
            font-size: 1.4rem;
            margin-right: 15px;
        }

        /* Erreur sous le champ spécifique */
        .field-error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Form Enhancements & Grid */
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }



        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
                localStorage.setItem('cuniapp_active_tab', tabId);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            const savedTab = localStorage.getItem('cuniapp_active_tab');

            // On récupère l'onglet actif depuis la session Laravel ou l'URL
            const sessionTab = "{{ session('active_tab') }}";
            const targetTab = sessionTab || tabFromUrl || savedTab || 'general-tab';

            const tabBtn = document.querySelector(`.tab-btn[data-tab="${targetTab}"]`);
            if (tabBtn) {
                tabBtn.click();
            }

            // Animation de disparition automatique des alertes après 5 secondes
            const alerts = document.querySelectorAll('.alert-custom');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endsection
