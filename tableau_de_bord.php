<?php
session_start();
require "Config.php"; 
// 1. Identification de l'étudiant connecté (ID 1 par défaut si aucune session n'est active)
if (!isset($_SESSION['id_etudiant'])) {
    header("Location: login_etudiants.php");
    exit();
}
echo "ID connecté : " . $_SESSION['id_etudiant'];
$id_etudiant = $_SESSION['id_etudiant'];

$sql = "SELECT nom, prenom, photo FROM `etudiants` WHERE id_etudiant = :id_etudiant";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_etudiant' => $id_etudiant]);
$etudiant = $stmt->fetch();
// 2. Récupération des statistiques dynamiques depuis la table 'Demande' (colonne id_etudiants)
$sql_stats = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'En attente' THEN 1 ELSE 0 END) as en_attente,
                SUM(CASE WHEN statut = 'Validée' THEN 1 ELSE 0 END) as valides,
                SUM(CASE WHEN statut = 'Rejetée' THEN 1 ELSE 0 END) as rejetes
              FROM `demande` 
              WHERE id_etudiant = :id_etudiant";
$stmt_stats = $pdo->prepare($sql_stats);
$stmt_stats->execute(['id_etudiant' => $id_etudiant]);
$stats = $stmt_stats->fetch();
// Requête pour récupérer les 5 dernières demandes
$sql_recentes = "SELECT type_demande, date_demande, statut 
                 FROM `demande` 
                 WHERE id_etudiant = :id_etudiant 
                 ORDER BY date_demande DESC LIMIT 5";
$stmt_recentes = $pdo->prepare($sql_recentes);
$stmt_recentes->execute(['id_etudiant' => $id_etudiant]);
$recentes = $stmt_recentes->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Étudiant - ISM ADONAÏ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f8fafc; color: #334155; }
        .container { display: flex; min-height: 100vh; }

        /* Sidebar Rouge Bordeaux Classique */
        .sidebar { width: 260px; background: #7a0000; color: white; }
        .logo { padding: 25px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .logo h2 { font-size: 18px; color: white; letter-spacing: 1px; }
        .profile { text-align: center; padding: 25px 0; background: #600000; }
        .profile img { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #8B0000; }
        .profile h4 { margin-top: 10px; color: white; font-size: 15px; }
        .profile p { font-size: 12px; color: #e0e0e0; }

        .menu { list-style: none; margin-top: 10px; }
        .menu li { padding: 14px 25px; cursor: pointer; transition: 0.3s; }
        .menu li a { color: #f1f1f1; text-decoration: none; display: flex; align-items: center; gap: 12px; font-size: 14px; }
        .menu li:hover, .menu li.active { background: #4a0000; color: white; }
        .menu li.active a { color: white; font-weight: 500; }

        /* Main Content */
        .main { flex: 1; padding: 30px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .topbar h2 { font-size: 22px; color: #1e293b; }
        .top-icons i { margin-left: 20px; color: #64748b; cursor: pointer; }

        /* Stats Cards */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .card { background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 1px 3px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
        .icon { width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; }
        .blue { background: #3b82f6; } .orange { background: #f59e0b; } .green { background: #22c55e; } .red { background: #ef4444; }
        .card h3 { font-size: 24px; color: #1e293b; }
        .card p { color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 600; }

        /* Panels & Table */
        .content { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 25px; }
        .panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
        .panel h3 { margin-bottom: 20px; font-size: 16px; color: #334155; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding-bottom: 15px; color: #94a3b8; font-size: 12px; text-transform: uppercase; }
        td { padding: 15px 0; border-top: 1px solid #f1f5f9; font-size: 14px; }
        
        .pending { color: #d97706; font-weight: 600; font-size: 13px; }
        .valid { color: #059669; font-weight: 600; font-size: 13px; }
        .rejected { color: #dc2626; font-weight: 600; font-size: 13px; }

        .notification { display: flex; gap: 12px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #475569; }
        .view-all { text-align: center; margin-top: 15px; }
        .view-all a { text-decoration: none; color: #2563eb; font-size: 13px; font-weight: 500; }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <div class="logo"><h2>ISM ADONAÏ</h2></div>
        <div class="profile">
            <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/100'; ?>" alt="Photo de profil">
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
            <p>Étudiant</p>
        </div>
        <ul class="menu">
            <li class="active"><a href="tableau_de_bord.php"><i class="fas fa-chart-pie"></i> Tableau de bord</a></li>
            <li><a href="mon_profil.php"><i class="fas fa-user"></i> Mon profil</a></li>
            <li><a href="nouvelle_demande.php"><i class="fa-solid fa-file-circle-plus"></i> Faire une demande</a></li>
            <li><a href="Mes_Demandes.php"><i class="fa-solid fa-folder"></i> Mes demandes</a></li>
            <li><a href="mes_documents.php"><i class="fas fa-file"></i> Mes documents</a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h2>Tableau de bord</h2>
            <div class="top-icons"><i class="fas fa-bell"></i><i class="fas fa-cog"></i></div>
        </div>

        <div class="stats">
    <div class="card"><div class="icon blue"><i class="fas fa-file-alt"></i></div><div><p>Total demandes</p><h3><?php echo $stats['total']; ?></h3></div></div>
    <div class="card"><div class="icon orange"><i class="fas fa-clock"></i></div><div><p>En attente</p><h3><?php echo $stats['en_attente']; ?></h3></div></div>
    <div class="card"><div class="icon green"><i class="fas fa-check"></i></div><div><p>Validées</p><h3><?php echo $stats['valides']; ?></h3></div></div>
    <div class="card"><div class="icon red"><i class="fas fa-times"></i></div><div><p>Rejetées</p><h3><?php echo $stats['rejetes']; ?></h3></div></div>
</div>

        <div class="content">
            <div class="panel">
                <h3>Demandes récentes</h3>
               <table>
    <tr><th>Type de demande</th><th>Date</th><th>Statut</th></tr>
    <?php foreach ($recentes as $row): 
        // Détermination de la classe CSS selon le statut
        $class = ($row['statut'] == 'Validée') ? 'valid' : (($row['statut'] == 'Rejetée') ? 'rejected' : 'pending');
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['type_demande']); ?></td>
        <td><?php echo date('d/m/Y', strtotime($row['date_demande'])); ?></td>
        <td class="<?php echo $class; ?>"><?php echo htmlspecialchars($row['statut']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
            </div>

            <div class="panel">
                <h3>Notifications</h3>
                <div class="notification"><i class="fas fa-check-circle" style="color:#22c55e"></i><div>Votre demande d'attestation est en cours de traitement.</div></div>
                <div class="notification"><i class="fas fa-check-circle" style="color:#22c55e"></i><div>Votre relevé de notes a été validé.</div></div>
                <div class="notification"><i class="fas fa-file-alt" style="color:#f59e0b"></i><div>Votre demande de stage est en attente.</div></div>
                <div class="view-all"><a href="notifications.php">Voir toutes les notifications</a></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>