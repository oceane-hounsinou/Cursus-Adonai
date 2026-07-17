<?php
session_start();
require "Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifiant = trim($_POST['identifiant']);
    $mdp = $_POST['mot_de_passe'];

    try {

        // 🔎 Recherche par email OU matricule
        $stmt = $pdo->prepare("
            SELECT * FROM etudiants 
            WHERE email = :id OR matricule = :id
        ");
        $stmt->execute(['id' => $identifiant]);
        $etudiant = $stmt->fetch();

        if (!$etudiant) {
            $_SESSION["erreur"] = "Compte introuvable.";
            header("Location: login_etudiants.php");
            exit();
        }

        // 🔐 Vérification mot de passe (TON CAS = SHA1)
        if (sha1($mdp) === $etudiant['motpasseetu']) {

            $_SESSION['id_etudiant'] = $etudiant['id_etudiant'];

            header("Location: tableau_de_bord.php");
            exit();

        } else {

            $_SESSION["erreur"] = "Mot de passe incorrect.";
            header("Location: login_etudiants.php");
            exit();
        }

    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}