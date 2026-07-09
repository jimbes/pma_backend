<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LTMO — Coordonnez votre parcours PMA ensemble</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500&family=Mulish:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-cream: #F5F1EA;
            --sand: #ECE1CF;
            --sage: #7E9C89;
            --sage-dark: #5F7D6B;
            --sage-light: #EDF1EA;
            --clay: #C39A82;
            --clay-dark: #BF8E72;
            --clay-light: #F3E6DC;
            --text-primary: #34302A;
            --text-secondary: #6B6358;
            --text-tertiary: #9A9183;
            --border: #EFE9DE;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Mulish', sans-serif;
            color: var(--text-primary);
            background-color: var(--bg-cream);
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: 'Newsreader', serif;
            font-weight: 400;
        }

        h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        h2 {
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 2rem;
            color: var(--text-primary);
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        a {
            color: var(--sage);
            text-decoration: none;
        }

        a:hover {
            color: var(--sage-dark);
        }

        .btn {
            display: inline-block;
            background-color: var(--sage);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn:hover {
            background-color: var(--sage-dark);
            color: white;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--sage);
            border: 1.5px solid var(--sage);
            padding: 0.75rem 1.75rem;
        }

        .btn-secondary:hover {
            background-color: var(--sage-light);
            color: var(--sage-dark);
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 2.5rem;
            box-shadow: 0 8px 18px -12px rgba(60, 50, 40, 0.25);
            margin-bottom: 2rem;
        }

        .section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .screenshot-placeholder {
            width: 280px;
            aspect-ratio: 9/20;
            background-color: var(--sand);
            border-radius: 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .screenshot-placeholder p {
            color: var(--text-tertiary);
            font-family: monospace;
            font-size: 0.85rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            h2 {
                font-size: 1.8rem;
            }
            .section {
                padding: 3rem 1.5rem;
            }
            .section > div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>

    <!-- Hero -->
    <section class="section" style="padding-top: 6rem; padding-bottom: 4rem;">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1>Coordonnez votre parcours PMA ensemble</h1>
            <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 2.5rem; font-weight: 500;">
                LTMO organise les rendez-vous, les traitements et les rappels de votre couple. Un seul regard partagé, zéro oubli, plus de sérénité.
            </p>
            <a href="https://play.google.com/store/apps/details?id=com.besse.ltmo" class="btn">Télécharger sur Play Store</a>
            <p style="font-size: 0.95rem; color: var(--text-tertiary); margin-top: 1.5rem;">
                Gratuit, sans publicité.
            </p>
        </div>
        <div style="max-width: 900px; margin: 3rem auto 0; display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
            <div class="screenshot-placeholder"><p>Écran 1<br>mobile</p></div>
            <div class="screenshot-placeholder"><p>Écran 2<br>mobile</p></div>
            <div class="screenshot-placeholder"><p>Écran 3<br>mobile</p></div>
        </div>
    </section>

    <!-- Features -->
    <section class="section" style="background-color: var(--sage-light);">
        <h2 style="text-align: center;">Ce que LTMO fait pour vous</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem;">
            <div style="display: flex; justify-content: center;">
                <div class="screenshot-placeholder"><p>Rendez-vous<br>partagés</p></div>
            </div>
            <div class="card" style="margin-bottom: 0;">
                <h3 style="color: var(--sage);">Rendez-vous partagés</h3>
                <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                    Échographies, prises de sang, consultations, ponctions, transferts — vous et votre partenaire voyez le même calendrier à jour. Configurez des rappels distincts pour chacun·e, avec plusieurs alertes par rendez-vous (24h avant, 2h avant, etc.).
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem;">
            <div class="card" style="margin-bottom: 0; order: 2;">
                <h3 style="color: var(--clay);">Suivi des traitements</h3>
                <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                    Injections, comprimés, patchs, ovules — chaque dose avec son dosage, sa fréquence et ses rappels personnalisés (1h avant, 15min avant, etc.). Marquez chaque prise comme effectuée ou non. L'historique est partagé en temps réel.
                </p>
            </div>
            <div style="display: flex; justify-content: center; order: 1;">
                <div class="screenshot-placeholder" style="background-color: var(--clay-light);"><p>Suivi des<br>traitements</p></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem;">
            <div style="display: flex; justify-content: center;">
                <div class="screenshot-placeholder"><p>Étapes du<br>parcours</p></div>
            </div>
            <div class="card" style="margin-bottom: 0;">
                <h3 style="color: var(--sage);">Étapes du parcours</h3>
                <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                    Préparation, stimulation, contrôle, déclenchement, ponction, transfert, attente du test — voyez où vous en êtes dans votre cycle en un coup d'œil. Les dates et statuts se mettent à jour ensemble.
                </p>
            </div>
        </div>

        <div class="card">
            <h3 style="color: var(--clay);">Un compte partagé, deux perspectives</h3>
            <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                Un partenaire invite l'autre. Dès ce moment, vous voyez et modifiez les mêmes rendez-vous, traitements et étapes en temps réel. Pas de synchronisation manuelle, pas de doublons.
            </p>
        </div>

        <div class="card">
            <h3 style="color: var(--sage);">Notifications intelligentes</h3>
            <p style="color: var(--text-secondary); font-size: 1.05rem; line-height: 1.8;">
                Choisissez qui reçoit quelle notification, quand, et comment. Votre partenaire ne manquera jamais une dose. Vous ne raterez jamais un rendez-vous. Les alertes vont à qui en a besoin.
            </p>
        </div>
    </section>

    <!-- Journey Timeline -->
    <section class="section">
        <h2 style="text-align: center; margin-bottom: 1rem;">Votre parcours, étape par étape</h2>
        <p style="text-align: center; color: var(--text-secondary); font-size: 1rem; margin-bottom: 3rem;">
            <em>Exemple type. Votre protocole médical peut être différent — LTMO s'adapte à vos recommandations cliniques.</em>
        </p>

        <div style="max-width: 800px; margin: 0 auto;">
            <div style="position: relative; padding: 2rem 0;">

                <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background-color: var(--sand); transform: translateX(-50%);"></div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; text-align: right; padding-right: 2rem;">
                        <div style="background-color: var(--sage-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--sage);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Préparation</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Examens, analyses, protocole choisi avec votre clinique.</p>
                        </div>
                    </div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="width: 16px; height: 16px; background-color: var(--sage); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; padding-right: 2rem;"></div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="background-color: var(--clay-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--clay);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Stimulation</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Débute les injections quotidiennes, suivi par échographies.</p>
                        </div>
                        <div style="width: 16px; height: 16px; background-color: var(--clay); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; text-align: right; padding-right: 2rem;">
                        <div style="background-color: var(--sage-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--sage);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Contrôle</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Ajustements de dosage, surveillance précise de la croissance.</p>
                        </div>
                    </div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="width: 16px; height: 16px; background-color: var(--sage); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; padding-right: 2rem;"></div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="background-color: var(--clay-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--clay);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Déclenchement</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Injection finale, 36h avant la ponction programmée.</p>
                        </div>
                        <div style="width: 16px; height: 16px; background-color: var(--clay); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; text-align: right; padding-right: 2rem;">
                        <div style="background-color: var(--sage-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--sage);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Ponction</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Prélèvement des ovocytes sous anesthésie.</p>
                        </div>
                    </div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="width: 16px; height: 16px; background-color: var(--sage); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; margin-bottom: 3rem; position: relative;">
                    <div style="width: 50%; padding-right: 2rem;"></div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="background-color: var(--clay-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--clay);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Transfert</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">Implantation de l'embryon dans l'utérus.</p>
                        </div>
                        <div style="width: 16px; height: 16px; background-color: var(--clay); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

                <div style="display: flex; position: relative;">
                    <div style="width: 50%; text-align: right; padding-right: 2rem;">
                        <div style="background-color: var(--sage-light); padding: 1.5rem; border-radius: 16px; border-left: 4px solid var(--sage);">
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Attente du test</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem;">12-14 jours avant le test sanguin de confirmation.</p>
                        </div>
                    </div>
                    <div style="width: 50%; padding-left: 2rem;">
                        <div style="width: 16px; height: 16px; background-color: var(--sage); border-radius: 50%; position: absolute; left: 50%; top: 1.5rem; transform: translateX(-50%); border: 3px solid var(--bg-cream);"></div>
                    </div>
                </div>

            </div>
        </div>

        <p style="text-align: center; color: var(--text-secondary); margin-top: 3rem; font-size: 1.05rem;">
            Chaque couple vit un parcours unique. LTMO s'adapte à votre protocole médical, pas l'inverse.
        </p>
    </section>

    <!-- Privacy & Trust -->
    <section class="section" style="background-color: var(--sand);">
        <div style="max-width: 900px; margin: 0 auto;">
            <h2 style="text-align: center; margin-bottom: 3rem;">Confidentialité &amp; confiance</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
                <div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--sage);">Vos données, votre couple</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8;">
                        Vous et votre partenaire êtes les seules personnes qui voyez vos rendez-vous, vos traitements et votre parcours. Jamais vendu, jamais utilisé pour la publicité.
                    </p>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--clay);">Un outil, pas un diagnostic</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8;">
                        LTMO vous aide à organiser et mémoriser. Les décisions médicales restent entre vous et votre équipe clinique.
                    </p>
                </div>
            </div>

            <div style="text-align: center; padding: 2rem; background-color: white; border-radius: 22px; border: 1px solid var(--border);">
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Questions sur la sécurité de vos données ?
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('privacy') }}" class="btn btn-secondary">Politique de confidentialité</a>
                    <a href="{{ route('account-deletion') }}" class="btn btn-secondary">Supprimer mon compte</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: var(--text-primary); color: white; padding: 3rem 2rem; text-align: center;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem;">LTMO</p>
            <div style="display: flex; gap: 2rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap;">
                <a href="/" style="color: white;">Accueil</a>
                <a href="{{ route('privacy') }}" style="color: white;">Confidentialité</a>
                <a href="{{ route('account-deletion') }}" style="color: white;">Supprimer mon compte</a>
            </div>
            <p style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.7);">
                LTMO est un outil d'organisation pour les couples en parcours PMA. Travaillez toujours avec votre équipe médicale.
            </p>
        </div>
    </footer>

</body>
</html>
