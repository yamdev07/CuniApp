{{-- resources/views/super-admin/dashboard.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Super Admin - Tableau de Bord')
@section('content')
    <div class="page-header mb-6">
        <div>
            <h2 class="page-title">
                <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
                Administration Super Admin
            </h2>
            <div class="breadcrumb text-xs">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                <span>/</span>
                <span>Super Admin</span>
            </div>
        </div>
    </div>

    {{-- ✅ Lovely CuniApp Banner for Super Admin --}}
    <div class="cuni-card mb-6 overflow-hidden position-relative" style="background: linear-gradient(135deg, var(--surface) 0%, var(--surface-alt) 100%); border: 1px solid var(--surface-border);">
        <div class="card-body p-6">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-0">Espace Haute Administration</h3>
                    </div>
                    <p class="text-secondary mb-0">
                        Bienvenue dans votre centre de contrôle maître. Gere les abonnements, surveillez les transactions et pilotez la croissance de <strong>CuniApp</strong> en temps réel.
                    </p>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-end">
                    <img src="https://img.freepik.com/free-vector/rabbit-concept-illustration_114360-1122.jpg" alt="Admin" style="height: 120px; opacity: 0.8; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));">
                </div>
            </div>
        </div>
        {{-- Subtle decorative elements --}}
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--primary); opacity: 0.05; border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -10px; left: 20%; width: 60px; height: 60px; background: var(--accent-orange); opacity: 0.03; border-radius: 50%;"></div>
    </div>

    {{-- Stats Grid - More Compact & Modern --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(59, 130, 246, 0.1);">
                        <i class="bi bi-building text-blue-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Entreprises</p>
                        <p class="stats-value-small">{{ number_format($stats['total_firms']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-cash-stack text-green-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Revenus (Mois)</p>
                        <p class="stats-value-small text-green-600">{{ number_format($stats['total_revenue_month'], 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-check-circle text-purple-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Abonnements Actifs</p>
                        <p class="stats-value-small">{{ number_format($stats['active_subscriptions']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-clock text-amber-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Expire Bientôt (7j)</p>
                        <p class="stats-value-small text-amber-600">{{ $stats['expiring_soon'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-card-compact {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid var(--surface-border);
        }
        .stats-card-compact:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .stats-icon-small {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .stats-label-small {
            font-size: 0.75rem;
            color: var(--text-tertiary);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            font-weight: 600;
        }
        .stats-value-small {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            line-height: 1.2;
        }
        .chart-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 250px;
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            color: var(--text-tertiary);
            text-align: center;
            padding: 24px;
            border: 1px dashed var(--surface-border);
        }
        .chart-placeholder i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.2;
            color: var(--primary);
        }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Signup Evolution Chart - Compact --}}
        <div class="lg:col-span-2">
            <div class="cuni-card h-full">
                <div class="card-header-custom flex items-center justify-between py-3">
                    <h3 class="card-title text-sm"><i class="bi bi-graph-up-arrow"></i> Évolution des Inscriptions (30 jours)</h3>
                    <div class="flex items-center gap-3">
                        <span class="badge secondary sm">{{ array_sum($signupCounts) }} total</span>
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn-cuni sm secondary" style="font-size: 0.75rem; padding: 4px 8px;">
                            Voir plus <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 250px; position: relative;">
                        <canvas id="signupChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Users / Quick Stats --}}
        <div>
            <div class="cuni-card h-full">
                <div class="card-header-custom py-3 flex items-center justify-between">
                    <h3 class="card-title text-sm"><i class="bi bi-activity"></i> Activité Globale</h3>
                    <div class="flex items-center gap-2">
                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; font-size: 0.7rem;">
                            {{ $activeUsers24h }} actifs (24h)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-sm" style="font-size: 0.8rem; margin: 0;">
                            <thead>
                                <tr style="background: var(--surface-alt);">
                                    <th style="padding: 8px 12px;">Utilisateur</th>
                                    <th style="padding: 8px 12px;">Entreprise</th>
                                    <th style="padding: 8px 12px; text-align: right;">Vues</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                    <tr>
                                        <td style="padding: 8px 12px;">
                                            <div class="fw-semibold">{{ $activity->user->name ?? 'Inconnu' }}</div>
                                            <div style="font-size: 0.7rem; color: var(--text-tertiary);">{{ $activity->updated_at->diffForHumans() }}</div>
                                        </td>
                                        <td style="padding: 8px 12px;">
                                            {{ $activity->user->firm->name ?? 'N/A' }}
                                        </td>
                                        <td style="padding: 8px 12px; text-align: right; font-weight: bold; color: var(--primary);">
                                            {{ $activity->hits }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Aucune activité récente</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Top Firms Leaderboard --}}
    <div class="cuni-card mb-6">
        <div class="card-header-custom flex items-center justify-between">
            <h3 class="card-title">
                <i class="bi bi-trophy"></i> Top 5 Entreprises (Par Revenus)
            </h3>
            <a href="{{ route('super.admin.firms') }}" class="btn-cuni sm secondary">
                Voir plus <i class="bi bi-arrow-right ml-1"></i>
            </a>
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
        <div class="card-header-custom flex items-center justify-between">
            <h3 class="card-title">
                <i class="bi bi-person-plus"></i> Inscriptions Récentes (7 jours)
            </h3>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn-cuni sm secondary">
                Voir plus <i class="bi bi-arrow-right ml-1"></i>
            </a>
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
    @push('scripts')
        <script>
            // Cleanup: Ensure Chart is initialized correctly after script loading fix in layout
            document.addEventListener('DOMContentLoaded', function() {
                const ctxSignup = document.getElementById('signupChart');
                if (ctxSignup) {
                    const signupCounts = @json($signupCounts);
                    const isSignupEmpty = signupCounts.length === 0 || signupCounts.every(val => val === 0);

                    if (isSignupEmpty) {
                        const container = ctxSignup.parentElement;
                        ctxSignup.style.display = 'none';
                        const placeholder = document.createElement('div');
                        placeholder.className = 'chart-placeholder';
                        placeholder.innerHTML = `
                        <i class="bi bi-graph-up-arrow"></i>
                        <p>Aucune inscription récente à afficher sur les 30 derniers jours.</p>
                    `;
                        container.appendChild(placeholder);
                    } else {
                        new Chart(ctxSignup.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: @json($signupLabels),
                                datasets: [{
                                    label: 'Nouvelles entreprises',
                                    data: signupCounts,
                                    borderColor: '#3B82F6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 2,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#3B82F6'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: '#1E293B',
                                        padding: 12,
                                        titleFont: { size: 14, weight: 'bold' },
                                        bodyFont: { size: 13 },
                                        cornerRadius: 8,
                                        displayColors: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            display: true,
                                            color: 'rgba(0,0,0,0.03)'
                                        },
                                        ticks: {
                                            stepSize: 1,
                                            font: { size: 11 }
                                        }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            maxRotation: 0,
                                            autoSkip: true,
                                            maxTicksLimit: 10,
                                            font: { size: 11 }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection
