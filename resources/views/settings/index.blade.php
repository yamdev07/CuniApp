@extends('layouts.cuniapp')
@section('title', __('Settings') . ' - CuniApp')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-gear-wide-connected"></i> {{ __('application_settings') }}
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                <span>/</span>
                <span>{{ __('Settings') }}</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-custom alert-custom-success alert-to-fade">
            <i class="bi bi-check2-circle alert-icon"></i>
            <div>
                <strong>{{ __('Success') }}</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-custom alert-custom-danger alert-to-fade">
            <i class="bi bi-exclamation-triangle alert-icon"></i>
            <div>
                <strong>{{ __('Error') }}</strong>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="settings-container">
        <!-- Sidebar Navigation -->
        <aside class="settings-sidebar">
            <div class="sidebar-group">
                <div class="sidebar-label">{{ __('Settings') }}</div>
                <button class="settings-nav-btn active" data-tab="system-tab">
                    <i class="bi bi-palette"></i> {{ __('Appearance') }}
                </button>
                <button class="settings-nav-btn" data-tab="notifications-tab">
                    <i class="bi bi-bell"></i> {{ __('Notifications') }}
                </button>
            </div>

            <div class="sidebar-group mt-5">
                <div class="sidebar-label">{{ __('Advanced Tools') }}</div>
                <div class="d-grid gap-3">
                    <form action="{{ route('settings.clearCache') }}" method="POST">
                        @csrf
                        <button type="submit" class="settings-action-btn warning">
                            <i class="bi bi-trash"></i> {{ __('Clear Cache') }}
                        </button>
                    </form>
                    <div style="height: 4px;"></div> <!-- Professional Space -->
                    <a href="{{ route('settings.export') }}" class="settings-action-btn primary">
                        <i class="bi bi-download"></i> {{ __('Export Data') }}
                    </a>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="settings-main">
            <!-- Tab: Appearance -->
            <section class="settings-tab-content active" id="system-tab">
                <div class="glass-card">
                    <div class="card-header-glass">
                        <h3 class="card-title"><i class="bi bi-palette"></i> {{ __('Appearance') }}</h3>
                        <p class="card-subtitle">{{ __('Customize the look and feel of your application.') }}</p>
                    </div>
                    <div class="card-body-glass">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="active_tab" value="system-tab">
                            
                            <!-- Theme Selection -->
                            <div class="form-section-glass">
                                <label class="section-label-glass">{{ __('Color Theme') }}</label>
                                <div class="theme-grid-glass">
                                    <label class="theme-card-glass">
                                        <input type="radio" name="theme" value="system" {{ (auth()->user()->theme == 'system' || !auth()->user()->theme) ? 'checked' : '' }}>
                                        <div class="theme-box system">
                                            <i class="bi bi-display"></i>
                                            <span>{{ __('System') }}</span>
                                        </div>
                                    </label>
                                    <label class="theme-card-glass">
                                        <input type="radio" name="theme" value="light" {{ auth()->user()->theme == 'light' ? 'checked' : '' }}>
                                        <div class="theme-box light">
                                            <i class="bi bi-sun"></i>
                                            <span>{{ __('Light') }}</span>
                                        </div>
                                    </label>
                                    <label class="theme-card-glass">
                                        <input type="radio" name="theme" value="dark" {{ auth()->user()->theme == 'dark' ? 'checked' : '' }}>
                                        <div class="theme-box dark">
                                            <i class="bi bi-moon-stars"></i>
                                            <span>{{ __('Dark') }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div style="height: 32px;"></div> <!-- Spacing instead of HR -->

                            <!-- Language Selection -->
                            <div class="form-section-glass">
                                <label class="section-label-glass">{{ __('Application Language') }}</label>
                                <div class="lang-grid-glass">
                                    <label class="lang-card-glass">
                                        <input type="radio" name="language" value="fr" {{ (auth()->user()->language ?? 'fr') == 'fr' ? 'checked' : '' }}>
                                        <div class="lang-box">
                                            <span class="flag">🇫🇷</span>
                                            <span>Français</span>
                                        </div>
                                    </label>
                                    <label class="lang-card-glass">
                                        <input type="radio" name="language" value="en" {{ (auth()->user()->language ?? 'fr') == 'en' ? 'checked' : '' }}>
                                        <div class="lang-box">
                                            <span class="flag">🇺🇸</span>
                                            <span>English</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="form-actions-glass">
                                <button type="submit" class="btn-glass primary">
                                    <i class="bi bi-save"></i> {{ __('Save Appearance') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Tab: Notifications -->
            <section class="settings-tab-content" id="notifications-tab">
                <div class="glass-card">
                    <div class="card-header-glass">
                        <h3 class="card-title"><i class="bi bi-bell"></i> {{ __('Notifications') }}</h3>
                        <p class="card-subtitle">{{ __('Choose how you want to be notified.') }}</p>
                    </div>
                    <div class="card-body-glass">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="active_tab" value="notifications-tab">
                            <div class="notification-options">
                                <div class="notification-item">
                                    <div class="item-info">
                                        <i class="bi bi-envelope"></i>
                                        <div>
                                            <div class="item-title">{{ __('Email Notifications') }}</div>
                                            <div class="item-desc">{{ __('Receive alerts by email.') }}</div>
                                        </div>
                                    </div>
                                    <label class="glass-switch">
                                        <input type="checkbox" name="notifications_email" value="1" {{ auth()->user()->notifications_email ? 'checked' : '' }}>
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>

                                <div class="notification-item">
                                    <div class="item-info">
                                        <i class="bi bi-display"></i>
                                        <div>
                                            <div class="item-title">{{ __('Dashboard Notifications') }}</div>
                                            <div class="item-desc">{{ __('Visual alerts in the app.') }}</div>
                                        </div>
                                    </div>
                                    <label class="glass-switch">
                                        <input type="checkbox" name="notifications_dashboard" value="1" {{ auth()->user()->notifications_dashboard ? 'checked' : '' }}>
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-actions-glass">
                                <button type="submit" class="btn-glass primary">
                                    <i class="bi bi-save"></i> {{ __('Save Preferences') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-hover: rgba(255, 255, 255, 0.06);
            --primary-glow: rgba(139, 92, 246, 0.15);
        }

        .settings-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 32px;
            margin-top: 24px;
            align-items: start;
        }

        /* Sidebar Styling */
        .settings-sidebar {
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-tertiary);
            margin-bottom: 12px;
            letter-spacing: 0.05em;
        }

        .settings-nav-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-weight: 500;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            text-align: left;
        }

        .settings-nav-btn:hover {
            background: var(--surface-alt);
            color: var(--text-primary);
        }

        .settings-nav-btn.active {
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .settings-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none !important;
        }

        .settings-action-btn.warning {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .settings-action-btn.warning:hover {
            background: #ef4444;
            color: white;
        }

        .settings-action-btn.primary {
            background: var(--primary-subtle);
            color: var(--primary);
            border-color: var(--primary-border);
        }

        .settings-action-btn.primary:hover {
            background: var(--primary);
            color: white;
        }

        /* Glass Cards */
        .glass-card {
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .card-header-glass {
            padding: 32px;
            border-bottom: 1px solid var(--surface-border);
            background: linear-gradient(to right, var(--surface-alt), transparent);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-subtitle {
            font-size: 14px;
            color: var(--text-tertiary);
            margin: 8px 0 0 0;
        }

        .card-body-glass {
            padding: 32px;
        }

        /* Forms */
        .section-label-glass {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .form-actions-glass {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--surface-border);
            display: flex;
            justify-content: flex-end;
        }

        .btn-glass {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-glass.primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-glass.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px var(--primary-glow);
        }

        /* Theme & Lang Boxes */
        .theme-grid-glass, .lang-grid-glass {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .lang-grid-glass { grid-template-columns: repeat(2, 1fr); }

        .theme-card-glass input, .lang-card-glass input { display: none; }

        .theme-box, .lang-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px;
            background: var(--surface-alt);
            border: 2px solid var(--surface-border);
            border-radius: var(--radius-lg);
            transition: all 0.2s;
        }

        .theme-card-glass input:checked + .theme-box,
        .lang-card-glass input:checked + .lang-box {
            border-color: var(--primary);
            background: var(--primary-subtle);
            color: var(--primary);
        }

        /* Notifications */
        .notification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--surface-alt);
            border-radius: var(--radius-lg);
            margin-bottom: 12px;
            border: 1px solid var(--surface-border);
        }

        .item-info { display: flex; align-items: center; gap: 16px; }
        .item-title { font-weight: 600; color: var(--text-primary); }
        .item-desc { font-size: 12px; color: var(--text-tertiary); }

        /* Switches */
        .glass-switch { position: relative; display: inline-block; width: 52px; height: 28px; }
        .glass-switch input { opacity: 0; width: 0; height: 0; }
        .switch-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: var(--gray-300); transition: .4s; border-radius: 34px;
        }
        .switch-slider:before {
            position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .switch-slider { background-color: var(--primary); }
        input:checked + .switch-slider:before { transform: translateX(24px); }

        /* Responsive */
        @media (max-width: 992px) {
            .settings-container { grid-template-columns: 1fr; }
            .settings-sidebar { display: flex; gap: 8px; overflow-x: auto; padding: 12px; }
            .sidebar-group { display: flex; gap: 8px; margin-bottom: 0 !important; }
            .sidebar-label { display: none; }
        }

        .settings-tab-content { display: none; }
        .settings-tab-content.active { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.settings-nav-btn');
            const contents = document.querySelectorAll('.settings-tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    tab.classList.add('active');
                    document.getElementById(target).classList.add('active');
                    localStorage.setItem('cuniapp_settings_tab', target);
                });
            });

            // Restore active tab
            const savedTab = localStorage.getItem('cuniapp_settings_tab') || '{{ session('active_tab') }}' || 'system-tab';
            const targetTab = document.querySelector(`[data-tab="${savedTab}"]`);
            if (targetTab) targetTab.click();

            // Success/Error Alerts Auto-dismiss
            const alert = document.querySelector('.alert-to-fade');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            }
        });
    </script>
@endsection
