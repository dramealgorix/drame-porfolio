    <?php
        require '../composants/fonctions.php';

/* =========================
   TABLEAU DES PROJETS
========================= */

$projets = [

    [
        'titre' => 'Agence Connect',
        'description' => 'Site moderne pour une agence digitale.',
        'technologies' => ['HTML5', 'CSS3', 'JavaScript'],
        'image' => '../images/agenceconnect.png',
        'categorie' => 'app',
    ],

    [
        'titre' => 'Assirik Travel Connect',
        'description' => 'Plateforme de réservation de billets et assistance voyage.',
        'technologies' => ['React', 'Node.js', 'Supabase'],
        'image' => '../images/assiriktravel.png',
        'categorie' => 'web',
    ],

    [
        'titre' => 'FitTrack',
        'description' => 'Application fitness et nutrition.',
        'technologies' => ['Vue.js', 'Firebase', 'PWA'],
        'image' => '../images/FitTrack.png',
        'categorie' => 'app',
    ],

    [
        'titre' => 'Élégance Boutique',
        'description' => 'Boutique e-commerce mode femme.',
        'technologies' => ['WooCommerce', 'Stripe', 'Paytech'],
        'image' => '../images/boutique fashion.png',
        'categorie' => 'ecommerce',
    ],

    [
        'titre' => 'La Table Restaurant',
        'description' => 'Site vitrine gastronomique moderne.',
        'technologies' => ['HTML5', 'SCSS', 'GSAP'],
        'image' => '../images/latable.jpg',
        'categorie' => 'web',
    ],

    [
        'titre' => 'Immobilier Premium',
        'description' => 'Plateforme immobilière haut de gamme.',
        'technologies' => ['HTML5', 'CSS3', 'JavaScript'],
        'image' => '../images/immobilier.jpg',
        'categorie' => 'web',
    ],

];

/* =========================
   RECHERCHE
========================= */

$mot_cle = nettoyer($_GET['q'] ?? '');

$resultats = [];

if ($mot_cle !== '') {

    foreach ($projets as $projet) {

        if (
            stripos($projet['titre'], $mot_cle) !== false ||
            stripos($projet['description'], $mot_cle) !== false
        ) {

            $resultats[] = $projet;

        }

    }

} else {
    $resultats = $projets;
}
?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio de projets web - Alexandre Dupont, développeur frontend">
    <title>Projets | DRAME ALGORIX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php require '../composants/navigation.php';?>
    
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <span class="page-badge animate-fade-in">Portfolio</span>
            <h1 class="page-title animate-fade-in-up">Mes Projets</h1>
            <p class="page-subtitle animate-fade-in-up delay-1">
                Une sélection de mes réalisations les plus récentes et significatives.
            </p>
        </div>
    </header>

    <!-- Projects Filter -->
    <section class="projects-section">
        <div class="container">
            <div class="projects-toolbar" data-animate>
                <form method="GET" class="projects-search" role="search">

                    <label class="sr-only" for="projects-search">
                        Rechercher un projet
                    </label>

                    <input
                        id="projects-search"
                        class="projects-search-input"
                        type="search"
                        name="q"
                        value="<?= $mot_cle ?>"
                        placeholder="Rechercher par nom, mot-clé..."
                    >
                    <button type="submit"
                            class="projects-search-clear">
                        Rechercher
                    </button>

                </form>

            </div>

            <div class="projects-grid-full">

                <?php if (!empty($resultats)) : ?>

        <?php foreach ($resultats as $projet) : ?>

            <article class="project-card-full"
                    data-category="<?= $projet['categorie'] ?>"
                    data-animate>

                <div class="project-image-full">

                    <img src="<?= htmlspecialchars($projet['image']) ?>"
                        alt="<?= $projet['titre'] ?>">

                </div>

            <div class="project-content-full">

                <div class="project-tags">

                    <?php foreach ($projet['technologies'] as $tech) : ?>

                        <span class="tag">
                            <?= $tech ?>
                        </span>

                    <?php endforeach; ?>

                </div>

                <h3 class="project-title">
                    <?= htmlspecialchars($projet['titre']) ?>
                </h3>

                <p class="project-description">
                    <?=htmlspecialchars($projet['description'])  ?>
                </p>

            </div>

        </article>

    <?php endforeach; ?>

    <?php else : ?>

    <p>Aucun projet ne correspond à votre recherche.</p>

<?php endif; ?>
            
                
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-animate>
                <h2 class="cta-title">Un projet en tête ?</h2>
                <p class="cta-description">
                    Transformons ensemble votre vision en une réalité digitale exceptionnelle.
                </p>
                <div class="cta-buttons">
                    <a href="/pages/contact.php" class="btn btn-primary btn-large">
                        <span>Discutons de votre projet</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
        <?php require '../composants/footer.php'; ?>

    <script src="../js/script.js"></script>
</body>
</html>
