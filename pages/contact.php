<?php

session_start();
require_once '../config/connexion.php';
require_once '../composants/fonctions.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez DRAME ALGORIX - Développeur web frontend pour vos projets">
    <title>Contact | DRAME ALGORIX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- Navigation -->
        <?php require '../composants/navigation.php'; ?>
        
            <!-- TRAITEMENT DES FORMULAIRES -->
             <!-- Formulaire de contact -->

         <?php
            // Initialisation des variables
                $name = "";
                $email = "";
                $subject = "";

                $erreurs = [];
                $succes = "";
                
                // Génération du token CSRF
                $csrfToken = genererTokenCSRF();

                if (isset($_POST["contact_submit"])) {

    /*
    |--------------------------------------------------------------------------
    | Vérification CSRF
    |--------------------------------------------------------------------------
    */
    if (
        !isset($_POST['csrf_token']) ||
        !verifierTokenCSRF($_POST['csrf_token'])
    ) {

        $erreurs["csrf"] = "Jeton CSRF invalide.";

    } else {

        // Récupération
        $name = nettoyer($_POST["name"]);
        $email = nettoyer($_POST["email"]);
        $subject = nettoyer($_POST["subject"]);

        // Validation
        if (empty($_POST["name"])) {
            $erreurs["name"] = "Le nom est obligatoire.";
        }

        if (!champ_requis($_POST["email"])) {
            $erreurs["email"] = "L'adresse e-mail est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs["email"] = "L'adresse e-mail est invalide.";
        }

        if (!champ_requis($_POST["subject"])) {
            $erreurs["subject"] = "Le sujet ne peut pas être vide.";
        }

        /*
        |--------------------------------------------------------------------------
        | Insertion en base
        |--------------------------------------------------------------------------
        */
        if (empty($erreurs)) {

            $sql = "
                INSERT INTO messages_contact (
                    nom,
                    email,
                    message
                )
                VALUES (
                    :nom,
                    :email,
                    :message
                )
            ";

            $requete = $pdo->prepare($sql);

            $requete->execute([
                'nom' => $name,
                'email' => $email,
                'message' => $subject
            ]);

            $succes = "Votre message a été envoyé avec succès !";
            // Réinitialisation des champs apres succès
            $name = "";
            $email = "";
            $subject = "";
        }
    }
}
            ?>
            <!-- Traitement du formulaire projet -->
    
        <?php
            // Variables du projet

            $project_name = "";
            $project_email = "";
            $project_type = "";
            $project_budget = "";
            $project_description = "";

            $project_erreurs = [];
            $project_succes = "";
            $demande = []; 

                
                    if (isset($_POST["project_submit"])) {

                        // Vérification CSRF
                        if (
                            !isset($_POST['csrf_token']) ||
                            !verifierTokenCSRF($_POST['csrf_token'])
                        ) {
                            $project_erreurs["csrf"] = "Jeton CSRF invalide.";
                        } else {
                            // Le token est valide, on peut continuer le traitement
                            $project_name = nettoyer($_POST["project_name"]);
                            $project_email = nettoyer($_POST["project_email"]);
                            $project_type = nettoyer($_POST["project_type"]);
                            $project_budget = nettoyer($_POST["project_budget"]);
                            $project_description = nettoyer($_POST["project_description"]);

                // Nom
               if (!champ_requis($_POST["project_name"])){
                 $project_erreurs["project_name"]="Le nom est oblligatoire.";
               }

               // Email
               if(!champ_requis($_POST["project_email"])){
                    $project_erreurs["project_email"] = "L'email est obligatoire.";
               
                } else if (!filter_var($project_email, FILTER_VALIDATE_EMAIL)) {
                    $project_erreurs ["project_email"] = "Adresse email invalide.";

               }else {
                    $project_email = nettoyer($_POST["project_email"]);
               }

               // Type projet 
               if (!champ_requis($_POST["project_type"])){
                    $project_erreurs["project_type"] = "Veuillez sélectionner un type de projet.";
               }

               // Description 
               if (!champ_requis($_POST["project_description"])) {
                    $project_erreurs ["project_description"]="La description est obligatoire.";
               }

               // Si aucune erreur
                 if (empty($project_erreurs)) {
                
                /*-- Tableau associatif transfomé pour affiher dans la BASE DE DONNEE */
                    $sql = "
                    INSERT INTO demandes_projet (
                        nom,
                        email,
                        type_projet,
                        description,
                        budget
                    )
                    VALUES (
                        :nom,
                        :email,
                        :type_projet,
                        :description,
                        :budget
                    )
                ";

                $requete = $pdo->prepare($sql);

                $requete->execute([
                    'nom' => $project_name,
                    'email' => $project_email,
                    'type_projet' => $project_type,
                    'description' => $project_description,
                    'budget' => $project_budget
                ]);

                // Message succès envoie
                $project_succes = "Votre demande de projet a été envoyée avec succès.";
                 //Reinitialisation des champs apres succès
                $project_name = "";
                $project_email = "";
                $project_type = "";
                $project_budget = "";
                $project_description = "";
            }     
         }
        }      
        ?>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <span class="page-badge animate-fade-in">Contact</span>
            <h1 class="page-title animate-fade-in-up">Travaillons Ensemble</h1>
            <p class="page-subtitle animate-fade-in-up delay-1">
                Une idée de projet ? Une question ? N&apos;hésitez pas à me contacter.
            </p>
        </div>
    </header>


    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">

            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info" data-animate>
                    <h2 class="contact-info-title">Coordonnées</h2>
                    <p class="contact-info-description">
                        Je suis disponible pour des projets freelance, des collaborations 
                        ou simplement pour échanger sur vos idées.
                    </p>

                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="M22 6l-10 7L2 6"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span class="contact-label">Email</span>
                                <a href="mailto:contact@dramealgorix.com">contact@dramealgorix.com</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span class="contact-label">Téléphone</span>
                                <a href="tel:+221781622782">+221 78 162 27 82</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span class="contact-label">Localisation</span>
                                <span>Dakar, Sénégal</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-social">
                        <h3>Retrouvez-moi sur</h3>
                        <div class="social-links-large">
                            <a href="https://github.com" target="_blank" rel="noopener" class="social-link-large" aria-label="GitHub">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                <span>GitHub</span>
                            </a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener" class="social-link-large" aria-label="LinkedIn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <span>LinkedIn</span>
                            </a>
                            <a href="https://twitter.com" target="_blank" rel="noopener" class="social-link-large" aria-label="Twitter">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span>Twitter</span>
                            </a>
                        </div>
                    </div>

                    <div class="availability-badge">
                        <span class="status-dot"></span>
                        <span>Disponible pour de nouveaux projets</span>
                    </div>
                </div>

                <!-- Formulaire de contact -->
                <div class="contact-forms" data-animate>
                    <!-- Contact Form Rapide -->
                    <div class="form-card" id="contact-form-card">
                        <h3 class="form-title">Envoyez-moi un message</h3>

                        <!-- Affichage message succès  -->
                        <?php if (!empty($succes)) : ?>
                            <div class="success-message">
                                <?= $succes ?>
                            </div>
                        <?php endif; ?>
                              
                        <form class="contact-form" 
                            id="contact-form" 
                            method ="POST"
                            novalidate>
                                <!-- Champ caché pour le token CSRF -->
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= echapper($csrfToken) ?>"
>
                            <div class="form-group">
                                <label for="name">Nom complet *</label>
                                <input type="text"
                                 id="name"
                                 name="name" 
                                 value="<?= echapper($name) ?>"
                                 required placeholder="Votre nom">

                                    <span class="error">
                                        <?= $erreurs["name"] ?? "" ?>
                                    </span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email"
                                 id="email" 
                                 name="email" 
                                 value="<?= echapper($email) ?>"
                                 required placeholder="votre@email.com">
                                    <span class="error">
                                        <?= $erreurs["email"] ?? "" ?>
                                    </span>
                            </div>
                            <div class="form-group">
                                <label for="subject">Sujet</label>
                                <input type="text"
                                 id="subject" 
                                 name="subject"
                                value="<?= echapper($subject) ?>"
                                 required placeholder="Sujet de votre message">
                                 <span class="error">
                                    <?= $erreurs["subject"] ?? "" ?>
                                </span>

                            </div>
                
                            <button type="submit"
                                name ="contact_submit"
                                class="btn btn-primary btn-full">
                                <span>Envoyer le message</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 2L11 13"/>
                                    <path d="M22 2l-7 20-4-9-9-4 20-7z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Soumettre Formulaire projet -->
                    <div class="form-card" id="project-form-card">
                        <h3 class="form-title">Demande de Projet</h3>
                            <!-- Affichage message succès -->
                        <?php if (!empty($project_succes)) : ?>
                            <div class="success-message">
                                <?= $project_succes ?>
                            </div>
                        <?php endif; ?>

                        <p class="form-subtitle">Vous avez un projet en tête ? Décrivez-le moi.</p>
                        <form class="project-form" 
                            id="project-form"
                            method ="POST"
                            novalidate>
                            <div class="form-row">
                                <div class="form-group">
                                    <!-- Champ caché pour le token CSRF -->
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= echapper($csrfToken) ?>"
                                    >
                                    <label for="project-name">Nom *</label>
                                    <input type="text" 
                                        id="project-name" 
                                        name="project_name" 
                                        value="<?= echapper($project_name) ?>"
                                        required placeholder="Votre nom">
                                        <span class="error">
                                            <?= $project_erreurs["project_name"] ?? "" ?>
                                        </span>
                                </div>
                                <div class="form-group">
                                    <label for="project-email">Email *</label>
                                    <input type="email" 
                                        id="project-email" 
                                        name="project_email" 
                                        value="<?= echapper($project_email) ?>"
                                        required placeholder="votre@email.com">
                                        <span class="error">
                                            <?= $project_erreurs["project_email"] ?? "" ?>
                                        </span>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label for="project-type">Type de projet *</label>
                                <select id="project-type" name="project_type" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="website">
                                        <?= ($project_type === 'website') ? 'selected' : '' ?>
                                        Site Web
                                    </option>

                                    <option value="webapp"
                                        <?= ($project_type === 'webapp') ? 'selected' : '' ?>>
                                        Application Web
                                    </option>

                                    <option value="ecommerce"
                                        <?= ($project_type === 'e-commerce') ? 'selected' : '' ?>>
                                        E-commerce
                                    </option>

                                    <option value="redesign"
                                        <?= ($project_type === 'redesign') ? 'selected' : '' ?>>
                                        Refonte de site
                                    </option>

                                    <option value="Marketing digital" 
                                        <?= ($project_type === 'marketing digital') ? 'selected' : '' ?>>  
                                        Marketing digital           
                                    </option>

                                    <option value="other">Autre</option>
                                </select>
                                    <span class="error">
                                        <?= $project_erreurs["project_type"] ?? "" ?>
                                    </span>
                            </div>
                            <div class="form-group">
                                <label for="project-budget">Budget estimé</label>
                                <select id="project-budget" name="project_budget">
                                    <option value="">Sélectionnez un budget</option>
                                    <option 
                                        <?= ($project_budget === 'Moins de 299 000 FCFA') ? 'selected' : '' ?>>
                                        Moins de 299 000 FCFA
                                    </option>
                                    <option 
                                        <?= ($project_budget === '399 000 FCFA - 699 000FCFA') ? 'selected' : '' ?>>
                                        399 000 FCFA - 699 000FCFA
                                    </option>
                                    <option
                                        <?= ($project_budget === '700 000 FCFA FCFA - 999 000 FCFA') ? 'selected' : '' ?>>
                                        700 000 FCFA FCFA - 999 000 FCFA
                                    </option>
                                    <option
                                        <?= ($project_budget === 'Plus de 1 000 000 FCFA') ? 'selected' : '' ?>>
                                        Plus de 1 000 000 FCFA
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project-description">Description du projet *</label>
                                <textarea id="project-description"
                                    name="project_description" rows="6" 
                                    required placeholder="Décrivez votre projet, vos besoins spécifiques..."><?= $project_description?></textarea>
                                    
                                    <span class="error">
                                        <?= $project_erreurs["project_description"] ?? "" ?>
                                    </span>
                            </div>
                            <button type="submit" 
                                name="project_submit"
                                class="btn btn-primary btn-full">
                                <span>Soumettre ma demande</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
                            <!--Recapitulatif du formulaire -->

    <?php if (!empty($demande)) : ?>

        <div class="project-summary">

            <h3>Récapitulatif de votre demande</h3>

            <p><strong>Nom :</strong> <?= $demande["nom"] ?></p>

            <p><strong>Email :</strong> <?= $demande["email"] ?></p>

            <p><strong>Type :</strong> <?= $demande["type_projet"] ?></p>

            <p><strong>Budget :</strong> <?= $demande["budget"] ?></p>

            <p><strong>Description :</strong> <?= $demande["description"] ?></p>

        </div>

    <?php endif; ?>

    <!-- Footer -->
        <?php require '../composants/footer.php'; ?>

    <!-- Success Modal -->
        <!-- Partie supprimé pour evite le conflit avec le PHP qui va gérer les formulaire -->
      

    <script src="../js/script.js"></script>
*/
</body>
</html>
