<?php
session_start();

require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/fonctions/utilitaires.php';
require_once __DIR__ . '/fonctions/auth.php';
require_once __DIR__ . '/fonctions/commentaires.php';

// Vérifie que l'utilisateur est connecté
exigerConnexion();

// Récupération de l'identifiant du commentaire
$commentaireId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Récupération de l'identifiant de l'article
$articleId = isset($_GET['article']) ? (int) $_GET['article'] : 0;

// Vérifie que l'identifiant du commentaire est valide
if ($commentaireId > 0) {

    // Suppression du commentaire appartenant à l'utilisateur connecté
    supprimerCommentaire(
        $pdo,
        $commentaireId,
        utilisateurId()
    );

}

// Retour vers la page de l'article
rediriger("article.php?id=" . $articleId);