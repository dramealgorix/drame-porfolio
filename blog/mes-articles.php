<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';

// Protéger la page pour les users connectés
exigerConnexion();

require_once __DIR__ .'/fonctions/articles.php';

$mesArticles = recupererArticlesUtilisateur(
  $pdo,
  utilisateurId()
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes articles — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include 'composants/navigation.php'; ?>

    <main>
      <div class="container-medium" style="padding:2.5rem 1rem;">
        <div class="page-header-row" style="margin-bottom:2rem;">
          <div>
            <h1 class="page-title">Mes articles</h1>
            <p class="page-desc">Gérez tous vos articles ici.</p>
          </div>
          <a href="publier.php" class="btn btn-accent btn-sm">✏️ Nouvel article</a>
        </div>

        <!-- Statistiques -->
          <p class="value accent">
           <?= count($mesArticles). " Articles publiés" ?>

          </p>

        <!-- Liste des articles -->

    <div class="articles-list">
      <div class="articles-list-header">
          <?= count($mesArticles) ?> article(s)
      </div>

      <?php if (empty($mesArticles)) : ?>

          <div class="empty-state">
              <h3>Vous n'avez publié aucun article.</h3>
              <p>Commencez par publier votre premier article.</p>

              <a href="publier.php" class="btn btn-accent">
                  ✏️ Publier un article
              </a>
          </div>

      <?php else : ?>

          <?php foreach ($mesArticles as $article) : ?>

              <div class="article-list-item"
                  <div class="article-list-thumb">
                      <?php if (!empty($article['image_couverture'])) : ?>
                          <img
                              src="images/articles/<?= echapper($article['image_couverture']) ?>"
                              alt="<?= echapper($article['titre']) ?>"
                          >
                      <?php else : ?>
                          <div class="image-placeholder">
                              Aucune image
                          </div>
                      <?php endif; ?>

                  </div>

                  <div class="article-list-info">

                      <a href="article.php?id=<?= $article['id'] ?>">
                          <h3><?= echapper($article['titre']) ?></h3>
                      </a>

                      <div class="article-list-meta">

                          <span>
                              <?= date('d/m/Y', strtotime($article['date_publication'])) ?>
                          </span>

                          <span>
                              💬<?= $article['nombre_commentaires'] ?> commentaire(s)
                          </span>

                      </div>
                  </div>
                  <div class="article-list-actions">

                      <a
                          href="article.php?id=<?= $article['id'] ?>"
                          title="Voir"
                      >
                          👁️
                      </a>

                      <a
                          href="modifier.php?id=<?= $article['id'] ?>"
                          title="Modifier"
                      >
                          ✏️
                      </a>

                      <a
                          href="supprimer.php?id=<?= $article['id'] ?>"
                          title="Supprimer"
                          onclick="return confirm ("Voulez-vous vraiment supprimer cet article ?");
                      >
                          🗑️
                      </a>
                  </div>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>

    </div>
    </main> 

    <?php include 'composants/footer.php'; ?>
  </div>
</body>
</html>
