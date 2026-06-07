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

/*
|--------------------------------------------------------------------------
| Vérification CSRF
|--------------------------------------------------------------------------
*/
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
| Interdiction de supprimer son propre compte
|--------------------------------------------------------------------------
*/
if ($id == $_SESSION['admin_id']) {

    die(
        'Impossible de supprimer votre propre compte.'
    );
}

/*
|--------------------------------------------------------------------------
| Suppression
|--------------------------------------------------------------------------
*/
$sql = "
    DELETE FROM administrateurs
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

header('Location: index.php');
exit;