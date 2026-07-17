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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouvelle demande</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #f5f6fa;
}

.wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
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

.profile {
    text-align: center;
    padding: 20px;
}

.profile img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 10px;
    object-fit: cover;
}

.profile h4 {
    font-size: 14px;
}

.profile p {
    font-size: 12px;
    opacity: .8;
}

/* Styles du Menu */
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
    padding: 14px 20px;
    color: white;
    text-decoration: none;
    width: 100%;
    height: 100%;
    transition: background 0.3s;
}

.menu li a:visited {
    color: white;
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
    background: #0b3b97;
}

.menu li.active a:hover {
    background: #0b3b97;
}

/* Content */
.content {
    flex: 1;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.header h2 {
    font-size: 22px;
    color: #1f2937;
}

.header-icons i {
    margin-left: 15px;
    color: #555;
}

.breadcrumb {
    color: #777;
    font-size: 13px;
    margin-bottom: 15px;
}

.card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 20px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #374151;
}

select,
textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 10px;
    font-size: 14px;
    outline: none;
}

textarea {
    height: 120px;
    resize: none;
}

.file-box {
    border: 1px solid #d1d5db;
    padding: 8px;
    border-radius: 4px;
    background: white;
}

.note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
}

.btn {
    background: #0a2f87;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 10px;
    transition: 0.2s;
}

.btn:hover {
    background: #08256b;
}
</style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">

        <div class="logo">
            ISM ADONAÏ
        </div>

        <div class="profile">
            <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/150'; ?>" alt="Profil">
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
            <p>Étudiant</p>
        </div>

        <ul class="menu">
            <li><a href="tableau_de_bord.php"><i class="fas fa-chart-pie"></i> Tableau de bord</a></li>
            <li><a href="mon_profil.php"><i class="fas fa-user"></i> Mon profil</a></li>
            <li class="active"><a href="nouvelle_demande.php"><i class="fa-solid fa-file-circle-plus"></i> Faire une demande</a></li>
            <li><a href="Mes_Demandes.php"><i class="fa-solid fa-folder"></i> Mes demandes</a></li>
            <li><a href="mes_documents.php"><i class="fas fa-file"></i> Mes documents</a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>

    </aside>

    <main class="content">

        <div class="header">
            <h2>Nouvelle demande</h2>
            <div class="header-icons">
                <i class="fa-regular fa-bell"></i>
                <i class="fa-solid fa-gear"></i>
            </div>
        </div>

        <div class="breadcrumb">
            Accueil / Faire une demande
        </div>

        <form class="card" action="enregistrer_demande.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="type_demande">Type de demande</label>
                <select name="type_demande" id="type_demande" required>
                    <option value="" disabled selected>Sélectionnez le type de demande</option>
                    <option value="Attestation de scolarité">Attestation de scolarité</option>
                    <option value="Relevé de notes">Relevé de notes</option>
                    <option value="Certificat de réussite">Certificat de réussite</option>
                    <option value="Certificat de réussite">Demande de stage</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" placeholder="Décrivez votre demande en détail..." required></textarea>
            </div>

            <div class="form-group">
                <label for="piece_jointe">Pièces jointes (optionnel)</label>
                <div class="file-box">
                    <input type="file" name="piece_jointe" id="piece_jointe">
                </div>
                <div class="note">
                    Formats acceptés : PDF, JPG, PNG (Max 5 Mo)
                </div>
            </div>

            <button type="submit" class="btn">
                ENVOYER LA DEMANDE
            </button>

        </form>

    </main>

</div>

</body>
</html>