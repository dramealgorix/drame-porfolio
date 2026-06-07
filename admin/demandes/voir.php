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

/*
|--------------------------------------------------------------------------
| Marquer la demande comme lue
|--------------------------------------------------------------------------
*/
$sql = "
    UPDATE demandes_projet
    SET lu = 1
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

/*
|--------------------------------------------------------------------------
| Récupération de la demande
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT *
    FROM demandes_projet
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

$demande = $requete->fetch();

if (!$demande) {

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la demande</title>
</head>
<body>

<h1>Détail de la demande de projet</h1>

<p>
    <strong>Nom :</strong>
    <?= echapper($demande['nom']) ?>
</p>

<p>
    <strong>Email :</strong>
    <?= echapper($demande['email']) ?>
</p>

<p>
    <strong>Type de projet :</strong>
    <?= echapper($demande['type_projet']) ?>
</p>

<p>
    <strong>Budget :</strong>
    <?= echapper($demande['budget']) ?>
</p>

<p>
    <strong>Date :</strong>
    <?= $demande['date_demande'] ?>
</p>

<p>
    <strong>Description :</strong>
</p>

<p>
    <?= nl2br(
        echapper($demande['description'])
    ) ?>
</p>

<p>
    <a href="index.php">
        Retour aux demandes
    </a>
</p>

</body>
</html>