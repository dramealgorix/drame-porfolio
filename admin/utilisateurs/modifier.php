<?php

session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

if (!estConnecte()) {

    header('Location: ../connexion.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;
}

$sql = "
    SELECT *
    FROM administrateurs
    WHERE id = :id
    
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

$admin = $requete->fetch();

if (!$admin) {

    header('Location: index.php');
    exit;
}

//Déclaration des variables
$erreurs = [];
$succes = '';

$prenom = $admin['prenom'];
$nom = $admin['nom'];
$email = $admin['email'];

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
            $sql = "
                SELECT id
                FROM administrateurs
                WHERE email = :email
                AND id != :id
            ";

            $requete = $pdo->prepare($sql);

            $requete->execute([
                'email' => $email,
                'id' => $id
            ]);

            if ($requete->fetch()) {

                $erreurs[] =
                    "Cet email existe déjà.";
            }
                
            $motDePasseHash = $admin['mot_de_passe'];

if (!empty($motDePasse)) {

    $motDePasseHash = password_hash(
        $motDePasse,
        PASSWORD_BCRYPT
    );
}

/*
|--------------------------------------------------------------------------
| Mise à jour
|--------------------------------------------------------------------------
*/
if (empty($erreurs)) {

    $sql = "
        UPDATE administrateurs
        SET
            prenom = :prenom,
            nom = :nom,
            email = :email,
            mot_de_passe = :mot_de_passe
        WHERE id = :id
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'mot_de_passe' => $motDePasseHash,
        'id' => $id
    ]);

    $succes = "Administrateur modifié avec succès.";

    // Recharger les données en mémoire
    $admin['mot_de_passe'] = $motDePasseHash;

    //Reinitialiser le formulaire
    $prenom = $admin['prenom'];
    $nom = $admin['nom'];
    $email = $admin['email'];
    $motDePasseHash = $admin['mot_de_passe'];
}
               
                    
    } 
} 

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier administrateur</title>
</head>
<body>

<h1>Modifier administrateur</h1>

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
        >
    </p>

    <p>
        <label>Nom</label><br>
        <input
            type="text"
            name="nom"
            value="<?= echapper($nom) ?>"
        >
    </p>

    <p>
        <label>Email</label><br>
        <input
            type="email"
            name="email"
            value="<?= echapper($email) ?>"
        >
    </p>

    <p>
        <label>Nouveau mot de passe (facultatif)</label><br>
        <input
            type="password"
            name="mot_de_passe"
        >
    </p>

    <button type="submit">
        Modifier
    </button>

</form>

<p>
    <a href="index.php">
        Retour
    </a>
</p>

</body>
</html>
