<?php
session_start();
require "Config.php";


if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id = (int)$_GET['id'];
    $statut = $_GET['statut'];

    // Mise à jour sécurisée
    $stmt = $pdo->prepare("UPDATE demande SET statut = :statut WHERE id_demande = :id");
    $stmt->execute(['statut' => $statut, 'id' => $id]);
    
    echo "Succès";
}

// Sécurité : Vérifier si l'admin est connecté
if (!isset($_SESSION['admin'])) {
    header("Location: Connexion.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
$statut_ajax = filter_input(INPUT_GET, 'statut', FILTER_SANITIZE_SPECIAL_CHARS);

if ($id) {
    // Détermination du statut
    if ($statut_ajax) {
        $nouveau_statut = $statut_ajax; // Pour le changement via menu déroulant
    } else {
        $nouveau_statut = ($action === 'valider') ? 'Validée' : 'Refusée';
    }
    
    // Mise à jour en base
    $stmt = $pdo->prepare("UPDATE demande SET statut = :statut WHERE id_demande = :id");
    $stmt->execute(['statut' => $nouveau_statut, 'id' => $id]);

    // Redirection si ce n'est pas une requête AJAX
    if (!$statut_ajax) {
        header("Location: gestion_demandes.php?success=1");
        exit();
    }
}
?>