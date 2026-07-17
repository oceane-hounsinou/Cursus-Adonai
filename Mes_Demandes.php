<?php
session_start();
require "Config.php";

// Vérification de la connexion
if (!isset($_SESSION['id_etudiant'])) {
    header("Location: Connexion.php");
    exit();
}

$id_etudiant = $_SESSION['id_etudiant'];

try {
    // Récupération des informations de l'étudiant
    $stmt = $pdo->prepare("
        SELECT nom, prenom, photo
        FROM etudiants
        WHERE id_etudiant = ?
    ");
    $stmt->execute([$id_etudiant]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$etudiant) {
        die("Profil étudiant introuvable.");
    }

    // Récupération des demandes de l'étudiant
    $stmt_demandes = $pdo->prepare("
        SELECT *
        FROM demande
        WHERE id_etudiant = ?
        ORDER BY date_demande DESC
    ");
    $stmt_demandes->execute([$id_etudiant]);
    $liste_demandes = $stmt_demandes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes demandes | ISM ADONAÏ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f5f6fa; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #8B0000; color: white; }
        .logo { padding: 20px; font-weight: 700; font-size: 1.2rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .profile { text-align: center; padding: 25px 0; }
        .profile img { width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px; object-fit: cover; border: 3px solid rgba(255,255,255,0.2); }
        .menu { list-style: none; }
        .menu li a { display: flex; align-items: center; padding: 15px 20px; color: white; text-decoration: none; transition: background 0.3s; }
        .menu li.active { background: #0c3c9b; }
        .content { flex: 1; padding: 30px; }
        .card { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px; font-size: 14px; color: #6b7280; border-bottom: 2px solid #f3f4f6; }
        td { padding: 15px; font-size: 14px; border-bottom: 1px solid #f3f4f6; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .pending { background: #fef3c7; color: #92400e; }
        .valid { background: #dcfce7; color: #166534; }
        .reject { background: #fee2e2; color: #991b1b; }
        .btn-voir { background: #f9fafb; border: 1px solid #d1d5db; padding: 6px 15px; border-radius: 5px; text-decoration: none; color: #374151; font-size: 13px; transition: 0.2s; }
        .btn-voir:hover { background: #e5e7eb; }
    </style>
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="logo">ISM ADONAÏ</div>
        <div class="profile">
            
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
        </div>
        <ul class="menu">
            <li><a href="tableau_de_bord.php"><i class="fas fa-chart-pie"></i> Tableau de bord</a></li>
            <li class="active"><a href="Mes_Demandes.php"><i class="fa-solid fa-folder"></i> Mes demandes</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>

    <main class="content">
        <h2>Mes demandes</h2>
        <br>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Type de demande</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($liste_demandes)): ?>
                        <tr><td colspan="4" style="text-align:center; padding: 20px; color: #666;">Aucune demande enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($liste_demandes as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['type_demande']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($d['date_demande'])); ?></td>
                                <td>
                                    <?php 
                                        $statut = htmlspecialchars($d['statut']);
                                        $classe = ($statut == 'En attente') ? 'pending' : (($statut == 'Validée') ? 'valid' : 'reject');
                                    ?>
                                    <span class="badge <?php echo $classe; ?>">
                                        <?php echo $statut; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="voir_demande.php?id=<?php echo $d['id_demande']; ?>" class="btn-voir">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>