<?php
// Inclure ce fichier en haut de chaque page admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["utilisateur_id"]) || $_SESSION["role"] !== "admin") {
    $_SESSION["erreur"] = "Accès refusé. Veuillez vous connecter en tant qu'administrateur.";
    header("Location: connexion.php");
    exit();
}