@extends('layouts.cuniapp')
@section('title', 'Modifier Naissance - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-pencil-square"></i> Modifier la Naissance</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('naissances.index') }}">Naissances</a>
                <span>/</span>
                <span>Modification</span>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><strong>Erreurs de validation</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title"><i class="bi bi-egg-fill"></i> Informations de la Naissance</h3>
            <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary"><i class="bi bi-arrow-left"></i>
                Retour</a>
        </div>
        <div class="card-body">
            <form action="{{ route('naissances.update', $naissance) }}" method="POST">
                @csrf @method('PUT')
                @if (!$naissance->sex_verified)
                    <div
                        style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-lg); padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 24px; color: var(--accent-orange);"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--accent-orange);">Vérification en attente</div>
                            <div style="font-size: 13px; color: var(--text-secondary);">Cette portée a
                                {{ $naissance->jours_depuis_naissance }} jours. Veuillez confirmer le sexe et la date de
                                naissance.</div>
                        </div>
                        @if ($naissance->reminder_count > 0)
                            <div
                                style="background: var(--accent-orange); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $naissance->reminder_count }} rappel(s)</div>
                        @endif
                    </div>
                @endif
                <div class="settings-grid">
                    <div class="form-section">
                        <h4 class="section-subtitle"><i class="bi bi-arrow-down-right-square"></i> Femelle</h4>
                        <div class="form-group">
                            <label class="form-label">Femelle *</label>
                            <select name="femelle_id" class="form-select" required>
                                @foreach ($femelles as $femelle)
                                    <option value="{{ $femelle->id }}"
                                        {{ old('femelle_id', $naissance->femelle_id) == $femelle->id ? 'selected' : '' }}>
                                        {{ $femelle->nom }} ({{ $femelle->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-section">
                        <h4 class="section-subtitle"><i class="bi bi-calendar"></i> Date & Lieu</h4>
                        <div class="form-group">
                            <label class="form-label">Date de naissance *</label>
                            <input type="date" name="date_naissance" class="form-control"
                                value="{{ old('date_naissance', $naissance->date_naissance->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Heure (optionnel)</label>
                            <input type="time" name="heure_naissance" class="form-control"
                                value="{{ old('heure_naissance', $naissance->heure_naissance?->format('H:i')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lieu (Box/Cage)</label>
                            <input type="text" name="lieu_naissance" class="form-control"
                                value="{{ old('lieu_naissance', $naissance->lieu_naissance) }}" placeholder="Ex: Box A-12">
                        </div>
                    </div>
                    <div class="form-section">
                        <h4 class="section-subtitle"><i class="bi bi-collection"></i> Détails de la Portée</h4>
                        <div class="form-group">
                            <label class="form-label">Nombre de vivants *</label>
                            <input type="number" name="nb_vivant" class="form-control"
                                value="{{ old('nb_vivant', $naissance->nb_vivant) }}" required min="0"
                                max="20">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nombre de mort-nés</label>
                            <input type="number" name="nb_mort_ne" class="form-control"
                                value="{{ old('nb_mort_ne', $naissance->nb_mort_ne) }}" min="0" max="20">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Poids moyen à la naissance (g)</label>
                            <input type="number" step="0.01" name="poids_moyen_naissance" class="form-control"
                                value="{{ old('poids_moyen_naissance', $naissance->poids_moyen_naissance) }}"
                                min="0" max="200">
                        </div>
                    </div>
                    <div class="form-section">
                        <h4 class="section-subtitle"><i class="bi bi-heart-pulse"></i> Santé & Suivi</h4>
                        <div class="form-group">
                            <label class="form-label">État de santé *</label>
                            <select name="etat_sante" class="form-select" required>
                                <option value="Excellent"
                                    {{ old('etat_sante', $naissance->etat_sante) == 'Excellent' ? 'selected' : '' }}>
                                    Excellent</option>
                                <option value="Bon"
                                    {{ old('etat_sante', $naissance->etat_sante) == 'Bon' ? 'selected' : '' }}>Bon</option>
                                <option value="Moyen"
                                    {{ old('etat_sante', $naissance->etat_sante) == 'Moyen' ? 'selected' : '' }}>Moyen
                                </option>
                                <option value="Faible"
                                    {{ old('etat_sante', $naissance->etat_sante) == 'Faible' ? 'selected' : '' }}>Faible
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de sevrage prévue</label>
                            <input type="date" name="date_sevrage_prevue" class="form-control"
                                value="{{ old('date_sevrage_prevue', $naissance->date_sevrage_prevue?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de vaccination prévue</label>
                            <input type="date" name="date_vaccination_prevue" class="form-control"
                                value="{{ old('date_vaccination_prevue', $naissance->date_vaccination_prevue?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Observations</label>
                            <textarea name="observations" class="form-control" rows="3" placeholder="Notes sur la portée...">{{ old('observations', $naissance->observations) }}</textarea>
                        </div>
                    </div>
                    <div class="form-section"
                        style="background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2);">
                        <h4 class="section-subtitle"><i class="bi bi-shield-check"
                                style="color: var(--accent-green);"></i> Confirmation de Vérification</h4>
                        <div class="form-group">
                            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                                <input type="checkbox" name="sex_verified" value="1"
                                    {{ old('sex_verified', $naissance->sex_verified) ? 'checked' : '' }}
                                    style="width: 20px; height: 20px; accent-color: var(--accent-green); margin-top: 2px;">
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);">Sexe et date vérifiés</div>
                                    <div style="font-size: 13px; color: var(--text-tertiary);">Je confirme que le sexe des
                                        lapereaux et la date de naissance ont été vérifiés</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div
                    style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                    <button type="submit" class="btn-cuni primary"><i class="bi bi-check-circle"></i> Enregistrer les
                        modifications</button>
                    <a href="{{ route('naissances.index') }}" class="btn-cuni secondary"><i class="bi bi-x-circle"></i>
                        Annuler</a>
                </div>
            </form>
        </div>
    </div>
    <style>
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
    </style>
@endsection
