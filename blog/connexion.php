<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';

redirigerSiConnecte();

$messageErreur = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = nettoyer($_POST['email'] ?? '');
    $motDePasse = nettoyer($_POST['mot_de_passe'] ?? '');

    if (!champ_requis($email) || !champ_requis($motDePasse)) {
        $messageErreur = 'Veuillez remplir tous les champs.';
    } elseif (!estEmailValide($email)) {
        $messageErreur = 'Veuillez saisir une adresse email valide.';
    } else {
        $requete = $pdo->prepare("
            SELECT id, prenom, nom, email, mot_de_passe
            FROM blog_utilisateurs
            WHERE email = :email
            LIMIT 1
        ");

        $requete->execute([
            'email' => $email
        ]);

        $utilisateur = $requete->fetch();

        if (!$utilisateur || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            $messageErreur = 'Email ou mot de passe incorrect.';
        } else {
            $_SESSION['utilisateur'] = [
                'id' => (int) $utilisateur['id'],
                'prenom' => $utilisateur['prenom'],
                'nom' => $utilisateur['nom'],
                'email' => $utilisateur['email']
            ];

            session_regenerate_id(true);

            rediriger('accueil.php');
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — ESTM Blog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="auth-page">

    <!-- Panneau décoratif gauche -->
    <div class="auth-panel">
      <div class="auth-panel-content">
        <div class="logo">
          <span class="logo-icon">📖</span>
          <span>ESTM Blog</span>
        </div>
      </div>
      <div class="auth-panel-content">
        <h2>&ldquo;Partager la connaissance, c'est multiplier<br> <span>les opportunités.&rdquo;</span></h2>
        <p>Rejoignez plus de 127 membres qui partagent leurs idées sur ESTM Blog.</p>
      </div>
      <div></div>
    </div>

    <!-- Formulaire -->
    <div class="auth-form-panel">
      <div class="auth-form-inner">
        <div class="auth-mobile-logo">
          <span class="logo-icon">📖</span>
          <span style="font-size:1.25rem;font-weight:800;">ESTM Blog</span>
        </div>

        <h1>Bienvenue !</h1>
        <p>Connectez-vous à votre compte pour gérer vos articles.</p>
        <?php include __DIR__ . '/composants/alertes.php'; ?>
        
        <form action="" method="POST" class="formulaire">
          <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" 
              id="email" 
              name="email" 
              class="form-input" 
              placeholder="vous@estm.edu" 
              autocomplete="email" 
              value="<?= echapper($email) ?>"
              required>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
              <input type="password" 
              id="password" 
              name="mot_de_passe" 
              class="form-input" placeholder="••••••••" 
              autocomplete="mot_de_passe" 

              required>
          </div>

          <div class="form-group" style="display:flex;align-items:center;gap:0.625rem;">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0;font-weight:400;color:var(--muted-foreground);">Se souvenir de moi</label>
          </div>

          <button type="submit" class="btn btn-accent btn-full">Se connecter</button>
        </form>

        <p class="auth-footer-text">
          Pas de compte ? <a href="inscription.php">Créer un compte</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
