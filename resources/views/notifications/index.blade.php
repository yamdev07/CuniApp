@extends('layouts.cuniapp')
@section('title', 'Notifications - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-bell-fill"></i> Notifications
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Notifications</span>
        </div>
    </div>
    <div class="flex gap-3">
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn-cuni secondary">
                <i class="bi bi-check2-all"></i> Tout marquer comme lu
            </button>
        </form>
    </div>
</div>

@if($notifications->isEmpty())
<div class="cuni-card">
    <div class="card-body text-center py-16">
        <div class="text-6xl mb-4 opacity-20">
            <i class="bi bi-bell-slash-fill"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Aucune notification</h3>
        <p class="text-gray-500">Vous n'avez aucune notification pour le moment</p>
    </div>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Stats Cards -->
    <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Non lues', 'count' => $unreadCount, 'color' => 'red', 'icon' => 'bi-envelope'],
                ['label' => 'Succès', 'count' => $notifications->where('type', 'success')->count(), 'color' => 'green', 'icon' => 'bi-check-circle'],
                ['label' => 'Alertes', 'count' => $notifications->where('type', 'warning')->count(), 'color' => 'amber', 'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Infos', 'count' => $notifications->where('type', 'info')->count(), 'color' => 'blue', 'icon' => 'bi-info-circle'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold mt-1 text-gray-800">{{ $stat['count'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" 
                         style="background: rgba({{ $stat['color'] === 'red' ? '239, 68, 68' : ($stat['color'] === 'green' ? '16, 185, 129' : ($stat['color'] === 'amber' ? '245, 158, 11' : '59, 130, 246')) }}, 0.1)">
                        <i class="bi {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Notifications List -->
    <div class="lg:col-span-3">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-list-ul"></i> Historique des notifications
                </h3>
                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                    {{ $unreadCount }} non lues
                </span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notif)
                <div class="p-4 hover:bg-gray-50 transition-colors {{ $notif->is_read ? 'opacity-75' : 'bg-blue-50 border-l-4 border-blue-500' }}">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex-shrink-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center" 
                                 style="background: rgba(
                                    {{ $notif->type === 'success' ? '16, 185, 129' : 
                                       ($notif->type === 'warning' ? '245, 158, 11' : 
                                       ($notif->type === 'error' ? '239, 68, 68' : '59, 130, 246')) }}, 0.1)">
                                <i class="bi {{ $notif->icon }} text-lg {{
                                    $notif->type === 'success' ? 'text-green-500' : 
                                    ($notif->type === 'warning' ? 'text-amber-500' : 
                                    ($notif->type === 'error' ? 'text-red-500' : 'text-blue-500'))
                                }}"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-gray-800">{{ $notif->title }}</h4>
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1 text-sm">{{ $notif->message }}</p>
                            
                            @if($notif->action_url)
                            <div class="mt-2 flex items-center gap-2">
                                <a href="{{ $notif->action_url }}" 
                                   class="text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 group">
                                    Voir les détails <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                                @if(!$notif->is_read)
                                <span class="text-xs text-blue-500 font-medium">• Nouveau</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-end space-y-1">
                            @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                    <i class="bi bi-check-circle"></i> Marquer lu
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" 
                                        onclick="return confirm('Supprimer cette notification ?')">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $notifications->links('vendor.pagination.bootstrap-5-sm') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .cuni-card { transition: all 0.2s ease; }
    .cuni-card:hover { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
</style>
@endpush