<?php


/**
 * Publier un nouvel article
 */
function publierArticle(
    PDO $pdo,
    int $auteurId,
    string $titre,
    string $contenu,
    ?string $image = null
): int {

    $sql = "
        INSERT INTO blog_articles
        (
            auteur_id,
            titre,
            contenu,
            image_couverture
        )
        VALUES
        (
            :auteur_id,
            :titre,
            :contenu,
            :image
        )
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'auteur_id' => $auteurId,
        'titre' => $titre,
        'contenu' => $contenu,
        'image' => $image
    ]);

    return (int)$pdo->lastInsertId();
}

/**
 * Je recupere tous les articles avec auteur + nombre de commentaires
 */
function recupererTousLesArticles(PDO $pdo): array
{
    $sql = "
        SELECT 
            a.id,
            a.titre,
            a.contenu,
            a.image_couverture,
            a.date_publication,
            u.prenom,
            u.nom,
            COUNT(c.id) AS nombre_commentaires
        FROM blog_articles a
        INNER JOIN blog_utilisateurs u ON a.auteur_id = u.id
        LEFT JOIN blog_commentaires c ON c.article_id = a.id
        GROUP BY a.id, a.titre, a.contenu, a.image_couverture, a.date_publication, u.prenom, u.nom
        ORDER BY a.date_publication DESC
    ";

    $requete = $pdo->query($sql);
    return $requete->fetchAll();
}

/**
 * Retourne un extrait propre du contenu
 */
function extraitArticle(string $contenu, int $limite = 180): string
{
    $texte = strip_tags($contenu);

    if (mb_strlen($texte) <= $limite) {
        return $texte;
    }

    return mb_substr($texte, 0, $limite) . '...';
}
/**
 * Récupère un article par son ID
 */
function recupererArticleParId(PDO $pdo, int $articleId): array
{
    $sql = "
        SELECT 
            a.id,
            a.titre,
            a.contenu,
            a.image_couverture,
            a.date_publication,
            a.auteur_id,
            u.prenom,
            u.nom
        FROM blog_articles a
        INNER JOIN blog_utilisateurs u ON a.auteur_id = u.id
        WHERE a.id = :id
        LIMIT 1
    ";

    $requete = $pdo->prepare($sql);
    $requete->execute(['id' => $articleId]);

    $article = $requete->fetch();
    return $article ?: null;
}

/**
 * Récupère les commentaires d’un article
 */
function recupererCommentairesArticle(PDO $pdo, int $articleId): array
{
    $sql = "
        SELECT 
            c.id,
            c.contenu,
            c.date_commentaire,
            c.auteur_id,
            u.prenom,
            u.nom
        FROM blog_commentaires c
        INNER JOIN blog_utilisateurs u ON c.auteur_id = u.id
        WHERE c.article_id = :article_id
        ORDER BY c.date_commentaire DESC
    ";

    $requete = $pdo->prepare($sql);
    $requete->execute(['article_id' => $articleId]);

    return $requete->fetchAll();
}

/**
 * Récupère tous les articles de l'utilisateur connecté
 */
function recupererArticlesUtilisateur(PDO $pdo, int $utilisateurId): ?array
{
    $sql = "
        SELECT
            a.id,
            a.titre,
            a.image_couverture,
            a.date_publication,
            COUNT(c.id) AS nombre_commentaires
        FROM blog_articles a

        LEFT JOIN blog_commentaires c
            ON c.article_id = a.id

        WHERE a.auteur_id = :utilisateur_id

        GROUP BY
            a.id,
            a.titre,
            a.image_couverture,
            a.date_publication

        ORDER BY a.date_publication DESC
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'utilisateur_id' => $utilisateurId
    ]);

    return $requete->fetchAll();
}


/**
 * Vérifie qu'un article appartient bien à un utilisateur
 */
function recupererArticleUtilisateur(PDO $pdo, int $articleId, int $utilisateurId): ?array
{
    $sql = "
        SELECT *
        FROM blog_articles
        WHERE id = :id
        AND auteur_id = :auteur_id
        LIMIT 1
    ";

    $requete = $pdo->prepare($sql);

    $requete->execute([
        'id' => $articleId,
        'auteur_id' => $utilisateurId
    ]);

    $article = $requete->fetch();

    return $article ?: null;
}


/**
 * Met à jour un article
 */
function modifierArticle(
    PDO $pdo,
    int $articleId,
    string $titre,
    string $contenu,
    ?string $image = null
): bool
{
    if ($image !== null) {

        $sql = "
            UPDATE blog_articles
            SET
                titre = :titre,
                contenu = :contenu,
                image_couverture = :image
            WHERE id = :id
        ";

        $requete = $pdo->prepare($sql);

        return $requete->execute([
            'id' => $articleId,
            'titre' => $titre,
            'contenu' => $contenu,
            'image' => $image
        ]);
    }

    $sql = "
        UPDATE blog_articles
        SET
            titre = :titre,
            contenu = :contenu
        WHERE id = :id
    ";

    $requete = $pdo->prepare($sql);

    return $requete->execute([
        'id' => $articleId,
        'titre' => $titre,
        'contenu' => $contenu
    ]);
}

/**
 * Supprime un article appartenant à l'utilisateur connecté.
 *
 * @param PDO $pdo Connexion à la base de données.
 * @param int $articleId Identifiant de l'article.
 * @param int $utilisateurId Identifiant de l'utilisateur connecté.
 *
 * @return bool True si la suppression a réussi, sinon false.
 */
function supprimerArticle(PDO $pdo, int $articleId, int $utilisateurId): bool
{
    $sql = "
        DELETE FROM blog_articles
        WHERE id = :id
        AND auteur_id = :auteur_id
    ";

    $requete = $pdo->prepare($sql);

    return $requete->execute([
        'id' => $articleId,
        'auteur_id' => $utilisateurId
    ]);
}