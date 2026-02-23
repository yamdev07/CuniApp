@extends('layouts.cuniapp')

@section('title', 'Détails Naissance - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-eye"></i>
            Détails de la Naissance
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a>
            <span>/</span>
            <span>Détails</span>
        </div>
    </div>
</div>

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-star"></i>
            Informations du Lapereau
        </h3>
        <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    <div class="card-body">
        <div class="settings-grid">
            <div class="form-group">
                <label class="form-label">Nom du lapin</label>
                <div style="font-weight: 600; color: var(--gray-800);">{{ $naissance->nom_lapin }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Sexe</label>
                <div>
                    @if($naissance->sexe === 'M')
                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">Mâle</span>
                    @else
                        <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">Femelle</span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Date de naissance</label>
                <div style="font-weight: 600; color: var(--gray-800);">{{ date('d/m/Y', strtotime($naissance->date_naissance)) }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Poids</label>
                <div style="font-weight: 600; color: var(--gray-800);">{{ $naissance->poids }} kg</div>
            </div>
        </div>
        <div style="margin-top: 24px;">
            <a href="{{ route('naissances.edit', $naissance->id) }}" class="btn-cuni primary">
                <i class="bi bi-pencil"></i>
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection