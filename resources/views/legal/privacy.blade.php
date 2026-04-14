{{-- resources/views/legal/privacy.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Politique de Confidentialité - CuniApp Élevage')

@section('content')
    <div class="legal-page">

        {{-- Header --}}
        <div class="cuni-card mb-6">
            <div class="card-body"
                style="padding: 40px 32px; text-align: center; background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface) 100%);">
                <div
                    style="width: 72px; height: 72px; margin: 0 auto 20px; background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-cyan) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <i class="bi bi-shield-check" style="font-size: 32px; color: white;"></i>
                </div>
                <h1 style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                    Politique de Confidentialité
                </h1>
                <p style="color: var(--text-secondary); font-size: 15px; max-width: 600px; margin: 0 auto;">
                    Dernière mise à jour : <strong>{{ date('d/m/Y') }}</strong><br>
                    Nous protégeons vos données avec soin.
                </p>
            </div>
        </div>

        {{-- Sommaire --}}
        <div class="cuni-card mb-6">
            <div class="card-body">
                <h3
                    style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-list-ul" style="color: var(--primary);"></i> Sommaire
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <a href="#article-1" class="toc-link">1. Données Collectées</a>
                    <a href="#article-2" class="toc-link">2. Utilisation des Données</a>
                    <a href="#article-3" class="toc-link">3. Partage & Transmission</a>
                    <a href="#article-4" class="toc-link">4. Sécurité & Conservation</a>
                    <a href="#article-5" class="toc-link">5. Vos Droits</a>
                    <a href="#article-6" class="toc-link">6. Cookies & Traceurs</a>
                    <a href="#article-7" class="toc-link">7. Modifications</a>
                    <a href="#article-8" class="toc-link">8. Contact DPO</a>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="cuni-card">
            <div class="card-body legal-content">

                {{-- Article 1 --}}
                <article id="article-1" class="legal-section">
                    <h2>1. Données Collectées</h2>
                    <p>Nous collectons uniquement les données nécessaires au fonctionnement de CuniApp :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-person"></i> <strong>Données de compte</strong> : nom, email, mot de passe
                            chiffré</li>
                        <li><i class="bi bi-building"></i> <strong>Données d'entreprise</strong> : nom de la ferme,
                            description, logo</li>
                        <li><i class="bi bi-piggy-bank"></i> <strong>Données de facturation</strong> : historique des
                            paiements (via FedaPay)</li>
                        <li><i class="bi bi-calendar-event"></i> <strong>Données d'élevage</strong> : informations sur vos
                            lapins, saillies, naissances, ventes</li>
                        <li><i class="bi bi-globe"></i> <strong>Données techniques</strong> : adresse IP, navigateur, logs
                            d'accès (pour la sécurité)</li>
                    </ul>
                    <p class="text-muted" style="font-size: 13px; margin-top: 12px;">
                        <i class="bi bi-info-circle"></i> Nous ne collectons <strong>jamais</strong> de données sensibles
                        (origine raciale, opinions politiques, santé, etc.).
                    </p>
                </article>

                {{-- Article 2 --}}
                <article id="article-2" class="legal-section">
                    <h2>2. Utilisation des Données</h2>
                    <p>Vos données sont utilisées exclusivement pour :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-check-circle-fill"></i> Fournir et maintenir le service CuniApp</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gérer votre abonnement et vos factures</li>
                        <li><i class="bi bi-check-circle-fill"></i> Améliorer l'expérience utilisateur (analytics
                            anonymisés)</li>
                        <li><i class="bi bi-check-circle-fill"></i> Vous envoyer des notifications importantes (mise à jour,
                            sécurité)</li>
                        <li><i class="bi bi-check-circle-fill"></i> Respecter nos obligations légales et réglementaires</li>
                    </ul>
                    <p>Nous n'utilisons <strong>jamais</strong> vos données d'élevage à des fins publicitaires ou de
                        revente.</p>
                </article>

                {{-- Article 3 --}}
                <article id="article-3" class="legal-section">
                    <h2>3. Partage & Transmission</h2>
                    <p>Vos données peuvent être partagées uniquement avec :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-cloud"></i> <strong>Hébergeurs</strong> : pour le stockage sécurisé (serveurs
                            conformes RGPD)</li>
                        <li><i class="bi bi-credit-card"></i> <strong>FedaPay</strong> : pour le traitement des paiements
                            (nous ne stockons pas vos coordonnées bancaires)</li>
                        <li><i class="bi bi-shield"></i> <strong>Autorités</strong> : uniquement sur réquisition légale
                            valable</li>
                    </ul>
                    <p>
                        <strong>Collaborateurs de ferme</strong> : Si vous êtes administrateur d'une entreprise, les
                        utilisateurs que vous invitez auront accès aux données de votre ferme. Vous restez responsable de la
                        gestion de leurs permissions.
                    </p>
                </article>

                {{-- Article 4 --}}
                <article id="article-4" class="legal-section">
                    <h2>4. Sécurité & Conservation</h2>

                    <h3> Mesures de Sécurité</h3>
                    <ul class="legal-list">
                        <li><i class="bi bi-lock"></i> Chiffrement des mots de passe (bcrypt)</li>
                        <li><i class="bi bi-shield-lock"></i> Protection CSRF et validation des entrées</li>
                        <li><i class="bi bi-hdd"></i> Sauvegardes automatiques chiffrées</li>
                        <li><i class="bi bi-eye-slash"></i> Accès restreint aux données par authentification et rôles</li>
                    </ul>

                    <h3> Durée de Conservation</h3>
                    <p>
                    <table style="width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px;">
                        <thead>
                            <tr style="background: var(--surface-alt);">
                                <th style="padding: 12px; text-align: left; border: 1px solid var(--surface-border);">Type
                                    de donnée</th>
                                <th style="padding: 12px; text-align: left; border: 1px solid var(--surface-border);">Durée
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Données de compte</td>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Jusqu'à suppression du
                                    compte + 30 jours</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Données d'élevage</td>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Illimitée (vous les
                                    contrôlez)</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Logs techniques</td>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">12 mois maximum</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">Factures</td>
                                <td style="padding: 12px; border: 1px solid var(--surface-border);">10 ans (obligation
                                    légale OHADA)</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                </article>

                {{-- Article 5 --}}
                <article id="article-5" class="legal-section">
                    <h2>5. Vos Droits</h2>
                    <p>Conformément au RGPD et aux lois locales, vous disposez des droits suivants :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-eye"></i> <strong>Droit d'accès</strong> : consulter toutes vos données</li>
                        <li><i class="bi bi-pencil"></i> <strong>Droit de rectification</strong> : corriger des informations
                            inexactes</li>
                        <li><i class="bi bi-trash"></i> <strong>Droit à l'effacement</strong> : supprimer votre compte et
                            vos données</li>
                        <li><i class="bi bi-download"></i> <strong>Droit à la portabilité</strong> : exporter vos données au
                            format JSON/CSV</li>
                        <li><i class="bi bi-ban"></i> <strong>Droit d'opposition</strong> : refuser certains traitements
                            (ex: newsletters)</li>
                    </ul>
                    <p>
                        Pour exercer ces droits : <a href="mailto:contact@anyxtech.com">contact@anyxtech.com</a>
                        ou via <strong>Paramètres → Exporter mes données</strong> dans l'application.
                    </p>
                </article>

                {{-- Article 6 --}}
                <article id="article-6" class="legal-section">
                    <h2>6. Cookies & Traceurs</h2>
                    <p>CuniApp utilise des cookies essentiels pour :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-key"></i> Maintenir votre session de connexion</li>
                        <li><i class="bi bi-palette"></i> Mémoriser votre préférence de thème (clair/sombre)</li>
                        <li><i class="bi bi-shield-check"></i> Protéger contre les attaques CSRF</li>
                    </ul>
                    <p>
                        <strong>Aucun cookie publicitaire ou de tracking tiers</strong> n'est déposé.
                        Vous pouvez gérer les cookies via les paramètres de votre navigateur.
                    </p>
                </article>

                {{-- Article 7 --}}
                <article id="article-7" class="legal-section">
                    <h2>7. Modifications</h2>
                    <p>
                        Nous pouvons mettre à jour cette politique. Les modifications entreront en vigueur
                        7 jours après publication. En cas de changement substantiel, nous vous notifierons
                        par email ou via une notification dans l'application.
                    </p>
                    <p>
                        <strong>Version actuelle</strong> : 1.0 du {{ date('d/m/Y') }}
                    </p>
                </article>

                {{-- Article 8 --}}
                <article id="article-8" class="legal-section">
                    <h2>8. Contact DPO</h2>
                    <p>
                        Pour toute question relative à la protection de vos données :
                    </p>
                    <div class="contact-box">
                        <ul class="contact-list">
                            <li>
                                <i class="bi bi-envelope-fill"></i>
                                <a href="mailto:privacy@anyxtech.com">privacy@anyxtech.com</a>
                            </li>
                            <li>
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Houéyiho après le pont devant Volta United, Cotonou, Bénin</span>
                            </li>
                            <li>
                                <i class="bi bi-building"></i>
                                <span>AnyxTech SARL - Responsable du traitement</span>
                            </li>
                        </ul>
                    </div>
                </article>

            </div>
        </div>

        {{-- Footer Actions --}}
        {{-- <div class="cuni-card mt-6" style="border-left: 4px solid var(--accent-purple);">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <p style="margin: 0; color: var(--text-secondary); font-size: 14px;">
                <i class="bi bi-info-circle-fill" style="color: var(--accent-purple); margin-right: 8px;"></i>
                Cette politique s'applique à tous les utilisateurs de CuniApp.
            </p>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('terms') }}" class="btn-cuni secondary">
                    <i class="bi bi-file-text"></i> Conditions d'Utilisation
                </a>
                <a href="{{ url()->previous() }}" class="btn-cuni primary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div> --}}

        {{-- ✅ FOOTER AVEC DÉTECTION EXPLICITE DE L'AUTHENTIFICATION --}}
        @php
            $isAuthenticated = auth()->check();
            $returnText = $isAuthenticated ? 'au tableau de bord' : 'à l\'inscription';
            $returnHint = $isAuthenticated ? 'au tableau de bord.' : 'au formulaire d\'inscription avec vos données.';
            $acceptText = $isAuthenticated
                ? 'En naviguant sur CuniApp, vous acceptez ces conditions.'
                : 'En créant un compte, vous acceptez automatiquement ces conditions.';
        @endphp

        <div style="text-align: center; margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--surface-border);">

            {{-- Bouton principal : Fermer l'onglet --}}
            <button type="button" onclick="closeLegalTab()" class="btn-cuni primary" id="closeBtn">
                <i class="bi bi-x-lg"></i>
                Fermer et retourner {{ $returnText }}
            </button>

            {{-- Message d'aide (caché par défaut) --}}
            <div id="helpMessage"
                style="display: none; margin-top: 16px; padding: 12px 16px; background: var(--primary-subtle); border-radius: var(--radius); border-left: 3px solid var(--primary);">
                <p style="margin: 0; font-size: 13px; color: var(--text-secondary);">
                    <i class="bi bi-info-circle" style="margin-right: 6px;"></i>
                    Votre navigateur empêche la fermeture automatique de cet onglet.<br>
                    <strong>Veuillez fermer cet onglet manuellement</strong> pour retourner {{ $returnHint }}
                </p>
            </div>

            <p style="margin-top: 16px; font-size: 13px; color: var(--text-tertiary);">
                {{ $acceptText }}
            </p>
        </div>




    </div>

    {{-- Styles hérités de terms.blade.php (à inclure ou centraliser) --}}
    <style>
        .legal-page {
            max-width: 900px;
            margin: 0 auto;
        }

        .toc-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: var(--surface-alt);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .toc-link:hover {
            background: var(--primary-subtle);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(4px);
        }

        .toc-link::before {
            content: '•';
            color: var(--primary);
            font-weight: bold;
        }

        .legal-content {
            padding: 32px;
        }

        .legal-section {
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--surface-border);
        }

        .legal-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .legal-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-subtle);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legal-section h2::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--accent-purple);
            border-radius: 2px;
        }

        .legal-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 24px 0 12px 0;
        }

        .legal-content p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .legal-list {
            list-style: none;
            padding: 0;
            margin: 16px 0;
        }

        .legal-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 0;
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
        }

        .legal-list li i {
            color: var(--accent-green);
            font-size: 14px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .legal-content a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .legal-content a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .contact-box {
            background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface-alt) 100%);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-top: 24px;
        }

        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .contact-list li i {
            width: 20px;
            text-align: center;
            color: var(--primary);
            font-size: 16px;
        }

        .contact-list a {
            color: var(--text-primary);
            font-weight: 500;
        }

        .contact-list a:hover {
            color: var(--primary);
        }

        .theme-dark .toc-link {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .legal-section h2 {
            border-bottom-color: var(--surface-border);
        }

        .theme-dark .contact-box {
            background: linear-gradient(135deg, var(--surface-elevated) 0%, var(--surface-alt) 100%);
        }

        @media (max-width:768px) {
            .legal-content {
                padding: 24px 20px;
            }

            .legal-section h2 {
                font-size: 18px;
            }

            .toc-link {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        html {
            scroll-behavior: smooth;
        }
    </style>

    {{-- <script>
        // Passer l'état d'authentification au JS pour fallback si window.close() échoue
        const isAuthenticated = {{ $isAuthenticated ? 'true' : 'false' }};
        const returnUrl = isAuthenticated ? '{{ route('dashboard') }}' : '{{ route('welcome') }}';

        function closeLegalTab() {
            const closeBtn = document.getElementById('closeBtn');
            const helpMessage = document.getElementById('helpMessage');

            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.focus();
                    window.close();

                    // Fallback si close() échoue
                    if (closeBtn) closeBtn.style.display = 'none';
                    if (helpMessage) helpMessage.style.display = 'block';
                } catch (e) {
                    if (closeBtn) closeBtn.style.display = 'none';
                    if (helpMessage) helpMessage.style.display = 'block';
                }
            } else if (returnUrl) {
                // Si pas d'opener mais on a une URL : rediriger
                window.location.href = returnUrl;
            } else {
                if (closeBtn) closeBtn.style.display = 'none';
                if (helpMessage) helpMessage.style.display = 'block';
            }
        }

        // Highlight active section on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.legal-section');
            const tocLinks = document.querySelectorAll('.toc-link');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        tocLinks.forEach(link => {
                            link.style.background = '';
                            link.style.borderColor = '';
                            link.style.color = '';
                            if (link.getAttribute('href') === `#${id}`) {
                                link.style.background = 'var(--primary-subtle)';
                                link.style.borderColor = 'var(--primary)';
                                link.style.color = 'var(--primary)';
                            }
                        });
                    }
                });
            }, {
                threshold: 0.3
            });

            sections.forEach(section => observer.observe(section));
        });
    </script> --}}

    <script>
        // État d'authentification (pour le texte du bouton uniquement)
        const isAuthenticated = {{ $isAuthenticated ? 'true' : 'false' }};

        function closeLegalTab() {
            const closeBtn = document.getElementById('closeBtn');
            const helpMessage = document.getElementById('helpMessage');

            // Cas 1 : Onglet ouvert depuis un autre onglet (target="_blank")
            if (window.opener && !window.opener.closed) {
                try {
                    // Focus sur l'onglet parent d'abord
                    window.opener.focus();
                    // Tente de fermer l'onglet courant
                    window.close();

                    // Si on arrive ici après 100ms, window.close() a probablement échoué
                    setTimeout(() => {
                        if (closeBtn) closeBtn.style.display = 'none';
                        if (helpMessage) {
                            helpMessage.style.display = 'block';
                            helpMessage.innerHTML = `<p style="margin:0;font-size:13px;color:var(--text-secondary);">
                        <i class="bi bi-info-circle" style="margin-right:6px;"></i>
                        Votre navigateur empêche la fermeture automatique.<br>
                        <strong>Veuillez fermer cet onglet manuellement</strong> pour retourner au formulaire.
                    </p>`;
                        }
                    }, 100);
                } catch (e) {
                    // Erreur explicite : afficher l'aide
                    if (closeBtn) closeBtn.style.display = 'none';
                    if (helpMessage) {
                        helpMessage.style.display = 'block';
                        helpMessage.innerHTML = `<p style="margin:0;font-size:13px;color:var(--text-secondary);">
                    <i class="bi bi-info-circle" style="margin-right:6px;"></i>
                    Veuillez fermer cet onglet manuellement pour retourner au formulaire.
                </p>`;
                    }
                }
            }
            // Cas 2 : Utilisateur connecté mais pas d'opener (ex: accès direct)
            else if (isAuthenticated) {
                // Rediriger vers le dashboard uniquement si connecté
                window.location.href = '{{ route('dashboard') }}';
            }
            // Cas 3 : Utilisateur non connecté et pas d'opener → afficher l'aide
            else {
                if (closeBtn) closeBtn.style.display = 'none';
                if (helpMessage) {
                    helpMessage.style.display = 'block';
                    helpMessage.innerHTML = `<p style="margin:0;font-size:13px;color:var(--text-secondary);">
                <i class="bi bi-info-circle" style="margin-right:6px;"></i>
                Veuillez retourner à l'onglet d'inscription pour continuer.
            </p>`;
                }
            }
        }

        // Highlight active section on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.legal-section');
            const tocLinks = document.querySelectorAll('.toc-link');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        tocLinks.forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('active');
                                link.style.background = 'var(--primary-subtle)';
                                link.style.borderColor = 'var(--primary)';
                                link.style.color = 'var(--primary)';
                            }
                        });
                    }
                });
            }, {
                threshold: 0.3
            });

            sections.forEach(section => observer.observe(section));
        });
    </script>
@endsection
