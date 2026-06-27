<?php

$host = "127.0.0.1:3307";
$dbname = "Blog_2026";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    ); 

    $pdo->setAttribute(
    PDO::ATTR_DEFAULT_FETCH_MODE,
    PDO::FETCH_ASSOC
);

} catch (PDOException $e) {

    // Puisque le consigne interdit d'afficher les détails de l'erreur au user, on affiche un message générique
    error_log($e->getMessage());
    die("Une erreur est survenue, veuillez ressayer plus tard."); 
}
 
