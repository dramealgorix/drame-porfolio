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
    FROM demandes_projet
    ORDER BY date_demande DESC
";

$requete = $pdo->query($sql);

$demandes = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de projet</title>
</head>
<body>

<h1>Demandes de projet</h1>

<table border="1">

<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Type</th>
    <th>Date</th>
    <th>État</th>
    <th>Action</th>
</tr>

<?php foreach ($demandes as $demande): ?>

<tr>

    <td><?= echapper($demande['nom']) ?></td>

    <td><?= echapper($demande['email']) ?></td>

    <td><?= echapper($demande['type_projet']) ?></td>

    <td><?= $demande['date_demande'] ?></td>

    <td>
        <?= $demande['lu'] == 0 ? 'Non lu' : 'Lu' ?>
    </td>

    <td>
        <a href="voir.php?id=<?= $demande['id'] ?>">
            Voir
        </a>
    </td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>