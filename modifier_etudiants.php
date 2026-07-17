<?php
$host = "localhost";
$dbname = "ism_adonai";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_etudiant'])) {
    $id_etudiant = intval($_POST['id_etudiant']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $matricule = htmlspecialchars($_POST['matricule']);
    $sexe = htmlspecialchars($_POST['sexe']);
    $email = htmlspecialchars($_POST['email']);
    $filiere = htmlspecialchars($_POST['filiere']);

    try {
        $sql = "UPDATE `etudiants` SET `nom` = ?, `prenom` = ?, `matricule` = ?, `sexe` = ?, `email` = ?, `filiere` = ? WHERE `id_etudiant` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $matricule, $sexe, $email, $filiere, $id_etudiant]);
        
        header("Location: gestion_etudiants.php");
        exit();
    } catch (PDOException $e) {
        echo "<script>alert('Erreur lors de la modification : " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Récupération de l'étudiant à modifier
if (isset($_GET['id'])) {
    $id_etudiant = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM `etudiants` WHERE `id_etudiant` = ?");
    $stmt->execute([$id_etudiant]);
    $etudiant = $stmt->fetch();
    
    if (!$etudiant) {
        header("Location: gestion_etudiants.php");
        exit();
    }
} else {
    header("Location: gestion_etudiants.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant - ISM ADONAÏ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f8fafc; color: #334155; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background: #0d2342; color: #cbd5e1; flex-shrink: 0; }
        .logo { padding: 25px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .logo h2 { font-size: 18px; color: white; letter-spacing: 1px; }
        .profile { text-align: center; padding: 25px 0; background: #0a1b33; }
        .profile img { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #475569; object-fit: cover; }
        .profile h4 { margin-top: 10px; color: white; font-size: 15px; }
        .profile p { font-size: 12px; color: #94a3b8; }

        .menu { list-style: none; margin-top: 10px; }
        .menu li { padding: 14px 25px; cursor: pointer; transition: 0.3s; }
        .menu li a { color: #cbd5e1; text-decoration: none; display: flex; align-items: center; justify-content: space-between; font-size: 14px; width: 100%; }
        .menu li a .nav-text { display: flex; align-items: center; gap: 12px; }
        .menu li:hover, .menu li.active { background: #1e3a61; color: white; }
        .menu li.active a { color: white; font-weight: 500; }

        /* --- MAIN CONTENT --- */
        .main { flex: 1; padding: 30px; display: flex; flex-direction: column; }
        .topbar { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px; }
        .top-icons i { margin-left: 20px; color: #64748b; cursor: pointer; font-size: 16px; }

        .content-header { margin-bottom: 25px; }
        .content-header h2 { font-size: 22px; color: #0d2342; font-weight: 700; margin-bottom: 5px; }
        .breadcrumb { font-size: 13px; color: #94a3b8; }
        .breadcrumb a { color: #3b82f6; text-decoration: none; }
        .breadcrumb span { margin: 0 5px; }

        /* --- FORM PANEL --- */
        .panel { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,.05); border: 1px solid #e2e8f0; max-width: 700px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: span 2; }
        label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        input, select { padding: 12px 14px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 14px; color: #334155; outline: none; transition: all 0.2s; }
        input:focus, select:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        
        .btn-container { display: flex; gap: 12px; }
        .btn { padding: 12px 24px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s; border: none; text-decoration: none; }
        .btn-save { background: #3b82f6; color: white; }
        .btn-save:hover { background: #2563eb; }
        .btn-cancel { background: #e2e8f0; color: #475569; }
        .btn-cancel:hover { background: #cbd5e1; }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <div class="logo"><h2>ISM ADONAÏ</h2></div>
        <div class="profile">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="Admin ISM">
            <h4>Admin ISM</h4>
            <p>Administrateur</p>
        </div>
        <ul class="menu">
            <li><a href="dashboard.php"><span class="nav-text"><i class="fas fa-chart-pie"></i> Tableau de bord</span></a></li>
            <li class="active"><a href="gestion_etudiants.php"><span class="nav-text"><i class="fas fa-users"></i> Étudiants</span></a></li>
            <li><a href="#"><span class="nav-text"><i class="fas fa-file-alt"></i> Demandes</span></a></li>
            <li><a href="#"><span class="nav-text"><i class="fas fa-folder"></i> Documents</span></a></li>
            <li><a href="#"><span class="nav-text"><i class="fas fa-user-shield"></i> Utilisateurs</span></a></li>
            <li><a href="#"><span class="nav-text"><i class="fas fa-bar-chart"></i> Rapports</span></a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="top-icons"><i class="fas fa-bell"></i><i class="fas fa-cog"></i></div>
        </div>

        <div class="content-header">
            <h2>Modifier la fiche étudiant</h2>
            <div class="breadcrumb">
                <a href="dashboard.php">Accueil</a> <span>/</span> <a href="gestion_etudiants.php">Étudiants</a> <span>/</span> Modification
            </div>
        </div>

        <div class="panel">
            <form action="modifier_etudiants.php" method="POST">
                <input type="hidden" name="id_etudiant" value="<?php echo $etudiant['id_etudiant']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Matricule</label>
                        <input type="text" name="matricule" value="<?php echo htmlspecialchars($etudiant['matricule']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Sexe</label>
                        <select name="sexe" required>
                            <option value="Masculin" <?php echo ($etudiant['sexe'] == 'Masculin') ? 'selected' : ''; ?>>Masculin</option>
                            <option value="Féminin" <?php echo ($etudiant['sexe'] == 'Féminin') ? 'selected' : ''; ?>>Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom" value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Adresse Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($etudiant['email']); ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Filière</label>
                        <select name="filiere" required>
                            <option value="Informatique" <?php echo ($etudiant['filiere'] == 'Informatique') ? 'selected' : ''; ?>>Informatique</option>
                            <option value="Génie Logiciel" <?php echo ($etudiant['filiere'] == 'Génie Logiciel') ? 'selected' : ''; ?>>Génie Logiciel</option>
                            <option value="Réseaux et Télécommunications" <?php echo ($etudiant['filiere'] == 'Réseaux et Télécommunications') ? 'selected' : ''; ?>>Réseaux et Télécommunications</option>
                            <option value="Intelligence Artificielle" <?php echo ($etudiant['filiere'] == 'Intelligence Artificielle') ? 'selected' : ''; ?>>Intelligence Artificielle</option>
                            <option value="Gestion des Entreprises" <?php echo ($etudiant['filiere'] == 'Gestion des Entreprises') ? 'selected' : ''; ?>>Gestion des Entreprises</option>
                            <option value="Marketing et communication" <?php echo ($etudiant['filiere'] == 'Marketing et communication') ? 'selected' : ''; ?>>Marketing et communication</option>
                            <option value="Comptabilité et Finance" <?php echo ($etudiant['filiere'] == 'Comptabilité et Finance') ? 'selected' : ''; ?>>Comptabilité et Finance</option>
                            <option value="Droit des affaires" <?php echo ($etudiant['filiere'] == 'Droit des affaires') ? 'selected' : ''; ?>>Droit des affaires</option>
                            <option value="Droit Public" <?php echo ($etudiant['filiere'] == 'Droit Public') ? 'selected' : ''; ?>>Droit Public</option>
                        </select>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Sauvegarder</button>
                    <a href="gestion_etudiants.php" class="btn btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>