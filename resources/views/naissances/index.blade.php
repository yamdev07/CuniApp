@extends('layouts.cuniapp')
@section('title', 'Naissances - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-egg-fill"></i> Gestion des Naissances</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Naissances</span>
            </div>
        </div>
        <a href="{{ route('naissances.create') }}" class="btn-cuni primary">
            <i class="bi bi-plus-lg"></i> Nouvelle naissance
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total naissances</p>
                        <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(59, 130, 246, 0.1)"><i class="bi bi-egg text-blue-500 text-lg"></i></div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Ce mois-ci</p>
                        <p class="text-2xl font-bold mt-1">{{ $stats['this_month'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(16, 185, 129, 0.1)"><i
                            class="bi bi-calendar-check text-green-500 text-lg"></i></div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Lapereaux vivants</p>
                        <p class="text-2xl font-bold mt-1">{{ $stats['nb_vivant_total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(139, 92, 246, 0.1)"><i
                            class="bi bi-heart-pulse text-purple-500 text-lg"></i></div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Taux de survie</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($stats['taux_survie_moyen'], 1) }}%</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(245, 158, 11, 0.1)"><i class="bi bi-graph-up text-amber-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">À vérifier</p>
                        <p class="text-2xl font-bold mt-1">{{ $stats['pending_verification'] ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(239, 68, 68, 0.1)"><i
                            class="bi bi-exclamation-triangle text-red-500 text-lg"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title"><i class="bi bi-list-ul"></i> Historique des naissances</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">Date</th>
                            <th class="text-uppercase text-muted fw-semibold small">Femelle</th>
                            <th class="text-uppercase text-muted fw-semibold small">Vivants</th>
                            <th class="text-uppercase text-muted fw-semibold small">Mort-nés</th>
                            <th class="text-uppercase text-muted fw-semibold small">Total</th>
                            <th class="text-uppercase text-muted fw-semibold small">Santé</th>
                            <th class="text-uppercase text-muted fw-semibold small">Vérification</th>
                            <th class="text-uppercase text-muted fw-semibold small">Sevrage</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($naissances as $naissance)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $naissance->date_naissance ? \Carbon\Carbon::parse($naissance->date_naissance)->format('d/m/Y') : '-' }}
                                    @if ($naissance->heure_naissance)
                                        <small class="text-muted">({{ $naissance->heure_naissance->format('H:i') }})</small>
                                    @endif
                                </td>
                                <td>{{ $naissance->femelle->nom ?? 'N/A' }}<small
                                        class="text-muted">({{ $naissance->femelle->code ?? '-' }})</small></td>
                                <td><span class="badge"
                                        style="background: rgba(16, 185, 129, 0.1); color: #10B981;">{{ $naissance->nb_vivant }}</span>
                                </td>
                                <td class="text-muted">{{ $naissance->nb_mort_ne }}</td>
                                <td class="fw-semibold">{{ $naissance->nb_total }}</td>
                                <td>
                                    @php
                                        $healthColors = [
                                            'Excellent' => 'rgba(16, 185, 129, 0.1); color: #10B981;',
                                            'Bon' => 'rgba(59, 130, 246, 0.1); color: #3B82F6;',
                                            'Moyen' => 'rgba(245, 158, 11, 0.1); color: #F59E0B;',
                                            'Faible' => 'rgba(239, 68, 68, 0.1); color: #EF4444;',
                                        ];
                                    @endphp
                                    <span class="badge"
                                        style="background: {{ $healthColors[$naissance->etat_sante] ?? $healthColors['Bon'] }}">{{ $naissance->etat_sante }}</span>
                                </td>
                                <td>
                                    @if ($naissance->sex_verified)
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;"><i
                                                class="bi bi-check-circle"></i> Vérifié</span>
                                    @elseif($naissance->reminder_count > 0)
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;"><i
                                                class="bi bi-exclamation-triangle"></i> {{ $naissance->reminder_count }}
                                            rappel(s)</span>
                                    @else
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;"><i
                                                class="bi bi-clock"></i> En attente</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $naissance->date_sevrage_prevue ? $naissance->date_sevrage_prevue->format('d/m/Y') : '-' }}
                                    @if ($naissance->jours_avant_sevrage > 0 && $naissance->jours_avant_sevrage <= 7)
                                        <span class="badge"
                                            style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; font-size: 10px;">J-{{ $naissance->jours_avant_sevrage }}</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons">
                                        <a href="{{ route('naissances.show', $naissance) }}" class="btn-cuni sm secondary"
                                            title="Détails"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni sm secondary"
                                            title="Modifier"><i class="bi bi-pencil"></i></a>
                                        @if (!$naissance->is_archived)
                                            <form action="{{ route('naissances.archive', $naissance) }}" method="POST"
                                                id="archive-form-{{ $naissance->id }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <button type="button" class="btn-cuni sm danger" title="Archiver"
                                                onclick="showModal('confirm', 'Archiver cette naissance ?', 'Êtes-vous sûr de vouloir archiver cette naissance ? Cette action peut être annulée.', function() { document.getElementById('archive-form-{{ $naissance->id }}').submit(); })">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('naissances.destroy', $naissance) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-cuni sm danger" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr ?')"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="table-empty-state"><i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Aucune naissance enregistrée</p><a
                                            href="{{ route('naissances.create') }}" class="btn-cuni primary mt-3"><i
                                                class="bi bi-plus-lg"></i> Première naissance</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($naissances->hasPages())
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <div class="text-muted" style="font-size: 13px;">Affichage de
                        <strong>{{ $naissances->firstItem() }}</strong> à <strong>{{ $naissances->lastItem() }}</strong>
                        sur <strong>{{ $naissances->total() }}</strong> naissances
                    </div>
                    <nav>{{ $naissances->links('vendor.pagination.bootstrap-5-sm') }}</nav>
                </div>
            @endif
        </div>
    </div>
@endsection
