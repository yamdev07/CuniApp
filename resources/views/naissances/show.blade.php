{{-- resources/views/naissances/show.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Détails Naissance #{{ $naissance->id }}')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-egg-fill"></i> Détails Naissance #{{ $naissance->id }}</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('naissances.index') }}">Naissances</a>
                <span>/</span>
                <span>#{{ $naissance->id }}</span>
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            @if ($canVerifySex)
                <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni primary">
                    <i class="bi bi-pencil"></i> Vérifier le sexe
                </a>
            @else
                <button class="btn-cuni secondary" disabled title="Disponible dans {{ $daysUntilVerification }} jours">
                    <i class="bi bi-lock"></i> Vérifier le sexe ({{ $daysUntilVerification }}j)
                </button>
            @endif
            <a href="{{ route('naissances.index') }}" class="btn-cuni secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="cuni-card">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations Principales</h3>
                </div>
                <div class="card-body">
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Femelle</label>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold">{{ $naissance->femelle->nom ?? 'N/A' }}</span>
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                    {{ $naissance->femelle->code ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de naissance</label>
                            <p>
                                {{-- ✅ ADD null check --}}
                                @if ($naissance->date_naissance)
                                    {{ $naissance->date_naissance->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Âge de la portée</label>
                            <p class="fw-semibold" style="color: var(--primary);">
                                {{ $naissance->jours_depuis_naissance }} jours
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">État de santé (portée)</label>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                {{ $naissance->etat_sante }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vérification du sexe</label>
                            @if ($naissance->sex_verified)
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-check-circle"></i> Vérifié le
                                    {{ $naissance->sex_verified_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                                    <i class="bi bi-clock"></i> En attente ({{ $daysUntilVerification }} jours restants)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Lapereaux List with Search & Pagination -->
            <div class="cuni-card" style="margin-top: 24px;">
                <div class="card-header-custom">
                    <h3 class="card-title">
                        <i class="bi bi-collection"></i> Liste des Lapereaux ({{ $naissance->total_lapereaux }})
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Search & Filters -->
                    <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
                        <form method="GET" action="{{ route('naissances.show', $naissance) }}"
                            style="display: flex; gap: 12px; flex: 1; min-width: 300px;">
                            <input type="text" name="search_lapereau" class="form-control"
                                placeholder="Rechercher par nom ou code..." value="{{ request('search_lapereau') }}"
                                style="flex: 1;">
                            <button type="submit" class="btn-cuni primary">
                                <i class="bi bi-search"></i>
                            </button>
                            @if (request('search_lapereau'))
                                <a href="{{ route('naissances.show', $naissance) }}" class="btn-cuni secondary">
                                    <i class="bi bi-x"></i>
                                </a>
                            @endif
                        </form>

                        <select name="filter_etat" class="form-select" style="width: auto;"
                            onchange="window.location.href=this.value">
                            <option value="{{ route('naissances.show', $naissance) }}">Tous les états</option>
                            <option value="{{ route('naissances.show', $naissance) }}?filter_etat=vivant"
                                {{ request('filter_etat') == 'vivant' ? 'selected' : '' }}>Vivants</option>
                            <option value="{{ route('naissances.show', $naissance) }}?filter_etat=mort"
                                {{ request('filter_etat') == 'mort' ? 'selected' : '' }}>Morts</option>
                            <option value="{{ route('naissances.show', $naissance) }}?filter_etat=vendu"
                                {{ request('filter_etat') == 'vendu' ? 'selected' : '' }}>Vendus</option>
                        </select>

                        <select name="filter_sex" class="form-select" style="width: auto;"
                            onchange="window.location.href=this.value">
                            <option value="{{ route('naissances.show', $naissance) }}">Tous les sexes</option>
                            <option value="{{ route('naissances.show', $naissance) }}?filter_sex=male"
                                {{ request('filter_sex') == 'male' ? 'selected' : '' }}>Mâles</option>
                            <option value="{{ route('naissances.show', $naissance) }}?filter_sex=female"
                                {{ request('filter_sex') == 'female' ? 'selected' : '' }}>Femelles</option>
                        </select>
                    </div>

                    @if ($lapereaux->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Sexe</th>
                                        <th>Poids (g)</th>
                                        <th>Santé</th>
                                        <th>État</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lapereaux as $lapereau)
                                        <tr>
                                            <td class="fw-semibold" style="font-family: 'JetBrains Mono', monospace;">
                                                {{ $lapereau->code }}
                                            </td>
                                            <td>{{ $lapereau->nom ?? '-' }}</td>
                                            <td>
                                                @if ($lapereau->sex)
                                                    @if ($lapereau->sex === 'male')
                                                        <span class="badge"
                                                            style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                                            <i class="bi bi-gender-male"></i> Mâle
                                                        </span>
                                                    @else
                                                        <span class="badge"
                                                            style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">
                                                            <i class="bi bi-gender-female"></i> Femelle
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge"
                                                        style="background: rgba(107, 114, 128, 0.1); color: #6B7D95;">
                                                        <i class="bi bi-question-circle"></i> À vérifier
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $lapereau->poids_naissance ?? '-' }}g</td>
                                            <td>
                                                <span class="badge"
                                                    style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                                    {{ $lapereau->etat_sante ?? 'Bon' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($lapereau->etat === 'vivant')
                                                    <span class="badge"
                                                        style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Vivant</span>
                                                @elseif($lapereau->etat === 'vendu')
                                                    <span class="badge"
                                                        style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">Vendu</span>
                                                @else
                                                    <span class="badge"
                                                        style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">Mort</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('lapins.edit', $lapereau->id) }}"
                                                    class="btn-cuni sm secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($lapereaux->hasPages())
                            <div style="margin-top: 20px;">
                                {{ $lapereaux->appends(request()->query())->links('pagination.bootstrap-5-sm') }}
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center" style="padding: 40px;">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5;"></i><br>
                            Aucun lapereau enregistré pour cette naissance.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Stats -->
            <div class="cuni-card">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-graph-up"></i> Statistiques</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500">Total Lapereaux</label>
                            <p class="font-semibold" style="font-size: 1.5rem;">{{ $naissance->total_lapereaux }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Vivants</label>
                            <p class="font-semibold" style="font-size: 1.2rem; color: var(--accent-green);">
                                {{ $naissance->nb_vivant }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Morts</label>
                            <p class="font-semibold" style="font-size: 1.2rem; color: var(--accent-red);">
                                {{ $naissance->nb_mort_ne }}
                            </p>
                        </div>
                        <hr style="border-color: var(--surface-border);">
                        <div>
                            <label class="text-sm text-gray-500">Taux de survie</label>
                            <p class="font-semibold" style="font-size: 1.2rem; color: var(--primary);">
                                {{ $naissance->taux_survie }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Info -->
            <div class="cuni-card" style="margin-top: 24px;">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-heart"></i> Parents</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Mère</label>
                            <p class="font-semibold">{{ $naissance->femelle->nom ?? 'N/A' }}</p>
                        </div>
                        @if ($naissance->saillie)
                            <div>
                                <label class="text-sm text-gray-500">Père</label>
                                <p class="font-semibold">{{ $naissance->saillie->male->nom ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
