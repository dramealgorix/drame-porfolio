<?php

/**
 * Nettoie une chaîne de caractères
 */
function nettoyer(?string $valeur): string
{
    return htmlspecialchars(trim((string) $valeur));
}

/**
 * Échappe une chaîne pour l'affichage HTML
 */
function echapper(?string $valeur): string
{
    return htmlspecialchars(
        (string) $valeur, 
        ENT_QUOTES, 
        'UTF-8');
}

/**
 * Vérifie si un champ est vide
 */
function champ_requis(string $valeur): bool {

    return !empty(trim($valeur));
}
/**
 * Vérifie si un email est valide
 */
function estEmailValide(?string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Redirection simple
 */
function rediriger(string $url): void
{
    header("Location: $url");
    exit;
}