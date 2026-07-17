<?php
session_start();
require "Config.php"; 

if (!isset($_SESSION['id_etudiant'])) {
    $_SESSION['id_etudiant'] = 1; 
}
$id_etudiant = $_SESSION['id_etudiant'];

// On récupère les infos de l'étudiant, notamment sa photo
try {
    $sql = "SELECT nom, prenom, photo FROM `etudiants` WHERE id_etudiant = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_etudiant]);
    $etudiant = $stmt->fetch();

    if (!$etudiant) {
        die("Profil étudiant introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
// Récupération des notifications
$sql_notifs = "SELECT id, titre, message, statut, date_notification 
               FROM `notifications` 
               WHERE utilisateur_id = :id 
               ORDER BY date_notification DESC";
$stmt_notifs = $pdo->prepare($sql_notifs);
$stmt_notifs->execute(['id' => $id_etudiant]);
$notifications = $stmt_notifs->fetchAll();

// Calcul dynamique du nombre de non-lus (statut = 0 par exemple)
    $count_non_lus = 0;
    foreach ($notifications as $n) {
        if ($n['statut'] == 0) { // Adaptez '0' selon votre base (ex: 'non lu')
            $count_non_lus++;
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notifications | ISM ADONAÏ</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}

body {
    background: #f5f6fa;
}

/* CONTAINER */
.container {
    display: flex;
    min-height: 100vh;
}

/* ================= ALIGNEMENT SIDEBAR BURGUNDY ================= */
.sidebar {
    width: 260px;
    min-height: 100vh;
    background: #8B0000;
    color: white;
}

.logo {
    padding: 18px;
    font-weight: bold;
    border-bottom: 1px solid rgba(255,255,255,.1);
}

.profil {
    text-align: center;
    padding: 20px;
}

.profil img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 10px;
    object-fit: cover;
}

.profil h4 {
    font-size: 14px;
}

.profil p {
    font-size: 12px;
    opacity: .8;
    margin-top: 2px;
}

/* MENU HARMONISÉ */
.menu {
    list-style: none;
}

.menu li {
    padding: 0;
    font-size: 14px;
}

.menu li a {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Permet de repousser le badge à droite */
    padding: 14px 20px;
    color: white;
    text-decoration: none;
    width: 100%;
    height: 100%;
    transition: background 0.3s;
}

.menu li a span {
    display: flex;
    align-items: center;
}

.menu li a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.menu li a:hover {
    background: rgba(255, 255, 255, 0.1);
}

.menu li.active {
    background: #0c3c9b;
}

.menu li.active a:hover {
    background: #0c3c9b;
}

/* BADGE NOTIFICATION MENU */
.badge {
    background: #ff3b30;
    color: white;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: bold;
}

/* ================= CONTENU ================= */
.content {
    flex: 1;
    padding: 25px 35px;
}

.header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 20px;
}

.header i {
    color: #6b7280;
}

/* ICON HEADER + BADGE */
.icon-notif {
    position: relative;
    font-size: 18px;
    color: #555;
}

.icon-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: #ff3b30;
    color: white;
    font-size: 10px;
    padding: 2px 5px;
    border-radius: 50%;
}

/* TITRE */
.titre {
    margin-top: 15px;
    font-size: 24px;
    color: #1f2937;
}

.chemin {
    color: #6b7280;
    margin: 8px 0 25px;
    font-size: 13px;
}

/* CARD */
.card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 15px;
}

/* NOTIFICATION */
.notification {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.notification:last-child {
    border-bottom: none;
}

.info {
    display: flex;
    align-items: center;
}

.info i {
    color: #f6b100;
    margin-right: 12px;
    font-size: 18px;
}

.texte h5 {
    font-size: 14px;
    color: #333;
}

.texte p {
    font-size: 12px;
    color: #777;
    margin-top: 4px;
}

/* POINT VERT (NON LU) */
.point {
    width: 9px;
    height: 9px;
    background: #28c76f; 
    border-radius: 50%;
}

/* notification validée */
.valide i {
    color: #3379ff;
}

/* bouton */
.bouton {
    text-align: center;
    margin-top: 20px;
}

button {
    background: #072c77;
    color: white;
    border: none;
    padding: 12px 35px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

button:hover {
    background: #051f55;
}
</style>
</head>

<body>

<div class="container">

    <aside class="sidebar">
        <div class="logo">ISM ADONAÏ</div>

        <div class="profil">
            <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/150'; ?>" alt="Profil">
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
            <p>Étudiant</p>
        </div>

        <ul class="menu">
            <li><a href="tableau_de_bord.php"><span><i class="fas fa-chart-pie"></i> Tableau de bord</span></a></li>
            <li><a href="mon_profil.php"><span><i class="fas fa-user"></i> Mon profil</span></a></li>
            <li><a href="nouvelle_demande.php"><span><i class="fa-solid fa-file-circle-plus"></i> Faire une demande</span></a></li>
            <li><a href="Mes_Demandes.php"><span><i class="fa-solid fa-folder"></i> Mes demandes</span></a></li>
            <li><a href="mes_documents.php"><span><i class="fas fa-file"></i> Mes documents</span></a></li>
            <li class="active">
                <a href="notifications.php">
                    <span><i class="fas fa-bell"></i> Notifications</span>
                    <span class="badge">3</span>
                </a>
            </li>
            <li><a href="Deconnexion.php"><span><i class="fas fa-sign-out-alt"></i> Déconnexion</span></a></li>
        </ul>
    </aside>

    <main class="content">

        <span class="badge"><?php echo $count_non_lus; ?></span>

<span class="icon-badge"><?php echo $count_non_lus; ?></span>
        <h2 class="titre">Notifications</h2>
        <div class="chemin">Accueil / Notifications</div>

        <div class="card">
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $n): 
            // Déterminer la classe et l'icône selon le type ou le contenu du message
            $is_valide = (strpos($n['message'], 'validé') !== false);
            $icon = $is_valide ? 'fa-circle-check' : 'fa-bell';
            $class_css = $is_valide ? 'notification valide' : 'notification';
        ?>
        <div class="<?php echo $class_css; ?>">
            <div class="info">
                <i class="fa <?php echo $icon; ?>"></i>
                <div class="texte">
                    <h5><?php echo htmlspecialchars($n['message']); ?></h5>
                    <p><?php echo date('d/m/Y H:i', strtotime($n['date_notification'])); ?></p>
                </div>
            </div>
            <?php if ($n['statut'] == 0): ?>
                <div class="point"></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="padding: 20px; text-align: center;">Aucune notification pour le moment.</p>
    <?php endif; ?>

    <div class="bouton">
        <a href="marquer_notifications.php">
            <button>TOUT MARQUER COMME LU</button>
        </a>
    </div>
</div>

    </main>

</div>

</body>
</html>