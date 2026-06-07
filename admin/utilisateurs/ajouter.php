<?php

session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

if (!estConnecte()) {

    header('Location: ../connexion.php');
    exit;
}

$erreurs = [];
$succes = '';

$prenom = '';
$nom = '';
$email = '';

$csrfToken = genererTokenCSRF();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !verifierTokenCSRF($_POST['csrf_token'])
    ) {

        $erreurs[] = 'Jeton CSRF invalide.';

    } else {

        $prenom = nettoyer($_POST['prenom'] ?? '');
        $nom = nettoyer($_POST['nom'] ?? '');
        $email = nettoyer($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        if (!champ_requis($prenom)) {
            $erreurs[] = 'Le prénom est obligatoire.';
        }

        if (!champ_requis($nom)) {
            $erreurs[] = 'Le nom est obligatoire.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Adresse email invalide.';
        }

        if (!champ_requis($motDePasse)) {
            $erreurs[] = 'Le mot de passe est obligatoire.';
        }

        // Vérification de l'unicité de l'email, s'il existe déja
        $sql = 
            "SELECT id 
            FROM administrateurs 
            WHERE email = :email
        ";
        $requete = $pdo->prepare($sql);
        $requete->execute([
            'email' => $email]);

            if ($requete->fetch()) {
                $erreurs[] = 'Un administrateur avec cette adresse email existe déjà.';
            }
            if (empty($erreurs)) {
                    $motDePasseHash = 
                     password_hash(
                       $motDePasse, 
                       PASSWORD_BCRYPT
                    );

                    $sql = 
                    "INSERT INTO administrateurs (
                        prenom, 
                        nom, 
                        email, 
                        mot_de_passe)
                    VALUES (
                        :prenom,
                        :nom,
                        :email, 
                        :mot_de_passe
                    )";

                    $requete = $pdo->prepare($sql);

                    $requete->execute([
                        'prenom' => $prenom,
                        'nom' => $nom,
                        'email' => $email,
                        'mot_de_passe' => $motDePasseHash
                    ]);

                    $succes = 'Administrateur ajouté avec succès.';
                    // Réinitialiser les champs du formulaire
                    $prenom = '';
                    $nom = '';
                    $email = '';      

                    
            }
    }
}

?>
    //formulaire ajouter un administrateur
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un administrateur</title>
</head>

<body>

<h1>Ajouter un administrateur</h1>

<?php foreach ($erreurs as $erreur): ?>
    <p><?= echapper($erreur) ?></p>
<?php endforeach; ?>

<?php if (!empty($succes)): ?>
    <p><?= echapper($succes) ?></p>
<?php endif; ?>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= echapper($csrfToken) ?>"
    >

    <p>
        <label>Prénom</label><br>

        <input
            type="text"
            name="prenom"
            value="<?= echapper($prenom) ?>"
            required
        >
    </p>

    <p>
        <label>Nom</label><br>

        <input
            type="text"
            name="nom"
            value="<?= echapper($nom) ?>"
            required
        >
    </p>

    <p>
        <label>Email</label><br>

        <input
            type="email"
            name="email"
            value="<?= echapper($email) ?>"
            required
        >
    </p>

    <p>
        <label>Mot de passe</label><br>

        <input
            type="password"
            name="mot_de_passe"
            required
        >
    </p>

    <button type="submit">
        Ajouter l'administrateur
    </button>

</form>

<p>
    <a href="index.php">
        Retour à la liste
    </a>
</p>

</body>
</html>