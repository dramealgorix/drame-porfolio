<?php

Session_start();

require_once '../../config/connexion.php';
require_once '../../composants/fonctions.php';

if (!estConnecte()) {

    header('Location: ../../connexion.php');
    exit;
}

$sql = "
    SELECT * FROM administrateurs
    ORDER BY date_creation DESC
";

$requete = $pdo->query($sql);

$administrateurs = $requete->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administrateurs</title>
</head>
<body>

<h1>Gestion des administrateurs</h1>

<p>
    <a href="ajouter.php">
        Ajouter un administrateur
    </a>
</p>

<table border="1">

    <tr>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Date création</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($administrateurs as $admin): ?>

        <tr>

            <td><?= echapper($admin['prenom']) ?></td>

            <td><?= echapper($admin['nom']) ?></td>

            <td><?= echapper($admin['email']) ?></td>

            <td><?= $admin['date_creation'] ?></td>

            <td>

                <a href="modifier.php?id=<?= $admin['id'] ?>">
                    Modifier
                </a>

            </td>

            <td>
                <form
    method="POST"
    action="supprimer.php"
    style="display:inline;"
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= echapper(genererTokenCSRF()) ?>"
    >

    <input
        type="hidden"
        name="id"
        value="<?= $admin['id'] ?>"
    >

    <button
        type="submit"
        onclick="
            return confirm(
                'Supprimer cet administrateur ?'
            );
        "
    >
        Supprimer
    </button>

</form>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>