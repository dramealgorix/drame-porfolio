<?php
// Enregistrement de la visite
require_once '../config/connexion.php';
require_once '../composants/fonctions.php';

enregistrerVisite(
    $pdo,
    basename($_SERVER['PHP_SELF'])
);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="À propos d'Alexandre Dupont - Développeur web frontend passionné">
    <title>À Propos | DRAME ALGORIX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- Navigation -->
    
        <!-- Require navigation remplacer dans composants/navigation.php -->     
     <?php require '../composants/navigation.php'; ?>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <span class="page-badge animate-fade-in">À Propos</span>
            <h1 class="page-title animate-fade-in-up">Mon Histoire</h1>
            <p class="page-subtitle animate-fade-in-up delay-1">
                Découvrez mon parcours, ma passion et ma vision du développement web.
            </p>
        </div>
    </header>

    <!-- About Intro Section -->
    <section class="about-intro">
        <div class="container">
            <div class="about-grid">
                <div class="about-image-container" data-animate>
                    <div class="about-image-wrapper">
                        <img src="../images/WhatsApp Image 2026-04-06 at 15.59.02.jpeg" alt="DRAME Yahaya">
                        <div class="about-image-decoration"></div>
                    </div>
                </div>
                <div class="about-text" data-animate>
                    <h2 class="about-heading">Passionné par le code et le design</h2>
                    <p class="about-lead">
                        Je suis Yahaya DRAME, étudiant en génie logiciel et administration Réseaux à Dakar. Depuis plus de 2 ans, 
                        je transforme des idées créatives en expériences digitales mémorables en parallèle avec mes études en informatiques.
                    </p>
                    <p>
                        Mon parcours a commencé par une fascination pour la technologie et le design. 
                        Après un stage en informatique, une expérience en agence et des travaux en groupe, 
                        j'ai développé une base solide dans la création d'interfaces utilisateurs 
                        modernes et performantes.
                    </p>
                    <p>
                        Je crois fermement que le web doit être accessible à tous. C'est pourquoi je m'efforce 
                        de créer des sites qui ne sont pas seulement beaux, mais aussi intuitifs, rapides 
                        et inclusifs.
                    </p>
                    <div class="about-highlights">
                        <div class="highlight-item">
                            <span class="highlight-icon">🎯</span>
                            <div class="highlight-content">
                                <h4>Mission</h4>
                                <p>Créer des expériences web qui font la différence</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <span class="highlight-icon">💡</span>
                            <div class="highlight-content">
                                <h4>Vision</h4>
                                <p>Un web plus beau, plus rapide, plus accessible</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Valeurs</span>
                <h2 class="section-title">Ce qui me guide</h2>
            </div>
            <div class="values-grid">
                <div class="value-card" data-animate>
                    <div class="value-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="value-title">Excellence</h3>
                    <p class="value-description">
                        Chaque ligne de code est écrite avec soin. Je ne me contente jamais du "suffisant" 
                        et cherche toujours la meilleure solution.
                    </p>
                </div>
                <div class="value-card" data-animate>
                    <div class="value-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <h3 class="value-title">Collaboration</h3>
                    <p class="value-description">
                        Les meilleurs projets naissent d'une collaboration étroite. J'écoute, 
                        je comprends et je m'adapte aux besoins de chaque client.
                    </p>
                </div>
                <div class="value-card" data-animate>
                    <div class="value-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </div>
                    <h3 class="value-title">Innovation</h3>
                    <p class="value-description">
                        La technologie évolue constamment. Je reste à jour avec les dernières 
                        tendances pour offrir des solutions modernes et durables.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Parcours</span>
                <h2 class="section-title">Mon Expérience</h2>
            </div>
            <div class="timeline">
                <div class="timeline-item" data-animate>
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <span class="timeline-date">2025 - Présent</span>
                        <h3 class="timeline-title">Etudiant en Informatique</h3>
                        <p class="timeline-company"></p>
                        <p class="timeline-description">
                            Etudiant en Genie Logiciel et Administration Réseau
                        </p>
                    </div>
                </div>
                <div class="timeline-item" data-animate>
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <span class="timeline-date">2025 - Présent</span>
                        <h3 class="timeline-title">Dévéloppeur Freelance</h3>
                        <p class="timeline-company">Indépendant - Dakar</p>
                        <p class="timeline-description">
                            J'accompagne des PME dans la création de leurs produits digitaux et des boutiques pour plus de ventes en ligne . 
                            Spécialisation en React et interfaces modernes.
                        </p>
                    </div>
                </div>
                <div class="timeline-item" data-animate>
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <span class="timeline-date">2024</span>
                        <h3 class="timeline-title">Développeur junior Frontend et Wordpress</h3>
                        <p class="timeline-company">Chez ALGORIX Solutions</p>
                        <p class="timeline-description">
                            Développement d'applications web et sites e-commerce pour des clients 
                            variés.
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-animate>
                <h2 class="cta-title">Envie de collaborer ?</h2>
                <p class="cta-description">
                    Je suis toujours ouvert à de nouvelles opportunités et projets passionnants.
                </p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-primary btn-large">
                        <span>Me contacter</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="projects.php" class="btn btn-outline btn-large">Voir mes projets</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
        <?php require '../composants/footer.php'; ?>

    <script src="../js/script.js"></script>
</body>
</html>
