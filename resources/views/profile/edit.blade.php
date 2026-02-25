@extends('layouts.cuniapp')

@section('title', 'Profil - CuniApp Élevage')

@section('content')

    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-person-circle"></i> Mon Profil
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Profil</span>
            </div>
        </div>
    </div>

    <div class="tab-content show active" id="profile-tab">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-person-circle"></i>
                    Mon Profil Utilisateur
                </h3>
            </div>
            <div class="card-body">

                {{-- Message de succès avec style "Alerte Personnalisée" --}}
                @if (session('status') === 'profile-updated')
                    <div class="alert-custom alert-custom-success alert-to-fade">
                        <i class="bi bi-check2-circle alert-icon"></i>
                        <div>
                            <strong>Génial !</strong> Votre profil a été mis à jour avec succès.
                        </div>
                    </div>
                @endif

                {{-- Bloc d'erreurs général avec style "Alerte Personnalisée" --}}
                @if ($errors->any())
                    <div class="alert-custom alert-custom-danger">
                        <i class="bi bi-exclamation-octagon alert-icon"></i>
                        <div>
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                            @error('current_password')
                                <div class="field-error"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer mot de passe</label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-save"></i>
                            Mettre à jour le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Alertes personnalisées avec bordure gauche */
        .alert-custom {
            border: none;
            border-left: 5px solid;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .alert-custom-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #22c55e;
        }
        .alert-custom-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #ef4444;
        }
        .alert-icon {
            font-size: 1.4rem;
            margin-right: 15px;
        }

        /* Erreur sous le champ spécifique */
        .field-error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Form Enhancements & Grid */
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }

        #profile-tab {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.querySelector('.alert-to-fade');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = "opacity 0.6s ease, transform 0.6s ease";
                    successAlert.style.opacity = "0";
                    successAlert.style.transform = "translateY(-10px)";
                    setTimeout(() => { successAlert.remove(); }, 600);
                }, 5000);
            }
        });
    </script>

@endsection