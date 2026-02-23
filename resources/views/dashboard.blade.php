@extends('layouts.app')

@section('title', 'Tableau de Bord Élevage - CuniApp')

@section('content')
<div class="anyxtech-dashboard">
    <!-- Header Section -->
    <header class="dash-header">
        <div class="header-wrapper">
            <div class="brand-identity">
                <div class="anyxtech-logo">
                    <svg viewBox="0 0 40 40" fill="none">
                        <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="url(#logoGrad)"/>
                        <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="#0A1628"/>
                        <defs>
                            <linearGradient id="logoGrad" x1="5" y1="5" x2="35" y2="35">
                                <stop offset="0%" stop-color="#00D9FF"/>
                                <stop offset="100%" stop-color="#0066FF"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="brand-text">
                    <h1 class="brand-title">CuniApp <span class="subtitle-accent">Élevage</span></h1>
                    <p class="brand-tagline">Gestion intelligente de votre cheptel</p>
                </div>
            </div>

            <div class="header-controls">
                <a href="{{ route('settings.index') }}" class="ctrl-btn secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 15v-3m0 0V9m0 3h3m-3 0H9m9-9a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Paramètres
                </a>
                <a href="{{ route('lapins.create') }}" class="ctrl-btn primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Nouvelle entrée
                </a>
            </div>
        </div>

        <div class="metrics-grid">
            @php
                $metricsData = [
                    ['icon' => 'total', 'value' => $nbMales + $nbFemelles, 'label' => 'Total Lapins', 'type' => 'primary', 'change' => '+8.2%', 'trend' => 'up'],
                    ['icon' => 'male', 'value' => $nbMales, 'label' => 'Mâles', 'type' => 'blue', 'change' => '+5.1%', 'trend' => 'up'],
                    ['icon' => 'female', 'value' => $nbFemelles, 'label' => 'Femelles', 'type' => 'pink', 'change' => '+12%', 'trend' => 'up'],
                    ['icon' => 'breed', 'value' => $nbSaillies, 'label' => 'Saillies', 'type' => 'purple', 'change' => '-3.1%', 'trend' => 'down'],
                    ['icon' => 'birth', 'value' => $nbMisesBas, 'label' => 'Portées', 'type' => 'green', 'change' => '+15%', 'trend' => 'up'],
                    ['icon' => 'alert', 'value' => 3, 'label' => 'Alertes', 'type' => 'orange', 'change' => '0%', 'trend' => 'neutral']
                ];
            @endphp

            @foreach($metricsData as $metric)
            <div class="metric-card {{ $metric['type'] }}" data-trend="{{ $metric['trend'] }}">
                <div class="metric-icon">
                    @if($metric['icon'] === 'total')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                            <circle cx="17" cy="7" r="2"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        </svg>
                    @elseif($metric['icon'] === 'male')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="10" cy="14" r="6"/><path d="M16 8h6V2M22 2l-8.5 8.5"/>
                        </svg>
                    @elseif($metric['icon'] === 'female')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="8" r="6"/><path d="M12 14v8M9 19h6"/>
                        </svg>
                    @elseif($metric['icon'] === 'breed')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    @elseif($metric['icon'] === 'birth')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    @endif
                </div>
                <div class="metric-data">
                    <div class="metric-value">{{ $metric['value'] }}</div>
                    <div class="metric-label">{{ $metric['label'] }}</div>
                    <div class="metric-trend {{ $metric['trend'] }}">
                        <span class="trend-arrow">{{ $metric['trend'] === 'up' ? '↗' : ($metric['trend'] === 'down' ? '↘' : '→') }}</span>
                        {{ $metric['change'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </header>

    <!-- Main Grid -->
    <div class="main-grid">
        <!-- Left Column -->
        <div class="primary-col">
            <!-- Performance Overview -->
            <div class="section-block">
                <div class="section-title">
                    <h2>Performance</h2>
                    <span class="title-accent"></span>
                </div>
                
                <div class="performance-grid">
                    @php
                        $perfCards = [
                            ['type' => 'blue', 'icon' => 'male', 'value' => $nbMales, 'title' => 'Mâles Reproducteurs', 'progress' => 75, 'trend' => '+12%'],
                            ['type' => 'pink', 'icon' => 'female', 'value' => $nbFemelles, 'title' => 'Femelles Reproductrices', 'progress' => 85, 'trend' => '+8%'],
                            ['type' => 'purple', 'icon' => 'breed', 'value' => $nbSaillies, 'title' => 'Saillies en Cours', 'progress' => 60, 'trend' => '-3%'],
                            ['type' => 'green', 'icon' => 'birth', 'value' => $nbMisesBas, 'title' => 'Mises Bas Récentes', 'progress' => 90, 'trend' => '+15%']
                        ];
                    @endphp

                    @foreach($perfCards as $card)
                    <div class="perf-card {{ $card['type'] }}">
                        <div class="card-top">
                            <span class="card-label">{{ $card['title'] }}</span>
                            <div class="card-badge {{ $card['type'] }}">
                                @if($card['icon'] === 'male')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="10" cy="14" r="6"/><path d="M16 8h6V2M22 2l-8.5 8.5"/>
                                    </svg>
                                @elseif($card['icon'] === 'female')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="8" r="6"/><path d="M12 14v8M9 19h6"/>
                                    </svg>
                                @elseif($card['icon'] === 'breed')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="card-number">{{ $card['value'] }}</div>
                        <div class="progress-track">
                            <div class="progress-bar {{ $card['type'] }}" style="width: {{ $card['progress'] }}%"></div>
                        </div>
                        <div class="card-footer">
                            <span class="progress-label">{{ $card['progress'] }}% objectif</span>
                            <span class="trend-badge">{{ $card['trend'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section-block">
                <div class="section-title">
                    <h2>Actions Rapides</h2>
                    <span class="title-accent"></span>
                </div>
                
                <div class="actions-grid">
                    @foreach([
                        ['url' => route('males.index'), 'icon' => 'male', 'title' => 'Gérer Mâles', 'desc' => 'Consulter et modifier', 'color' => 'blue'],
                        ['url' => route('femelles.index'), 'icon' => 'female', 'title' => 'Gérer Femelles', 'desc' => 'Suivi reproduction', 'color' => 'pink'],
                        ['url' => route('saillies.index'), 'icon' => 'breed', 'title' => 'Planifier Saillie', 'desc' => 'Nouveau croisement', 'color' => 'purple'],
                        ['url' => route('naissances.index'), 'icon' => 'birth', 'title' => 'Naissance', 'desc' => 'Enregistrer portée', 'color' => 'green']
                    ] as $action)
                    <a href="{{ $action['url'] }}" class="action-tile {{ $action['color'] }}">
                        <div class="tile-icon">
                            @if($action['icon'] === 'male')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="10" cy="14" r="6"/><path d="M16 8h6V2M22 2l-8.5 8.5"/>
                                </svg>
                            @elseif($action['icon'] === 'female')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="8" r="6"/><path d="M12 14v8M9 19h6"/>
                                </svg>
                            @elseif($action['icon'] === 'breed')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endif
                        </div>
                        <h3>{{ $action['title'] }}</h3>
                        <p>{{ $action['desc'] }}</p>
                        <div class="tile-arrow">→</div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="sidebar-col">
            <!-- Calendar -->
            <div class="widget calendar-widget">
                <div class="widget-head">
                    <h3>Calendrier</h3>
                    <div class="calendar-controls">
                        <button class="cal-btn" id="prevMonth">‹</button>
                        <span class="cal-month" id="currentMonth">Février 2026</span>
                        <button class="cal-btn" id="nextMonth">›</button>
                    </div>
                </div>
                <div class="calendar-body" id="calendarGrid"></div>
                <div class="calendar-legend">
                    <div class="legend-row">
                        <span class="legend-dot purple"></span>
                        <span>Saillies</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot green"></span>
                        <span>Naissances</span>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="widget activity-widget">
                <div class="widget-head">
                    <h3>Activité</h3>
                    <button class="text-link">Tout voir</button>
                </div>
                <div class="timeline">
                    @foreach([
                        ['type' => 'green', 'title' => 'Mise bas enregistrée', 'desc' => 'Femelle #245 - 6 lapereaux', 'time' => 'Il y a 2h'],
                        ['type' => 'purple', 'title' => 'Saillie programmée', 'desc' => 'F#245 × M#112', 'time' => 'Hier 15:30'],
                        ['type' => 'orange', 'title' => 'Vaccination requise', 'desc' => '3 lapins concernés', 'time' => '23 août'],
                        ['type' => 'blue', 'title' => 'Rapport généré', 'desc' => 'Stats mensuelles', 'time' => '20 août']
                    ] as $item)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $item['type'] }}"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">{{ $item['title'] }}</div>
                            <div class="timeline-desc">{{ $item['desc'] }}</div>
                            <div class="timeline-time">{{ $item['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Alerts -->
            <div class="widget alerts-widget">
                <div class="widget-head">
                    <h3>Alertes</h3>
                    <span class="alert-badge">3</span>
                </div>
                <div class="alerts-list">
                    @foreach([
                        ['priority' => 'high', 'title' => 'Vaccination urgente', 'time' => 'Dans 2 jours'],
                        ['priority' => 'medium', 'title' => 'Saillie à confirmer', 'time' => 'Demain'],
                        ['priority' => 'low', 'title' => 'Rapport mensuel', 'time' => 'Fin semaine']
                    ] as $alert)
                    <div class="alert-row {{ $alert['priority'] }}">
                        <div class="alert-indicator"></div>
                        <div class="alert-text">
                            <div class="alert-title">{{ $alert['title'] }}</div>
                            <div class="alert-time">{{ $alert['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&family=Space+Mono:wght@400;700&display=swap');

:root {
    /* CuniApp Color Palette */
    --anyx-dark: #0A1628;
    --anyx-darker: #050B14;
    --anyx-navy: #162B4D;
    --anyx-blue: #0066FF;
    --anyx-cyan: #00D9FF;
    --anyx-purple: #8B5CF6;
    --anyx-pink: #EC4899;
    --anyx-green: #10B981;
    --anyx-orange: #F59E0B;
    
    /* Gradients */
    --grad-primary: linear-gradient(135deg, #00D9FF 0%, #0066FF 100%);
    --grad-accent: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
    --grad-success: linear-gradient(135deg, #10B981 0%, #059669 100%);
    
    /* Surfaces */
    --surface-1: #0F1C2E;
    --surface-2: #1A2942;
    --surface-3: #253A5C;
    
    /* Text */
    --text-primary: #F9FAFB;
    --text-secondary: #9CA3AF;
    --text-tertiary: #6B7280;
    
    /* Effects */
    --glow-blue: 0 0 20px rgba(0, 102, 255, 0.3);
    --glow-cyan: 0 0 20px rgba(0, 217, 255, 0.3);
    --shadow-1: 0 4px 6px rgba(0, 0, 0, 0.3);
    --shadow-2: 0 10px 25px rgba(0, 0, 0, 0.4);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Space Mono', monospace;
    background: var(--anyx-darker);
    color: var(--text-primary);
    line-height: 1.6;
}

.anyxtech-dashboard {
    max-width: 1800px;
    margin: 0 auto;
    padding: 32px;
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Header */
.dash-header {
    background: var(--surface-1);
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 32px;
    border: 1px solid rgba(0, 217, 255, 0.1);
    position: relative;
}

.dash-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--grad-primary);
}

.header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 32px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.brand-identity {
    display: flex;
    align-items: center;
    gap: 20px;
}

.anyxtech-logo {
    width: 56px;
    height: 56px;
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.anyxtech-logo svg {
    width: 100%;
    height: 100%;
    filter: drop-shadow(var(--glow-cyan));
}

.brand-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 28px;
    font-weight: 900;
    background: var(--grad-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: 1px;
}

.subtitle-accent {
    font-weight: 500;
    opacity: 0.8;
}

.brand-tagline {
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 4px;
    letter-spacing: 0.5px;
}

.header-controls {
    display: flex;
    gap: 12px;
}

.ctrl-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    font-family: 'Space Mono', monospace;
    font-size: 14px;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ctrl-btn.primary {
    background: var(--grad-primary);
    color: white;
    box-shadow: var(--glow-blue);
}

.ctrl-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 102, 255, 0.5);
}

.ctrl-btn.secondary {
    background: var(--surface-2);
    color: var(--text-primary);
    border: 1px solid rgba(0, 217, 255, 0.2);
}

.ctrl-btn.secondary:hover {
    border-color: var(--anyx-cyan);
    background: var(--surface-3);
}

.ctrl-btn svg {
    stroke-width: 2.5;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 32px;
}

.metric-card {
    background: var(--surface-2);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    opacity: 0;
    transition: opacity 0.3s;
}

.metric-card.primary::before { background: var(--grad-primary); }
.metric-card.blue::before { background: var(--anyx-blue); }
.metric-card.pink::before { background: var(--anyx-pink); }
.metric-card.purple::before { background: var(--anyx-purple); }
.metric-card.green::before { background: var(--anyx-green); }
.metric-card.orange::before { background: var(--anyx-orange); }

.metric-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 217, 255, 0.3);
}

.metric-card:hover::before {
    opacity: 1;
}

.metric-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.metric-card.primary .metric-icon { background: linear-gradient(135deg, rgba(0, 217, 255, 0.15), rgba(0, 102, 255, 0.15)); }
.metric-card.blue .metric-icon { background: rgba(0, 102, 255, 0.15); }
.metric-card.pink .metric-icon { background: rgba(236, 72, 153, 0.15); }
.metric-card.purple .metric-icon { background: rgba(139, 92, 246, 0.15); }
.metric-card.green .metric-icon { background: rgba(16, 185, 129, 0.15); }
.metric-card.orange .metric-icon { background: rgba(245, 158, 11, 0.15); }

.metric-icon svg {
    width: 28px;
    height: 28px;
    stroke-width: 2.5;
}

.metric-card.primary .metric-icon svg { stroke: var(--anyx-cyan); }
.metric-card.blue .metric-icon svg { stroke: var(--anyx-blue); }
.metric-card.pink .metric-icon svg { stroke: var(--anyx-pink); }
.metric-card.purple .metric-icon svg { stroke: var(--anyx-purple); }
.metric-card.green .metric-icon svg { stroke: var(--anyx-green); }
.metric-card.orange .metric-icon svg { stroke: var(--anyx-orange); }

.metric-value {
    font-family: 'Orbitron', sans-serif;
    font-size: 32px;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 4px;
}

.metric-label {
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 400;
}

.metric-trend {
    margin-top: 8px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 700;
}

.metric-trend.up { color: var(--anyx-green); }
.metric-trend.down { color: var(--anyx-orange); }
.metric-trend.neutral { color: var(--text-tertiary); }

.trend-arrow {
    font-size: 16px;
}

/* Main Grid */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 32px;
}

/* Section Blocks */
.section-block {
    background: var(--surface-1);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
}

.section-title h2 {
    font-family: 'Orbitron', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
}

.title-accent {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--anyx-cyan) 0%, transparent 100%);
}

/* Performance Grid */
.performance-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.perf-card {
    background: var(--surface-2);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.perf-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.1;
    transition: opacity 0.4s;
}

.perf-card.blue::after { background: var(--anyx-blue); }
.perf-card.pink::after { background: var(--anyx-pink); }
.perf-card.purple::after { background: var(--anyx-purple); }
.perf-card.green::after { background: var(--anyx-green); }

.perf-card:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 217, 255, 0.3);
}

.perf-card:hover::after {
    opacity: 0.2;
}

.card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-label {
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 400;
}

.card-badge {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-badge.blue { background: rgba(0, 102, 255, 0.15); }
.card-badge.pink { background: rgba(236, 72, 153, 0.15); }
.card-badge.purple { background: rgba(139, 92, 246, 0.15); }
.card-badge.green { background: rgba(16, 185, 129, 0.15); }

.card-badge svg {
    width: 20px;
    height: 20px;
    stroke-width: 2.5;
}

.card-badge.blue svg { stroke: var(--anyx-blue); }
.card-badge.pink svg { stroke: var(--anyx-pink); }
.card-badge.purple svg { stroke: var(--anyx-purple); }
.card-badge.green svg { stroke: var(--anyx-green); }

.card-number {
    font-family: 'Orbitron', sans-serif;
    font-size: 40px;
    font-weight: 900;
    margin-bottom: 16px;
}

.progress-track {
    height: 6px;
    background: var(--surface-1);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 16px;
}

.progress-bar {
    height: 100%;
    border-radius: 3px;
    transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.progress-bar.blue { background: var(--anyx-blue); box-shadow: 0 0 10px var(--anyx-blue); }
.progress-bar.pink { background: var(--anyx-pink); box-shadow: 0 0 10px var(--anyx-pink); }
.progress-bar.purple { background: var(--anyx-purple); box-shadow: 0 0 10px var(--anyx-purple); }
.progress-bar.green { background: var(--anyx-green); box-shadow: 0 0 10px var(--anyx-green); }

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-label {
    font-size: 12px;
    color: var(--text-tertiary);
}

.trend-badge {
    font-size: 12px;
    font-weight: 700;
    color: var(--anyx-green);
}

/* Actions Grid */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.action-tile {
    background: var(--surface-2);
    border-radius: 16px;
    padding: 24px;
    text-decoration: none;
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.action-tile::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    opacity: 0;
    transition: opacity 0.3s;
}

.action-tile.blue::before { background: var(--anyx-blue); }
.action-tile.pink::before { background: var(--anyx-pink); }
.action-tile.purple::before { background: var(--anyx-purple); }
.action-tile.green::before { background: var(--anyx-green); }

.action-tile:hover {
    transform: translateY(-6px);
    border-color: rgba(0, 217, 255, 0.3);
}

.action-tile:hover::before {
    opacity: 1;
}

.tile-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.action-tile.blue .tile-icon { background: rgba(0, 102, 255, 0.15); }
.action-tile.pink .tile-icon { background: rgba(236, 72, 153, 0.15); }
.action-tile.purple .tile-icon { background: rgba(139, 92, 246, 0.15); }
.action-tile.green .tile-icon { background: rgba(16, 185, 129, 0.15); }

.tile-icon svg {
    width: 24px;
    height: 24px;
    stroke-width: 2.5;
}

.action-tile.blue .tile-icon svg { stroke: var(--anyx-blue); }
.action-tile.pink .tile-icon svg { stroke: var(--anyx-pink); }
.action-tile.purple .tile-icon svg { stroke: var(--anyx-purple); }
.action-tile.green .tile-icon svg { stroke: var(--anyx-green); }

.action-tile h3 {
    font-family: 'Orbitron', sans-serif;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 6px;
}

.action-tile p {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 12px;
}

.tile-arrow {
    margin-top: auto;
    font-size: 20px;
    color: var(--anyx-cyan);
    transition: transform 0.3s;
}

.action-tile:hover .tile-arrow {
    transform: translateX(6px);
}

/* Widgets */
.widget {
    background: var(--surface-1);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.widget-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.widget-head h3 {
    font-family: 'Orbitron', sans-serif;
    font-size: 16px;
    font-weight: 700;
}

.text-link {
    background: none;
    border: none;
    color: var(--anyx-cyan);
    font-size: 12px;
    cursor: pointer;
    font-family: 'Space Mono', monospace;
    font-weight: 700;
    transition: color 0.3s;
}

.text-link:hover {
    color: var(--anyx-blue);
}

/* Calendar */
.calendar-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cal-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--surface-2);
    color: var(--anyx-cyan);
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s;
    font-family: 'Space Mono', monospace;
    font-weight: 700;
}

.cal-btn:hover {
    background: var(--anyx-cyan);
    color: var(--anyx-dark);
}

.cal-month {
    font-family: 'Orbitron', sans-serif;
    font-size: 13px;
    font-weight: 600;
    min-width: 120px;
    text-align: center;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-bottom: 20px;
}

.cal-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    font-weight: 700;
}

.cal-day.header {
    color: var(--text-secondary);
    font-size: 11px;
    cursor: default;
}

.cal-day:not(.header):hover {
    background: var(--surface-2);
}

.cal-day.today {
    background: var(--grad-primary);
    color: white;
    box-shadow: var(--glow-blue);
}

.cal-day.event::after {
    content: '';
    position: absolute;
    bottom: 4px;
    width: 4px;
    height: 4px;
    border-radius: 50%;
}

.cal-day.event.purple::after {
    background: var(--anyx-purple);
}

.cal-day.event.green::after {
    background: var(--anyx-green);
}

.calendar-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.legend-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: var(--text-secondary);
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.legend-dot.purple { background: var(--anyx-purple); }
.legend-dot.green { background: var(--anyx-green); }

/* Timeline */
.timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.timeline-item {
    display: flex;
    gap: 16px;
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 24px;
    width: 2px;
    height: calc(100% + 8px);
    background: var(--surface-2);
}

.timeline-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
    box-shadow: 0 0 10px currentColor;
}

.timeline-dot.green { background: var(--anyx-green); }
.timeline-dot.purple { background: var(--anyx-purple); }
.timeline-dot.orange { background: var(--anyx-orange); }
.timeline-dot.blue { background: var(--anyx-blue); }

.timeline-title {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 4px;
}

.timeline-desc {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 6px;
}

.timeline-time {
    font-size: 11px;
    color: var(--text-tertiary);
}

/* Alerts */
.alert-badge {
    background: var(--anyx-orange);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 12px;
    font-family: 'Orbitron', sans-serif;
}

.alerts-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.alert-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    background: var(--surface-2);
    border-radius: 12px;
    border-left: 3px solid;
    transition: all 0.3s;
}

.alert-row.high { border-left-color: var(--anyx-orange); }
.alert-row.medium { border-left-color: var(--anyx-cyan); }
.alert-row.low { border-left-color: var(--anyx-blue); }

.alert-row:hover {
    transform: translateX(4px);
    background: var(--surface-3);
}

.alert-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    animation: blink 2s ease-in-out infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.alert-row.high .alert-indicator { background: var(--anyx-orange); box-shadow: 0 0 8px var(--anyx-orange); }
.alert-row.medium .alert-indicator { background: var(--anyx-cyan); box-shadow: 0 0 8px var(--anyx-cyan); }
.alert-row.low .alert-indicator { background: var(--anyx-blue); box-shadow: 0 0 8px var(--anyx-blue); }

.alert-title {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 2px;
}

.alert-time {
    font-size: 11px;
    color: var(--text-tertiary);
}

/* Responsive */
@media (max-width: 1400px) {
    .main-grid {
        grid-template-columns: 1fr;
    }
    
    .sidebar-col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    }
}

@media (max-width: 1024px) {
    .performance-grid,
    .actions-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .anyxtech-dashboard {
        padding: 20px;
    }
    
    .header-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .sidebar-col {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .brand-title {
        font-size: 22px;
    }
    
    .metric-value {
        font-size: 28px;
    }
    
    .card-number {
        font-size: 32px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthSpan = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    let currentDate = new Date();
    const months = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    const weekdays = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
    
    function renderCalendar(date) {
        calendarGrid.innerHTML = '';
        
        weekdays.forEach(day => {
            const dayEl = document.createElement('div');
            dayEl.className = 'cal-day header';
            dayEl.textContent = day;
            calendarGrid.appendChild(dayEl);
        });
        
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        
        const startDay = firstDay === 0 ? 6 : firstDay - 1;
        currentMonthSpan.textContent = `${months[month]} ${year}`;

        for (let i = 0; i < startDay; i++) {
            calendarGrid.appendChild(document.createElement('div'));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'cal-day';
            dayEl.textContent = day;

            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dayEl.classList.add('today');
            }

            if ([5, 12, 18].includes(day)) {
                dayEl.classList.add('event', 'purple');
            }
            if ([8, 15, 22].includes(day)) {
                dayEl.classList.add('event', 'green');
            }

            calendarGrid.appendChild(dayEl);
        }
    }

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    renderCalendar(currentDate);

    // Animate progress bars
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 500);

    // Add staggered fade-in animation
    const elements = document.querySelectorAll('.metric-card, .perf-card, .action-tile, .widget');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 50);
    });
});
</script>
@endsection