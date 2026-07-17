<?php
session_start();
require "Config.php";

// 🔴 Vérification de la session admin
if (!isset($_SESSION['id_admin'])) {
    die("Vous devez vous connecter en tant qu'administrateur.");
}

$id_admin = $_SESSION['id_admin'];
try {
    // 🔵 Requête sécurisée
    $sql = "SELECT * FROM admins WHERE id_admin = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_admin]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // 🔴 Si admin introuvable
    if (!$admin) {
        die("Profil administrateur introuvable.");
    }

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Administrateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        /* Reprise de votre CSS existant */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }
        body { background: #f5f6fa; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #8B0000; color: white; }
        .logo-block { display: flex; align-items: center; gap: 12px; padding: 20px; }
        .logo-icon-wrapper { background: #ffffff; color: #9b0000; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 22px; }
        .logo-main { color: #ffffff; font-size: 20px; font-weight: 800; }
        .logo-sub { color: #ff5500; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .profile-mini { text-align: center; padding: 20px; }
        .profile-mini img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 2px solid white; }
        .menu { list-style: none; }
        .menu li a { display: flex; align-items: center; padding: 14px 20px; color: white; text-decoration: none; transition: background 0.3s; }
        .menu li a:hover, .menu li.active { background: rgba(255, 255, 255, 0.1); }
        .content { flex: 1; padding: 25px; }
        .card { background: white; border-radius: 10px; padding: 30px; border: 1px solid #e5e7eb; }
        .profile-header { text-align: center; margin-bottom: 30px; }
        .profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info { display: flex; flex-direction: column; }
        .info label { font-size: 13px; color: #666; margin-bottom: 5px; }
        .info input { padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #f9fafb; }
        .btn { background: #072c77; color: white; border: none; padding: 12px 25px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>

<div class="wrapper">
    <aside class="sidebar">
        <div class="logo-block">
            <div class="logo-icon-wrapper"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="logo-text">
                <span class="logo-main">Adonaï</span>
                <span class="logo-sub">Admin</span>
            </div>
        </div>

        <div class="profile-mini">
            <i class="fas fa-user-shield" style="font-size: 50px;"></i>
            <h4><?php echo htmlspecialchars($admin['nom']); ?></h4>
            <p>Administrateur</p>
        </div>

        <ul class="menu">
            <li><a href="tableau_de_bord_admin.php"><i class="fas fa-chart-line"></i> Tableau de bord</a></li>
            <li class="active"><a href="profil_admin.php"><i class="fas fa-user-cog"></i> Mon profil</a></li>
            <li><a href="gestion_etudiants.php"><i class="fas fa-users"></i> Gestion Étudiants</a></li>
            <li><a href="Deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </aside>

    <main class="content">
        <h2>Mon Profil Administrateur</h2>
        <div class="breadcrumb">Admin / Profil</div>

        <div class="card">
            <div class="profile-header">
                <i class="fas fa-user-circle" style="font-size: 100px; color: #ccc;"></i>
                <h3><?php echo htmlspecialchars($admin['nom']); ?></h3>
                <p>Administrateur Système</p>
            </div>

            <div class="profile-grid">
                <div class="info">
                    <label>Nom complet</label>
                    <input type="text" value="<?php echo htmlspecialchars($admin['nom']); ?>" readonly>
                </div>
                <div class="info">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($admin['email']); ?>" readonly>
                </div>
                <div class="info">
                    <label>Code administrateur</label>
                    <input type="text" value="<?php echo htmlspecialchars($admin['code']); ?>" readonly>
                </div>
            </div>

            <div class="btn-container" style="margin-top: 30px; text-align: right;">
                <a href="modifier_profil_admin.php" class="btn"><i class="fas fa-edit"></i> Modifier</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>