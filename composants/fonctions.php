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