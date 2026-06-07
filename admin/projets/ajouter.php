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
//Variables pour le formulaire

$erreurs = [];
$succes = '';

$titre = '';
$description = '';
$technologies = '';
$lien = '';

$csrfToken = genererTokenCSRF();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | Vérification CSRF
    |--------------------------------------------------------------------------
    */
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
        | Upload image
        |--------------------------------------------------------------------------
        */
        $nomImage = null;

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

            $extensionsAutorisees = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'gif'
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
        | Insertion
        |--------------------------------------------------------------------------
        */
        if (empty($erreurs)) {

            $sql = "
                INSERT INTO projets (
                    titre,
                    description,
                    technologies,
                    image,
                    lien
                )
                VALUES (
                    :titre,
                    :description,
                    :technologies,
                    :image,
                    :lien
                )
            ";

            $requete = $pdo->prepare($sql);

            $requete->execute([
                'titre' => $titre,
                'description' => $description,
                'technologies' => $technologies,
                'image' => $nomImage,
                'lien' => $lien
            ]);

            $succes =
                'Projet ajouté avec succès.';

            $titre = '';
            $description = '';
            $technologies = '';
            $lien = '';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
</head>

<body>

<h1>Ajouter un projet</h1>

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
        <label>Image</label><br>
        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp,.gif"
        >
    </p>

    <button type="submit">
        Ajouter le projet
    </button>

</form>

</body>
</html>