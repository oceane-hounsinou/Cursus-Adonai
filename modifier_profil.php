<?php
session_start();
require "Config.php"; 

if (!isset($_SESSION['id_etudiant'])) {
    $_SESSION['id_etudiant'] = 1; 
}

$id_etudiant = $_SESSION['id_etudiant'];

// Récupération des données en temps réel 
try {
    $sql = "SELECT e.*, f.nom_filiere, n.nom_niveau 
            FROM `etudiants` e
            LEFT JOIN `filiere` f ON e.filiere = f.id
            LEFT JOIN `niveaux` n ON e.id_niveau = n.id_niveau
            WHERE e.id_etudiant = :id";
            
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
<title>Modifier mon profil</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}
body { background: #f5f6fa; }
.wrapper { display: flex; min-height: 100vh; }

/* Sidebar */
.sidebar { width: 260px; min-height: 100vh; background: #8B0000; color: white; }
.logo { padding: 18px; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,.1); }
.profile-mini { text-align: center; padding: 20px; }
.profile-mini img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; }
.profile-mini h4 { margin-top: 10px; font-size: 14px; }
.profile-mini p { font-size: 12px; opacity: .8; }

/* ================= CORRECTION STYLES DU MENU ================= */
.menu { list-style: none; }
.menu li { padding: 0; font-size: 14px; }
.menu li a { 
    display: flex; 
    align-items: center; 
    padding: 14px 20px; 
    color: white; 
    text-decoration: none; 
    width: 100%; 
    transition: background 0.3s;
}
.menu li a:hover { background: rgba(255, 255, 255, 0.1); }
.menu li.active { background: #0c3c9b; }
.menu li.active a:hover { background: #0c3c9b; }
.menu li i { margin-right: 10px; width: 20px; text-align: center; }

/* Contenu */
.content { flex: 1; padding: 25px; }
.topbar { display: flex; justify-content: space-between; align-items: center; }
.topbar h2 { font-size: 24px; }
.icons i { margin-left: 15px; color: #666; }
.breadcrumb { margin-top: 5px; margin-bottom: 20px; color: #777; font-size: 13px; }

.card { background: white; border-radius: 10px; padding: 30px; border: 1px solid #e5e7eb; }
.profile-header { text-align: center; margin-bottom: 30px; }
.profile-header img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #f3f4f6; }
.profile-header h3 { margin-top: 15px; }

/* Style ajouté pour le bouton d'importation de photo */
.file-upload-btn {
    margin-top: 12px;
    display: inline-block;
}
.file-upload-btn input[type="file"] {
    font-size: 13px;
    color: #555;
    cursor: pointer;
}

.profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.info { display: flex; flex-direction: column; }
.info label { font-size: 13px; color: #666; margin-bottom: 5px; font-weight: 600; }
.info input { padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; font-size: 14px; outline: none; }
.info input[readonly] { background: #f3f4f6; color: #777; cursor: not-allowed; }

.btn-container { margin-top: 30px; text-align: right; }
.btn {
    background: #072c77;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: background 0.2s;
}
.btn:hover { background: #051f55; }
</style>
</head>

<body>

<div class="wrapper">

    <aside class="sidebar">
        <div class="logo">ISM ADONAÏ</div>

        <div class="profile-mini">
            <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/150'; ?>" alt="Profil">
            <h4><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h4>
            <p>Étudiant</p>
        </div>

        <ul class="menu">
            <li><a href="tableau_de_bord.php"><i class="fas fa-chart-pie"></i> Tableau de bord</a></li>
            <li class="active"><a href="mon_profil.php"><i class="fas fa-user"></i> Mon profil</a></li>
            <li><a href="nouvelle_demande.php"><i class="fa-solid fa-file-circle-plus"></i> Faire une demande</a></li>
            <li><a href="Mes_Demandes.php"><i class="fa-solid fa-folder"></i> Mes demandes</a></li>
            <li><a href="mes_documents.php"><i class="fas fa-file"></i> Mes documents</a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>

    <main class="content">
        <div class="topbar">
            <h2>Modifier mon profil</h2>
            <div class="icons">
                <i class="far fa-bell"></i>
                <i class="fas fa-cog"></i>
            </div>
        </div>

        <div class="breadcrumb">Accueil / Modifier mon profil</div>

        <div class="card">
            
            <form action="enregistrer_modification.php" method="POST" enctype="multipart/form-data">
                
                <div class="profile-header">
                    <img src="<?php echo !empty($etudiant['photo']) ? 'uploads/'.$etudiant['photo'] : 'https://i.pravatar.cc/200'; ?>" alt="Profil Header">
                    <h3><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h3>
                    <p style="margin-bottom: 10px;">Étudiant</p>
                    
                    <div class="file-upload-btn">
                        <input type="file" name="nouvelle_photo" accept="image/png, image/jpeg, image/jpg">
                    </div>
                </div>

                <div class="profile-grid">

                    <div class="info">
                        <label>Nom complet</label>
                        <input type="text" name="nom_complet" value="<?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?>" required>
                    </div>

                    <div class="info">
                        <label>Matricule</label>
                        <input type="text" value="<?php echo htmlspecialchars($etudiant['matricule']); ?>" readonly>
                    </div>

                    <div class="info">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($etudiant['email']); ?>" required>
                    </div>

                    <div class="info">
                        <label>Téléphone *</label>
                        <input type="text" name="telephone" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" required>
                    </div>

                    <div class="info">
                        <label>Filière</label>
                        <input type="text" value="<?php echo (is_array($etudiant) && isset($etudiant['nom_filiere'])) ? htmlspecialchars($etudiant['nom_filiere']) : (isset($etudiant['filiere']) ? htmlspecialchars($etudiant['filiere']) : 'Non définie'); ?>" readonly>
                    </div>

                    <div class="info">
                        <label>Niveau</label>
                        <input type="text" value="<?php echo (is_array($etudiant) && isset($etudiant['nom_niveau'])) ? htmlspecialchars($etudiant['nom_niveau']) : (isset($etudiant['niveau']) ? htmlspecialchars($etudiant['niveau']) : 'Non défini'); ?>" readonly>
                    </div>

                    <div class="info">
                        <label>Date de naissance</label>
                        <input type="date" name="date_naissance" value="<?php echo (is_array($etudiant) && isset($etudiant['date_naissance'])) ? htmlspecialchars($etudiant['date_naissance']) : ''; ?>">
                    </div>

                    <div class="info">
                        <label>Lieu de naissance / Adresse</label>
                        <input type="text" name="lieu_naissance" value="<?php echo (is_array($etudiant) && isset($etudiant['lieu_naissance'])) ? htmlspecialchars($etudiant['lieu_naissance']) : ''; ?>">
                    </div>

                </div>

                <div class="btn-container">
                    <button class="btn" type="submit" name="enregistrer">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </main>

</div>

</body>
</html>