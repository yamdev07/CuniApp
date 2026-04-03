{{-- resources/views/admin/subscriptions/transactions.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Transactions Globales - Admin')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-cash-stack"></i> Transactions Globales
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Admin</span>
                <span>/</span>
                <span>Transactions</span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-currency-euro text-green-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Revenus Totaux</p>
                        <p class="stats-value-small text-green-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(59, 130, 246, 0.1);">
                        <i class="bi bi-check-circle text-blue-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Complétées</p>
                        <p class="stats-value-small text-blue-600">{{ number_format($stats['completed_count']) }}</p>
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
                        <p class="stats-label-small">En attente</p>
                        <p class="stats-value-small text-amber-600">{{ number_format($stats['pending_count']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(239, 68, 68, 0.1);">
                        <i class="bi bi-x-circle text-red-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Échouées</p>
                        <p class="stats-value-small text-red-600">{{ number_format($stats['failed_count']) }}</p>
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

    <div class="cuni-card">
        <div class="card-header-custom flex items-center justify-between">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i> Historique des Transactions
            </h3>
            <form method="GET" style="display: flex; gap: 12px;">
                <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complétées</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échouées</option>
                </select>
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--surface-border);">
                            <th style="padding: 12px; text-align: left;">Référence</th>
                            <th style="padding: 12px; text-align: left;">Utilisateur</th>
                            <th style="padding: 12px; text-align: left;">Méthode</th>
                            <th style="padding: 12px; text-align: right;">Montant</th>
                            <th style="padding: 12px; text-align: left;">Statut</th>
                            <th style="padding: 12px; text-align: left;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr style="border-bottom: 1px solid var(--surface-border);">
                                <td style="padding: 12px; font-family: monospace;">{{ $transaction->transaction_id ?? '-' }}</td>
                                <td style="padding: 12px; font-weight: 600;">
                                    {{ $transaction->user->name ?? 'Utilisateur Inconnu' }}<br>
                                    <small style="color: var(--text-tertiary); font-weight: normal;">{{ $transaction->user->email ?? '' }}</small>
                                </td>
                                <td style="padding: 12px;">
                                    @if ($transaction->payment_method === 'manual')
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">Manuel</span>
                                    @elseif ($transaction->payment_method === 'fedapay')
                                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--blue-600);">FedaPay</span>
                                    @else
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">{{ $transaction->payment_method ?? 'Inconnu' }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: bold;">
                                    {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td style="padding: 12px;">
                                    @if ($transaction->status === 'completed')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Complétée</span>
                                    @elseif ($transaction->status === 'pending')
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--accent-orange);">En attente</span>
                                    @elseif ($transaction->status === 'failed')
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">Échouée</span>
                                    @else
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">{{ $transaction->status }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                        @if($transactions->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">Aucune transaction trouvée.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- ✅ PAGINATION STYLISÉE CuniApp --}}
            @if ($transactions->hasPages())
                <div class="flex flex-col md:flex-row justify-between items-center mt-8 gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">

                    {{-- Résumé --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Affichage de <strong>{{ $transactions->firstItem() }}</strong> à
                        <strong>{{ $transactions->lastItem() }}</strong> sur
                        <strong>{{ $transactions->total() }}</strong> transactions
                    </div>

                    <div class="flex gap-2 flex-wrap justify-center">

                        {{-- Bouton Précédent --}}
                        @if ($transactions->onFirstPage())
                            <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed" style="pointer-events: none;">
                                <i class="bi bi-chevron-left"></i> Précédent
                            </span>
                        @else
                            <a href="{{ $transactions->previousPageUrl() }}" class="btn-cuni secondary sm">
                                <i class="bi bi-chevron-left"></i> Précédent
                            </a>
                        @endif

                        {{-- Numéros de pages --}}
                        @php
                            $start = max($transactions->currentPage() - 2, 1);
                            $end = min($transactions->currentPage() + 2, $transactions->lastPage());
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $transactions->url(1) }}" class="btn-cuni sm" style="min-width: 40px; justify-content: center;">1</a>
                            @if ($start > 2)
                                <span class="text-gray-400 px-2 flex items-center">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $transactions->url($i) }}"
                                class="btn-cuni sm {{ $i == $transactions->currentPage() ? 'primary' : 'secondary' }}"
                                style="min-width: 40px; justify-content: center;">
                                {{ $i }}
                            </a>
                        @endfor

                        @if ($end < $transactions->lastPage())
                            @if ($end < $transactions->lastPage() - 1)
                                <span class="text-gray-400 px-2 flex items-center">...</span>
                            @endif
                            <a href="{{ $transactions->url($transactions->lastPage()) }}" class="btn-cuni sm" style="min-width: 40px; justify-content: center;">
                                {{ $transactions->lastPage() }}
                            </a>
                        @endif

                        {{-- Bouton Suivant --}}
                        @if ($transactions->hasMorePages())
                            <a href="{{ $transactions->nextPageUrl() }}" class="btn-cuni secondary sm">
                                Suivant <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed" style="pointer-events: none;">
                                Suivant <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
