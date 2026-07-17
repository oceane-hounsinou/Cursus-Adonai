<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// ... reste de votre code<?php
session_start();
require "Config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['id_admin'];
    $mdp = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT id_admin, mot_de_passe FROM admins WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $admin['mot_de_passe'] === sha1($mdp)) {
        $_SESSION['id_admin'] = $admin['id_admin'];
        header("Location: profil_admin.php");
        exit();
    } else {
        $_SESSION["erreur"] = "Identifiants incorrects.";
        header("Location: tableau_de_bord_admin.php");
        exit();
    }
} else {
    header("Location: login_admin.php");
    exit();
}
?>