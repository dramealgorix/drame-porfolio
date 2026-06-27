<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';
require_once __DIR__ . '/fonctions/articles.php';

exigerConnexion();

$messageErreur = ''; 
$messageSucces = ''; 

 // Vérifie que l'ID est présent dans l'URL 
 $articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  if ($articleId <= 0) { 
    rediriger('mes-articles.php'); 
  } 
  
  // Vérifie que l'article appartient à l'utilisateur connecté 
  $article = recupererArticleUtilisateur( 
    $pdo, 
    $articleId, 
    utilisateurId() 
  ); 
  
  if (!$article) { 
    rediriger('mes-articles.php'); 
  }

// ===================================================
// Traitement du formulaire de modification
// ===================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Nettoyage des données
  $titre = nettoyer($_POST['titre'] ?? '');
  $contenu = nettoyer($_POST['contenu'] ?? '');

  // On conserve l'image actuelle
  $nomImage = $article['image_couverture'];

  // Vérification des champs obligatoires
  if (!champ_requis($titre) || !champ_requis($contenu)) {

      $messageErreur = "Veuillez remplir les champs obligatoires.";

  } else {

      // ============================================
      // Vérifie si une nouvelle image a été envoyée
      // ============================================
      if (
          isset($_FILES['image_couverture']) &&
          $_FILES['image_couverture']['error'] === UPLOAD_ERR_OK
      ) {

          // Extension du fichier
          $extension = strtolower(
              pathinfo(
                  $_FILES['image_couverture']['name'],
                  PATHINFO_EXTENSION
              )
          );
          // Extensions autorisées
          $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'webp'];
          if (in_array($extension, $extensionsAutorisees)) {

              // Nouveau nom de fichier
              $nomImage = uniqid('article_') . '.' . $extension;

              // Déplacement de l'image
              move_uploaded_file(
                  $_FILES['image_couverture']['tmp_name'],
                  __DIR__ . '/images/articles/' . $nomImage
              );
          }
      }

      // ==========================
      // Mise à jour de l'article
      // ==========================
      modifierArticle(
          $pdo,
          $articleId,
          $titre,
          $contenu,
          $nomImage
      );

      // Redirection
      rediriger("article.php?id=" . $articleId);
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier l'article — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include 'composants/navigation.php'; ?>

    <main>
      <div class="container-medium" style="padding:2.5rem 1rem;">
        <a href="article.php?Id=<?= $articleId ?>" 
          class="back-link">← Retour à l'article</a>
          <h1 class="page-title">Modifier l'article</h1>

        <?php include __DIR__ . '/composants/alertes.php'; ?>
        <div class="card">
          <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="title">Titre de l'article <span class="required">*</span></label>
              <input type="text" 
                id="title" 
                name="titre" 
                class="form-input" 
                value= "<?= echapper($article['titre']) ?>"
                style="font-size:1.125rem;font-weight:500;">
            </div>

            <div class="form-group">
              <label>Image de couverture</label>
                <?php if (!empty($article['image_couverture'])) : ?>
                <div style="border:1px solid var(--border);border-radius:1rem;overflow:hidden;margin-bottom:0.5rem;">
                    <img
                        src="images/articles/<?= echapper($article['image_couverture']) ?>"
                        alt="Couverture actuelle"
                        style="width:100%;height:14rem;object-fit:cover;">
                </div>
                <?php endif; ?>

              <input type="file" 
                name="image_couverture" 
                accept="image/jpeg,image/png,image/webp">
                <p class="form-hint">Laissez vide pour conserver l'image actuelle.</p>
            </div>

            <div class="form-group">
              <label for="content">Contenu de l'article <span class="required">*</span></label>
              <textarea 
                id="content" 
                name="contenu" 
                class="form-textarea"
                rows="18"
                required> <?= echapper($article['contenu']) ?>
              </textarea>
            </div>

            <div class="form-actions">
              <button type="submit" 
                class="btn btn-accent">💾 Enregistrer les modifications</button>
              <a href="article.php?id=<?= $articleId ?>" 
                class="btn btn-secondary">Annuler
              </a>
            </div>
          </form>
        </div>
      </div>
    </main>

    <?php include 'composants/footer.php'; ?>
  </div>
</body>
</html>
