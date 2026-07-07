{{-- resources/views/components/public-footer.blade.php --}}
<footer style="background:var(--surface-alt);border-top:1px solid var(--surface-border);position:relative;z-index:10;">
    <div style="max-width:1280px;margin:0 auto;padding:48px 24px;">
        <div style="display:grid;grid-template-columns:1.5fr 1fr 1fr 1.5fr;gap:40px;">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-radius:var(--radius);display:flex;align-items:center;justify-content:center;">
                        <svg viewBox="0 0 40 40" fill="none" width="20" height="20"><path d="M20 5L35 15V25L20 35L5 25V15L20 5Z" fill="white"/><path d="M20 12L28 17V23L20 28L12 23V17L20 12Z" fill="rgba(255,255,255,0.8)"/></svg>
                    </div>
                    <div style="font-size:18px;font-weight:700;color:var(--text-primary);">CuniApp <span style="color:var(--primary);">{{ __('Élevage') }}</span></div>
                </div>
                <p style="font-size:13px;color:var(--text-secondary);line-height:1.7;margin-bottom:20px;">{{ __('La solution complète pour la gestion intelligente de votre élevage de lapins.') }} {{ __('Suivez vos reproductions, naissances et performances en toute simplicité.') }}</p>
                <div style="display:flex;align-items:center;gap:12px;margin-top:16px;">
                    <div style="display:flex;align-items:center;background:var(--surface);border:1px solid var(--surface-border);border-radius:var(--radius);overflow:hidden;">
                        <button class="footer-theme-btn" data-theme="light" onclick="setTheme('light')" style="padding:6px 10px;font-size:13px;border:none;background:transparent;color:var(--text-tertiary);cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.2s;" title="{{ __('Thème clair') }}"><i class="bi bi-sun"></i></button>
                        <button class="footer-theme-btn" data-theme="dark" onclick="setTheme('dark')" style="padding:6px 10px;font-size:13px;border:none;background:transparent;color:var(--text-tertiary);cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.2s;" title="{{ __('Thème sombre') }}"><i class="bi bi-moon"></i></button>
                        <button class="footer-theme-btn" data-theme="system" onclick="setTheme('system')" style="padding:6px 10px;font-size:13px;border:none;background:transparent;color:var(--text-tertiary);cursor:pointer;display:flex;align-items:center;gap:4px;transition:all 0.2s;" title="{{ __('Thème du système') }}"><i class="bi bi-circle-half"></i></button>
                    </div>
                    <div style="display:flex;align-items:center;background:var(--surface);border:1px solid var(--surface-border);border-radius:var(--radius);overflow:hidden;">
                        <a href="{{ route('lang.switch', 'fr') }}" style="padding:6px 10px;font-size:13px;text-decoration:none;background:{{ app()->getLocale() === 'fr' ? 'var(--primary)' : 'transparent' }};color:{{ app()->getLocale() === 'fr' ? 'white' : 'var(--text-tertiary)' }};display:flex;align-items:center;gap:4px;transition:all 0.2s;">🇫🇷 FR</a>
                        <a href="{{ route('lang.switch', 'en') }}" style="padding:6px 10px;font-size:13px;text-decoration:none;background:{{ app()->getLocale() === 'en' ? 'var(--primary)' : 'transparent' }};color:{{ app()->getLocale() === 'en' ? 'white' : 'var(--text-tertiary)' }};display:flex;align-items:center;gap:4px;transition:all 0.2s;">🇺🇸 EN</a>
                    </div>
                </div>
            </div>
            <div>
                <h4 style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:16px;"><i class="bi bi-compass" style="margin-right:6px;"></i>{{ __('Navigation') }}</h4>
                <ul style="list-style:none;padding:0;margin:0;">
                    <li style="margin-bottom:8px;"><a href="{{ route('home') }}#features" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Fonctionnalités') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('home') }}#pricing" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Tarifs') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Connexion') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}#register" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Commencer') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:16px;"><i class="bi bi-briefcase" style="margin-right:6px;"></i>{{ __("Gestion d'Élevage") }}</h4>
                <ul style="list-style:none;padding:0;margin:0;">
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}#register" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Suivi des Reproductions') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}#register" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Gestion des Naissances') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}#register" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Inventaire Complet') }}</a></li>
                    <li style="margin-bottom:8px;"><a href="{{ route('connect') }}#register" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;"><i class="bi bi-chevron-right" style="font-size:10px;color:var(--text-tertiary);"></i>{{ __('Gestion des Ventes') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:16px;"><i class="bi bi-envelope" style="margin-right:6px;"></i>{{ __('Contact & Infos') }}</h4>
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;"><i class="bi bi-geo-alt-fill" style="color:var(--primary);font-size:16px;margin-top:2px;"></i><div><strong style="display:block;font-size:13px;color:var(--text-primary);margin-bottom:2px;">{{ __('Adresse') }}</strong><span style="font-size:12px;color:var(--text-secondary);">Houé​yiho après le pont devant Volta United, Cotonou, Bénin</span></div></div>
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;"><i class="bi bi-whatsapp" style="color:var(--primary);font-size:16px;margin-top:2px;"></i><div><strong style="display:block;font-size:13px;color:var(--text-primary);margin-bottom:2px;">WhatsApp</strong><a href="https://wa.me/22901524152" target="_blank" style="font-size:12px;color:var(--text-secondary);text-decoration:none;">+229 01 52 41 52 41</a></div></div>
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;"><i class="bi bi-envelope-fill" style="color:var(--primary);font-size:16px;margin-top:2px;"></i><div><strong style="display:block;font-size:13px;color:var(--text-primary);margin-bottom:2px;">Email</strong><a href="mailto:contact@anyxtech.com" style="font-size:12px;color:var(--text-secondary);text-decoration:none;">contact@anyxtech.com</a></div></div>
            </div>
        </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding:24px;border-top:1px solid var(--surface-border);background:var(--surface);">
        <div>
            <p style="font-size:13px;color:var(--text-secondary);">&copy; {{ date('Y') }} <a href="{{ route('home') }}" style="color:var(--primary);text-decoration:none;font-weight:600;">CuniApp {{ __('Élevage') }}</a>. {{ __('Tous droits réservés.') }}</p>
            <p style="font-size:11px;color:var(--text-tertiary);margin-top:4px;">Version {{ config('app.version', '1.0.0') }} <span style="margin:0 6px;">•</span> {{ __('Build') }} {{ date('Y.m.d') }}</p>
        </div>
        <div style="display:flex;align-items:center;gap:20px;">
            <a href="{{ route('privacy') }}" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:6px;"><i class="bi bi-shield-check"></i>{{ __('Confidentialité') }}</a>
            <a href="{{ route('terms') }}" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:6px;"><i class="bi bi-file-text"></i>{{ __('Conditions') }}</a>
            <a href="{{ route('connect') }}" style="font-size:13px;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:6px;"><i class="bi bi-headset"></i>{{ __('Support') }}</a>
        </div>
    </div>
</footer>
