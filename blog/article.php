<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';

require_once __DIR__ . '/fonctions/auth.php';

require_once __DIR__ . '/fonctions/articles.php';
require_once __DIR__ . '/fonctions/commentaires.php';

$messageErreur = '';
$messageSucces = '';

$articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($articleId <= 0) {
    rediriger('accueil.php');
}

$article = recupererArticleParId($pdo, $articleId);

if (!$article) {
    rediriger('accueil.php');
}

/**
 * Ajout d’un commentaire si utilisateur connecté
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && estConnecte()) {
    $contenuCommentaire = nettoyer($_POST['contenu_commentaire'] ?? '');

    if (!champ_requis($contenuCommentaire)) {
        $messageErreur = 'Veuillez écrire un commentaire.';
    } else {
        $ajout = ajouterCommentaire(
            $pdo,
            $articleId,
            (int) utilisateurId(),
            $contenuCommentaire
        );

        if ($ajout) {
            $messageSucces = 'Votre commentaire a été ajouté avec succès.';
        } else {
            $messageErreur = 'Une erreur est survenue lors de l’ajout du commentaire.';
        }
    }
}

$commentaires = recupererCommentairesArticle($pdo, $articleId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= echapper($article['titre']) ?> — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include __DIR__ . '/composants/navigation.php'; ?>

    <main>
      <div class="page-header">
        <div class="page-header-inner">
          <a href="accueil.php" class="back-link">← Retour aux articles</a>
        </div>
      </div>

      <article class="article-detail">
        <span class="article-category">📘 Article du blog</span>

        <h1><?= echapper($article['titre']) ?></h1>

        <div class="article-meta">
          <div class="article-author">
            <div class="avatar-placeholder">
              <?= strtoupper(mb_substr($article['prenom'], 0, 1) . mb_substr($article['nom'], 0, 1)) ?>
            </div>

            <div>
              <p class="article-author-name"><?= echapper($article['prenom'] . ' ' . $article['nom']) ?></p>
              <p class="article-author-role">Auteur de l’article</p>
            </div>
          </div>

          <div class="article-info">
            <span>📅 <?= date('d/m/Y', strtotime($article['date_publication'])) ?></span>
            <span>💬 <?= count($commentaires) ?> commentaire(s)</span>
          </div>
        </div>

        <?php if (!empty($article['image_couverture'])) : ?>
          <div class="article-cover">
            <img
              src="images/articles/<?= echapper($article['image_couverture']) ?>"
              alt="<?= echapper($article['titre']) ?>"
            >
          </div>
        <?php endif; ?>

        <div class="article-content">
          <?= nl2br(echapper($article['contenu'])) ?>
        </div>
      </article>

      <!-- Commentaires -->
      <section class="comments-section">
        <div class="comments-inner">
          <h2 class="comments-title">💬 <?= count($commentaires) ?> Commentaire(s)</h2>

          <?php include __DIR__ . '/composants/alertes.php'; ?>

          <?php if (estConnecte()) : ?>
            <div class="comment-form">
              <form action="" method="POST" class="formulaire">
                <textarea
                  name="contenu_commentaire"
                  class="form-input"
                  placeholder="Partagez votre avis sur cet article..."
                  required
                ></textarea>

                <button type="submit" class="btn btn-accent btn-sm">
                  Publier le commentaire
                </button>
              </form>
            </div>
          <?php else : ?>
            <div class="empty-state">
              <p>Vous devez être connecté pour laisser un commentaire.</p>
              <a href="connexion.php" class="btn btn-accent">Se connecter</a>
            </div>
          <?php endif; ?>

          <?php if (empty($commentaires)) : ?>
            <div class="empty-state">
              <h3>Aucun commentaire pour le moment</h3>
              <p>Soyez le premier à réagir à cet article.</p>
            </div>
          <?php else : ?>
            <?php foreach ($commentaires as $commentaire) : ?>
              <div class="comment-card">
                <div class="comment-header">
                  <div class="avatar-placeholder">
                    <?= strtoupper(mb_substr($commentaire['prenom'], 0, 1) . mb_substr($commentaire['nom'], 0, 1)) ?>
                  </div>

                  <div>
                    <p class="comment-author">
                      <?= echapper($commentaire['prenom'] . ' ' . $commentaire['nom']) ?>
                    </p>
                    <p class="comment-date">
                      <?= date('d/m/Y à H:i', strtotime($commentaire['date_commentaire'])) ?>
                    </p>
                  </div>
                </div>

                <p class="comment-text">
                  <?= nl2br(echapper($commentaire['contenu'])) ?>
                </p>
                <?php if (
                  estConnecte() &&
                  utilisateurId() == $commentaire['auteur_id']
                ) : ?>

                  <a
                     href="supprimer-commentaire.php?id=<?= $commentaire['id'] ?>&article=<?= $articleId ?>"
                     class="action-delete"
                     onclick="return confirm('Voulez-vous supprimer ce commentaire ?');"
                  >🗑️ supprimer</a>
                  
                <?php endif; ?>
              </div> 
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/composants/footer.php'; ?>
  </div>
</body>
</html>

