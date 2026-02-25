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

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer mot de passe</label>
                            <input type="password" name="new_password_confirmation" class="form-control"
                                placeholder="••••••••">
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
        /* Form Enhancements */
        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-subtle);
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            accent-color: var(--primary);
            cursor: pointer;
        }

        #profile-tab {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* .cuni-card {
                border: 2px solid red;
            } */

        @media (max-width: 768px) {

            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

@endsection
