<?php

session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

if (!estConnecte()) {

    header('Location: ../connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;
}

if (
    !isset($_POST['csrf_token']) ||
    !verifierTokenCSRF($_POST['csrf_token'])
) {

    die('Jeton CSRF invalide.');
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Récupération image
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT image
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
| Suppression image
|--------------------------------------------------------------------------
*/
if (!empty($projet['image'])) {

    $fichier =
        '../../images/projets/'
        . $projet['image'];

    if (file_exists($fichier)) {

        unlink($fichier);
    }
}

/*
|--------------------------------------------------------------------------
| Suppression projet
|--------------------------------------------------------------------------
*/
$sql = "
    DELETE FROM projets
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

header('Location: index.php');
exit;