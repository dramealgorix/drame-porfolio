<?php

/**
 * Vérifie qu'un champ n'est pas vide
 */
function champ_requis(string $valeur): bool {

    return !empty(trim($valeur));

}

/**
 * Nettoie les données
 */
function nettoyer(string $valeur): string {

    return htmlspecialchars(trim($valeur));
}

/**
 * Protection XSS
 */
function echapper(string $valeur): string
{
    return htmlspecialchars(
        $valeur,
        ENT_QUOTES,
        'UTF-8'
    );
}

/**
 * Génération du token CSRF
 */
function genererTokenCSRF(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Vérification du token CSRF
 */
function verifierTokenCSRF(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Adresse IP du visiteur
 */
function obtenirAdresseIP(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? 'Inconnue';
}

/**
 * Enregistre une visite
 */
function enregistrerVisite(PDO $pdo, string $page): void
{
    $sql = "
        INSERT INTO visites (
            adresse_ip,
            page
        )
        VALUES (
            :adresse_ip,
            :page
        )
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'adresse_ip' => obtenirAdresseIP(),
        'page' => $page
    ]);
}

/**
 * Vérifie si un administrateur est connecté
 */
function estConnecte(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['admin_id']);
}