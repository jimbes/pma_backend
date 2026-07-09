<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de confidentialité - LTMO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-feature-settings: "cv11", "ss01"; }
    </style>
</head>
<body class="bg-white text-slate-700 antialiased">

    <nav class="border-b border-slate-200">
        <div class="max-w-2xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="text-sm font-semibold tracking-tight text-slate-900">LTMO</a>
            <a href="/" class="text-sm text-slate-500 hover:text-slate-900">← Accueil</a>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-6 py-16">
        <p class="text-sm font-medium text-indigo-600 mb-3">Confidentialité</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Politique de confidentialité</h1>
        <p class="mt-3 text-slate-500">
            LTMO accompagne les couples dans leur parcours de procréation médicalement assistée.
            Ce document explique quelles données nous traitons, pourquoi, et comment les utiliser
            correctement.
        </p>
        <p class="mt-4 text-sm text-slate-400">Dernière mise à jour : {{ now()->format('d/m/Y') }}</p>

        <div class="mt-14 space-y-14">

            <section>
                <h2 class="text-lg font-semibold text-slate-900">1. Présentation de l'application</h2>
                <div class="mt-3 space-y-4 text-slate-600 leading-relaxed">
                    <p>
                        LTMO est une application destinée aux couples suivant un parcours de PMA
                        (fécondation in vitro, insémination, etc.). Elle permet à deux personnes
                        formant un couple de partager et de suivre ensemble leurs rendez-vous
                        médicaux, leurs traitements et médicaments, ainsi que les grandes étapes
                        de leur parcours, avec des rappels envoyés à l'un des partenaires, à
                        l'autre, ou aux deux.
                    </p>
                    <p>
                        Cette politique s'applique à l'application mobile et à l'espace
                        d'administration.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">2. Données que nous collectons</h2>
                <div class="mt-4 divide-y divide-slate-100 border-y border-slate-100">
                    <div class="py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-slate-900 col-span-1">Compte</dt>
                        <dd class="text-sm text-slate-600 col-span-2">Nom, adresse email, mot de passe (stocké de façon chiffrée), lien vers votre partenaire au sein d'un couple</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-slate-900 col-span-1">Santé (parcours PMA)</dt>
                        <dd class="text-sm text-slate-600 col-span-2">Rendez-vous médicaux (date, heure, lieu, type, praticien), médicaments et posologies, horaires de prise, historique des prises, étapes du parcours (stimulation, déclenchement, ponction, transfert...), notes associées</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-slate-900 col-span-1">Notifications</dt>
                        <dd class="text-sm text-slate-600 col-span-2">Préférences de rappel (canal push/email), identifiant technique de votre appareil pour l'envoi des notifications, historique d'envoi</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-slate-900 col-span-1">Partage de couple</dt>
                        <dd class="text-sm text-slate-600 col-span-2">Invitations envoyées à un partenaire, statut d'acceptation</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-slate-900 col-span-1">Technique</dt>
                        <dd class="text-sm text-slate-600 col-span-2">Type d'appareil et plateforme, horodatage des connexions, journaux nécessaires à la sécurité du service</dd>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500 border-l-2 border-indigo-200 pl-4">
                    Les données relatives à votre traitement sont des <strong class="text-slate-700 font-medium">données de santé</strong>
                    au sens du RGPD. Elles bénéficient d'une protection renforcée et ne sont jamais
                    utilisées à des fins commerciales, publicitaires ou de profilage.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">3. Pourquoi nous utilisons ces données</h2>
                <ul class="mt-4 space-y-2 text-slate-600 leading-relaxed list-disc list-outside pl-5">
                    <li>Vous permettre, avec votre partenaire, de consulter et gérer les mêmes informations de traitement</li>
                    <li>Envoyer les rappels de médicaments et de rendez-vous à la ou aux personnes concernées, au bon moment</li>
                    <li>Assurer la sécurité de votre compte et prévenir les accès non autorisés</li>
                    <li>Diagnostiquer et corriger les problèmes techniques du service</li>
                </ul>
                <p class="mt-4 text-slate-600 leading-relaxed">
                    Nous ne vendons pas vos données, ne les utilisons pas à des fins publicitaires,
                    et ne les partageons pas au-delà des prestataires techniques strictement
                    nécessaires au fonctionnement du service (section 5).
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">4. Base légale et durée de conservation</h2>
                <div class="mt-3 space-y-4 text-slate-600 leading-relaxed">
                    <p>
                        Le traitement de vos données repose sur votre consentement (création
                        volontaire d'un compte et saisie de vos informations de traitement) et sur
                        l'exécution du service que vous nous demandez (rappels, partage entre
                        partenaires).
                    </p>
                    <p>
                        Vos données sont conservées tant que votre compte est actif. Lorsque vous
                        ou votre partenaire supprimez le compte, l'ensemble des données associées
                        est supprimé de façon définitive.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">5. Avec qui vos données sont-elles partagées</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    Seuls votre partenaire et vous-même avez accès aux données de traitement. Nous
                    faisons appel aux prestataires techniques suivants, uniquement pour faire
                    fonctionner le service :
                </p>
                <ul class="mt-4 space-y-3 text-slate-600 leading-relaxed list-disc list-outside pl-5">
                    <li><strong class="text-slate-800 font-medium">Firebase Cloud Messaging (Google)</strong> — envoi des notifications push. Seul un identifiant technique d'appareil est transmis, jamais votre dossier médical.</li>
                    <li><strong class="text-slate-800 font-medium">Hébergeur</strong> — l'application et la base de données sont hébergées chez notre prestataire d'hébergement.</li>
                    <li><strong class="text-slate-800 font-medium">Service d'envoi d'emails</strong> — invitations de partenaire et notifications par email lorsque ce canal est activé.</li>
                </ul>
                <p class="mt-4 text-slate-600 leading-relaxed">Aucune donnée n'est transmise à des fins commerciales ou publicitaires.</p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">6. Sécurité</h2>
                <ul class="mt-4 space-y-2 text-slate-600 leading-relaxed list-disc list-outside pl-5">
                    <li>Mots de passe stockés sous forme chiffrée, jamais en clair</li>
                    <li>Accès à l'API protégé par authentification par jeton</li>
                    <li>Chaque compte n'a accès qu'aux données de son propre couple</li>
                    <li>Espace d'administration réservé à un accès restreint et journalisé</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">7. Vos droits</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    Conformément au RGPD, vous disposez des droits suivants sur vos données :
                </p>
                <ul class="mt-4 space-y-2 text-slate-600 leading-relaxed list-disc list-outside pl-5">
                    <li><strong class="text-slate-800 font-medium">Accès</strong> — obtenir une copie des données que nous détenons sur vous</li>
                    <li><strong class="text-slate-800 font-medium">Rectification</strong> — corriger des données inexactes</li>
                    <li><strong class="text-slate-800 font-medium">Effacement</strong> — demander la suppression de votre compte et de vos données</li>
                    <li><strong class="text-slate-800 font-medium">Portabilité</strong> — recevoir vos données dans un format structuré</li>
                    <li><strong class="text-slate-800 font-medium">Opposition</strong> et retrait du consentement à tout moment</li>
                </ul>
                <p class="mt-4 text-slate-600 leading-relaxed">
                    Pour exercer l'un de ces droits, supprimez votre compte directement depuis
                    l'application ou contactez-nous à l'adresse indiquée en section 10.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">8. Bon usage de l'application</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    LTMO est un outil d'organisation destiné à faciliter la coordination entre
                    partenaires. Afin de garantir un usage sûr et respectueux, merci de respecter
                    les règles suivantes :
                </p>
                <ul class="mt-4 space-y-3 text-slate-600 leading-relaxed list-disc list-outside pl-5">
                    <li><strong class="text-slate-800 font-medium">LTMO ne remplace pas un avis médical.</strong> Les rappels et informations affichés sont une aide à l'organisation ; seul votre médecin est habilité à prescrire, modifier ou interrompre un traitement.</li>
                    <li><strong class="text-slate-800 font-medium">Vérifiez l'exactitude des informations saisies</strong> (dosages, horaires, dates). Vous restez seul responsable de la conformité de votre traitement avec les prescriptions de votre médecin.</li>
                    <li><strong class="text-slate-800 font-medium">Ne partagez pas vos identifiants de connexion</strong> avec une personne autre que votre partenaire. Le partage d'informations passe par la fonctionnalité d'invitation, pas par un échange de mot de passe.</li>
                    <li><strong class="text-slate-800 font-medium">Respectez le consentement de votre partenaire</strong> quant au partage de ses informations et aux notifications qu'il ou elle reçoit.</li>
                    <li><strong class="text-slate-800 font-medium">N'utilisez pas l'application pour un tiers</strong> sans son consentement explicite.</li>
                    <li><strong class="text-slate-800 font-medium">Ne tentez pas de contourner les mesures de sécurité</strong> (accès non autorisé à un autre compte, exploitation de failles, usage automatisé non prévu de l'API).</li>
                    <li><strong class="text-slate-800 font-medium">Signalez tout dysfonctionnement ou usage suspect</strong> plutôt que de tenter de le corriger vous-même.</li>
                </ul>
                <p class="mt-4 text-sm text-slate-500 border-l-2 border-indigo-200 pl-4">
                    Le non-respect de ces règles peut entraîner la suspension ou la suppression du
                    compte concerné, notamment en cas d'usage frauduleux ou d'atteinte à la
                    sécurité du service.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">9. Modifications de cette politique</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    Cette politique peut être mise à jour pour refléter l'évolution de
                    l'application ou de la réglementation. La date de dernière mise à jour figure
                    en haut de cette page.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-semibold text-slate-900">10. Contact</h2>
                <p class="mt-3 text-slate-600 leading-relaxed">
                    Pour toute question relative à vos données ou à cette politique :
                    <a href="mailto:contact@jimmy-besse.fr" class="text-indigo-600 hover:underline">contact@jimmy-besse.fr</a>
                </p>
            </section>

        </div>
    </main>

    <footer class="border-t border-slate-200 mt-8">
        <div class="max-w-2xl mx-auto px-6 py-8 flex items-center justify-between text-sm text-slate-400">
            <p>&copy; {{ now()->format('Y') }} LTMO</p>
            <div class="flex gap-6">
                <a href="/" class="hover:text-slate-600">Accueil</a>
                <a href="{{ route('privacy') }}" class="text-slate-600 font-medium">Confidentialité</a>
            </div>
        </div>
    </footer>

</body>
</html>
