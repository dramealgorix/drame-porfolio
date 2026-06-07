<?php

session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

if (!estConnecte()) {

    header('Location: ../connexion.php');
    exit;
}

$sql = "
    SELECT *
    FROM messages_contact
    ORDER BY date_envoi DESC
";

$requete = $pdo->query($sql);

$messages = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages de contact</title>
</head>
<body>

<h1>Messages de contact</h1>

<table border="1">

    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Date</th>
        <th>État</th>
        <th>Action</th>
    </tr>

    <?php foreach ($messages as $message): ?>

        <tr>

            <td>
                <?= echapper($message['nom']) ?>
            </td>

            <td>
                <?= echapper($message['email']) ?>
            </td>

            <td>
                <?= $message['date_envoi'] ?>
            </td>

            <td>

                <?php if ($message['lu'] == 0): ?>

                    Non lu

                <?php else: ?>

                    Lu

                <?php endif; ?>

            </td>

            <td>

                <a href="voir.php?id=<?= $message['id'] ?>">
                    Voir
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>