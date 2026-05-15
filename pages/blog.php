<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio de développeur web frontend - Création de sites web modernes et performants">
    <title>DRAME ALGORIX| Développeur Web Frontend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- Navigation -->
         <!-- Require navigation remplacer dans composants/navigation.php -->     
     <?php require '../composants/navigation.php'; ?>


<!--Hero section de la page blog & Actualités-->
    <section class="blog-hero">
        <div class="container">
            <h1>Blog & Actualités</h1>
            <p>
                Découvrez mes derniers articles, conseils pratiques et tendances 
                sur le monde du développement & l'IA, stratégie marketing et transformation digitale.
            </p>
        </div>
    </section>
    
    <section class="blog-empty">
        <div class="container">
            <p>Aucun article disponible pour le moment.</p>
        </div>
    </section>

   
    <!-- Footer -->
        <?php require '../composants/footer.php'; ?>
    
</body>
</html>