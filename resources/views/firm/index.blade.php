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

    @if ($errors->any())
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Erreurs de validation</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
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
                                        @php
                                            $status = $employee->status ?? 'active'; // Default to active if null
                                            $statusLabels = [
                                                'active' => ['Actif', 'rgba(16, 185, 129, 0.1)', '#10B981'],
                                                'inactive' => ['Inactif', 'rgba(107, 114, 128, 0.1)', '#6B7280'],
                                            ];
                                            $label = $statusLabels[$status] ?? [
                                                'Non spécifié',
                                                'rgba(245, 158, 11, 0.1)',
                                                '#F59E0B',
                                            ];
                                        @endphp
                                        <span class="badge"
                                            style="background: {{ $label[1] }}; color: {{ $label[2] }}; font-size: 11px; padding: 4px 10px; border-radius: 20px;">
                                            @if ($status === 'active')
                                                <i class="bi bi-check-circle-fill" style="margin-right: 4px;"></i>
                                            @elseif($status === 'inactive')
                                                <i class="bi bi-pause-circle-fill" style="margin-right: 4px;"></i>
                                            @else
                                                <i class="bi bi-question-circle-fill" style="margin-right: 4px;"></i>
                                            @endif
                                            {{ $label[0] }}
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

    {{-- ✅ ADD EMPLOYEE MODAL - COMPLETE WITH PASSWORD VALIDATION --}}
    <div id="addEmployeeModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: var(--surface); border-radius: var(--radius-lg); max-width: 550px; width: 90%; padding: 32px; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700;">
                    <i class="bi bi-person-plus" style="color: var(--primary);"></i>
                    Ajouter un Employé
                </h3>
                <button type="button" onclick="closeAddEmployeeModal()"
                    style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-secondary);">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('firm.employee.store') }}" method="POST" id="addEmployeeForm">
                @csrf

                {{-- Name Field --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">
                        Nom complet *
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                            minlength="2" maxlength="255" placeholder="Ex: Jean Dupont" style="padding-left: 40px;">
                        <i class="bi bi-person"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                        </i>
                    </div>
                    @error('name')
                        <div class="validation-message error" style="margin-top: 6px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">
                        Adresse email *
                    </label>
                    <div style="position: relative;">
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                            placeholder="employe@exemple.com" style="padding-left: 40px;">
                        <i class="bi bi-envelope"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                        </i>
                    </div>
                    <small style="color: var(--text-tertiary); font-size: 11px; margin-top: 4px; display: block;">
                        <i class="bi bi-info-circle"></i> L'email doit être unique dans tout le système
                    </small>
                    @error('email')
                        <div class="validation-message error" style="margin-top: 6px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">
                        Mot de passe *
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" id="employeePassword" required
                            minlength="8" placeholder="••••••••" style="padding-left: 40px; padding-right: 40px;">
                        <i class="bi bi-lock"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                        </i>
                        <button type="button" onclick="togglePassword('employeePassword', this)"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-400);">
                            <i class="bi bi-eye" id="employeePasswordIcon"></i>
                        </button>
                    </div>

                    {{-- Password Strength Indicator --}}
                    <div class="password-strength-container" style="margin-top: 8px;">
                        <div class="password-strength-bar"
                            style="height: 6px; background: var(--gray-200); border-radius: 3px; overflow: hidden; margin-bottom: 8px;">
                            <div class="password-strength-fill" id="employeePasswordStrengthFill"
                                style="height: 100%; border-radius: 3px; transition: all 0.3s ease; width: 0%;"></div>
                        </div>
                        <div class="password-strength-text" id="employeePasswordStrengthText"
                            style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            Faible
                        </div>
                    </div>

                    {{-- Password Requirements --}}
                    <div class="password-requirements"
                        style="margin-top: 12px; padding: 12px; background: var(--gray-50); border-radius: var(--radius); font-size: 11px;">
                        <div class="password-requirements-title"
                            style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-shield-check"></i>
                            Le mot de passe doit contenir:
                        </div>
                        <div class="password-requirement" id="req-length"
                            style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: var(--gray-600);">
                            <i class="bi bi-circle" style="font-size: 10px;"></i>
                            <span>Au moins 8 caractères</span>
                        </div>
                        <div class="password-requirement" id="req-uppercase"
                            style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: var(--gray-600);">
                            <i class="bi bi-circle" style="font-size: 10px;"></i>
                            <span>Une majuscule</span>
                        </div>
                        <div class="password-requirement" id="req-lowercase"
                            style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: var(--gray-600);">
                            <i class="bi bi-circle" style="font-size: 10px;"></i>
                            <span>Une minuscule</span>
                        </div>
                        <div class="password-requirement" id="req-number"
                            style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: var(--gray-600);">
                            <i class="bi bi-circle" style="font-size: 10px;"></i>
                            <span>Un chiffre</span>
                        </div>
                        <div class="password-requirement" id="req-symbol"
                            style="display: flex; align-items: center; gap: 8px; color: var(--gray-600);">
                            <i class="bi bi-circle" style="font-size: 10px;"></i>
                            <span>Un caractère spécial</span>
                        </div>
                    </div>

                    @error('password')
                        <div class="validation-message error" style="margin-top: 6px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Confirm Password Field --}}
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">
                        Confirmer le mot de passe *
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" class="form-control"
                            id="employeePasswordConfirmation" required placeholder="••••••••"
                            style="padding-left: 40px; padding-right: 40px;">
                        <i class="bi bi-lock-fill"
                            style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                        </i>
                        <button type="button" onclick="togglePassword('employeePasswordConfirmation', this)"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-400);">
                            <i class="bi bi-eye" id="employeePasswordConfirmationIcon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="validation-message error" style="margin-top: 6px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <input type="hidden" name="role" value="employee">

                <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                    <button type="button" class="btn-cuni secondary" onclick="closeAddEmployeeModal()">
                        Annuler
                    </button>
                    <button type="submit" class="btn-cuni primary" id="submitEmployeeBtn">
                        <i class="bi bi-check-circle"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ EDIT EMPLOYEE MODAL --}}
    <div id="editEmployeeModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700;">
                    <i class="bi bi-pencil" style="color: var(--primary);"></i>
                    Modifier l'Employé
                </h3>
                <button type="button" onclick="closeEditEmployeeModal()"
                    style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-secondary);">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="editEmployeeForm" method="POST">
                @csrf
                @method('PATCH')

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Nom complet
                        *</label>
                    <input type="text" name="name" id="editEmployeeName" class="form-control" required>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Email *</label>
                    <input type="email" name="email" id="editEmployeeEmail" class="form-control" required>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Statut</label>
                    <select name="status" id="editEmployeeStatus" class="form-select" required>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                    <button type="button" class="btn-cuni secondary" onclick="closeEditEmployeeModal()">
                        Annuler
                    </button>
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // ============================================
            // MODAL FUNCTIONS
            // ============================================
            function showAddEmployeeModal() {
                document.getElementById('addEmployeeModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
                document.getElementById('employeePassword').focus();
            }

            function closeAddEmployeeModal() {
                document.getElementById('addEmployeeModal').style.display = 'none';
                document.body.style.overflow = '';
                document.getElementById('addEmployeeForm').reset();
                resetPasswordStrength();
            }

            function showEditEmployeeModal(id, name, email, status) {
                // ✅ Use placeholder and replace
                document.getElementById('editEmployeeForm').action =
                    "{{ route('firm.employee.update', ['userId' => ':id']) }}".replace(':id', id);

                document.getElementById('editEmployeeName').value = name;
                document.getElementById('editEmployeeEmail').value = email;
                document.getElementById('editEmployeeStatus').value = status;
                document.getElementById('editEmployeeModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeEditEmployeeModal() {
                document.getElementById('editEmployeeModal').style.display = 'none';
                document.body.style.overflow = '';
            }

            // Close modals on outside click
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddEmployeeModal();
                    closeEditEmployeeModal();
                }
            });

            // ============================================
            // PASSWORD TOGGLE FUNCTION
            // ============================================
            function togglePassword(inputId, button) {
                const input = document.getElementById(inputId);
                const icon = button.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }

            // ============================================
            // PASSWORD STRENGTH CALCULATOR (Like Welcome Page)
            // ============================================
            const employeePassword = document.getElementById('employeePassword');
            const passwordStrengthFill = document.getElementById('employeePasswordStrengthFill');
            const passwordStrengthText = document.getElementById('employeePasswordStrengthText');

            if (employeePassword) {
                employeePassword.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    updatePasswordStrengthUI(strength);
                    updatePasswordRequirements(password);
                });
            }

            function calculatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
                return score;
            }

            function updatePasswordStrengthUI(strength) {
                if (!passwordStrengthFill || !passwordStrengthText) return;

                const classes = ['weak', 'fair', 'good', 'strong'];
                const labels = ['Faible', 'Moyen', 'Bon', 'Excellent'];
                const widths = ['25%', '50%', '75%', '100%'];
                const colors = ['var(--accent-red)', 'var(--accent-orange)', 'var(--accent-cyan)', 'var(--accent-green)'];

                const index = Math.min(strength - 1, 3);

                if (index >= 0) {
                    passwordStrengthFill.className = 'password-strength-fill ' + classes[index];
                    passwordStrengthFill.style.width = widths[index];
                    passwordStrengthFill.style.backgroundColor = colors[index];
                    passwordStrengthText.className = 'password-strength-text ' + classes[index];
                    passwordStrengthText.textContent = labels[index];
                    passwordStrengthText.style.color = colors[index];
                } else {
                    passwordStrengthFill.style.width = '0%';
                    passwordStrengthText.textContent = 'Faible';
                }
            }

            function updatePasswordRequirements(password) {
                const requirements = [{
                        id: 'req-length',
                        test: () => password.length >= 8
                    },
                    {
                        id: 'req-uppercase',
                        test: () => /[A-Z]/.test(password)
                    },
                    {
                        id: 'req-lowercase',
                        test: () => /[a-z]/.test(password)
                    },
                    {
                        id: 'req-number',
                        test: () => /[0-9]/.test(password)
                    },
                    {
                        id: 'req-symbol',
                        test: () => /[!@#$%^&*(),.?":{}|<>]/.test(password)
                    }
                ];

                requirements.forEach(req => {
                    const element = document.getElementById(req.id);
                    const icon = element.querySelector('i');

                    if (req.test()) {
                        element.classList.add('met');
                        element.style.color = 'var(--accent-green)';
                        icon.classList.remove('bi-circle');
                        icon.classList.add('bi-check-circle-fill');
                        icon.style.color = 'var(--accent-green)';
                    } else {
                        element.classList.remove('met');
                        element.style.color = 'var(--gray-600)';
                        icon.classList.remove('bi-check-circle-fill');
                        icon.classList.add('bi-circle');
                        icon.style.color = 'var(--gray-400)';
                    }
                });
            }

            function resetPasswordStrength() {
                if (passwordStrengthFill) {
                    passwordStrengthFill.style.width = '0%';
                    passwordStrengthFill.style.backgroundColor = 'var(--gray-200)';
                }
                if (passwordStrengthText) {
                    passwordStrengthText.textContent = 'Faible';
                    passwordStrengthText.style.color = 'var(--accent-red)';
                }

                // Reset requirements
                ['req-length', 'req-uppercase', 'req-lowercase', 'req-number', 'req-symbol'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.classList.remove('met');
                        element.style.color = 'var(--gray-600)';
                        const icon = element.querySelector('i');
                        if (icon) {
                            icon.classList.remove('bi-check-circle-fill');
                            icon.classList.add('bi-circle');
                            icon.style.color = 'var(--gray-400)';
                        }
                    }
                });
            }

            // ============================================
            // FORM SUBMISSION WITH LOADING STATE
            // ============================================
            document.getElementById('addEmployeeForm')?.addEventListener('submit', function(e) {
                const password = document.getElementById('employeePassword').value;
                const passwordConfirmation = document.getElementById('employeePasswordConfirmation').value;

                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    showToast('❌ Les mots de passe ne correspondent pas', 'error');
                    return;
                }

                const strength = calculatePasswordStrength(password);
                if (strength < 4) {
                    e.preventDefault();
                    showToast('⚠️ Le mot de passe est trop faible. Veuillez le renforcer.', 'error');
                    return;
                }

                const submitBtn = document.getElementById('submitEmployeeBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Ajout en cours...';
                }
            });

            // ============================================
            // TOAST NOTIFICATION
            // ============================================
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 30px;
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)'};
        padding: 16px 24px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
    `;

                const icon = type === 'success' ? 'check-circle-fill' : 'x-circle-fill';
                const color = type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)';

                toast.innerHTML = `
        <i class="bi bi-${icon}" style="color: ${color}; font-size: 20px;"></i>
        <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
    `;

                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Add animation styles
            if (!document.getElementById('cuniapp-animations-style')) {
                const style = document.createElement('style');
                style.id = 'cuniapp-animations-style';
                style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
                document.head.appendChild(style);
            }
        </script>
    @endpush
@endsection
