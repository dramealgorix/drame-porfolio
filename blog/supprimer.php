<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';
require_once __DIR__ . '/fonctions/articles.php';

// Seuls les utilisateurs connectés peuvent supprimer un article
exigerConnexion();

// Récupération de l'identifiant de l'article
$articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Vérification de l'identifiant
if ($articleId > 0) {

    supprimerArticle(
        $pdo,
        $articleId,
        utilisateurId()
    );
}

// Retour vers la liste des articles
rediriger('mes-articles.php');