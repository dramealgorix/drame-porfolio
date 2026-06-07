<?php

session_start();

require_once '../config/connexion.php';
require_once '../composants/fonctions.php';

/*
|--------------------------------------------------------------------------
| Protection de la page
|--------------------------------------------------------------------------
*/
if (!estConnecte()) {

    header('Location: connexion.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Statistiques
|--------------------------------------------------------------------------
*/
$nbProjets = $pdo->query(
    "SELECT COUNT(*) FROM projets"
)->fetchColumn();

$nbMessages = $pdo->query(
    "SELECT COUNT(*)
        FROM messages_contact
        WHERE lu = 0"
    )->fetchColumn();

$nbDemandes = $pdo->query(
    "SELECT COUNT(*)
    FROM demandes_projet
    WHERE lu = 0"
)->fetchColumn();

/*
|--------------------------------------------------------------------------
| Nombre de visites
|--------------------------------------------------------------------------
*/
$nbVisites = $pdo->query(
    "SELECT COUNT(*) FROM visites"
)->fetchColumn();

/*
|--------------------------------------------------------------------------
| 5 dernières visites
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT *
    FROM visites
    ORDER BY date_visite DESC
    LIMIT 5
";

$dernieresVisites = $pdo->query($sql)->fetchAll();

/*
|--------------------------------------------------------------------------
| 5 dernières demandes
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT *
    FROM demandes_projet
    ORDER BY date_demande DESC
    LIMIT 5
";

$requete = $pdo->query($sql);

$dernieresDemandes = $requete->fetchAll();


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>

    <h1>
        Bienvenue
        <?= echapper($_SESSION['admin_prenom']) ?>
    </h1>

    <hr>

    <h2>Statistiques</h2>

    <p>
        Nombre Total de projets publiés :
        <strong><?= $nbProjets ?></strong>
    </p>

    <p>
        Nombre de messages de contact non lus :
        <strong><?= $nbMessages ?></strong>
    </p>

    <p>
        Nombre de demandes non lues:
        <strong><?= $nbDemandes ?></strong>
    </p>

    <p>
        Nombre Total de visites :
        <strong><?= $nbVisites ?></strong>  
    </p>
    <hr>

<h2>Dernières visites</h2>

<table border="1" cellpadding="5">

    <tr>
        <th>Adresse IP</th>
        <th>Page</th>
        <th>Date</th>
    </tr>

    <?php foreach ($dernieresVisites as $visite): ?>

        <tr>

            <td>
                <?= echapper($visite['adresse_ip']) ?>
            </td>

            <td>
                <?= echapper($visite['page']) ?>
            </td>

            <td>
                <?= echapper($visite['date_visite']) ?>
            </td>

        </tr>

    <?php endforeach; ?>
</table>
    <hr>
        <!-- Affichage des 5 dernières demandes de projet -->
    <h2>Dernières demandes de projet</h2>

<table border="1">

    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Type</th>
        <th>Date</th>
    </tr>

    <?php foreach ($dernieresDemandes as $demande): ?>

        <tr>

            <td>
                <?= echapper($demande['nom']) ?>
            </td>

            <td>
                <?= echapper($demande['email']) ?>
            </td>

            <td>
                <?= echapper($demande['type_projet']) ?>
            </td>

            <td>
                <?= $demande['date_demande'] ?>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

    <a href="deconnexion.php">
        Déconnexion
    </a>

  

</body>

</html>