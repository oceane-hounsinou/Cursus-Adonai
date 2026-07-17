<?php
session_start();
require "Config.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identifiant = trim($_POST["identifiant"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    // Rechercher l'utilisateur par email ou matricule
    $sql = $pdo->prepare("
        SELECT *
        FROM utilisateurs
        WHERE email = ? OR matricule = ?
    ");

    $sql->execute([
        $identifiant,
        $identifiant
    ]);

    $utilisateur = $sql->fetch(PDO::FETCH_ASSOC);


    
    // Vérifier si l'utilisateur existe
    if ($utilisateur) {

        // Vérification du mot de passe
        // Si vos mots de passe sont enregistrés en texte simple
        if ($mot_de_passe == $utilisateur["mot_de_passe"]) {

            // Création des variables de session
            $_SESSION["utilisateur_id"] = $utilisateur["id"];
            $_SESSION["nom"] = $utilisateur["nom"];
            $_SESSION["prenom"] = $utilisateur["prenom"];
            $_SESSION["email"] = $utilisateur["email"];
            $_SESSION["role"] = $utilisateur["role"];


            // Redirection selon le rôle
            if ($utilisateur["role"] == "etudiant") {

                header("Location: tableau_de_bord.php");
                exit();

            } elseif ($utilisateur["role"] == "admin") {

                header("Location: dashboard_admin.php");
                exit();

            } else {

                $_SESSION["erreur"] = "Votre compte ne possède aucun rôle valide.";
                header("Location: connexion.php");
                exit();
            }

        } else {

            $_SESSION["erreur"] = "Mot de passe incorrect.";
            header("Location: connexion.php");
            exit();
        }

    } else {

        $_SESSION["erreur"] = "Matricule ou email introuvable.";
        header("Location: connexion.php");
        exit();
    }

} else {

    // Empêcher l'accès direct au fichier
    header("Location: connexion.php");
    exit();
}
?>