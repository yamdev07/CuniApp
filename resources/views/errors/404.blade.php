@extends('layouts.cuniapp')

@section('title', 'Page non trouvée')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Erreur 404</h2>
    </div>

    <div class="cuni-card">
        <div class="card-body text-center py-16">
            <div class="text-6xl mb-4 opacity-20" style="color: var(--primary);">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Page non trouvée</h3>
            <p class="text-gray-500 mb-6">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            
            <a href="{{ route('dashboard') }}" class="btn-cuni primary">
                <i class="bi bi-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
@endsection