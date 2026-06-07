<?php

session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

/*
|--------------------------------------------------------------------------
| Vérification connexion admin
|--------------------------------------------------------------------------
*/
if (!estConnecte()) {

    header('Location: ../connexion.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Vérification ID
|--------------------------------------------------------------------------
*/
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Récupération du projet
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT *
    FROM projets
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

$projet = $requete->fetch();

if (!$projet) {

    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Variables formulaire
|--------------------------------------------------------------------------
*/
$erreurs = [];
$succes = '';

$titre = $projet['titre'];
$description = $projet['description'];
$technologies = $projet['technologies'];
$lien = $projet['lien'];

$csrfToken = genererTokenCSRF();

/*
|--------------------------------------------------------------------------
| Traitement du formulaire
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !verifierTokenCSRF($_POST['csrf_token'])
    ) {

        $erreurs[] = 'Jeton CSRF invalide.';

    } else {

        $titre = nettoyer($_POST['titre'] ?? '');
        $description = nettoyer($_POST['description'] ?? '');
        $technologies = nettoyer($_POST['technologies'] ?? '');
        $lien = nettoyer($_POST['lien'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        if (!champ_requis($titre)) {
            $erreurs[] = 'Le titre est obligatoire.';
        }

        if (!champ_requis($description)) {
            $erreurs[] = 'La description est obligatoire.';
        }

        if (!champ_requis($technologies)) {
            $erreurs[] = 'Les technologies sont obligatoires.';
        }

        /*
        |--------------------------------------------------------------------------
        | Gestion image
        |--------------------------------------------------------------------------
        */
        $nomImage = $projet['image'];

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

            $extensionsAutorisees = [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp'
            ];

            $extension = strtolower(
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                )
            );

            if (
                !in_array(
                    $extension,
                    $extensionsAutorisees
                )
            ) {

                $erreurs[] =
                    'Format image non autorisé.';
            }

            if (empty($erreurs)) {

                $nomImage =
                    uniqid('projet_', true)
                    . '.'
                    . $extension;

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    '../../images/projets/' . $nomImage
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */
        if (empty($erreurs)) {

            $sql = "
                UPDATE projets
                SET
                    titre = :titre,
                    description = :description,
                    technologies = :technologies,
                    image = :image,
                    lien = :lien
                WHERE id = :id
            ";

            $requete = $pdo->prepare($sql);

            $requete->execute([
                'titre' => $titre,
                'description' => $description,
                'technologies' => $technologies,
                'image' => $nomImage,
                'lien' => $lien,
                'id' => $id
            ]);

            $succes =
                'Projet modifié avec succès.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un projet</title>
</head>

<body>

<h1>Modifier un projet</h1>

<?php foreach ($erreurs as $erreur): ?>
    <p><?= echapper($erreur) ?></p>
<?php endforeach; ?>

<?php if (!empty($succes)): ?>
    <p><?= echapper($succes) ?></p>
<?php endif; ?>

<form
    method="POST"
    enctype="multipart/form-data"
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= echapper($csrfToken) ?>"
    >

    <p>
        <label>Titre</label><br>
        <input
            type="text"
            name="titre"
            value="<?= echapper($titre) ?>"
        >
    </p>

    <p>
        <label>Description</label><br>
        <textarea
            name="description"
            rows="5"
        ><?= echapper($description) ?></textarea>
    </p>

    <p>
        <label>Technologies</label><br>
        <input
            type="text"
            name="technologies"
            value="<?= echapper($technologies) ?>"
        >
    </p>

    <p>
        <label>Lien externe</label><br>
        <input
            type="url"
            name="lien"
            value="<?= echapper($lien) ?>"
        >
    </p>

    <p>
        <label>Nouvelle image (facultatif)</label><br>
        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.gif,.webp"
        >
    </p>

    <button type="submit">
        Modifier le projet
    </button>

</form>

<p>
    <a href="index.php">
        Retour à la liste
    </a>
</p>

</body>
</html>