<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';
require_once __DIR__ . '/fonctions/articles.php';

// Récupération de tous les articles
$articles = recupererTousLesArticles($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESTM Blog — Le blog communautaire des étudiants</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include __DIR__ . '/composants/navigation.php'; ?>

    <main>
      <!-- Hero -->
      <section class="hero">
        <div class="hero-inner">
          <span class="hero-badge">Plateforme étudiante</span>
          <h1>
            Le blog communautaire
            <span>des étudiants de l'ESTM</span>
          </h1>
          <p>
            Découvrez les articles de vos camarades, partagez vos expériences
            et contribuez à la vie intellectuelle de notre communauté.
          </p>

          <div class="hero-actions">
            <a href="#articles" class="btn btn-accent">Lire les articles →</a>

            <?php if (estConnecte()) : ?>
              <a href="publier.php" class="btn btn-outline-light">✏️ Publier un article</a>
            <?php else : ?>
              <a href="connexion.php" class="btn btn-outline-light">Se connecter pour publier</a>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- Articles -->
      <section id="articles" class="section">
        <div class="container">
          <div class="section-header">
            <h2>Articles récents</h2>
            <p>Découvrez les dernières publications de la communauté ESTM.</p>
          </div>

          <?php if (empty($articles)) : ?>
            <div class="empty-state">
              <h3>Aucun article publié pour le moment</h3>
              <p>Soyez le premier à partager un article sur le blog.</p>

              <?php if (estConnecte()) : ?>
                <a href="publier.php" class="btn btn-accent">Publier un article</a>

                <?php else : ?>
                < a href="connexion.php" class="btn btn-accent">Se connecter pour publier</>
                <?php endif; ?>
            </div>
          <?php else : ?>
            <div class="articles-grid">
              <?php foreach ($articles as $article) : ?>
                <article class="article-card">
                  <a href="article.php?id=<?= (int) $article['id'] ?>" class="article-card-image">
                    <?php if (!empty($article['image_couverture'])) : ?>
                      <img
                        src="images/articles/<?= echapper($article['image_couverture']) ?>"
                        alt="<?= echapper($article['titre']) ?>"
                      >
                    <?php else : ?>
                      <div class="image-placeholder">ESTM Blog</div>
                    <?php endif; ?>
                  </a>

                  <div class="article-card-body">
                    <a href="article.php?id=<?= (int) $article['id'] ?>">
                      <h3><?= echapper($article['titre']) ?></h3>
                    </a>

                    <p class="article-card-excerpt">
                      <?= echapper(extraitArticle($article['contenu'])) ?>
                    </p>

                    <div class="article-card-meta">
                      <div class="article-card-author">
                        <div class="avatar-placeholder">
                          <?= strtoupper(mb_substr($article['prenom'], 0, 1) . mb_substr($article['nom'], 0, 1)) ?>
                        </div>
                        <span><?= echapper($article['prenom'] . ' ' . $article['nom']) ?></span>
                      </div>

                      <div class="article-card-stats">
                        <span><?= date('d/m/Y', strtotime($article['date_publication'])) ?></span>
                        <span>💬 <?= (int) $article['nombre_commentaires'] ?></span>
                      </div>
                    </div>

                    <a href="article.php?id=<?= (int) $article['id'] ?>" class="article-card-link">
                      Lire l'article
                    </a>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </main>
    <?php include __DIR__ . '/composants/footer.php'; ?>
  </div>
</body>
</html>
