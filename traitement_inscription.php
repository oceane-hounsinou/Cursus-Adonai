<?php
session_start();
require "Config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {

        $sql = "INSERT INTO etudiants 
        (matricule, nom, prenom, sexe, date_naissance, lieu_naissance, nationalite, telephone, email, filiere, niveau, motpasseetu) 
        VALUES 
        (:mat, :nom, :pre, :sexe, :dn, :ln, :nat, :tel, :email, :fil, :niv, :pass)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':mat'   => $_POST['matricule'],
            ':nom'   => $_POST['nom'],
            ':pre'   => $_POST['prenom'],
            ':sexe'  => $_POST['sexe'],
            ':dn'    => $_POST['date_naissance'],
            ':ln'    => $_POST['lieu_naissance'],
            ':nat'   => $_POST['nationalite'],
            ':tel'   => $_POST['telephone'],
            ':email' => $_POST['email'],
            ':fil'   => $_POST['filiere'],
            ':niv'   => $_POST['niveau'],
            ':pass'  => sha1($_POST['password'])
        ]);

        // ID du nouvel étudiant
        $_SESSION['id_etudiant'] = $pdo->lastInsertId();

        // IMPORTANT : un seul header + exit
        header("Location: tableau_de_bord.php");
        exit();

    } catch (PDOException $e) {
        die("Erreur inscription : " . $e->getMessage());
    }

} else {
    header("Location: inscription_etudiant.php");
    exit();
}