{{-- resources/views/super-admin/firms/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails de l\'Entreprise')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-building"></i> {{ $firm->name }}
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('super.admin.dashboard') }}">Super Admin</a>
                <span>/</span>
                <a href="{{ route('super.admin.firms') }}">Entreprises</a>
                <span>/</span>
                <span>{{ $firm->name }}</span>
            </div>
        </div>
        <div class="header-actions">
            @if ($firm->status === 'active')
                <form action="{{ route('super.admin.firms.ban', $firm->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cuni sm danger" onclick="return confirm('Êtes-vous sûr de vouloir bannir cette entreprise ?')">
                        <i class="bi bi-slash-circle"></i> Bannir l'Entreprise
                    </button>
                </form>
            @else
                <form action="{{ route('super.admin.firms.activate', $firm->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cuni sm success">
                        <i class="bi bi-check-circle"></i> Réactiver l'Entreprise
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Firm Info & Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="cuni-card md:col-span-1">
            <div class="card-header-custom">
                <h3 class="card-title">Informations</h3>
            </div>
            <div class="card-body p-4">
                <ul style="list-style: none; padding: 0; margin: 0; line-height: 2;">
                    <li><strong>Propriétaire:</strong> {{ $firm->owner->name ?? 'N/A' }}</li>
                    <li><strong>Email:</strong> {{ $firm->owner->email ?? 'N/A' }}</li>
                    <li><strong>Téléphone:</strong> {{ $firm->phone ?? 'N/A' }}</li>
                    <li><strong>Localisation:</strong> {{ $firm->location ?? 'N/A' }}</li>
                    <li><strong>Statut:</strong> 
                        @if($firm->status === 'active')
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                        @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">Banni</span>
                        @endif
                    </li>
                    <li><strong>Créé le:</strong> {{ $firm->created_at->format('d/m/Y') }}</li>
                    <li><strong>Abonnement Actuel:</strong> {{ $firm->activeSubscription->plan->name ?? 'Aucun' }}</li>
                </ul>
            </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="bi bi-currency-euro text-green-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">Revenus Générés</p>
                            <p class="stats-value-small">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(139, 92, 246, 0.1);">
                            <i class="bi bi-cart text-purple-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">Ventes Totales</p>
                            <p class="stats-value-small">{{ number_format($stats['total_sales']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="bi bi-collection text-amber-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">Cheptel (Mâles / Femelles)</p>
                            <p class="stats-value-small">{{ $stats['total_males'] }} / {{ $stats['total_femelles'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-card-compact { transition: transform 0.2s ease; border: 1px solid var(--surface-border); }
        .stats-card-compact:hover { transform: translateY(-2px); }
        .stats-icon-small { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .stats-label-small { font-size: 0.72rem; color: var(--text-tertiary); margin: 0; text-transform: uppercase; letter-spacing: 0.025em; font-weight: 600; }
        .stats-value-small { font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--text-primary); line-height: 1.2; }
    </style>

    {{-- Admin Account --}}
    <div class="cuni-card mt-6">
        <div class="card-header-custom">
            <h3 class="card-title"><i class="bi bi-person-badge"></i> Administrateur de l'entreprise</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--surface-border);">
                            <th style="padding: 12px; text-align: left;">Nom</th>
                            <th style="padding: 12px; text-align: left;">Email</th>
                            <th style="padding: 12px; text-align: left;">Rôle</th>
                            <th style="padding: 12px; text-align: left;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($firm->users as $u)
                            <tr style="border-bottom: 1px solid var(--surface-border);">
                                <td style="padding: 12px; font-weight: 600;">{{ $u->name }}</td>
                                <td style="padding: 12px; color: var(--text-tertiary);">{{ $u->email }}</td>
                                <td style="padding: 12px;">
                                    @if($u->role === 'firm_admin')
                                        <span class="badge" style="background: rgba(139, 92, 246, 0.1); color: var(--purple-600);">Admin</span>
                                    @else
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">Employé</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    @if($u->status === 'active')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                                    @else
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($firm->users->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
