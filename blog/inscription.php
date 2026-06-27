<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';

redirigerSiConnecte();

$messageErreur = '';
$messageSucces = '';

$prenom = '';
$nom = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = nettoyer($_POST['prenom'] ?? '');
    $nom = nettoyer($_POST['nom'] ?? '');
    $email = nettoyer($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'] ?? '';

    // Vérifications
    if (
        !champ_requis($prenom) ||
        !champ_requis($nom) ||
        !champ_requis($email) ||
        !champ_requis($motDePasse) ||
        !champ_requis($confirmationMotDePasse)
    ) {
        $messageErreur = 'Tous les champs sont obligatoires.';
    } elseif (!estEmailValide($email)) {
        $messageErreur = 'Veuillez saisir une adresse email valide.';
    } elseif ($motDePasse !== $confirmationMotDePasse) {
        $messageErreur = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($motDePasse) < 8) {
        $messageErreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        // Vérifier si l'email existe déjà
        $requete = $pdo->prepare("SELECT id FROM blog_utilisateurs WHERE email = :email LIMIT 1");
        $requete->execute([
            'email' => $email
        ]);

        $utilisateurExistant = $requete->fetch();

        if ($utilisateurExistant) {
            $messageErreur = 'Cette adresse email est déjà utilisée.';
        } else {
            // Hash du mot de passe
            $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

            // Insertion utilisateur
            $requeteInsertion = $pdo->prepare("
                INSERT INTO blog_utilisateurs (prenom, nom, email, mot_de_passe)
                VALUES (:prenom, :nom, :email, :mot_de_passe)
            ");

            $requeteInsertion->execute([
                'prenom' => $prenom,
                'nom' => $nom,
                'email' => $email,
                'mot_de_passe' => $motDePasseHash
            ]);

            $utilisateurId = (int) $pdo->lastInsertId();

            // Connexion automatique après inscription
            $_SESSION['utilisateur'] = [
                'id' => $utilisateurId,
                'prenom' => $prenom,
                'nom' => $nom,
                'email' => $email
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
    <title>Inscription — ESTM Blog</title>
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
            <h2>Rejoignez la communauté <span>ESTM Blog</span></h2>
            <p>Publiez des articles, commentez les posts de vos camarades et construisez votre présence académique en ligne.</p>

            <ul class="auth-features">
                <li>Publiez vos travaux et réflexions</li>
                <li>Commentez et échangez avec la communauté</li>
                <li>Gérez votre profil étudiant</li>
            </ul>

            <p style="margin-top:2rem;font-size:0.75rem;color:rgba(255,255,255,0.4);">
                Accès réservé aux membres inscrits à l'ESTM.
            </p>
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

            <h1>Créer un compte</h1>
            <p>Inscrivez-vous pour rejoindre la communauté.</p>

            <?php include __DIR__ . '/composants/alertes.php'; ?>

            <form action="" method="POST" class="formulaire">
                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input
                            type="text"
                            id="prenom"
                            name="prenom"
                            class="form-input"
                            placeholder="Amina"
                            autocomplete="given-name"
                            value="<?= echapper($prenom) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            class="form-input"
                            placeholder="Traoré"
                            autocomplete="family-name"
                            value="<?= echapper($nom) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="vous@estm.edu"
                        autocomplete="email"
                        value="<?= echapper($email) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input
                        type="password"
                        id="mot_de_passe"
                        name="mot_de_passe"
                        class="form-input"
                        placeholder="Minimum 8 caractères"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirmation_mot_de_passe">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="confirmation_mot_de_passe"
                        name="confirmation_mot_de_passe"
                        class="form-input"
                        placeholder="Répétez votre mot de passe"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-accent btn-full">
                    Créer mon compte
                </button>
            </form>

            <p class="auth-footer-text">
                Déjà inscrit ? <a href="connexion.php">Se connecter</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
```
