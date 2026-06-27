<?php

/**
 * Ajoute un commentaire à un article
 */
function ajouterCommentaire
    (PDO $pdo, 
    int $articleId, 
    int $auteurId,
    string $contenu): bool
{
    $sql = "
        INSERT INTO blog_commentaires 
        (article_id, 
        auteur_id, 
        contenu)
        VALUES (:article_id, :auteur_id, :contenu
        )";

    $requete = $pdo->prepare($sql);

    return $requete->execute([
        'article_id' => $articleId,
        'auteur_id' => $auteurId,
        'contenu' => $contenu
    ]);
}
// Supprimer commentaire
function supprimerCommentaire
    (PDO $pdo, 
    int $commentaireId, 
    int $utilisateurId): bool 
        { $sql = " 
            DELETE FROM 
            blog_commentaires
            WHERE id = :id 
            AND auteur_id = :auteur_id 
            "; 
            
            $requete = $pdo->prepare($sql); 
                return $requete->execute(
                    [ 'id' => $commentaireId, 
                        'auteur_id' => $utilisateurId ]); }
