@extends('layouts.cuniapp')

@section('title', 'Notifications - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-bell-fill"></i> Notifications
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Notifications</span>
            </div>
        </div>

        <div class="flex gap-3 items-center flex-wrap justify-end">
            {{-- Filtres --}}
            <div class="btn-group" role="group" style="display: inline-flex; background: var(--surface-alt); padding: 4px; border-radius: 8px; border: 1px solid var(--surface-border);">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                   class="btn-cuni sm {{ $filter === 'all' ? 'primary' : 'light' }}"
                   style="border-radius: 6px; font-size: 0.85rem; padding: 6px 12px;">
                    Toutes
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="btn-cuni sm {{ $filter === 'unread' ? 'primary' : 'light' }}"
                   style="border-radius: 6px; font-size: 0.85rem; padding: 6px 12px;">
                    Non lues 
                    @if($unreadCount > 0)
                        <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; font-size: 0.7rem; margin-left: 4px; padding: 2px 6px;">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="btn-cuni sm {{ $filter === 'read' ? 'primary' : 'light' }}"
                   style="border-radius: 6px; font-size: 0.85rem; padding: 6px 12px;">
                    Lues
                </a>
            </div>

            {{-- Bouton Tout lire --}}
            @if($unreadCount > 0 && $filter !== 'read')
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cuni secondary sm">
                        <i class="bi bi-check2-all"></i> <span class="hidden sm:inline">Tout lire</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if ($notifications->isEmpty())
        <div class="cuni-card">
            <div class="card-body text-center py-16">
                <div class="text-6xl mb-4 opacity-20" style="color: var(--text-primary);">
                    <i class="bi bi-bell-slash-fill"></i>
                </div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--text-primary);">
                    @if($filter === 'unread') Aucune notification non lue
                    @elseif($filter === 'read') Aucune notification lue
                    @else Aucune notification
                    @endif
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    @if($filter === 'unread') Vous êtes à jour !
                    @elseif($filter === 'read') Vous n'avez pas encore lu de notifications.
                    @else Vous n'avez aucune notification pour le moment.
                    @endif
                </p>
                @if($filter !== 'all')
                    <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="btn-cuni primary mt-4">
                        Voir toutes les notifications
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Stats Cards -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @php
                    $stats = [
                        ['label' => 'Non lues', 'count' => $notifications->where('is_read', false)->count(), 'color' => 'red', 'icon' => 'bi-envelope'],
                        ['label' => 'Succès', 'count' => $notifications->where('type', 'success')->count(), 'color' => 'green', 'icon' => 'bi-check-circle'],
                        ['label' => 'Alertes', 'count' => $notifications->where('type', 'warning')->count(), 'color' => 'amber', 'icon' => 'bi-exclamation-triangle'],
                        ['label' => 'Infos', 'count' => $notifications->where('type', 'info')->count(), 'color' => 'blue', 'icon' => 'bi-info-circle'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="cuni-card">
                        <div class="card-body p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm" style="color: var(--text-secondary);">{{ $stat['label'] }}</p>
                                    <p class="text-2xl font-bold mt-1" style="color: var(--text-primary);">{{ $stat['count'] }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                    style="background: rgba({{ $stat['color'] === 'red' ? '239, 68, 68' : ($stat['color'] === 'green' ? '16, 185, 129' : ($stat['color'] === 'amber' ? '245, 158, 11' : '59, 130, 246')) }}, 0.1);">
                                    <i class="bi {{ $stat['icon'] }} text-lg" style="color: {{ $stat['color'] === 'red' ? '#EF4444' : ($stat['color'] === 'green' ? '#10B981' : ($stat['color'] === 'amber' ? '#F59E0B' : '#3B82F6')) }};"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Notifications List -->
            <div class="lg:col-span-3">
                <div class="cuni-card">
                    <div class="card-header-custom">
                        <h3 class="card-title" style="color: var(--text-primary);">
                            <i class="bi bi-list-ul"></i> 
                            @if($filter === 'unread') Notifications Non Lues
                            @elseif($filter === 'read') Notifications Lues
                            @else Historique des notifications
                            @endif
                        </h3>
                        @if($filter === 'all')
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                {{ $unreadCount }} non lues
                            </span>
                        @endif
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($notifications as $notif)
                            @php
                                // Bordure bleue si non lu, transparente si lu
                                $borderClass = !$notif->is_read ? 'border-l-4 border-blue-500' : 'border-l-4 border-transparent';
                                // Opacité pour les lus
                                $opacityClass = $notif->is_read ? 'opacity-60' : 'opacity-100';
                            @endphp

                            {{-- Ligne de notification avec Hover Bleu Nuit --}}
                            <div class="p-4 {{ $borderClass }} {{ $opacityClass }} notif-item" style="background: transparent; transition: background-color 0.2s;">
                                <div class="flex items-start gap-4">
                                    <!-- Icone -->
                                    <div class="mt-0.5 flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                            style="background: rgba({{ $notif->type === 'success' ? '16, 185, 129' : ($notif->type === 'warning' ? '245, 158, 11' : ($notif->type === 'error' ? '239, 68, 68' : '59, 130, 246')) }}, 0.15);">
                                            <i class="bi {{ $notif->icon }} text-lg" 
                                               style="color: {{ $notif->type === 'success' ? '#10B981' : ($notif->type === 'warning' ? '#F59E0B' : ($notif->type === 'error' ? '#EF4444' : '#3B82F6')) }};">
                                            </i>
                                        </div>
                                    </div>

                                    <!-- Contenu -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="text-base font-bold truncate pr-4" style="color: var(--text-primary);">
                                                {{ $notif->title }}
                                            </h4>
                                            <span class="text-xs whitespace-nowrap" style="color: var(--text-secondary);">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm leading-relaxed mb-2" style="color: var(--text-secondary); font-size: 0.95rem;">
                                            {{ $notif->message }}
                                        </p>

                                        @if ($notif->action_url)
                                            <a href="{{ $notif->action_url }}" class="inline-flex items-center gap-1 text-xs font-medium" style="color: var(--primary);">
                                                Voir les détails <i class="bi bi-arrow-right-short"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-col items-end gap-2 pl-2">
                                        @if (!$notif->is_read)
                                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" title="Marquer comme lue">
                                                @csrf
                                                <button type="submit" class="action-btn p-1.5 rounded" style="color: var(--primary);">
                                                    <i class="bi bi-check-circle-fill text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" title="Supprimer">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-delete p-1.5 rounded" style="color: var(--text-secondary);" onclick="return confirm('Supprimer cette notification ?')">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if ($notifications->hasPages())
                        <div class="p-4 border-t" style="border-color: var(--surface-border);">
                            {{ $notifications->links('pagination.bootstrap-5-sm') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .cuni-card {
            background: var(--surface);
            border: 1px solid var(--surface-border);
        }

        /* Hover BLEU NUIT sur toute la ligne */
        .notif-item:hover {
            background-color: #f1f5f9 !important; /* Bleu nuit (Slate 800) */
            cursor: pointer;
        }

         .theme-dark .notif-item:hover {
            background-color: #1e293b !important; /* Bleu nuit (Slate 800) */
            cursor: pointer;
        }
        
         .theme-dark .notif-item:hover h4,
       .theme-dark .notif-item:hover p,
        .theme-dark .notif-item:hover span,
       .theme-dark .notif-item:hover a {
            color: #f1f5f9 !important; /* Gris très clair / Blanc cassé */
        }

        /* Ajustement de la couleur du texte au survol pour rester lisible sur le bleu nuit */
        .notif-item:hover h4,
        .notif-item:hover p,
        .notif-item:hover span,
        .notif-item:hover a {
            color: #1e293b !important; /* Gris très clair / Blanc cassé */
        }
        
        /* Hover ROUGE spécifique sur le bouton supprimer */
        .action-btn-delete:hover {
            background-color: #ef4444 !important; /* Rouge vif */
            color: #ffffff !important; /* Icône blanche */
            transition: all 0.2s ease;
        }

        /* Petit hover subtil sur le bouton "Lire" */
        .action-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush