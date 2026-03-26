{{-- resources/views/super-admin/dashboard.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Super Admin - Tableau de Bord')
@section('content')
    <div class="page-header">
        <h2 class="page-title">
            <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
            Administration Super Admin
        </h2>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Entreprises</p>
                        <p class="text-2xl font-bold">{{ $stats['total_firms'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(59, 130, 246, 0.1);">
                        <i class="bi bi-building text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Revenus (Mois)</p>
                        <p class="text-2xl font-bold" style="color: var(--accent-green);">
                            {{ number_format($stats['total_revenue_month'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-cash-stack text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Abonnements Actifs</p>
                        <p class="text-2xl font-bold">{{ $stats['active_subscriptions'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-check-circle text-purple-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Expire Bientôt (7j)</p>
                        <p class="text-2xl font-bold" style="color: var(--accent-orange);">
                            {{ $stats['expiring_soon'] }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-clock text-amber-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Firms Leaderboard --}}
    <div class="cuni-card mb-6">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-trophy"></i> Top 5 Entreprises (Par Revenus)
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Entreprise</th>
                            <th>Administrateur</th>
                            <th>Abonnement</th>
                            <th>Revenus</th>
                            <th>Ventes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topFirms as $index => $firm)
                            <tr>
                                <td>
                                    @if ($index === 0)
                                        <span class="badge" style="background: #FFD700; color: #000;">🥇 1</span>
                                    @elseif($index === 1)
                                        <span class="badge" style="background: #C0C0C0; color: #000;">🥈 2</span>
                                    @elseif($index === 2)
                                        <span class="badge" style="background: #CD7F32; color: #000;">🥉 3</span>
                                    @else
                                        <span class="badge">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $firm->name }}</td>
                                <td>{{ $firm->owner->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($firm->activeSubscription)
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                            {{ $firm->activeSubscription->plan->name ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">
                                            Aucun
                                        </span>
                                    @endif
                                </td>
                                <td class="fw-bold" style="color: var(--primary);">
                                    {{ number_format($firm->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                                </td>
                                <td>{{ $firm->total_sales ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Signups --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-person-plus"></i> Inscriptions Récentes (7 jours)
            </h3>
        </div>
        <div class="card-body">
            @if ($recentSignups->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Entreprise</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSignups as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->firm->name ?? 'N/A' }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.subscriptions.show', $user->id) }}"
                                            class="btn-cuni sm secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 py-8">Aucune inscription récente</p>
            @endif
        </div>
    </div>
@endsection
