@extends('layouts.cuniapp')

@section('title', 'Archives des Abonnements - Admin')

@section('content')
    {{-- Affichage des messages de succès/erreur --}}
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10B981; color: #065f46; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #EF4444; color: #991b1b; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-archive"></i> Archives des Abonnements
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('admin.subscriptions.index') }}">Abonnements</a>
                <span>/</span>
                <span>Archives</span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.subscriptions.index') }}" class="btn-cuni primary">
                <i class="bi bi-arrow-left"></i> Retour aux Actifs
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 13px; color: var(--text-secondary);">Total Archivés</p>
                        <p style="font-size: 28px; font-weight: 700; color: var(--text-primary);">{{ $stats['total_archived'] ?? 0 }}</p>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(107, 114, 128, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-archive" style="font-size: 24px; color: var(--gray-500);"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cuni-card">
            <div class="card-body p-4">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 13px; color: var(--text-secondary);">Dernier Archivage</p>
                        @php
                            try {
                                $lastArchived = \App\Models\Subscription::whereNotNull('archived_at')->latest('archived_at')->first();
                                echo $lastArchived ? $lastArchived->archived_at->format('d/m/Y H:i') : '-';
                            } catch (\Exception $e) { echo '-'; }
                        @endphp
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clock-history" style="font-size: 24px; color: #3B82F6;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table des Archives --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i> Historique des Abonnements Archivés
            </h3>
        </div>
        <div class="card-body">
            @if(!$users || $users->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="mt-4 text-lg">Aucune archive trouvée.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--surface-border);">
                                <th style="padding: 12px; text-align: left;">Utilisateur</th>
                                <th style="padding: 12px; text-align: left;">Email</th>
                                <th style="padding: 12px; text-align: left;">Abonnement</th>
                                <th style="padding: 12px; text-align: left;">Statut</th>
                                <th style="padding: 12px; text-align: left;">Expiration</th>
                                <th style="padding: 12px; text-align: left;">Date d'archivage</th>
                                <th style="padding: 12px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $mainSub = null;
                                    if($user->subscriptions) {
                                        $mainSub = $user->subscriptions->whereNotNull('archived_at')->first();
                                    }
                                @endphp

                                @if($mainSub)
                                    <tr style="border-bottom: 1px solid var(--surface-border);">
                                        <td style="padding: 12px; font-weight: 600;">{{ $user->name }}</td>
                                        <td style="padding: 12px;">{{ $user->email }}</td>
                                        <td style="padding: 12px;">
                                            {{ $mainSub->plan && $mainSub->plan->name ? $mainSub->plan->name : 'Plan inconnu' }}
                                        </td>
                                        <td style="padding: 12px;">
                                            @if($mainSub->status === 'active')
                                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Actif</span>
                                            @elseif($mainSub->status === 'expired')
                                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">Expiré</span>
                                            @elseif($mainSub->status === 'cancelled')
                                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">Annulé</span>
                                            @else
                                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">{{ ucfirst($mainSub->status) }}</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px;">
                                            {{ $mainSub->end_date ? \Carbon\Carbon::parse($mainSub->end_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td style="padding: 12px;">
                                            <span style="font-weight: 600; color: var(--text-secondary); font-size: 0.9em;">
                                                {{ $mainSub->archived_at ? $mainSub->archived_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px;">
                                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                                {{-- BOUTON RESTAURER --}}
                                                <form action="{{ route('admin.subscriptions.restore', $mainSub->id) }}" method="POST" onsubmit="return confirm('Restaurer cet abonnement ?');">
                                                    @csrf
                                                    <button type="submit" class="btn-cuni sm secondary">
                                                        <i class="bi bi-box-arrow-in-right"></i> Restaurer
                                                    </button>
                                                </form>

                                                {{-- BOUTON SUPPRIMER --}}
                                                <form action="{{ route('admin.subscriptions.destroy', $mainSub->id) }}" method="POST" onsubmit="return confirm('SUPPRIMER DÉFINITIVEMENT ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-cuni sm light" style="color: red;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div style="margin-top: 24px;">
                        {{ $users->links('pagination.bootstrap-5-sm') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection