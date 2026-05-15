
    <!-- Fonction de validation d'un champ -->
     <!-- Une variable speciale $_SERVER['PHP_SELF'] 
      qui contient le chemin de la page actuelle -->
       <?php
            $page_courante = basename($_SERVER['PHP_SELF']);
        ?>

    <!-- Navigation -->
    <nav class="navbar" id="navbar" role="navigation" aria-label="Principale">
        <div class="container nav-container">
            <a href="/index.php" class="logo">
                <span class="logo-text">DRAME ALGORIX</span>

            </a>
            <ul class="nav-menu" id="nav-menu">
                <li>
                    <a href="/index.php" 
                    class="nav-link <?= ($page_courante === 'index.php') ? 'actif' : ''; ?>">
                    Accueil</a>    
                </li>

                <li>
                    <a href="/pages/about.php"
                     class="nav-link <?= ($page_courante === 'about.php') ? 'actif' : ''; ?>">
                     À Propos</a>
                </li>

                <li>
                    <a href="/pages/projects.php" 
                    class="nav-link <?= ($page_courante === 'projects.php') ? 'actif' : ''; ?>">
                    Projets</a>
                </li>

                <li>
                    <a href="/pages/blog.php"
                     class="nav-link <?= ($page_courante === 'blog.php') ? 'actif' : ''; ?>">
                     Blog
                    </a>
                </li>

                <li>
                    <a href="/pages/contact.php"
                     class="nav-link <?= ($page_courante === 'contact.php') ? 'actif' : ''; ?>">
                     Contact
                    </a>
                </li>

                <li class="nav-item-cta">
                    <!-- Mon lien Calendly pour prise de rendez-vous (Planifier un appel) --> 
                    <a href="#" class="nav-cta nav-cta--mobile" target="_blank" rel="noopener noreferrer">Réserver un appel</a>
                </li>
            </ul>

            <div class="nav-actions">
                <a href="https://calendly.com/yahayadrame21/30min" class="nav-cta nav-cta--desktop" target="_blank" rel="noopener noreferrer">Réserver un appel</a>
                <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-menu">
                    <span class="hamburger" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </nav>