<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';
require_once __DIR__ . '/fonctions/articles.php';

$mesArticles = recupererArticlesUtilisateur(
    $pdo,
    utilisateurId()
);

exigerConnexion();

// Récupération des informations de l'utilisateur connecté
$utilisateur = recupererUtilisateurParId(
  $pdo,
  utilisateurId()
);

// Si l'utilisateur est introuvable, retour à la connexion
if (!$utilisateur) {
  rediriger('connexion.php');
}

// ===============================
// AJOUT du bloc
// ===============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_profil'])) {

  $prenom = nettoyer($_POST['prenom'] ?? '');
  $nom = nettoyer($_POST['nom'] ?? '');
  $email = nettoyer($_POST['email'] ?? '');

  if (
      !champ_requis($prenom) ||
      !champ_requis($nom) ||
      !champ_requis($email)
  ) {

      $messageErreur = "Tous les champs sont obligatoires.";

  } elseif (!estEmailValide($email)) {

      $messageErreur = "Adresse email invalide.";

  } else {

      modifierProfil(
          $pdo,
          utilisateurId(),
          $prenom,
          $nom,
          $email
      );

      // Mise à jour de la session
      $_SESSION['utilisateur']['prenom'] = $prenom;
      $_SESSION['utilisateur']['nom'] = $nom;
      $_SESSION['utilisateur']['email'] = $email;

      $messageSucces = "Profil mis à jour avec succès.";

      // Recharge les nouvelles informations
      $utilisateur = recupererUtilisateurParId(
          $pdo,
          utilisateurId()
      );
  }
}

// ============================================
// Modification du mot de passe
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_mot_de_passe'])) {

  $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'] ?? '';
  $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'] ?? '';

  // Vérifie que les deux champs sont remplis
  if (
      !champ_requis($nouveauMotDePasse) ||
      !champ_requis($confirmationMotDePasse)
  ) {

      $messageErreur = "Veuillez remplir les deux champs.";

  }
  // Vérifie que les mots de passe sont identiques
  elseif ($nouveauMotDePasse !== $confirmationMotDePasse) {

      $messageErreur = "Les mots de passe ne correspondent pas.";

  }
  // Vérifie une longueur minimale
  elseif (strlen($nouveauMotDePasse) < 8) {

      $messageErreur = "Le mot de passe doit contenir au moins 8 caractères.";

  }
  else {

      // Hash du mot de passe
      $motDePasseHash = password_hash(
          $nouveauMotDePasse,
          PASSWORD_DEFAULT
      );

      // Mise à jour
      modifierMotDePasse(
          $pdo,
          utilisateurId(),
          $motDePasseHash
      );

      $messageSucces = "Mot de passe mis à jour avec succès.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page">
    <?php include 'composants/navigation.php'; ?>

    <main>
      <div class="container-medium" style="padding:2.5rem 1rem;">
        <h1 class="page-title">Mon profil</h1>
        <p class="page-desc" style="margin-bottom:2rem;">Gérez vos informations personnelles et la sécurité de votre compte.</p>

        <div class="profile-grid">
          <!-- Colonne gauche -->
          <div>
            <div class="card profile-card" style="margin-bottom:1.5rem;">
              <div class="profile-avatar">
                <img src="#"
                  alt=""
                >
              </div>
              <p class="profile-name">
              <?= echapper($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>
              </p>
              <p class="profile-email">
              <?= echapper($utilisateur['email']) ?>
              </p>
              <span class="profile-badge">Membre ESTM</span>
            </div>

            <div class="card">
              <h3 style="font-size:0.875rem;font-weight:600;margin-bottom:1rem;">Statistiques</h3>
              <ul class="profile-stats-list">
                <li>
                  <span class="stat-name">📖 Articles publiés</span>
                  <span class="stat-val accent"> <?= count($mesArticles) ?> </span>
                </li>
              </ul>
              <div style="border-top:1px solid var(--border);margin-top:1rem;padding-top:1rem;">
                <a href="mes-articles.php" style="font-size:0.75rem;font-weight:600;color:var(--accent);">Voir mes articles →</a>
              </div>
            </div>
          </div>

          <!-- Colonne droite -->
          <div>
            <!-- Informations personnelles -->
            <div class="card form-section">
              <div class="form-section-title">
                <div class="form-section-icon">👤</div>
                <div>
                  <h2>Informations personnelles</h2>
                  <p>Mettez à jour votre nom et votre adresse email.</p>
                </div>
              </div>

              <form action="" method="POST">
                <div class="form-row form-row-2">
                  <div class="form-group">
                    <label for="firstName">Prénom</label>
                    <input type="text" 
                      id="firstName" 
                      name="prenom" 
                      class="form-input" 
                      value="<?= echapper($utilisateur['prenom']) ?>">
                  </div>
                  <div class="form-group">
                    <label for="lastName">Nom</label>
                    <input type="text" 
                      id="lastName" 
                      name="nom" 
                      class="form-input" 
                      value="<?= echapper($utilisateur['nom']) ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label for="email">Adresse email</label>
                  <input type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    value="<?= echapper($utilisateur['email']) ?>">
                </div>

                <div class="form-section-actions">
                  <button type="submit"
                    name="modifier_profil" 
                    class="btn btn-accent btn-sm">Enregistrer
                  </button>
                </div>
              </form>
            </div>

            <!-- Sécurité -->
            <div class="card form-section">
              <div class="form-section-title">
                <div class="form-section-icon">🔑</div>
                <div>
                  <h2>Sécurité</h2>
                  <p>Laissez vide si vous ne souhaitez pas changer votre mot de passe.</p>
                </div>
              </div>

              <form action="" method="POST">
                <div class="form-group">
                  <label for="newPassword">Nouveau mot de passe <span style="font-weight:400;color:var(--muted-foreground);">(optionnel)</span></label>
                  <input type="password" 
                    id="newPassword" 
                    name="nouveau_mot_de_passe" 
                    class="form-input" 
                    placeholder="Nouveau mot de passe">
                </div>

                <div class="form-group">
                  <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
                  <input type="password" 
                    id="confirmPassword" 
                    name="confirmation_mot_de_passe" 
                    class="form-input" placeholder="Confirmer le mot de passe">
                </div>

                <div class="form-section-actions">
                  <button type="submit" 
                    name="modifier_mot_de_passe"
                    class="btn btn-accent btn-sm">
                    Mettre à jour le mot de passe</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include 'composants/footer.php'; ?>
  </div>
</body>
</html>
