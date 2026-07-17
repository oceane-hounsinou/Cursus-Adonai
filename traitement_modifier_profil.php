<?php
session_start();
require "Config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: Connexion.php");
    exit();
}

// Vérifier si le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ID de l'étudiant connecté
    $id_etudiant = $_SESSION['utilisateur_id'];

    // Récupération des données du formulaire
    $nom_complet = htmlspecialchars($_POST['nom_complet']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $filiere = htmlspecialchars($_POST['filiere']);
    $niveau = htmlspecialchars($_POST['niveau']);
    $date_naissance = $_POST['date_naissance'];
    $adresse = htmlspecialchars($_POST['adresse']);

    // Mise à jour de la base de données
    $requete = $pdo->prepare("
        UPDATE utilisateurs 
        SET 
            nom_complet = ?,
            email = ?,
            telephone = ?,
            filiere = ?,
            niveau = ?,
            date_naissance = ?,
            adresse = ?
        WHERE id = ?
    ");

    $modification = $requete->execute([
        $nom_complet,
        $email,
        $telephone,
        $filiere,
        $niveau,
        $date_naissance,
        $adresse,
        $id_etudiant
    ]);

    // Vérifier si la modification est réussie
    if ($modification) {
        header("Location: enregistrer_modifications.php");
        exit();
    } else {
        echo "Erreur lors de la modification du profil.";
    }

} else {
    // Accès direct au fichier interdit
    header("Location: mon_profil.php");
    exit();
}
?>