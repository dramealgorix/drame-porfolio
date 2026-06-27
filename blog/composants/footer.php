<footer class="footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo">
          <span class="logo-icon">📖</span>
          <span>ESTM Blog</span>
        </div>
        <p>La plateforme communautaire des étudiants de l'ESTM. Partagez vos idées, vos expériences et vos connaissances.</p>
      </div>

      <div>
        <h3>Navigation</h3>
        <ul>
          
          <li><a href="accueil.php">Accueil</a></li>

          <?php if ($estConnecte) : ?>
          <li><a href="publier.php">Publier un article</a></li>
          <li><a href="mes-articles.php">Mes articles</a></li>
          <li><a href="profil.php">Mon profil</a></li>
          <?php else : ?>
      
          <li><a href="connexion.php">Se connecter</a></li>
          <li><a href="inscription.php">Inscription</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div>
        <h3>À propos</h3>
        <ul>
          <li><a href="#">À propos de l'ESTM</a></li>
          <li><a href="#">Règlement de publication</a></li>
          <li><a href="#">Politique de confidentialité</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2026 ESTM Blog. Tous droits réservés.</p>
    </div>
  </div>
</footer>
