{{-- resources/views/legal/terms.blade.php --}}

@extends('layouts.cuniapp')

@section('title', 'Conditions Générales d\'Utilisation - CuniApp Élevage')

@section('content')
    <div class="legal-page">

        {{-- Header Section --}}
        <div class="cuni-card mb-6">
            <div class="card-body"
                style="padding: 40px 32px; text-align: center; background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface) 100%);">
                <div
                    style="width: 72px; height: 72px; margin: 0 auto 20px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <i class="bi bi-file-earmark-text" style="font-size: 32px; color: white;"></i>
                </div>
                <h1 style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                    Conditions Générales d'Utilisation
                </h1>
                <p style="color: var(--text-secondary); font-size: 15px; max-width: 600px; margin: 0 auto;">
                    Dernière mise à jour : <strong>{{ date('d/m/Y') }}</strong><br>
                    En utilisant CuniApp, vous acceptez les présentes conditions.
                </p>
            </div>
        </div>

        {{-- Table of Contents --}}
        <div class="cuni-card mb-6">
            <div class="card-body">
                <h3
                    style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-list-ul" style="color: var(--primary);"></i> Sommaire
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <a href="#article-1" class="toc-link">1. Objet & Acceptation</a>
                    <a href="#article-2" class="toc-link">2. Description du Service</a>
                    <a href="#article-3" class="toc-link">3. Compte & Sécurité</a>
                    <a href="#article-4" class="toc-link">4. Abonnements & Paiements</a>
                    <a href="#article-5" class="toc-link">5. Données & Confidentialité</a>
                    <a href="#article-6" class="toc-link">6. Propriété Intellectuelle</a>
                    <a href="#article-7" class="toc-link">7. Responsabilités</a>
                    <a href="#article-8" class="toc-link">8. Modifications & Résiliation</a>
                    <a href="#article-9" class="toc-link">9. Droit Applicable & Contact</a>
                </div>
            </div>
        </div>

        {{-- Content Sections --}}
        <div class="cuni-card">
            <div class="card-body legal-content">

                {{-- Article 1 --}}
                <article id="article-1" class="legal-section">
                    <h2>1. Objet & Acceptation</h2>
                    <p>
                        Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation
                        de la plateforme <strong>CuniApp</strong>, solution SaaS de gestion professionnelle
                        d'élevages cunicoles (lapins), développée par l'équipe CuniApp.
                    </p>
                    <p>
                        L'inscription, l'accès ou l'utilisation du service, y compris pendant la période
                        d'essai gratuite, vaut acceptation pleine et entière des présentes conditions.
                        Si vous n'acceptez pas ces CGU, veuillez ne pas utiliser CuniApp.
                    </p>
                </article>

                {{-- Article 2 --}}
                <article id="article-2" class="legal-section">
                    <h2>2. Description du Service</h2>
                    <p>CuniApp est un outil ERP web permettant aux éleveurs et gestionnaires de :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-check-circle-fill"></i> Suivre le cycle de reproduction (saillie, palpation,
                            mise bas, sevrage)</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gérer le cheptel, les portées et l'historique
                            reproductif</li>
                        <li><i class="bi bi-check-circle-fill"></i> Émettre des factures PDF et suivre les paiements</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gérer plusieurs fermes/entreprises via un compte
                            multi-tenant</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accéder à des tableaux de bord analytiques et
                            indicateurs de performance</li>
                    </ul>
                    <p>
                        Le service est fourni « en l'état » et accessible via navigateur web.
                        Une connexion internet stable est requise. Nous ne garantissons pas une disponibilité ininterrompue.
                    </p>
                </article>

                {{-- Article 3 --}}
                <article id="article-3" class="legal-section">
                    <h2>3. Compte & Sécurité</h2>
                    <p>
                        Pour utiliser CuniApp, vous devez créer un compte en fournissant des informations exactes et à jour.
                        Vous êtes responsable :
                    </p>
                    <ul class="legal-list">
                        <li><i class="bi bi-shield-check"></i> De la confidentialité de vos identifiants de connexion</li>
                        <li><i class="bi bi-shield-check"></i> De toute activité effectuée sous votre compte</li>
                        <li><i class="bi bi-shield-check"></i> De nous signaler immédiatement tout accès non autorisé</li>
                    </ul>
                    <p>
                        L'âge minimum pour utiliser CuniApp est de <strong>18 ans</strong> ou l'âge de la majorité légale
                        dans votre juridiction.
                    </p>
                </article>

                {{-- Article 4 --}}
                <article id="article-4" class="legal-section">
                    <h2>4. Abonnements & Paiements</h2>

                    <h3>4.1 Plans & Tarifs</h3>
                    <p>
                        CuniApp propose différents plans d'abonnement (Gratuit/Essai, Standard, Premium, Entreprise).
                        Les tarifs et fonctionnalités sont détaillés sur la page
                        <a href="{{ route('subscription.plans') }}">Tarifs</a>.
                    </p>

                    <h3>4.2 Période d'Essai</h3>
                    <p>
                        Un essai gratuit peut être offert (ex: 14 jours). À l'issue de cette période,
                        l'accès aux fonctionnalités premium sera suspendu sauf souscription à un plan payant.
                    </p>

                    <h3>4.3 Paiements & Renouvellement</h3>
                    <p>
                        Les paiements sont sécurisés via l'API <strong>FedaPay</strong> et acceptent :
                        Mobile Money (MoMo, Moov), cartes bancaires, et autres moyens locaux selon votre région.
                    </p>
                    <ul class="legal-list">
                        <li><i class="bi bi-arrow-repeat"></i> Les abonnements sont renouvelés automatiquement sauf
                            résiliation avant la date d'échéance</li>
                        <li><i class="bi bi-arrow-repeat"></i> Vous pouvez gérer votre abonnement et annuler le
                            renouvellement depuis votre espace « Paramètres »</li>
                        <li><i class="bi bi-arrow-repeat"></i> Aucun remboursement partiel n'est accordé pour les périodes
                            non utilisées, sauf obligation légale</li>
                    </ul>

                    <h3>4.4 Facturation</h3>
                    <p>
                        Une facture PDF est générée automatiquement après chaque paiement et disponible dans votre espace
                        client.
                    </p>
                </article>

                {{-- Article 5 --}}
                <article id="article-5" class="legal-section">
                    <h2>5. Données & Confidentialité</h2>
                    <p>
                        La protection de vos données est essentielle. Notre traitement des données personnelles est décrit
                        dans notre <a href="{{ route('privacy') }}">Politique de Confidentialité</a>.
                    </p>
                    <p>En résumé :</p>
                    <ul class="legal-list">
                        <li><i class="bi bi-database"></i> Nous collectons les données nécessaires au fonctionnement du
                            service (compte, données d'élevage, facturation)</li>
                        <li><i class="bi bi-database"></i> Vos données d'élevage vous appartiennent ; vous pouvez les
                            exporter ou supprimer votre compte à tout moment</li>
                        <li><i class="bi bi-database"></i> Nous ne vendons pas vos données à des tiers. Le partage se limite
                            aux prestataires techniques nécessaires (hébergement, paiement)</li>
                        <li><i class="bi bi-database"></i> Conformément au RGPD et aux lois locales, vous disposez d'un
                            droit d'accès, de rectification et de suppression</li>
                    </ul>
                </article>

                {{-- Article 6 --}}
                <article id="article-6" class="legal-section">
                    <h2>6. Propriété Intellectuelle</h2>
                    <p>
                        CuniApp, son code source, son design, son logo, sa documentation et tous les éléments constitutifs
                        sont la propriété exclusive de l'équipe CuniApp et protégés par les lois sur la propriété
                        intellectuelle.
                    </p>
                    <p>
                        L'utilisation du service vous accorde une licence personnelle, non exclusive, non transférable et
                        révocable
                        d'accéder et d'utiliser CuniApp conformément aux présentes CGU. Toute reproduction, modification,
                        reverse engineering ou distribution non autorisée est strictement interdite.
                    </p>
                </article>

                {{-- Article 7 --}}
                <article id="article-7" class="legal-section">
                    <h2>7. Responsabilités</h2>

                    <h3>7.1 Limitation de Garantie</h3>
                    <p>
                        CuniApp est fourni « tel quel », sans garantie explicite ou implicite quant à son exactitude,
                        sa fiabilité ou son adéquation à un usage particulier. Nous ne garantissons pas que :
                    </p>
                    <ul class="legal-list">
                        <li><i class="bi bi-exclamation-triangle"></i> Le service sera ininterrompu, sécurisé ou exempt
                            d'erreurs</li>
                        <li><i class="bi bi-exclamation-triangle"></i> Les calculs (dates de mise bas, indicateurs)
                            remplacent un avis vétérinaire ou professionnel</li>
                        <li><i class="bi bi-exclamation-triangle"></i> Les données saisies seront protégées contre toute
                            perte en cas de force majeure</li>
                    </ul>

                    <h3>7.2 Limitation de Responsabilité</h3>
                    <p>
                        Dans toute la mesure permise par la loi, la responsabilité cumulative de CuniApp ne pourra excéder
                        le montant payé par l'utilisateur au cours des 12 derniers mois précédant le sinistre.
                        Nous ne serons en aucun cas responsables des dommages indirects, pertes de profits, de données ou
                        d'opportunités.
                    </p>

                    <h3>7.3 Contenu Utilisateur</h3>
                    <p>
                        Vous restez seul responsable des données que vous saisissez dans CuniApp.
                        Nous nous réservons le droit de modérer ou supprimer tout contenu illégal, frauduleux ou contraire
                        aux CGU.
                    </p>
                </article>

                {{-- Article 8 --}}
                <article id="article-8" class="legal-section">
                    <h2>8. Modifications & Résiliation</h2>

                    <h3>8.1 Modification des CGU</h3>
                    <p>
                        Nous pouvons mettre à jour ces CGU à tout moment. Les modifications entreront en vigueur
                        7 jours après publication sur cette page, sauf changement majeur (tarifs, fonctionnalités)
                        pour lequel nous vous notifierons par email ou notification in-app.
                    </p>

                    <h3>8.2 Suspension & Résiliation</h3>
                    <p>
                        Nous nous réservons le droit de suspendre ou résilier votre accès à CuniApp, sans préavis ni
                        indemnité,
                        en cas de violation des CGU, de fraude, ou de non-paiement.
                    </p>
                    <p>
                        Vous pouvez résilier votre abonnement à tout moment depuis votre espace client.
                        En cas de suppression de compte, vos données seront conservées 30 jours avant suppression
                        définitive,
                        sauf obligation légale de conservation.
                    </p>
                </article>

                {{-- Article 9 --}}
                <article id="article-9" class="legal-section">
                    <h2>9. Droit Applicable & Contact</h2>
                    <p>
                        Les présentes CGU sont régies par le droit <strong>OHADA / Bénin</strong>.
                        Tout litige relèvera de la compétence exclusive des tribunaux de
                        <strong>Cotonou, Bénin</strong>, sauf disposition impérative contraire.
                    </p>

                    <div class="contact-box">
                        <h4><i class="bi bi-headset"></i> Nous Contacter</h4>
                        <p>Pour toute question relative aux CGU, à votre compte ou au service :</p>
                        <ul class="contact-list">
                            <li>
                                <i class="bi bi-envelope-fill"></i>
                                <a href="mailto:contact@anyxtech.com">contact@anyxtech.com</a>
                            </li>
                            <li>
                                <i class="bi bi-whatsapp"></i>
                                <a href="https://wa.me/22901524152" target="_blank">+229 01 52 41 52 41</a>
                            </li>
                            <li>
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Houéyiho après le pont devant Volta United, Cotonou, Bénin</span>
                            </li>
                            <li>
                                <i class="bi bi-github"></i>
                                <a href="https://github.com/yamdev07/CuniApp"
                                    target="_blank">github.com/yamdev07/CuniApp</a>
                            </li>
                        </ul>
                    </div>
                </article>

            </div>
        </div>

        {{-- Acceptance Banner --}}
        {{-- <div class="cuni-card mt-6" style="border-left: 4px solid var(--primary);">
            <div class="card-body"
                style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                <p style="margin: 0; color: var(--text-secondary); font-size: 14px;">
                    <i class="bi bi-info-circle-fill" style="color: var(--primary); margin-right: 8px;"></i>
                    En continuant à utiliser CuniApp, vous confirmez avoir lu et accepté ces Conditions Générales
                    d'Utilisation.
                </p>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('privacy') }}" class="btn-cuni secondary">
                        <i class="bi bi-shield-check"></i> Politique de Confidentialité
                    </a>
                    <a href="{{ route('welcome') }}" class="btn-cuni secondary">
                        <i class="bi bi-arrow-left"></i> Retour à l'inscription
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

    <style>
        /* Legal Page Specific Styles */
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
            background: var(--primary);
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

        .contact-box h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-box h4 i {
            color: var(--primary);
        }

        .contact-box>p {
            font-size: 13px;
            margin-bottom: 16px;
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

        /* Dark mode adjustments */
        .theme-dark .toc-link {
            background: var(--surface-elevated);
            border-color: var(--surface-border);
        }

        .theme-dark .toc-link:hover {
            background: var(--primary-subtle);
        }

        .theme-dark .legal-section h2 {
            border-bottom-color: var(--surface-border);
        }

        .theme-dark .contact-box {
            background: linear-gradient(135deg, var(--surface-elevated) 0%, var(--surface-alt) 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
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

            .contact-box {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .legal-page {
                padding: 0 8px;
            }

            .card-body[style*="40px"] {
                padding: 24px 16px !important;
            }

            .legal-section {
                margin-bottom: 32px;
                padding-bottom: 24px;
            }

            .btn-cuni {
                width: 100%;
                justify-content: center;
            }
        }

        /* Smooth scroll for anchor links */
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

                    if (closeBtn) closeBtn.style.display = 'none';
                    if (helpMessage) helpMessage.style.display = 'block';
                } catch (e) {
                    if (closeBtn) closeBtn.style.display = 'none';
                    if (helpMessage) helpMessage.style.display = 'block';
                }
            } else if (returnUrl) {
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
