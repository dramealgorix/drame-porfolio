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
    FROM projets
    ORDER BY date_creation DESC
";

$requete = $pdo->query($sql);

$projets = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des projets</title>
</head>

<body>

<h1>Gestion des projets</h1>

<p>
    <a href="ajouter.php">
        Ajouter un projet
    </a>
</p>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($projets as $projet): ?>

        <tr>

            <td>
                <?= $projet['id'] ?>
            </td>

            <td>
                <?= echapper($projet['titre']) ?>
            </td>

            <td>
                <?= $projet['date_creation'] ?>
            </td>

            <td>

                <a href="modifier.php?id=<?= $projet['id'] ?>">
                    Modifier
                </a>
                // Lien de suppression avec confirmation en utilant la méthode POST comme recommandé par le professeur
                <form
                method="POST"
                action="supprimer.php"
                style="display:inline;"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $projet['id'] ?>"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= echapper(genererTokenCSRF()) ?>"
    >

    <button
        type="submit"
        onclick="return confirm('Supprimer ce projet ?')"
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