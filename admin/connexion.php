<?php

session_start();

require_once '../config/connexion.php';
require_once '../composants/fonctions.php';

$erreurs = [];

// Verification de la soumission du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification du token CSRF
    // On vérifie que le token est présent et valide pour éviter les attaques CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !verifierTokenCSRF($_POST['csrf_token'])
    ) {

        $erreurs[] = "Jeton CSRF invalide.";

    } else {

        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        // Validation du mot de passe et de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Adresse email invalide.";
        }

        if (empty($motDePasse)) {
            $erreurs[] = "Le mot de passe est obligatoire.";
        }

        if (empty($erreurs)) {

            $requete = $pdo->prepare(
                "SELECT * FROM administrateurs WHERE email = :email"
            );

            $requete->execute([
                'email' => $email
            ]);
            
            $admin = $requete->fetch();

            // Vérification du mot de passe en utilisant password_verify 
            // pour comparer le mot de passe saisi avec le hash stocké dans la base de données
            if (
                $admin &&
                password_verify(
                    $motDePasse,
                    $admin['mot_de_passe']
                )
            ) {

                //Sécurisation de la session
                session_regenerate_id(true);

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_prenom'] = $admin['prenom'];

                // Redirection vers le dashboard après une connexion réussie
                header('Location: dashboard.php');
                exit;

            } else {

                $erreurs[] = "Email ou mot de passe incorrect.";
            }
        }
    }
}

$csrfToken = genererTokenCSRF();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
</head>
<body>

<h1>Connexion Administrateur</h1>

<?php foreach ($erreurs as $erreur): ?>
    <p><?= echapper($erreur) ?></p>
<?php endforeach; ?>

<form method="post">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= echapper($csrfToken) ?>"
    >

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" 
            id="mot_de_passe"
            name="mot_de_passe" 
            required>
            <br><br>

    <button type="submit">
        Se connecter
    </button>

</form>

</body>
</html>