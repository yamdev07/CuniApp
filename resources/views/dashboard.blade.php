@extends('layouts.cuniapp')

@section('title', 'Tableau de Bord - CuniApp Élevage')

@section('content')
    <style>
        /* Dashboard Specific Styles */
        .dash-header {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .header-wrapper-dash {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid var(--surface-border);
            flex-wrap: wrap;
            gap: 16px;
            background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface) 100%);
        }

        .brand-identity-dash {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .cuniapp-logo-dash {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .cuniapp-logo-dash svg {
            width: 32px;
            height: 32px;
        }

        .brand-text-dash h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.02em;
        }

        .brand-text-dash .subtitle-accent {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .brand-tagline-dash {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .header-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ctrl-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .ctrl-btn.primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .ctrl-btn.primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .ctrl-btn.secondary {
            background: var(--white);
            color: var(--text-primary);
            border: 1px solid var(--surface-border);
        }

        .ctrl-btn.secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .ctrl-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding: 24px;
        }

        .metric-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            border: 1px solid var(--surface-border);
            transition: all 0.2s ease;
            position: relative;
        }

        .metric-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-card.primary .metric-icon {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .metric-card.blue .metric-icon {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .metric-card.pink .metric-icon {
            background: rgba(236, 72, 153, 0.1);
            color: var(--accent-pink);
        }

        .metric-card.purple .metric-icon {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-purple);
        }

        .metric-card.green .metric-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .metric-card.orange .metric-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent-orange);
        }

        .metric-icon svg {
            width: 24px;
            height: 24px;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-800);
            letter-spacing: -0.02em;
        }

        .metric-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .metric-trend {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .metric-trend.up {
            color: var(--accent-green);
        }

        .metric-trend.down {
            color: var(--accent-red);
        }

        .metric-trend.neutral {
            color: var(--text-tertiary);
        }

        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
        }

        /* Section Blocks */
        .section-block {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--surface-border);
        }

        .section-title h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
            letter-spacing: -0.01em;
        }

        /* Performance Grid */
        .performance-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .perf-card {
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--surface-border);
            transition: all 0.2s ease;
        }

        .perf-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .card-badge {
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-badge.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .card-badge.pink {
            background: rgba(236, 72, 153, 0.1);
            color: var(--accent-pink);
        }

        .card-badge.purple {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-purple);
        }

        .card-badge.green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .card-badge svg {
            width: 18px;
            height: 18px;
        }

        .card-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--gray-800);
            letter-spacing: -0.02em;
        }

        .progress-track {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease;
        }

        .progress-bar.blue {
            background: #3B82F6;
        }

        .progress-bar.pink {
            background: var(--accent-pink);
        }

        .progress-bar.purple {
            background: var(--accent-purple);
        }

        .progress-bar.green {
            background: var(--accent-green);
        }

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
            font-weight: 600;
            color: var(--accent-green);
        }

        /* Actions Grid */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .action-tile {
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-decoration: none;
            color: var(--text-primary);
            border: 1px solid var(--surface-border);
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .action-tile:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .tile-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .action-tile.blue .tile-icon {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .action-tile.pink .tile-icon {
            background: rgba(236, 72, 153, 0.1);
            color: var(--accent-pink);
        }

        .action-tile.purple .tile-icon {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-purple);
        }

        .action-tile.green .tile-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
        }

        .tile-icon svg {
            width: 22px;
            height: 22px;
        }

        .action-tile h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--gray-800);
        }

        .action-tile p {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .tile-arrow {
            margin-top: auto;
            font-size: 18px;
            color: var(--primary);
            transition: transform 0.2s;
        }

        .action-tile:hover .tile-arrow {
            transform: translateX(4px);
        }

        /* Widgets */
        .widget {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--surface-border);
            box-shadow: var(--shadow);
        }

        .widget-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--surface-border);
        }

        .widget-head h3 {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .text-link {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: color 0.2s;
        }

        .text-link:hover {
            color: var(--primary-dark);
        }

        /* Calendar */
        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cal-btn {
            width: 30px;
            height: 30px;
            border: 1px solid var(--surface-border);
            background: var(--white);
            color: var(--text-primary);
            border-radius: var(--radius);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cal-btn:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .cal-month {
            font-size: 13px;
            font-weight: 600;
            min-width: 110px;
            text-align: center;
            color: var(--gray-800);
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 16px;
        }

        .cal-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            font-weight: 500;
            color: var(--text-primary);
        }

        .cal-day.header {
            color: var(--text-tertiary);
            font-size: 11px;
            cursor: default;
            font-weight: 600;
        }

        .cal-day:not(.header):hover {
            background: var(--gray-100);
        }

        .cal-day.today {
            background: var(--primary);
            color: var(--white);
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
            background: var(--accent-purple);
        }

        .cal-day.event.green::after {
            background: var(--accent-green);
        }

        .calendar-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            color: var(--text-secondary);
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .legend-dot.purple {
            background: var(--accent-purple);
        }

        .legend-dot.green {
            background: var(--accent-green);
        }

        /* Timeline */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .timeline-item {
            display: flex;
            gap: 12px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 20px;
            width: 1px;
            height: calc(100% + 4px);
            background: var(--gray-200);
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }

        .timeline-dot.green {
            background: var(--accent-green);
        }

        .timeline-dot.purple {
            background: var(--accent-purple);
        }

        .timeline-dot.orange {
            background: var(--accent-orange);
        }

        .timeline-dot.blue {
            background: #3B82F6;
        }

        .timeline-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 3px;
            color: var(--gray-800);
        }

        .timeline-desc {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        /* Alerts */
        .alert-badge {
            background: var(--accent-orange);
            color: var(--white);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 10px;
        }

        .alerts-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--surface-alt);
            border-radius: var(--radius);
            border-left: 3px solid;
            transition: all 0.2s;
        }

        .alert-row.high {
            border-left-color: var(--accent-orange);
        }

        .alert-row.medium {
            border-left-color: var(--accent-cyan);
        }

        .alert-row.low {
            border-left-color: #3B82F6;
        }

        .alert-row:hover {
            background: var(--gray-100);
        }

        .alert-indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .alert-row.high .alert-indicator {
            background: var(--accent-orange);
        }

        .alert-row.medium .alert-indicator {
            background: var(--accent-cyan);
        }

        .alert-row.low .alert-indicator {
            background: #3B82F6;
        }

        .alert-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
            color: var(--gray-800);
        }

        .alert-time {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header-wrapper-dash {
                flex-direction: column;
                align-items: flex-start;
            }

            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .performance-grid,
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .metric-value {
                font-size: 24px;
            }

            .card-number {
                font-size: 28px;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="cuniapp-dashboard">
        <!-- Header Section -->
        <header class="dash-header">
            <div class="header-wrapper-dash">
                <div class="brand-identity-dash">
                    <div class="cuniapp-logo-dash">
                        <svg viewBox="0 0 40 40" fill="none">
                            <path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white" />
                            <path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)" />
                        </svg>
                    </div>
                    <div class="brand-text-dash">
                        <h1>CuniApp <span class="subtitle-accent">Élevage</span></h1>
                        <p class="brand-tagline-dash">Gestion intelligente de votre cheptel</p>
                    </div>
                </div>

                <div class="header-controls">
                    <a href="{{ route('settings.index') }}" class="ctrl-btn secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        Paramètres
                    </a>
                    <a href="{{ route('lapins.create') }}" class="ctrl-btn primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Nouvelle entrée
                    </a>
                </div>
            </div>

            <div class="metrics-grid">
                @php
                    $metricsData = [
                        [
                            'icon' => 'total',
                            'value' => $nbMales + $nbFemelles,
                            'label' => 'Total Lapins',
                            'type' => 'primary',
                            'change' => '+8.2%',
                            'trend' => 'up',
                            'route' => '',
                        ],
                        [
                            'icon' => 'male',
                            'value' => $nbMales,
                            'label' => 'Mâles',
                            'type' => 'blue',
                            'change' => '+5.1%',
                            'trend' => 'up',
                            'route' => 'males.index',
                        ],
                        [
                            'icon' => 'female',
                            'value' => $nbFemelles,
                            'label' => 'Femelles',
                            'type' => 'pink',
                            'change' => '+12%',
                            'trend' => 'up',
                            'route' => 'femelles.index',
                        ],
                        [
                            'icon' => 'breed',
                            'value' => $nbSaillies,
                            'label' => 'Saillies',
                            'type' => 'purple',
                            'change' => '-3.1%',
                            'trend' => 'down',
                            'route' => 'saillies.index',
                        ],
                        [
                            'icon' => 'birth',
                            'value' => $nbMisesBas,
                            'label' => 'Portées',
                            'type' => 'green',
                            'change' => '+15%',
                            'trend' => 'up',
                            'route' => 'mises-bas.index',
                        ],
                        [
                            'icon' => 'alert',
                            'value' => 3,
                            'label' => 'Alertes',
                            'type' => 'orange',
                            'change' => '0%',
                            'trend' => 'neutral',
                            'route' => '',
                        ],
                    ];
                @endphp

                @foreach ($metricsData as $metric)
                    <a href="{{ Route::has($metric['route']) ? route($metric['route']) : '#' }}">
                        <div class="metric-card {{ $metric['type'] }}" data-trend="{{ $metric['trend'] }}">
                            <div class="metric-icon">
                                @if ($metric['icon'] === 'total')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                                        <circle cx="17" cy="7" r="2" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    </svg>
                                @elseif($metric['icon'] === 'male')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="10" cy="14" r="6" />
                                        <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                    </svg>
                                @elseif($metric['icon'] === 'female')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="8" r="6" />
                                        <path d="M12 14v8M9 19h6" />
                                    </svg>
                                @elseif($metric['icon'] === 'breed')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                @elseif($metric['icon'] === 'birth')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>
                                @endif
                            </div>
                            <div class="metric-data">
                                <div class="metric-value">{{ $metric['value'] }}</div>
                                <div class="metric-label">{{ $metric['label'] }}</div>
                                <div class="metric-trend {{ $metric['trend'] }}">
                                    <span
                                        class="trend-arrow">{{ $metric['trend'] === 'up' ? '↗' : ($metric['trend'] === 'down' ? '↘' : '→') }}</span>
                                    {{ $metric['change'] }}
                                </div>
                            </div>
                        </div>
                    </a>
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
                    </div>
                    <div class="performance-grid">
                        @php
                            $perfCards = [
                                [
                                    'type' => 'blue',
                                    'icon' => 'male',
                                    'value' => $nbMales,
                                    'title' => 'Mâles Reproducteurs',
                                    'progress' => 75,
                                    'trend' => '+12%',
                                ],
                                [
                                    'type' => 'pink',
                                    'icon' => 'female',
                                    'value' => $nbFemelles,
                                    'title' => 'Femelles Reproductrices',
                                    'progress' => 85,
                                    'trend' => '+8%',
                                ],
                                [
                                    'type' => 'purple',
                                    'icon' => 'breed',
                                    'value' => $nbSaillies,
                                    'title' => 'Saillies en Cours',
                                    'progress' => 60,
                                    'trend' => '-3%',
                                ],
                                [
                                    'type' => 'green',
                                    'icon' => 'birth',
                                    'value' => $nbMisesBas,
                                    'title' => 'Mises Bas Récentes',
                                    'progress' => 90,
                                    'trend' => '+15%',
                                ],
                            ];
                        @endphp

                        @foreach ($perfCards as $card)
                            <div class="perf-card {{ $card['type'] }}">
                                <div class="card-top">
                                    <span class="card-label">{{ $card['title'] }}</span>
                                    <div class="card-badge {{ $card['type'] }}">
                                        @if ($card['icon'] === 'male')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="10" cy="14" r="6" />
                                                <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                            </svg>
                                        @elseif($card['icon'] === 'female')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="8" r="6" />
                                                <path d="M12 14v8M9 19h6" />
                                            </svg>
                                        @elseif($card['icon'] === 'breed')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path
                                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-number">{{ $card['value'] }}</div>
                                <div class="progress-track">
                                    <div class="progress-bar {{ $card['type'] }}"
                                        style="width: {{ $card['progress'] }}%">
                                    </div>
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
                    </div>
                    <div class="actions-grid">
                        @foreach ([['url' => route('males.index'), 'icon' => 'male', 'title' => 'Gérer Mâles', 'desc' => 'Consulter et modifier', 'color' => 'blue'], ['url' => route('femelles.index'), 'icon' => 'female', 'title' => 'Gérer Femelles', 'desc' => 'Suivi reproduction', 'color' => 'pink'], ['url' => route('saillies.index'), 'icon' => 'breed', 'title' => 'Planifier Saillie', 'desc' => 'Nouveau croisement', 'color' => 'purple'], ['url' => route('naissances.index'), 'icon' => 'birth', 'title' => 'Naissance', 'desc' => 'Enregistrer portée', 'color' => 'green']] as $action)
                            <a href="{{ $action['url'] }}" class="action-tile {{ $action['color'] }}">
                                <div class="tile-icon">
                                    @if ($action['icon'] === 'male')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="10" cy="14" r="6" />
                                            <path d="M16 8h6V2M22 2l-8.5 8.5" />
                                        </svg>
                                    @elseif($action['icon'] === 'female')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="8" r="6" />
                                            <path d="M12 14v8M9 19h6" />
                                        </svg>
                                    @elseif($action['icon'] === 'breed')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
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
                        @foreach ([['type' => 'green', 'title' => 'Mise bas enregistrée', 'desc' => 'Femelle #245 - 6 lapereaux', 'time' => 'Il y a 2h'], ['type' => 'purple', 'title' => 'Saillie programmée', 'desc' => 'F#245 × M#112', 'time' => 'Hier 15:30'], ['type' => 'orange', 'title' => 'Vaccination requise', 'desc' => '3 lapins concernés', 'time' => '23 août'], ['type' => 'blue', 'title' => 'Rapport généré', 'desc' => 'Stats mensuelles', 'time' => '20 août']] as $item)
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
                        @foreach ([['priority' => 'high', 'title' => 'Vaccination urgente', 'time' => 'Dans 2 jours'], ['priority' => 'medium', 'title' => 'Saillie à confirmer', 'time' => 'Demain'], ['priority' => 'low', 'title' => 'Rapport mensuel', 'time' => 'Fin semaine']] as $alert)
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarGrid = document.getElementById('calendarGrid');
            const currentMonthSpan = document.getElementById('currentMonth');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');

            let currentDate = new Date();
            const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                'Octobre', 'Novembre', 'Décembre'
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
                el.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.4s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endsection
