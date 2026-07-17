<?php
session_start();
require "Config.php";

if (!isset($_SESSION['utilisateur_id'])) {
    die("Accès refusé");
}

if (!isset($_GET['file'])) {
    die("Fichier introuvable");
}

$file = basename($_GET['file']);
$user_id = $_SESSION['utilisateur_id'];

// vérifier propriété du fichier
$req = $pdo->prepare("
    SELECT * FROM documents 
    WHERE nom_fichier = ? AND utilisateur_id = ?
");
$req->execute([$file, $user_id]);

if ($req->rowCount() == 0) {
    die("Accès interdit");
}

$path = "documents/" . $file;

if (!file_exists($path)) {
    die("Fichier introuvable");
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$file.'"');
header('Content-Length: ' . filesize($path));

readfile($path);
exit();
?>