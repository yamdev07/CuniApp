{{-- resources/views/firm/index.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Gestion de l\'Entreprise - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-building"></i> Gestion de l'Entreprise
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Entreprise</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-cuni success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- Firm Info Card --}}
    <div class="cuni-card mb-6">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-info-circle"></i> Informations de l'Entreprise
            </h3>
        </div>
        <div class="card-body">
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Nom de l'entreprise</label>
                    <p class="fw-semibold">{{ $firm->name }}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <p>
                        <span class="badge"
                            style="background: {{ $firm->status === 'active' ? 'rgba(16, 185, 129, 0.1); color: #10B981;' : 'rgba(239, 68, 68, 0.1); color: #EF4444;' }}">
                            {{ ucfirst($firm->status) }}
                        </span>
                    </p>
                </div>
                <div class="form-group">
                    <label class="form-label">Administrateur</label>
                    <p class="fw-semibold">{{ $firm->owner->name ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Créée le</label>
                    <p>{{ $firm->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- User Usage Card --}}
    <div class="cuni-card mb-6">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-people"></i> Utilisation des Utilisateurs
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 36px; font-weight: 700; color: var(--primary);">
                        {{ $activeUsersCount }} / {{ $subscriptionLimit }}
                    </div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 8px;">
                        Utilisateurs Actifs
                    </div>
                </div>
                <div style="text-align: center; padding: 20px;">
                    <div
                        style="font-size: 36px; font-weight: 700; color: {{ $canAddMoreUsers ? 'var(--accent-green)' : 'var(--accent-red)' }};">
                        {{ $remainingUsers }}
                    </div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 8px;">
                        Places Disponibles
                    </div>
                </div>
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 36px; font-weight: 700; color: var(--accent-purple);">
                        {{ number_format($usagePercentage, 1) }}%
                    </div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 8px;">
                        Taux d'Utilisation
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div style="margin-top: 24px;">
                <div
                    style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-secondary); margin-bottom: 8px;">
                    <span>Progression</span>
                    <span>{{ $usagePercentage }}%</span>
                </div>
                <div style="height: 8px; background: var(--surface-border); border-radius: 4px; overflow: hidden;">
                    <div
                        style="height: 100%; width: {{ $usagePercentage }}%; background: {{ $usagePercentage >= 80 ? 'var(--accent-orange)' : 'var(--primary)' }}; border-radius: 4px; transition: width 1s ease;">
                    </div>
                </div>
                @if ($usagePercentage >= 80)
                    <div
                        style="margin-top: 12px; padding: 12px; background: rgba(245, 158, 11, 0.1); border-radius: var(--radius); border-left: 3px solid var(--accent-orange);">
                        <p style="font-size: 13px; color: var(--accent-orange); margin: 0;">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Attention:</strong> Vous approchez de la limite d'utilisateurs. Envisagez de mettre à
                            niveau votre abonnement.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Add Employee Button --}}
            @if ($canAddMoreUsers)
                <div style="margin-top: 24px;">
                    <button type="button" class="btn-cuni primary" onclick="showAddEmployeeModal()">
                        <i class="bi bi-person-plus"></i> Ajouter un Employé
                    </button>
                </div>
            @else
                <div style="margin-top: 24px;">
                    <a href="{{ route('subscription.plans') }}" class="btn-cuni danger">
                        <i class="bi bi-arrow-up-circle"></i> Mettre à Niveau l'Abonnement
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Employees List --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i> Liste des Employés
            </h3>
            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                {{ $employees->total() }} employé(s)
            </span>
        </div>
        <div class="card-body">
            @if ($employees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-uppercase text-muted fw-semibold small">Nom</th>
                                <th class="text-uppercase text-muted fw-semibold small">Email</th>
                                <th class="text-uppercase text-muted fw-semibold small">Statut</th>
                                <th class="text-uppercase text-muted fw-semibold small">Inscrit le</th>
                                <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 fw-semibold text-dark">{{ $employee->name }}</td>
                                    <td class="text-muted">{{ $employee->email }}</td>
                                    <td>
                                        <span class="badge"
                                            style="background: {{ $employee->status === 'active' ? 'rgba(16, 185, 129, 0.1); color: #10B981;' : 'rgba(107, 114, 128, 0.1); color: #6B7280;' }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $employee->created_at->format('d/m/Y') }}</td>
                                    <td class="pe-4">
                                        <div class="action-buttons">
                                            <button type="button" class="btn-cuni sm secondary"
                                                onclick="showEditEmployeeModal({{ $employee->id }}, '{{ $employee->name }}', '{{ $employee->email }}', '{{ $employee->status }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if ($employee->status === 'active')
                                                <form action="{{ route('firm.employee.deactivate', $employee->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-cuni sm danger"
                                                        onclick="return confirm('Désactiver cet employé ?')">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($employees->hasPages())
                    <div style="margin-top: 24px;">
                        {{ $employees->links('pagination.bootstrap-5-sm') }}
                    </div>
                @endif
            @else
                <div class="text-center py-16 text-gray-500">
                    <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="mt-4 text-lg">Aucun employé enregistré</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Add Employee Modal --}}
    <div id="addEmployeeModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
            <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
                <i class="bi bi-person-plus"></i> Ajouter un Employé
            </h3>
            <form action="{{ route('firm.employee.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Nom complet
                        *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Mot de passe
                        *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <input type="hidden" name="role" value="employee">
                <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                    <button type="button" class="btn-cuni secondary"
                        onclick="document.getElementById('addEmployeeModal').style.display='none'">
                        Annuler
                    </button>
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-check-circle"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function showAddEmployeeModal() {
                document.getElementById('addEmployeeModal').style.display = 'flex';
            }

            function showEditEmployeeModal(id, name, email, status) {
                // Implement edit modal similar to add
                console.log('Edit employee:', id, name, email, status);
            }

            // Close modals on outside click
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
