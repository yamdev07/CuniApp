{{-- resources/views/expenses/index.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Dépenses - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-wallet2"></i> Gestion des Dépenses</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Dépenses</span>
            </div>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn-cuni primary">
            <i class="bi bi-plus-lg"></i> Nouvelle Dépense
        </a>
    </div>

    @if (session('success'))
        <div class="alert-cuni success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Dépenses</p>
                        <p class="text-2xl font-bold text-danger">
                            {{ number_format($stats['total'] ?? 0, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(239, 68, 68, 0.1);">
                        <i class="bi bi-wallet2 text-danger text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Ce Mois-ci</p>
                        <p class="text-2xl font-bold text-warning">
                            {{ number_format($stats['this_month'] ?? 0, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-calendar text-warning text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Solde Net (Est.)</p>
                        @php
                            $sales = \App\Models\Sale::where('user_id', auth()->id())
                                ->where('payment_status', 'paid')
                                ->sum('total_amount');
                            $expenses = \App\Models\Expense::where('user_id', auth()->id())->sum('amount');
                            $net = $sales - $expenses;
                        @endphp
                        <p class="text-2xl font-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($net, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-graph-up text-success text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="cuni-card mb-6">
        <div class="card-body" style="padding: 16px;">
            <form method="GET" action="{{ route('expenses.index') }}">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-filter-circle" style="margin-right: 4px;"></i> Catégorie
                        </label>
                        <select name="category" class="form-select">
                            <option value="">Toutes</option>
                            <option value="Alimentation" {{ request('category') === 'Alimentation' ? 'selected' : '' }}>
                                Alimentation</option>
                            <option value="Vétérinaire" {{ request('category') === 'Vétérinaire' ? 'selected' : '' }}>
                                Vétérinaire</option>
                            <option value="Équipement" {{ request('category') === 'Équipement' ? 'selected' : '' }}>
                                Équipement</option>
                            <option value="Transport" {{ request('category') === 'Transport' ? 'selected' : '' }}>Transport
                            </option>
                            <option value="Autre" {{ request('category') === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-calendar" style="margin-right: 4px;"></i> Mois
                        </label>
                        <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1;">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        @if (request()->anyFilled(['category', 'month']))
                            <a href="{{ route('expenses.index') }}" class="btn-cuni secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title"><i class="bi bi-list-ul"></i> Historique des Dépenses</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">Date</th>
                            <th class="text-uppercase text-muted fw-semibold small">Catégorie</th>
                            <th class="text-uppercase text-muted fw-semibold small">Description</th>
                            <th class="text-uppercase text-muted fw-semibold small">Montant</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-semibold">{{ $expense->expense_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($expense->description, 50) }}</td>
                                <td class="text-danger fw-bold">- {{ $expense->formatted_amount }}</td>
                                <td class="pe-4">
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-cuni sm danger" title="Supprimer"
                                            onclick="return confirm('Supprimer cette dépense ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
