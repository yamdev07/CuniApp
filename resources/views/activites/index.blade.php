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
    {{-- <div class="cuni-card">
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
    </style> --}}


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
                    <div class="timeline-item" data-activity-id="{{ $activity['id'] ?? '' }}">
                        <!-- Lien vers les détails -->
                        <a href="{{ $activity['url'] }}" class="timeline-link"
                            style="text-decoration: none; color: inherit; flex: 1; display: flex; gap: 12px; align-items: flex-start;">
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
                            <i class="bi bi-chevron-right text-gray-400" style="align-self: center;"></i>
                        </a>

                        <!-- ✅ Bouton de suppression (apparaît au survol) -->
                        @if (isset($activity['id']) && isset($activity['model_type']))
                            <button type="button" class="btn-delete-activity" data-id="{{ $activity['id'] }}"
                                data-type="{{ $activity['model_type'] }}" title="Supprimer cette activité"
                                style="background: rgba(239,68,68,0.1); border: none; color: var(--accent-red); cursor: pointer; padding: 8px; opacity: 1; transition: all 0.2s; border-radius: var(--radius); align-self: flex-start; flex-shrink: 0;"
                                onmouseover="this.style.background='rgba(239,68,68,0.2)'; this.style.transform='scale(1.1)'"
                                onmouseout="this.style.background='rgba(239,68,68,0.1)'; this.style.transform='scale(1)'">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                </svg>
                            </button>
                        @endif
                    </div>
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

    {{-- ✅ CSS pour le bouton de suppression et animations --}}
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



        .timeline-link {
            display: flex;
            gap: 12px;
            flex: 1;
            align-items: flex-start;
        }

        .btn-delete-activity {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .btn-delete-activity:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            transform: scale(1.1);
        }

        /* Animation de suppression */
        .timeline-item.removing {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s ease;
        }
    </style>

    {{--  JavaScript pour gérer la suppression --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initActivityDeletion();
            });

            function initActivityDeletion() {
                document.querySelectorAll('.btn-delete-activity').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();

                        const activityId = this.dataset.id;
                        const modelType = this.dataset.type;
                        const timelineItem = this.closest('.timeline-item');

                        if (!activityId || !modelType) {
                            console.error('ID ou type manquant');
                            return;
                        }

                        // Confirmation
                        if (!confirm(
                                'Êtes-vous sûr de vouloir supprimer cette activité ? Cette action est irréversible.'
                            )) {
                            return;
                        }

                        // Animation de suppression
                        timelineItem.classList.add('removing');

                        // Requête AJAX
                        fetch(`/activities/${activityId}/delete`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        ?.content || '',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    id: parseInt(activityId),
                                    model_type: modelType
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Dans le .then(data => { ... })
                                if (data.success) {
                                    setTimeout(() => {
                                        timelineItem.remove();

                                        // ✅ Toast avec message simple
                                        showToast({
                                            type: 'success',
                                            title: '✓ Supprimé',
                                            message: 'Suppression réussie', // ← Message fixe, pas data.message
                                            duration: 2000
                                        });

                                        // Recharger si la liste est vide
                                        const timeline = document.querySelector('.timeline');
                                        if (timeline && timeline.querySelectorAll(
                                                '.timeline-item:not(.removing)').length === 0) {
                                            setTimeout(() => location.reload(), 500);
                                        }
                                    }, 300);
                                } else {
                                    // Annuler l'animation en cas d'erreur
                                    timelineItem.classList.remove('removing');
                                    showToast({
                                        type: 'error',
                                        title: 'Erreur',
                                        message: data.message || 'Échec de la suppression',
                                        duration: 5000
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                timelineItem.classList.remove('removing');
                                showToast({
                                    type: 'error',
                                    title: 'Erreur',
                                    message: 'Une erreur est survenue lors de la suppression',
                                    duration: 5000
                                });
                            });
                    });
                });
            }

            // ✅ Toast notification simple
            function showToast(options) {
                const {
                    type = 'info', title = '', message = '', duration = 3000
                } = options;

                const colors = {
                    success: {
                        bg: 'rgba(16,185,129,0.1)',
                        border: '#10B981',
                        text: '#10B981'
                    },
                    error: {
                        bg: 'rgba(239,68,68,0.1)',
                        border: '#EF4444',
                        text: '#EF4444'
                    },
                    info: {
                        bg: 'rgba(59,130,246,0.1)',
                        border: '#3B82F6',
                        text: '#3B82F6'
                    },
                };
                const c = colors[type] || colors.info;

                const toast = document.createElement('div');
                toast.style.cssText = `
        position: fixed; bottom: 24px; right: 24px;
        background: var(--surface); border: 1px solid ${c.border};
        border-left: 4px solid ${c.border}; border-radius: var(--radius-lg);
        padding: 16px 20px; box-shadow: var(--shadow-lg); z-index: 9999;
        animation: slideInRight 0.3s ease; max-width: 400px;
    `;
                toast.innerHTML = `
        <div style="display:flex;align-items:start;gap:12px;">
            <div style="flex:1;">
                <div style="font-weight:600;color:var(--text-primary);margin-bottom:4px;">${title}</div>
                <div style="font-size:13px;color:var(--text-secondary);">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                style="background:none;border:none;color:var(--text-tertiary);cursor:pointer;padding:4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        </script>
    @endpush

@endsection
