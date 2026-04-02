@extends('layouts.cuniapp')

@section('content')
<div class="onboarding-wrapper" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div class="onboarding-card cuni-card" style="max-width: 600px; width: 100%; animation: slideUp 0.6s ease-out;">
        <div class="card-header-custom" style="padding: 2.5rem 2rem 1.5rem; text-align: center;">
            <div class="setup-icon" style="font-size: 3rem; color: var(--cuni-primary); margin-bottom: 1rem;">
                <i class="bi bi-shop"></i>
            </div>
            <h2 class="card-title" style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">CuniApp Onboarding</h2>
            <p class="text-muted">Dernière étape ! Configurez votre élevage pour commencer.</p>
        </div>

        <div class="card-body" style="padding: 0 2.5rem 2.5rem;">
            @if ($errors->any())
                <div class="alert-cuni danger mb-6">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('firm.setup.store') }}" method="POST">
                @csrf
                <div class="form-group mb-6">
                    <label for="firm_name" class="form-label" style="font-weight: 600;">Nom de votre Entreprise / Élevage <span class="text-danger">*</span></label>
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-building"></i></span>
                        <input type="text" name="firm_name" id="firm_name" class="form-control" 
                               placeholder="Ex: Élevage Bio de l'Ouest" value="{{ old('firm_name') }}" required autofocus>
                    </div>
                    <small class="form-text text-muted">Ce nom apparaîtra sur vos rapports et factures.</small>
                </div>

                <div class="form-group mb-8">
                    <label for="firm_description" class="form-label" style="font-weight: 600;">Description (Optionnel)</label>
                    <textarea name="firm_description" id="firm_description" class="form-control" rows="3" 
                              placeholder="Décrivez brièvement votre activité...">{{ old('firm_description') }}</textarea>
                </div>

                <div class="setup-benefit mb-8" style="background: rgba(var(--cuni-primary-rgb), 0.05); border-radius: 12px; padding: 1.25rem; border-left: 4px solid var(--cuni-primary);">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-gift-fill text-primary me-2"></i>
                        <strong style="color: var(--cuni-primary);">Cadeau de Bienvenue</strong>
                    </div>
                    <p class="mb-0" style="font-size: 0.9rem;">En créant votre entreprise aujourd'hui, vous recevez automatiquement un <strong>Essai Gratuit de 14 jours</strong> sans engagement.</p>
                </div>

                <button type="submit" class="btn-cuni primary w-100 py-3" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px;">
                    Finaliser la Configuration <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .onboarding-wrapper {
        background: radial-gradient(circle at top right, rgba(var(--cuni-primary-rgb), 0.1), transparent 400px),
                    radial-gradient(circle at bottom left, rgba(var(--cuni-primary-rgb), 0.05), transparent 300px);
    }
    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-group-custom .input-icon {
        position: absolute;
        left: 1rem;
        color: var(--text-muted);
        font-size: 1.1rem;
    }
    .input-group-custom .form-control {
        padding-left: 3rem;
        height: 52px;
        border-radius: 10px;
        border: 1.5px solid var(--border-color);
        transition: all 0.3s ease;
    }
    .input-group-custom .form-control:focus {
        border-color: var(--cuni-primary);
        box-shadow: 0 0 0 4px rgba(var(--cuni-primary-rgb), 0.15);
    }
</style>
@endsection
