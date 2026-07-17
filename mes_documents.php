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
// ... votre code session et récupération de profil déjà existant ...

// Récupération des documents associés à cet étudiant
try {
    $sql_docs = "SELECT d.id_doc, d.nom_document, d.date_ajout, dem.type_demande 
                 FROM documents d
                 JOIN demande dem ON d.id_doc = dem.id_doc_associe 
                 WHERE d.id_etudiant_destinataire = :id_etudiant
                 ORDER BY d.date_ajout DESC";
    $stmt_docs = $pdo->prepare($sql_docs);
    $stmt_docs->execute(['id_etudiant' => $id_etudiant]);
    $documents = $stmt_docs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $documents = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mes documents | ISM ADONAÏ</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

/* Corps */
body {
    background: #f4f6f9;
}

/* Conteneur */
.container {
    display: flex;
    min-height: 100vh;
}

/* ================= MENU ================= */
.sidebar {
    width: 260px;
    background: #8B0000; 
    color: white;
    padding: 20px 0; 
}

.logo {
    text-align: center;
    font-weight: bold;
    margin-bottom: 25px;
    font-size: 18px;
    padding: 0 15px;
}

.photo {
    text-align: center;
    margin-bottom: 25px;
    padding: 0 15px;
}

.photo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
}

.photo h4 {
    margin-top: 10px;
    font-size: 15px;
}

.menu {
    list-style: none;
}

.menu li {
    margin: 5px 0;
}

.menu li a {
    color: white;
    text-decoration: none;
    font-size: 15px;
    display: flex;
    align-items: center;
    padding: 14px 20px;
    transition: 0.3s;
    width: 100%;
}

.menu li a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.menu li a:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Style de l'onglet actif (Mes documents) */
.menu li.active a {
    background: white;
    color: #003b7a;
    font-weight: bold;
}

.menu li.active a:hover {
    background: white;
}

/* ================= CONTENU ================= */
.content {
    flex: 1;
    padding: 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.icons i {
    margin-left: 15px;
    color: #777;
}

h2 {
    margin-top: 25px;
}

.breadcrumb {
    color: #777;
    margin: 8px 0 25px;
}

.card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    background: #f5f5f5;
    padding: 15px;
}

td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

.download {
    background: #e7f1ff;
    color: #0066ff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.2s;
}

.download:hover {
    background: #0066ff;
    color: white;
}
</style>
</head>
<body>

<div class="container">

    <div class="sidebar">
        <div class="logo">ISM ADONAÏ</div>

        <div class="photo">
            <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/150'; ?>" alt="photo">
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
        </div>

        <ul class="menu">
            <li><a href="tableau_de_bord.php"><i class="fas fa-chart-pie"></i> Tableau de bord</a></li>
            <li><a href="mon_profil.php"><i class="fas fa-user"></i> Mon profil</a></li>
            <li><a href="nouvelle_demande.php"><i class="fa-solid fa-file-circle-plus"></i> Faire une demande</a></li>
            <li><a href="Mes_Demandes.php"><i class="fa-solid fa-folder"></i> Mes demandes</a></li>
            <li class="active"><a href="mes_documents.php"><i class="fas fa-file"></i> Mes documents</a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <div></div>
            <div class="icons">
                <i class="fa fa-bell"></i>
                <i class="fa fa-gear"></i>
            </div>
        </div>

        <h2>Mes documents</h2>
        <p class="breadcrumb">Accueil / Mes documents</p>

        <div class="card">
    <table>
        <tr>
            <th>Document</th>
            <th>Type de demande</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php if (empty($documents)): ?>
            <tr><td colspan="4" style="text-align:center;">Aucun document disponible.</td></tr>
        <?php else: ?>
            <?php foreach ($documents as $doc): ?>
                <tr>
                    <td><?= htmlspecialchars($doc['nom_document']) ?></td>
                    <td><?= htmlspecialchars($doc['type_demande']) ?></td>
                    <td><?= date('d/m/Y', strtotime($doc['date_ajout'])) ?></td>
                    <td>
                        <a href="download.php?id=<?= $doc['id_doc'] ?>" class="download" style="text-decoration:none;">
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
    </div>

</div>

</body>
</html>