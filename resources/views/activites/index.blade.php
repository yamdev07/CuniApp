@extends('layouts.cuniapp')
@section('title', 'Historique des Activités - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-clock-history"></i> Historique des Activités
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Activités</span>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Total</p>
                <p class="text-xl font-bold text-primary">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Saillies</p>
                <p class="text-xl font-bold text-purple-600">{{ $stats['saillies'] }}</p>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Mises Bas</p>
                <p class="text-xl font-bold" style="color: var(--accent-orange)">{{ $stats['misesBas'] }}</p>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Naissances</p>
                <p class="text-xl font-bold text-green-600">{{ $stats['naissances'] }}</p>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Ventes</p>
                <p class="text-xl font-bold text-blue-600">{{ $stats['ventes'] }}</p>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-3 text-center">
                <p class="text-xs text-gray-500">Nouveaux lapins</p>
                <p class="text-xl font-bold" style="color: var(--accent-cyan)">{{ $stats['lapins'] }}</p>
            </div>
        </div>
    </div>

    <div class="cuni-card mb-6">
        <div class="card-body">
            <div class="flex gap-2 flex-wrap">
                <a href="?type=" class="btn-cuni sm {{ !$currentFilter ? 'primary' : 'secondary' }}">
                    <i class="bi bi-list-ul"></i> Tous
                </a>
                <a href="?type=purple" class="btn-cuni sm {{ $currentFilter === 'purple' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-heart"></i> Saillies
                </a>
                <a href="?type=amber" class="btn-cuni sm {{ $currentFilter === 'amber' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-egg"></i> Mises Bas
                </a>
                <a href="?type=green" class="btn-cuni sm {{ $currentFilter === 'green' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-egg-fill"></i> Naissances
                </a>
                <a href="?type=blue" class="btn-cuni sm {{ $currentFilter === 'blue' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-cart"></i> Ventes
                </a>
                {{-- <a href="?type=orange" class="btn-cuni sm {{ $currentFilter === 'orange' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-exclamation-triangle"></i> Alertes
                </a> --}}
                <a href="?type=cyan" class="btn-cuni sm {{ $currentFilter === 'cyan' ? 'primary' : 'secondary' }}">
                    <i class="bi bi-collection"></i> Nouveaux Lapins
                </a>
            </div>
        </div>
    </div>

    {{-- Liste des activités --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-check"></i> Toutes les activités
            </h3>
            <span class="text-sm text-gray-500">
                Page {{ $currentPage }} sur {{ $lastPage }}
            </span>
        </div>
        <div class="card-body">
            <div class="timeline" style="max-height: none;">
                @forelse($activities as $activity)
                    <a href="{{ $activity['url'] }}" class="timeline-item" style="text-decoration: none; color: inherit;">
                        <div class="timeline-dot {{ $activity['type'] }}"></div>
                        <div class="timeline-content" style="flex: 1;">
                            <div class="timeline-title">
                                @if (isset($activity['icon']))
                                    <i class="bi {{ $activity['icon'] }}" style="margin-right: 6px;"></i>
                                @endif
                                {{ $activity['title'] }}
                            </div>
                            <div class="timeline-desc">{{ $activity['desc'] }}</div>
                            <div class="timeline-time">
                                <i class="bi bi-clock"></i> {{ $activity['time'] }}
                                @if ($activity['date'])
                                    <span class="ml-2 text-gray-400">
                                        ({{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }})
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </a>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <i class="bi bi-inbox text-4xl mb-4 opacity-40"></i>
                        <p class="text-lg">Aucune activité enregistrée</p>
                        <a href="{{ route('saillies.create') }}" class="btn-cuni primary mt-4">
                            <i class="bi bi-plus-lg"></i> Enregistrer une première activité
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination simple --}}
            @if ($lastPage > 1)
                <div class="flex justify-center mt-6 gap-2">
                    @if ($currentPage > 1)
                        <a href="?page={{ $currentPage - 1 }}{{ request('type') ? '&type=' . request('type') : '' }}"
                            class="btn-cuni secondary sm">
                            <i class="bi bi-chevron-left"></i> Précédent
                        </a>
                    @endif

                    @for ($i = 1; $i <= $lastPage; $i++)
                        <a href="?page={{ $i }}{{ request('type') ? '&type=' . request('type') : '' }}"
                            class="btn-cuni sm {{ $i == $currentPage ? 'primary' : 'secondary' }}"
                            style="min-width: 40px; justify-content: center;">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($currentPage < $lastPage)
                        <a href="?page={{ $currentPage + 1 }}{{ request('type') ? '&type=' . request('type') : '' }}"
                            class="btn-cuni secondary sm">
                            Suivant <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>


    <style>
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .timeline-item {
            display: flex;
            gap: 12px;
            position: relative;
            padding: 12px;
            border-radius: var(--radius);
            transition: background 0.2s;
        }

        .timeline-item:hover {
            background: var(--surface-alt);
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 32px;
            width: 1px;
            height: calc(100% + 4px);
            background: var(--surface-border);
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 8px;
        }

        .timeline-dot.green {
            background: var(--accent-green);
        }

        .timeline-dot.purple {
            background: var(--accent-purple);
        }

        .timeline-dot.orange {
            background: var(--accent-orange);
        }

        .timeline-dot.blue {
            background: #3B82F6;
        }

        .timeline-dot.cyan {
            background: var(--accent-cyan, #06B6D4);
        }

        .timeline-dot.amber {
            background: var(--accent-orange);
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-primary);
        }

        .timeline-desc {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 12px;
            color: var(--text-tertiary);
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
@endsection
