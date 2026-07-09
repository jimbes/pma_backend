<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de confidentialité - LTMO</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 1rem;
            text-align: center;
        }
        header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        header p {
            opacity: 0.9;
        }
        .container {
            max-width: 820px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }
        .updated {
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        h2 {
            color: #667eea;
            margin-top: 2.5rem;
            margin-bottom: 0.75rem;
            font-size: 1.3rem;
        }
        h3 {
            color: #444;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            font-size: 1.05rem;
        }
        p, li {
            color: #444;
            margin-bottom: 0.75rem;
        }
        ul, ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        li {
            margin-bottom: 0.4rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0 1.5rem;
        }
        th, td {
            text-align: left;
            padding: 0.6rem 0.8rem;
            border-bottom: 1px solid #eee;
            font-size: 0.92rem;
        }
        th {
            color: #667eea;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 6px;
            padding: 1.25rem 1.5rem;
            margin: 1.25rem 0;
        }
        .box.warning {
            border-left-color: #d97706;
            background: #fffaf0;
        }
        .box p:last-child, .box ul:last-child {
            margin-bottom: 0;
        }
        a {
            color: #667eea;
        }
        footer {
            background: #f8f9fa;
            color: #666;
            text-align: center;
            padding: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #ddd;
        }
        footer a {
            color: #667eea;
            text-decoration: none;
            margin: 0 0.6rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <h1>Politique de confidentialité</h1>
        <p>LTMO — Accompagnement des couples en parcours de fertilité</p>
    </header>

    <div class="container">
        <a href="/" class="back-link">← Retour à l'accueil</a>
        <p class="updated">Dernière mise à jour : {{ now()->format('d/m/Y') }}</p>

        <h2>1. Présentation de l'application</h2>
        <p>
            LTMO est une application destinée aux couples suivant un parcours de procréation
            médicalement assistée (PMA) — fécondation in vitro, insémination, etc. Elle permet
            à deux personnes formant un couple de partager et de suivre ensemble leurs rendez-vous
            médicaux, leurs traitements et médicaments, ainsi que les grandes étapes de leur parcours,
            avec des rappels envoyés à l'un des partenaires, à l'autre, ou aux deux.
        </p>
        <p>
            Cette politique explique quelles données sont collectées, pourquoi, comment elles sont
            protégées, et quels sont vos droits. Elle s'applique à l'application mobile et à l'espace
            d'administration.
        </p>

        <h2>2. Données que nous collectons</h2>
        <table>
            <thead>
                <tr><th>Catégorie</th><th>Données concernées</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Compte</td>
                    <td>Nom, adresse email, mot de passe (stocké de façon chiffrée), lien vers votre partenaire au sein d'un couple</td>
                </tr>
                <tr>
                    <td>Données de santé (parcours PMA)</td>
                    <td>Rendez-vous médicaux (date, heure, lieu, type, praticien), médicaments et posologies, horaires de prise, historique des prises, étapes du parcours (stimulation, déclenchement, ponction, transfert, etc.), notes associées</td>
                </tr>
                <tr>
                    <td>Notifications</td>
                    <td>Préférences de rappel (activé/désactivé, canal push/email), identifiant technique de votre appareil pour l'envoi des notifications (jeton FCM), historique d'envoi des notifications</td>
                </tr>
                <tr>
                    <td>Partage de couple</td>
                    <td>Invitations envoyées à un partenaire, statut d'acceptation</td>
                </tr>
                <tr>
                    <td>Données techniques</td>
                    <td>Type d'appareil et plateforme (Android/iOS/web), horodatage des connexions, journaux techniques nécessaires au bon fonctionnement et à la sécurité du service</td>
                </tr>
            </tbody>
        </table>
        <div class="box warning">
            <p>
                Les données relatives à votre traitement (médicaments, rendez-vous, étapes du parcours)
                sont des <strong>données de santé</strong> au sens du RGPD. Elles bénéficient d'une
                protection renforcée et ne sont jamais utilisées à des fins commerciales, publicitaires
                ou de profilage.
            </p>
        </div>

        <h2>3. Pourquoi nous utilisons ces données</h2>
        <ul>
            <li>Vous permettre, avec votre partenaire, de consulter et gérer les mêmes informations de traitement ;</li>
            <li>Envoyer les rappels de médicaments et de rendez-vous à la ou aux personnes concernées, au bon moment ;</li>
            <li>Assurer la sécurité de votre compte et prévenir les accès non autorisés ;</li>
            <li>Diagnostiquer et corriger les problèmes techniques du service.</li>
        </ul>
        <p>
            Nous ne vendons pas vos données, ne les utilisons pas à des fins publicitaires, et ne les
            partageons pas avec des tiers en dehors des prestataires techniques strictement nécessaires
            au fonctionnement du service (voir section 5).
        </p>

        <h2>4. Base légale et durée de conservation</h2>
        <p>
            Le traitement de vos données repose sur votre consentement (création volontaire d'un
            compte et saisie de vos informations de traitement) et sur l'exécution du service que
            vous nous demandez (rappels, partage entre partenaires).
        </p>
        <p>
            Vos données sont conservées tant que votre compte est actif. Lorsque vous ou votre
            partenaire supprimez le compte, l'ensemble des données associées (rendez-vous,
            médicaments, historique de prise, jetons d'appareil) est supprimé de façon définitive.
        </p>

        <h2>5. Avec qui vos données sont-elles partagées</h2>
        <p>Seuls votre partenaire (au sein du même couple) et vous-même avez accès aux données de
        traitement. Nous faisons également appel aux prestataires techniques suivants, uniquement
        pour faire fonctionner le service :</p>
        <ul>
            <li><strong>Firebase Cloud Messaging (Google)</strong> — pour l'envoi des notifications push sur votre téléphone. Seul un identifiant technique d'appareil est transmis, jamais le contenu de votre dossier médical au-delà du texte du rappel lui-même.</li>
            <li><strong>Hébergeur</strong> — l'application et la base de données sont hébergées chez notre prestataire d'hébergement.</li>
            <li><strong>Service d'envoi d'emails</strong> — utilisé uniquement pour les invitations de partenaire et les notifications par email lorsque vous activez ce canal.</li>
        </ul>
        <p>Aucune donnée n'est transmise à des fins commerciales ou publicitaires.</p>

        <h2>6. Sécurité</h2>
        <ul>
            <li>Les mots de passe sont stockés sous forme chiffrée (hachage), jamais en clair ;</li>
            <li>L'accès à l'API est protégé par authentification par jeton (Laravel Sanctum) ;</li>
            <li>Chaque compte n'a accès qu'aux données de son propre couple ;</li>
            <li>L'espace d'administration est réservé à un accès restreint et journalisé.</li>
        </ul>

        <h2>7. Vos droits</h2>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants sur vos données :</p>
        <ul>
            <li><strong>Droit d'accès</strong> — obtenir une copie des données que nous détenons sur vous ;</li>
            <li><strong>Droit de rectification</strong> — corriger des données inexactes (directement dans l'application pour la plupart des informations) ;</li>
            <li><strong>Droit à l'effacement</strong> — demander la suppression de votre compte et de vos données ;</li>
            <li><strong>Droit à la portabilité</strong> — recevoir vos données dans un format structuré ;</li>
            <li><strong>Droit d'opposition et de retrait du consentement</strong> à tout moment.</li>
        </ul>
        <p>
            Pour exercer l'un de ces droits, vous pouvez supprimer votre compte directement depuis
            l'application, ou nous contacter à l'adresse indiquée ci-dessous.
        </p>

        <h2>8. Bon usage de l'application</h2>
        <p>
            LTMO est un outil d'organisation destiné à faciliter la coordination entre partenaires.
            Afin de garantir un usage sûr et respectueux de l'application, merci de respecter les
            règles suivantes :
        </p>
        <div class="box">
            <ul>
                <li><strong>LTMO ne remplace pas un avis médical.</strong> Les rappels, horaires et informations affichés dans l'application sont une aide à l'organisation ; seul votre médecin ou votre équipe médicale est habilité à prescrire, modifier ou interrompre un traitement.</li>
                <li><strong>Vérifiez l'exactitude des informations saisies</strong> (dosages, horaires de prise, dates de rendez-vous). Vous restez seul responsable de la conformité de votre traitement avec les prescriptions de votre médecin.</li>
                <li><strong>Ne partagez pas vos identifiants de connexion</strong> avec une personne autre que votre partenaire au sein du couple. Le partage d'informations avec votre partenaire doit se faire via la fonctionnalité d'invitation prévue à cet effet, pas par échange de mot de passe.</li>
                <li><strong>Respectez le consentement de votre partenaire</strong> quant au partage de ses informations : les deux membres du couple doivent être d'accord sur ce qui est partagé et sur qui reçoit quelles notifications.</li>
                <li><strong>N'utilisez pas l'application pour un tiers</strong> sans son consentement explicite (un compte est destiné à une seule personne).</li>
                <li><strong>Ne tentez pas de contourner les mesures de sécurité</strong> de l'application (accès non autorisé à un autre compte, exploitation de failles techniques, usage automatisé non prévu de l'API).</li>
                <li><strong>Signalez tout dysfonctionnement ou usage suspect</strong> à l'adresse de contact ci-dessous plutôt que de tenter de le corriger vous-même.</li>
            </ul>
        </div>
        <p>
            Le non-respect de ces règles peut entraîner la suspension ou la suppression du compte
            concerné, notamment en cas d'usage frauduleux, d'atteinte à la sécurité du service, ou
            d'utilisation portant atteinte aux droits d'un autre utilisateur.
        </p>

        <h2>9. Modifications de cette politique</h2>
        <p>
            Cette politique de confidentialité peut être mise à jour pour refléter l'évolution de
            l'application ou de la réglementation. La date de dernière mise à jour figure en haut
            de cette page.
        </p>

        <h2>10. Contact</h2>
        <p>
            Pour toute question relative à vos données personnelles ou à cette politique, vous
            pouvez nous contacter à l'adresse suivante : <a href="mailto:jimmy@besse.re">jimmy@besse.re</a>.
        </p>
    </div>

    <footer>
        <p>&copy; {{ now()->format('Y') }} LTMO</p>
        <p style="margin-top: 0.75rem;">
            <a href="/">Accueil</a> · <a href="{{ route('privacy') }}">Politique de confidentialité</a>
        </p>
    </footer>
</body>
</html>
