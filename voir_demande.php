<?php
session_start();
require "Config.php";

// Récupération de l'ID depuis l'URL
$id_demande = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $sql = "SELECT * FROM demande WHERE id_demande = :id AND id_etudiant = :id_etudiant";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_demande, 'id_etudiant' => $_SESSION['id_etudiant']]); // Attention à bien utiliser 'id_etudiant' ou 'id_etudiants' selon votre session
    $demande = $stmt->fetch();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!$demande) { die("Demande introuvable ou accès refusé."); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la demande</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f5f6fa; }
        .card { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; max-width: 600px; margin: auto; }
        h2 { color: #8B0000; margin-bottom: 15px; }
        .info { margin-bottom: 10px; }
        label { font-weight: bold; color: #6b7280; }
        .btn-back { display: inline-block; margin-top: 20px; padding: 10px; background: #8B0000; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Détail de la demande #<?php echo $demande['id_demande']; ?></h2>
        <div class="info"><label>Type :</label> <?php echo htmlspecialchars($demande['type_demande']); ?></div>
        <div class="info"><label>Date :</label> <?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></div>
        <div class="info"><label>Statut :</label> <?php echo htmlspecialchars($demande['statut']); ?></div>
        <div class="info"><label>Description :</label><p><?php echo nl2br(htmlspecialchars($demande['description'])); ?></p></div>
        
        <?php if (!empty($demande['piece_jointe'])): ?>
            <div class="info">
                <label>Pièce jointe :</label>
                <a href="uploads/<?php echo $demande['piece_jointe']; ?>" target="_blank">Télécharger le document</a>
            </div>
        <?php else: ?>
            <p>Aucune pièce jointe associée.</p>
        <?php endif; ?>

        <a href="Mes_Demandes.php" class="btn-back">Retour à mes demandes</a>
    </div>
</body>
</html>