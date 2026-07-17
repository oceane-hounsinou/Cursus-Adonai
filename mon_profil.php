<?php
// Empêcher le navigateur de mettre la page en cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// ... reste de votre code
session_start();
require "Config.php"; 

// Si la session n’existe pas, on force temporairement à 1 pour les tests
if (!isset($_SESSION['id_etudiant'])) {
    $_SESSION['id_etudiant'] = 1; 
}

$id_etudiant = $_SESSION['id_etudiant'];

// Récupération des données réelles de l’étudiant connecté
try {
    // Requête simplifiée : pas besoin de LEFT JOIN si les noms sont déjà dans la table etudiants
// Requête SQL sans jointure sur la table 'niveaux'
$sql = "SELECT * FROM `etudiants` WHERE id_etudiant = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_etudiant]);
$etudiant = $stmt->fetch();
            
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
<title>Mon Profil</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", sans-serif;
}
body { background: #f5f6fa; }
.wrapper { display: flex; min-height: 100vh; }

/* Encadré / Sidebar */
.sidebar { width: 260px; min-height: 100vh; background: #8B0000; color: white; }
 /* Conteneur statique du logo (pas de lien) */
        .logo-block {
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none;
        }

        .logo-icon-wrapper {
            background: #ffffff;
            color: #9b0000;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 22px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .logo-main {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-sub {
            color: #ff5500;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
.profile-mini { text-align: center; padding: 20px; }
.profile-mini img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; }
.profile-mini h4 { margin-top: 10px; font-size: 14px; }
.profile-mini p { font-size: 12px; opacity: .8; }

/* ================= CORRECTION STYLES DU MENU ================= */
.menu { list-style: none; }
.menu li { padding: 0; } /* Padding déplacé sur le lien pour maximiser la zone cliquable */

.menu li a { 
    display: flex; 
    align-items: center; 
    padding: 14px 20px; 
    color: white;             /* Écritures en blanc */
    text-decoration: none;    /* Aucun soulignement */
    width: 100%; 
    height: 100%; 
    transition: background 0.3s;
}

/* Conserve les écritures blanches même après clic */
.menu li a:visited { color: white; }

/* Effet de survol */
.menu li a:hover { background: rgba(255, 255, 255, 0.1); }

/* Style de l'onglet actif (Mon profil) */
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
.profile-header { text-align: center; margin-bottom: 30px; }
.profile-header img { 
    width: 120px; 
    height: 120px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 3px solid #f3f4f6; /* Ajoute une bordure pour mieux voir l'espace */
    display: block;            /* Important pour le centrage */
    margin: 0 auto;            /* Centre l'image */
}
.profile-header h3 { margin-top: 15px; }
.profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.info { display: flex; flex-direction: column; }
.info label { font-size: 13px; color: #666; margin-bottom: 5px; }
.info input { padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb; color: #333; }

.btn-container { margin-top: 30px; text-align: right; }
.btn {
    background: #072c77;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}
.btn:hover { background: #051f55; }
</style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">
        <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark menu">
        <div class="container">
            
            <!-- Bloc Logo épuré (Élément fixe, pas de lien) -->
            <div class="logo-block">
                <div class="logo-icon-wrapper">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-main">Adonaï</span>
                    <span class="logo-sub">Cursus</span>
                </div>
            </div>
         
</nav>

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
            <h2>Mon Profil</h2>
            <div class="icons">
                <i class="far fa-bell"></i>
                <i class="fas fa-cog"></i>
            </div>
        </div>

        <div class="breadcrumb">Accueil / Mon profil</div>

        <div class="card">
    <div class="profile-header">
        <img src="uploads/<?php echo $etudiant['photo']; ?>?v=<?php echo time(); ?>" alt="Photo de profil" style="display: block; margin: 0 auto; width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
        <h3><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></h3>
        <p>Étudiant</p>
    </div>

    

            <div class="profile-grid">
                <div class="info">
                    <label>Nom complet</label>
                    <input type="text" value="<?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?>" readonly>
                </div>

                <div class="info">
                    <label>Matricule</label>
                    <input type="text" value="<?php echo htmlspecialchars($etudiant['matricule']); ?>" readonly>
                </div>

                <div class="info">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($etudiant['email']); ?>" readonly>
                </div>

                <div class="info">
                    <label>Téléphone</label>
                    <input type="text" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" readonly>
                </div>

                <div class="info">
    <label>Filière</label>
    <input type="text" value="<?php echo !empty($etudiant['filiere']) ? htmlspecialchars($etudiant['filiere']) : 'Non définie'; ?>" readonly>
</div>

<div class="info">
    <label>Niveau</label>
    <input type="text" value="<?php echo !empty($etudiant['niveau']) ? htmlspecialchars($etudiant['niveau']) : 'Non défini'; ?>" readonly>
</div>

                <div class="info">
                    <label>Date de naissance</label>
                    <input type="date" value="<?php echo htmlspecialchars($etudiant['date_naissance']); ?>" readonly>
                </div>

                <div class="info">
                    <label>Lieu de naissance / Adresse</label>
                    <input type="text" value="<?php echo htmlspecialchars($etudiant['lieu_naissance']); ?>" readonly>
                </div>
            </div>

            <div class="btn-container">
                <a href="modifier_profil.php" class="btn">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
            </div>
        </div>
    </main>

</div>

</body>
</html>