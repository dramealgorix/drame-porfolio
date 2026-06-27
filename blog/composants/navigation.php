<?php
$estConnecte = isset($_SESSION['utilisateur']);
?>

<header class="navbar">
  <div class="navbar-inner">
    <a href="accueil.php" class="logo">
      <span class="logo-icon">📖</span>
      <span>ESTM Blog</span>
    </a>

    <nav class="nav-links">
      <a href="accueil.php">Accueil</a>

      <?php if ($estConnecte) : ?>
      <a href="publier.php">Publier</a>
      <a href="mes-articles.php">Mes articles</a>
      <a href="profil.php">Profil</a>
      <a href="deconnexion.php">Déconnexion</a>

      <?php else : ?>
        <div class="nav-auth">
          <a href="connexion.php">Se connecter</a>
          <a href="inscription.php" class="btn-accent">Inscription</a>
        </div>
      <?php endif; ?>
    </nav>

    <label for="nav-toggle" class="nav-toggle" aria-label="Menu">☰</label>
  </div>

  <input type="checkbox" id="nav-toggle" class="nav-checkbox">
  <nav class="nav-mobile">
    <a href="accueil.php">Accueil</a>
    <a href="publier.php">Publier</a>
    <a href="mes-articles.php">Mes articles</a>
    <a href="profil.php">Profil</a>
    <div class="nav-mobile-divider">
      <a href="connexion.php">Se connecter</a>
      <a href="inscription.php">Inscription</a>
    </div>
  </nav>
</header>
