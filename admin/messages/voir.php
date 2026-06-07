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
| Marquer comme lu
|--------------------------------------------------------------------------
*/
$sql = "
    UPDATE messages_contact
    SET lu = 1
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

/*
|--------------------------------------------------------------------------
| Récupération du message
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT *
    FROM messages_contact
    WHERE id = :id
";

$requete = $pdo->prepare($sql);

$requete->execute([
    'id' => $id
]);

$message = $requete->fetch();

if (!$message) {

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir message</title>
</head>
<body>

<h1>Détail du message</h1>

<p>
    <strong>Nom :</strong>
    <?= echapper($message['nom']) ?>
</p>

<p>
    <strong>Email :</strong>
    <?= echapper($message['email']) ?>
</p>

<p>
    <strong>Date :</strong>
    <?= $message['date_envoi'] ?>
</p>

<p>
    <strong>Message :</strong><br><br>

    <?= nl2br(
        echapper($message['message'])
    ) ?>
</p>

<p>
    <a href="index.php">
        Retour aux messages
    </a>
</p>

</body>
</html>