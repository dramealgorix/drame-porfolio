<?php

/**
 * Vérifie si un utilisateur est connecté
 */
function estConnecte(): bool
{
    return isset($_SESSION['utilisateur']);
}

/**
 * Retourne l'utilisateur connecté (tableau) ou null
 */
function utilisateurConnecte(): ?array
{
    return $_SESSION['utilisateur'] ?? null;
}

/**
 * Retourne l'ID de l'utilisateur connecté ou non (null)
 */
function utilisateurId(): ?int
{
    return $_SESSION['utilisateur']['id'] ?? null;
}

/**
 * Force la connexion : redirige vers connexion.php si non connecté
 */
function exigerConnexion(): void
{
    if (!estConnecte()) {
        rediriger('connexion.php');
    }
}

/**
 * Si déjà connecté, empêche l'accès aux pages inscription/connexion
 */
function redirigerSiConnecte(): void
{
    if (estConnecte()) {
        rediriger('accueil.php');
    }
}

/**
 * Récupère les informations d'un utilisateur à partir de son identifiant.
 *
 * @param PDO $pdo Connexion à la base de données.
 * @param int $utilisateurId Identifiant de l'utilisateur.
 *
 * @return array|null Les informations de l'utilisateur ou null si introuvable.
 */
function recupererUtilisateurParId(PDO $pdo, int $utilisateurId): ?array
{
    $sql = "
        SELECT
            id,
            prenom,
            nom,
            email
        FROM blog_utilisateurs
        WHERE id = :id
        LIMIT 1
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'id' => $utilisateurId
    ]);

    $utilisateur = $requete->fetch();

    return $utilisateur ?: null;
}

/*
 * Met à jour les informations personnelles d'un utilisateur
*/
function modifierProfil(
    PDO $pdo,
    int $utilisateurId,
    string $prenom,
    string $nom,
    string $email
): bool
{
    $sql = "
        UPDATE blog_utilisateurs
        SET
            prenom = :prenom,
            nom = :nom,
            email = :email
        WHERE id = :id
    ";

    $requete = $pdo->prepare($sql);

    return $requete->execute([
        'id' => $utilisateurId,
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email
    ]);
}

/**
 * Met à jour le mot de passe d'un utilisateur.
 *
 * @param PDO $pdo Connexion à la base de données.
 * @param int $utilisateurId Identifiant de l'utilisateur.
 * @param string $motDePasseHash Mot de passe déjà chiffré.
 *
 * @return bool True si la mise à jour est effectuée.
 */
function modifierMotDePasse(
    PDO $pdo,
    int $utilisateurId,
    string $motDePasseHash
): bool
{
    $sql = "
        UPDATE blog_utilisateurs
        SET mot_de_passe = :mot_de_passe
        WHERE id = :id
    ";

    $requete = $pdo->prepare($sql);

    return $requete->execute([
        'id' => $utilisateurId,
        'mot_de_passe' => $motDePasseHash
    ]);
}