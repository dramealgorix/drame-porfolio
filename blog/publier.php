<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';

// Exiger la connexion au user pour pouvoir publier Article
exigerConnexion();
require_once __DIR__ . '/fonctions/articles.php';

//
// Messages affichés à l'utilisateur
$messageErreur = '';
$messageSucces = '';

 // Vérifie si le formulaire a été soumis
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nettoyage des données saisies
    $titre = nettoyer($_POST['titre'] ?? '');
    $contenu = nettoyer($_POST['contenu'] ?? '');

    // Par défaut, aucun nom d'image n'est enregistré
    $nomImage = null;

    // Vérifie que les champs obligatoires sont remplis
    if (!champ_requis($titre) || !champ_requis($contenu)) {

        $messageErreur = "Veuillez remplir tous les champs obligatoires.";

    } else {

        // Gestion de l'image de couverture

        // Vérifie qu'un fichier a bien été envoyé sans erreur
        if (
            isset($_FILES['image_couverture']) &&
            $_FILES['image_couverture']['error'] === UPLOAD_ERR_OK
        ) {
          
          
            // Récupère l'extension du fichier (jpg, png, webp)
            $extension = strtolower(
                pathinfo(
                    $_FILES['image_couverture']['name'],
                    PATHINFO_EXTENSION
                )
            );

            // Liste des extensions autorisées
            $extensionsAutorisees = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            // Vérifie que l'extension est autorisée
            if (in_array($extension, $extensionsAutorisees)) {

                // Génère un nom unique pour éviter les doublons
                $nomImage = uniqid('article_') . '.' . $extension;

                // Déplace l'image dans le dossier uploads/articles
                move_uploaded_file(
                    $_FILES['image_couverture']['tmp_name'],
                    __DIR__ . '/images/articles/' . $nomImage
                );
            }
        }

        // ==========================
        // Enregistrement de l'article
        // ==========================

        // Insère l'article dans la base de données
        $idArticle = publierArticle(
            $pdo,
            utilisateurId(),  
            $titre,
            $contenu,
            $nomImage          // Image (optionnelle)
        );

        // Redirige vers la page de l'article nouvellement créé
        rediriger("article.php?id=" . $idArticle);
    }
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publier un article — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include 'composants/navigation.php'; ?>

    <main>
      <div class="container-medium" style="padding:2.5rem 1rem;">
        <a href="accueil.php" class="back-link">← Retour à l'accueil</a>
        <h1 class="page-title">Publier un article</h1>
        <p class="page-desc" style="margin-bottom:2rem;">Partagez vos idées avec la communauté ISEM</p>

        <div class="alert alert-info">
          <p class="alert-title">Conseil de rédaction</p>
          <p class="alert-text">Les articles bien structurés avec un titre accrocheur, une introduction claire et des sections organisées obtiennent plus d'engagement. Pensez à votre audience !</p>
        </div>

        <div class="card">
        <?php include __DIR__ . '/composants/alertes.php'; ?>
          <form action="" method="POST" enctype="multipart/form-data">
            
            <!--La valeur de lattribut "enctype" indique au nav de 
            diviser les données du formulaire en plusieurs parties afin 
            que les fichiers binaires puissent être téléchargés en toute 
            sécurité sur un serveur -->

            <div class="form-group">
              <label for="titre">Titre de l'article <span class="required">*</span></label>
              <input type="text" 
                id="title" 
                name="titre" 
                class="form-input" 
                placeholder="Entrez un titre pour votre article..."
                style="font-size:1.125rem;
                       font-weight:500;"
              >
            </div>

          <!--
            <div class="form-group">
              <label for="category">Catégorie <span class="required">*</span></label>
              <select id="category" name="category" class="form-select">
                <option value="" disabled selected>Choisir une catégorie...</option>
                <option value="Technologie">Technologie</option>
                <option value="Carrière">Carrière</option>
                <option value="Bien-être">Bien-être</option>
                <option value="Entrepreneuriat">Entrepreneuriat</option>
                <option value="Finance">Finance</option>
              </select>
            </div>
          -->
            <div class="form-group">
              <label>Image de couverture</label>
              <div class="file-upload">
                <p>📷 Cliquez pour uploader une image</p>
                <small>Formats acceptés : JPG, PNG, WebP — Max 5 Mo</small>
                  <input type="file" 
                    name="image_couverture" 
                    accept="image/jpeg,image/png,image/webp" 
                    style="margin-top:1rem;"
                  >
              </div>
            </div>

            <div class="form-group">
              <label for="content">Contenu de l'article <span class="required">*</span></label>
                <textarea id="content" 
                  name="contenu" 
                  class="form-textarea" 
                  rows="18" placeholder="Rédigez votre article ici...">
                </textarea>
                <p class="form-hint">Conseil : structurez votre article avec des titres clairs, des paragraphes courts et des exemples concrets.</p>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-accent">📤 Publier l'article</button>
              <a href="accueil.php" class="btn btn-secondary">Annuler</a>
            </div>
          </form>
        </div>
      </div>
    </main>

    <?php include 'composants/footer.php'; ?>
  </div>
</body>
</html>
