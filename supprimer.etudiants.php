<?php
$host = "localhost";
$dbname = "ism_adonai";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification de la présence du paramètre ID dans l'URL
if (isset($_GET['id'])) {
    $id_etudiant = intval($_GET['id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM `etudiants` WHERE `id_etudiant` = ?");
        $stmt->execute([$id_etudiant]);
    } catch (PDOException $e) {
        // En cas d'erreur liée à une contrainte d'intégrité, on alerte en JS avant de rediriger
        $error_msg = "Impossible de supprimer cet étudiant : " . $e->getMessage();
        echo "<script>alert(" . json_encode($error_msg) . "); window.location.href='gestion_etudiants.php';</script>";
        exit();
    }
}

// Redirection systématique vers le panneau principal de gestion
header("Location: gestion_etudiants.php");
exit();
?>