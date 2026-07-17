<?php
session_start();
require "Config.php";
//  catch (PDOException $e) {
//     // Affiche l'erreur réelle à l'écran au lieu de mourir
//     echo "Erreur SQL : " . $e->getMessage();
//     echo "<br>ID Demande : " . $id_demande;
//     echo "<br>ID Etudiant : " . $id_etudiant;
//     exit; 
// }

if (isset($_POST['valider_demande'])) {
    $id_demande = $_POST['id_demande'];
    $id_etudiant = $_POST['id_etudiants'];
    $id_doc = $_POST['id_document_a_associer'];

    try {
        // 1. Lier le document à l'étudiant (grâce à notre nouvelle colonne)
        $updateDoc = $pdo->prepare("UPDATE documents SET id_etudiant_destinataire = ? WHERE id_doc = ?");
        $updateDoc->execute([$id_etudiant, $id_doc]);

        // 2. Mettre à jour le statut de la demande pour qu'elle disparaisse de la liste 'En attente'
        $updateDemande = $pdo->prepare("UPDATE demande SET statut = 'Validé' WHERE id_demande = ?");
        $updateDemande->execute([$id_demande]);

        // Retour à la page précédente avec succès
        header("Location: gestion_demandes.php?msg=success");
        exit;
    } catch (PDOException $e) {
        die("Erreur technique : " . $e->getMessage());
    }
}
?>