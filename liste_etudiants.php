<?php
// 1. Connexion à la base de données
$host = "localhost";
$dbname = "ism_adonai";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red; padding:20px;'>Erreur de connexion : " . $e->getMessage() . "</div>");
}

// 2. Récupération dynamique des étudiants avec jointure sur la table niveaux
try {
    $query = $pdo->query("
        SELECT e.*, n.nom_niveau 
        FROM `etudiants` e
        LEFT JOIN `niveaux` n ON e.id_niveau = n.id_niveau
        ORDER BY e.id_etudiant DESC
    ");
    $liste_etudiants = $query->fetchAll();
} catch (PDOException $e) {
    die("<div style='color:red; padding:20px;'>Erreur SQL lors de la lecture : " . $e->getMessage() . "</div>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Étudiants - Adonaï Cursus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /*=============================
                STYLE DE BASE
        ==============================*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            color: #334155;
        }

        /*=============================
                NAVBAR & LOGO
        ==============================*/
        .menu {
            background: #9b0000;
            padding: 12px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

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

        .nav-link {
            color: white !important;
            font-size: 15px;
            margin-right: 15px;
            font-weight: 500;
            transition: .3s;
        }

        .nav-link:hover, .nav-item.active .nav-link {
            color: #ffd6d6 !important;
        }

        /*=============================
            STRUCTURE DE LA PAGE
        ==============================*/
        .page-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 25px 0;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
        }

        /* Barre de recherche et filtres */
        .filter-card {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.01);
            margin-bottom: 30px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #9b0000;
            box-shadow: 0 0 0 3px rgba(155, 0, 0, 0.1);
        }

        .btn-search {
            background: #9b0000;
            color: white;
            border: none;
            font-weight: 600;
        }

        .btn-search:hover {
            background: #7a0000;
            color: white;
        }

        /* Tableau des étudiants */
        .content-card {
            background: white;
            border: none;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        }

        .table {
            vertical-align: middle;
        }

        .table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 2px solid #f1f5f9;
            padding: 14px 10px;
            background: #f8fafc;
        }

        .table td {
            padding: 16px 10px;
            color: #334155;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Avatar de l'étudiant */
        .avatar-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-placeholder {
            width: 40px;
            height: 40px;
            background: #fff0f0;
            color: #9b0000;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .student-name {
            font-weight: 600;
            color: #1e293b;
        }

        .student-id {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Badges de filières */
        .badge-filiere {
            background: #eff6ff;
            color: #1e40af;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
        }

        .badge-status-active {
            background: #d1fae5;
            color: #065f46;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Actions */
        .btn-action-profile {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-profile:hover {
            background: #9b0000;
            color: white;
        }

        /*=============================
                FOOTER
        ==============================*/
        footer {
            background: #7a0000;
            color: white;
            padding: 60px 0 40px 0;
            margin-top: 60px;
        }

        footer h5, footer h6 {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        footer p {
            color: #ffe4e4;
            line-height: 1.8;
            font-size: 14px;
        }

        footer i {
            font-size: 20px;
            margin-right: 15px;
            transition: .3s;
            cursor: pointer;
        }

        footer i:hover {
            color: #ff5500;
            transform: scale(1.2);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark menu">
        <div class="container">
            <div class="logo-block">
                <div class="logo-icon-wrapper">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-main">Adonaï</span>
                    <span class="logo-sub">Cursus</span>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="tableau_de_bord.php">Espace Étudiant</a></li>
                    <li class="nav-item active"><a class="nav-link" href="tableau_de_bord_admin.php">Espace Admin</a></li>
                </ul>
                <div class="ms-3">
                    <a href="deconnexion.php" class="btn btn-outline-light py-2"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="page-title"><i class="bi bi-people-fill text-danger me-2"></i> Registre des Étudiants</h1>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <button class="btn btn-warning text-white fw-bold bg-orange border-0" onclick="alert('Fonctionnalité d\'export bientôt disponible')">
                        <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Exporter la liste
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container">

        <div class="card filter-card">
            <form class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Rechercher par nom, prénom ou matricule...">
                    </div>
                </div>
               <div class="col-md-3">
    <select class="form-select">
        <option value="">Toutes les filières</option>
        <option value="1" selected>Informatique</option>
        <option value="2">Génie Logiciel</option>
        <option value="3">Réseaux et Télécommunications</option>
        <option value="4">Intelligence Artificielle</option>
        
        <option value="5">Gestion des Entreprises</option>
        <option value="6">Marketing et communication</option>
        <option value="7">Comptabilité et Finance</option>
        
        <option value="8">Droit des affaires</option>
        <option value="9">Droit Public</option>
    </select>
</div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option value="">Tous les niveaux</option>
                        <option value="L1">Licence 1</option>
                        <option value="L2">Licence 2</option>
                        <option value="L3" selected>Licence 3</option>
                        <option value="M1">Master 1</option>
                        <option value="M1">Master 2</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-search">Filtrer</button>
                </div>
            </form>
        </div>

        <div class="card content-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Filière & Niveau</th>
                            <th>Date d'inscription</th>
                            <th>Statut du Compte</th>
                            <th class="text-end">Dossier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($liste_etudiants)): ?>
                            <?php foreach ($liste_etudiants as $etudiant): ?>
                                <?php 
                                    // Extraction des initiales pour le cercle d'avatar générique
                                    $initialeNom = !empty($etudiant['nom']) ? strtoupper(substr($etudiant['nom'], 0, 1)) : '';
                                    $initialePrenom = !empty($etudiant['prenom']) ? strtoupper(substr($etudiant['prenom'], 0, 1)) : '';
                                    $avatarText = $initialeNom . $initialePrenom;
                                ?>
                                <tr>
                                    <td>
                                        <div class="avatar-cell">
                                            <div class="avatar-placeholder"><?php echo htmlspecialchars($avatarText); ?></div>
                                            <div>
                                                <span class="student-name"><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']); ?></span>
                                                <span class="student-id d-block">Matricule : <?php echo htmlspecialchars($etudiant['matricule']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-filiere">
                                            <?php 
                                                $niveauAffiche = !empty($etudiant['nom_niveau']) ? $etudiant['nom_niveau'] : $etudiant['niveau'];
                                                echo htmlspecialchars($niveauAffiche . ' - ' . $etudiant['filiere']); 
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            // Fallback élégant si la date de création ou de naissance est absente
                                            echo !empty($etudiant['date_naissance']) ? date('d M Y', strtotime($etudiant['date_naissance'])) : 'N/A'; 
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge-status-active"><i class="bi bi-check-circle-fill me-1"></i> Actif</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="voir_profil_etudiant.php?id=<?php echo $etudiant['id_etudiant']; ?>" class="btn-action-profile">
                                            <i class="bi bi-folder2-open"></i> Voir le profil
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Aucun étudiant enregistré dans l'infrastructure de la base de données.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5><i class="bi bi-mortarboard-fill"></i> Adonaï Cursus</h5>
                    <p>La marque d'une école prestigieuse</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h6>Liens utiles</h6>
                    <p>
                        <a href="index.php" class="text-white text-decoration-none">Accueil</a><br>
                        <a href="a_propos.php" class="text-white text-decoration-none">À propos</a><br>
                        Services<br>
                        Contact
                    </p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h6>Nous contacter</h6>
                    <p>
                        <i class="bi bi-telephone"></i> +229 01 33 34 56 78<br>
                        <i class="bi bi-envelope"></i> contact@ismadonai.edu
                    </p>
                </div>
                <div class="col-md-2">
                    <h6>Suivez-nous</h6>
                    <i class="bi bi-facebook"></i>
                    <i class="bi bi-twitter-x"></i>
                    <i class="bi bi-linkedin"></i>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>