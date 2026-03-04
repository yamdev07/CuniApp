@extends('layouts.cuniapp')
@section('title', 'Paramètres - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-gear"></i> Paramètres de l'Application
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Paramètres</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-cuni success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="tabs-container">
        <button class="tab-btn active" data-tab="general-tab">
            <i class="bi bi-house"></i> Général
        </button>
        <button class="tab-btn" data-tab="breeding-tab">
            <i class="bi bi-heart-pulse"></i> Élevage
        </button>
        <button class="tab-btn" data-tab="system-tab">
            <i class="bi bi-palette"></i> Système & Notifications
        </button>
        <button class="tab-btn" data-tab="profile-tab">
            <i class="bi bi-person"></i> Profil
        </button>
    </div>

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
                    <i class="bi bi-building"></i> Informations de la Ferme
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
                            <i class="bi bi-save"></i> Enregistrer
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
                <h3 class="card-title"><i class="bi bi-egg"></i> Paramètres d'Élevage</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Jours de gestation (lapine)</label>
                            <input type="number" name="gestation_days" class="form-control"
                                value="{{ \App\Models\Setting::get('gestation_days', 31) }}" min="28" max="35">
                            <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Moyenne: 31 jours</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semaines de sevrage</label>
                            <input type="number" name="weaning_weeks" class="form-control"
                                value="{{ \App\Models\Setting::get('weaning_weeks', 6) }}" min="4" max="8">
                            <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Recommandé: 6 semaines</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seuil d'alerte (%)</label>
                            <input type="number" name="alert_threshold" class="form-control"
                                value="{{ \App\Models\Setting::get('alert_threshold', 80) }}" min="1"
                                max="100">
                            <small
                                style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Pour les notifications</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-calendar-check" style="color: var(--primary);"></i>
                                Délai initial de vérification (jours)</label>
                            <input type="number" name="verification_initial_days" class="form-control"
                                value="{{ \App\Models\Setting::get('verification_initial_days', 10) }}" min="5"
                                max="30">
                            <small
                                style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Après combien de jours envoyer la première
                                notification</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-bell" style="color: var(--accent-orange);"></i>
                                Délai premier rappel (jours)</label>
                            <input type="number" name="verification_reminder_days" class="form-control"
                                value="{{ \App\Models\Setting::get('verification_reminder_days', 15) }}" min="10"
                                max="60">
                            <small
                                style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Quand envoyer le premier rappel si non vérifié</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-arrow-repeat"
                                    style="color: var(--accent-purple);"></i> Intervalle des rappels (jours)</label>
                            <input type="number" name="verification_interval_days" class="form-control"
                                value="{{ \App\Models\Setting::get('verification_interval_days', 5) }}" min="1"
                                max="15">
                            <small
                                style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;"><i
                                    class="bi bi-info-circle"></i> Fréquence des rappels suivants</small>
                        </div>
                    </div>
                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn-cuni primary"><i class="bi bi-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-clipboard-data"></i> État des Vérifications en Attente</h3>
            </div>
            <div class="card-body">
                @php
                    // ✅ NEW - Order by relationship's date
$pendingVerifications = \App\Models\Naissance::pendingVerification()
    ->join('mises_bas', 'naissances.mise_bas_id', '=', 'mises_bas.id')
    ->orderBy('mises_bas.date_mise_bas', 'asc') // ← Use mise_bas date
    ->select('naissances.*')
                        ->limit(5)
                        ->get();
                    $totalPending = \App\Models\Naissance::pendingVerification()->count();
                @endphp
                @if ($totalPending > 0)
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div
                            style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-exclamation-triangle"
                                style="font-size: 24px; color: var(--accent-orange);"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $totalPending }} portée(s) en
                                attente de vérification</div>
                            <div style="font-size: 13px; color: var(--text-tertiary);">Sexe et date de naissance à
                                confirmer</div>
                        </div>
                    </div>
                    <div style="background: var(--surface-alt); border-radius: var(--radius-lg); padding: 16px;">
                        @foreach ($pendingVerifications as $naissance)
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: {{ !$loop->last ? '1px solid var(--surface-border)' : 'none' }};">
                                <div>
                                    <div style="font-weight: 500; color: var(--text-primary);">
                                        {{ $naissance->femelle->nom ?? 'N/A' }}<span
                                            style="color: var(--text-tertiary); font-size: 12px;">({{ $naissance->date_naissance->format('d/m/Y') }})</span>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-tertiary);">
                                        {{ $naissance->jours_depuis_naissance }} jours •
                                        {{ $naissance->reminder_count > 0 ? $naissance->reminder_count . ' rappel(s) envoyé(s)' : 'En attente' }}
                                    </div>
                                </div>
                                <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni sm secondary"><i
                                        class="bi bi-pencil"></i> Vérifier</a>
                            </div>
                        @endforeach
                    </div>
                    @if ($totalPending > 5)
                        <div style="text-align: center; margin-top: 16px;">
                            <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary">Voir toutes les
                                portées <i class="bi bi-arrow-right"></i></a>
                        </div>
                    @endif
                @else
                    <div style="text-align: center; padding: 32px; color: var(--text-tertiary);">
                        <i class="bi bi-check-circle"
                            style="font-size: 48px; color: var(--accent-green); margin-bottom: 16px; display: block;"></i>
                        <div style="font-weight: 500;">Toutes les portées sont à jour</div>
                        <div style="font-size: 13px; margin-top: 4px;">Aucune vérification en attente</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab Content: Système (Theme + Notifications) -->
    <div class="tab-content" id="system-tab">
        <!-- Theme Section -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-palette"></i> Apparence de l'Application
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom: 32px;">
                        <label class="form-label"
                            style="font-size: 15px; font-weight: 600; margin-bottom: 16px; display: block;">
                            <i class="bi bi-palette" style="color: var(--primary); margin-right: 8px;"></i> Thème de
                            l'application
                        </label>
                        <div class="theme-options"
                            style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                            <!-- System Theme -->
                            <label class="theme-option-card"
                                style="
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            padding: 24px;
                            border: 2px solid var(--surface-border);
                            border-radius: var(--radius-lg);
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: var(--surface-alt);
                        "
                                onmouseover="this.style.borderColor='var(--primary)'"
                                onmouseout="this.style.borderColor='var(--surface-border)'">
                                <input type="radio" name="theme" value="system"
                                    {{ (auth()->check() && auth()->user()->theme === 'system') || !auth()->user()->theme ? 'checked' : '' }}
                                    style="display: none;">
                                <div
                                    style="
                                width: 64px;
                                height: 64px;
                                border-radius: var(--radius-lg);
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-bottom: 16px;
                                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
                            ">
                                    <i class="bi bi-display" style="font-size: 32px; color: white;"></i>
                                </div>
                                <span
                                    style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">Système</span>
                                <span style="font-size: 12px; color: var(--text-tertiary); text-align: center;">Suit les
                                    paramètres de votre appareil</span>
                            </label>

                            <!-- Light Theme -->
                            <label class="theme-option-card"
                                style="
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            padding: 24px;
                            border: 2px solid var(--surface-border);
                            border-radius: var(--radius-lg);
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: var(--surface);
                        "
                                onmouseover="this.style.borderColor='var(--primary)'"
                                onmouseout="this.style.borderColor='var(--surface-border)'">
                                <input type="radio" name="theme" value="light"
                                    {{ auth()->check() && auth()->user()->theme === 'light' ? 'checked' : '' }}
                                    style="display: none;">
                                <div
                                    style="
                                width: 64px;
                                height: 64px;
                                border-radius: var(--radius-lg);
                                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-bottom: 16px;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                            ">
                                    <i class="bi bi-sun" style="font-size: 32px; color: #f59e0b;"></i>
                                </div>
                                <span
                                    style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">Clair</span>
                                <span style="font-size: 12px; color: var(--text-tertiary); text-align: center;">Thème
                                    lumineux et épuré</span>
                            </label>

                            <!-- Dark Theme -->
                            <label class="theme-option-card"
                                style="
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            padding: 24px;
                            border: 2px solid var(--surface-border);
                            border-radius: var(--radius-lg);
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background: #1a1a2e;
                        "
                                onmouseover="this.style.borderColor='var(--primary)'"
                                onmouseout="this.style.borderColor='var(--surface-border)'">
                                <input type="radio" name="theme" value="dark"
                                    {{ auth()->check() && auth()->user()->theme === 'dark' ? 'checked' : '' }}
                                    style="display: none;">
                                <div
                                    style="
                                width: 64px;
                                height: 64px;
                                border-radius: var(--radius-lg);
                                background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 100%);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-bottom: 16px;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                                border: 1px solid rgba(255, 255, 255, 0.1);
                            ">
                                    <i class="bi bi-moon-stars" style="font-size: 32px; color: #4da6ff;"></i>
                                </div>
                                <span style="font-weight: 600; color: white; margin-bottom: 4px;">Sombre</span>
                                <span
                                    style="font-size: 12px; color: rgba(255, 255, 255, 0.6); text-align: center;">Confortable
                                    pour les yeux</span>
                            </label>
                        </div>
                    </div>
                    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-save"></i> Enregistrer le thème
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-bell"></i> Préférences de Notifications
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <hr style="border: none; border-top: 1px solid var(--surface-border); margin: 32px 0;">

                    <div class="form-group">
                        <label class="form-label"
                            style="font-size: 15px; font-weight: 600; margin-bottom: 24px; display: block;">
                            <i class="bi bi-bell-fill" style="color: var(--primary); margin-right: 8px;"></i> Canaux de
                            Notification
                        </label>

                        <!-- Email Notifications Toggle -->
                        <div class="toggle-setting"
                            style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 20px;
                        background: var(--surface-alt);
                        border-radius: var(--radius-lg);
                        margin-bottom: 16px;
                        border: 1px solid var(--surface-border);
                    ">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div
                                    style="
                                width: 48px;
                                height: 48px;
                                border-radius: var(--radius-md);
                                background: rgba(37, 99, 235, 0.1);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                    <i class="bi bi-envelope" style="font-size: 24px; color: var(--primary);"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                                        Notifications par email</div>
                                    <div style="font-size: 13px; color: var(--text-tertiary);">Recevez les alertes
                                        importantes par courrier électronique</div>
                                </div>
                            </div>
                            <label class="toggle-switch"
                                style="
                            position: relative;
                            display: inline-block;
                            width: 60px;
                            height: 34px;
                        ">
                                <input type="checkbox" name="notifications_email"
                                    {{ auth()->check() && auth()->user()->notifications_email ? 'checked' : '' }}
                                    style="opacity: 0; width: 0; height: 0;">
                                <span class="toggle-slider"
                                    style="
                                position: absolute;
                                cursor: pointer;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background-color: var(--gray-300);
                                transition: 0.4s;
                                border-radius: 34px;
                            "></span>
                                <span class="toggle-knob"
                                    style="
                                position: absolute;
                                content: '';
                                height: 26px;
                                width: 26px;
                                left: 4px;
                                bottom: 4px;
                                background-color: white;
                                transition: 0.4s;
                                border-radius: 50%;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                            "></span>
                            </label>
                        </div>

                        <!-- Dashboard Notifications Toggle -->
                        <div class="toggle-setting"
                            style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 20px;
                        background: var(--surface-alt);
                        border-radius: var(--radius-lg);
                        margin-bottom: 16px;
                        border: 1px solid var(--surface-border);
                    ">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div
                                    style="
                                width: 48px;
                                height: 48px;
                                border-radius: var(--radius-md);
                                background: rgba(16, 185, 129, 0.1);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                    <i class="bi bi-bell" style="font-size: 24px; color: var(--accent-green);"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                                        Notifications sur le dashboard</div>
                                    <div style="font-size: 13px; color: var(--text-tertiary);">Affiche les alertes en temps
                                        réel dans l'application</div>
                                </div>
                            </div>
                            <label class="toggle-switch"
                                style="
                            position: relative;
                            display: inline-block;
                            width: 60px;
                            height: 34px;
                        ">
                                <input type="checkbox" name="notifications_dashboard"
                                    {{ auth()->check() && auth()->user()->notifications_dashboard ? 'checked' : '' }}
                                    style="opacity: 0; width: 0; height: 0;">
                                <span class="toggle-slider"
                                    style="
                                position: absolute;
                                cursor: pointer;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background-color: var(--gray-300);
                                transition: 0.4s;
                                border-radius: 34px;
                            "></span>
                                <span class="toggle-knob"
                                    style="
                                position: absolute;
                                content: '';
                                height: 26px;
                                width: 26px;
                                left: 4px;
                                bottom: 4px;
                                background-color: white;
                                transition: 0.4s;
                                border-radius: 50%;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                            "></span>
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-save"></i> Enregistrer les préférences
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
                    <i class="bi bi-person-circle"></i> Mon Profil Utilisateur
                </h3>
            </div>
            <div class="card-body">
                @if (session('success') && session('active_tab') == 'profile-tab')
                    <div class="alert-custom alert-custom-success">
                        <i class="bi bi-check-circle alert-icon"></i>
                        {{ session('success') }}
                    </div>
                @endif

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
                            <i class="bi bi-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Toggle Switch Styles */
        input[type="checkbox"]:checked+.toggle-slider {
            background-color: var(--primary);
        }

        input[type="checkbox"]:checked+.toggle-slider+.toggle-knob {
            transform: translateX(26px);
        }

        input[type="checkbox"]:focus+.toggle-slider {
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }

        /* Theme Option Cards */
        .theme-option-card:has(input[type="radio"]:checked) {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }

        /* Alert Styles */
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

        .alert-icon {
            font-size: 1.4rem;
            margin-right: 15px;
        }

        .field-error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
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

            .theme-options {
                grid-template-columns: 1fr !important;
            }
        }

        .form-control,
        .form-select,
        .tab-content .form-control {
            background-color: var(--surface-alt) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--surface-border) !important;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text-primary) !important;
            -webkit-box-shadow: 0 0 0px 1000px var(--surface-alt) inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-control:focus {
            background-color: var(--surface) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-subtle) !important;
            color: var(--text-primary) !important;
        }

        .form-control::placeholder {
            color: var(--text-tertiary) !important;
            opacity: 0.5;
        }

        .alert-custom-success {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border-left: 5px solid #22c55e;
        }

        .alert-custom-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border-left: 5px solid #ef4444;
        }

        /* Dark Mode Support for Toggles */
        .theme-dark .toggle-setting {
            background: var(--surface-elevated);
        }

        .theme-dark input[type="checkbox"]:checked+.toggle-slider {
            background-color: var(--primary);
        }

        /* Tabs Styles */
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
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            border-radius: var(--radius) var(--radius) 0 0;
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
            margin-right: 6px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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

                // Update URL hash for direct linking
                window.location.hash = tabId;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Check URL hash first
            const hash = window.location.hash.replace('#', '');
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            const savedTab = localStorage.getItem('cuniapp_active_tab');
            const sessionTab = "{{ session('active_tab') }}";

            const targetTab = hash || sessionTab || tabFromUrl || savedTab || 'general-tab';
            const tabBtn = document.querySelector(`.tab-btn[data-tab="${targetTab}"]`);

            if (tabBtn) {
                tabBtn.click();
            }

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
