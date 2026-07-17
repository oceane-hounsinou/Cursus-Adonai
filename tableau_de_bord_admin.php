<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'ism_adonai';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Statistiques
    $stmt = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM etudiants) as etudiant,
        (SELECT COUNT(*) FROM demande) as demandes_totales,
        (SELECT COUNT(*) FROM demande WHERE statut = 'En attente') as en_attente,
        (SELECT COUNT(*) FROM demande WHERE statut = 'Validé') as validees,
        (SELECT COUNT(*) FROM demande WHERE statut = 'Rejeté') as rejetees");
    $statistiques = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Récupération des demandes récentes avec jointure corrigée
$stmt_recentes = $pdo->query("
    SELECT 
        e.nom,
        d.type_demande,
        d.date_demande,
        d.statut
    FROM demande d
    INNER JOIN etudiants e 
        ON d.id_etudiant = e.id_etudiant
    ORDER BY d.date_demande DESC
    LIMIT 5
");
$demandes_recentes = $stmt_recentes->fetchAll(PDO::FETCH_ASSOC);
// Calcul du total pour les pourcentages
$total_demandes = $statistiques['demandes_totales'] > 0 ? $statistiques['demandes_totales'] : 1; 

// Calcul des pourcentages
$pct_attente = round(($statistiques['en_attente'] / $total_demandes) * 100);
$pct_validees = round(($statistiques['validees'] / $total_demandes) * 100);
$pct_rejetees = round(($statistiques['rejetees'] / $total_demandes) * 100);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISM ADONAÏ - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Sidebar Styles - Modifié en rouge sombre */
.sidebar {
    width: 260px;
    background-color: #9b0000; /* Rouge sombre ISM Adonaï */
    min-height: 100vh;
    color: #ffd6d6; /* Texte légèrement plus clair pour contraster avec le rouge */
}

/* Ajustement pour la section profil pour garder une cohérence */
.sidebar .profile-section {
    background-color: #7a0000; /* Un ton encore plus foncé pour le profil */
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* Ajustement de l'état actif du menu */
.sidebar .nav-link.active {
    background-color: rgba(0,0,0,0.2); /* Fond sombre lors de la sélection */
    color: #fff;
}
            color: #334155;
            overflow-x: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: #0d2342;
            min-height: 100vh;
            color: #94a3b8;
        }
        .sidebar .logo-section {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .logo-box {
            width: 40px;
            height: 40px;
            background-color: #f59e0b;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #fbbf24;
        }
        .sidebar .profile-section {
            background-color: #0a1b33;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #475569;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #1e3a61;
            color: #fff;
        }
        /* Cards & Main Styles */
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .badge-waiting {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .badge-success-custom {
            background-color: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .custom-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .table th {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table td {
            font-size: 0.85rem;
            padding: 1rem 0.5rem;
            vertical-align: middle;
        }
    </style>
</head>
<body class="d-flex">

    <aside class="sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="logo-section p-4 d-flex align-items-center gap-3">
                <div class="logo-box">ISM</div>
                <div>
                    <h1 class="h6 text-white mb-0 fw-bold tracking-wide">ISM ADONAÏ</h1>
                    <small class="text-warning fw-semibold text-uppercase" style="font-size: 10px; letter-spacing: 1px;"></small>
                </div>
            </div>

            <div class="profile-section px-4 py-3 d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" class="profile-img" alt="Avatar">
                <div>
                    <p class="text-white small mb-0 fw-semibold">Admin ISM</p>
                    <small class="text-muted" style="font-size: 11px;">Administrateur</small>
                </div>
            </div>

            <nav class="p-3">
                <a href="tabeau_de_bord_admin.php" class="nav-link active">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-speedometer2 text-info"></i>
                        <span>Tableau de bord</span>
                    </div>
                </a>
                
                <a href="profil_admin.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-gear text-secondary"></i>
                        <span>Mon Profil</span>
                    </div>
                    <i class="bi bi-chevron-right small text-muted"></i>
                </a>

                <a href="gestion_etudiants.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-gear text-secondary"></i>
                        <span>Gestion Étudiant</span>
                    </div>
                    <i class="bi bi-chevron-right small text-muted"></i>
                </a>

                <a href="liste_etudiants.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-people text-secondary"></i>
                        <span>Étudiants</span>
                    </div>
                    <i class="bi bi-chevron-right small text-muted"></i>
                </a>
                <a href="gestion_demandes.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-file-earmark-text text-secondary"></i>
                        <span>Gestion Demandes</span>
                    </div>
                    <i class="bi bi-chevron-right small text-muted"></i>
                </a>
                <a href="gestion_documents.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-folder text-secondary"></i>
                        <span>Documents</span>
                    </div>
                </a>
                <a href="utilisateurs.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-check text-secondary"></i>
                        <span>Utilisateurs</span>
                    </div>
                </a>
                <a href="rapport.php" class="nav-link">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bar-chart text-secondary"></i>
                        <span>Rapports</span>
                    </div>
                </a>
            </nav>
        </div>

        <div class="p-3 border-top border-secondary border-opacity-10">
            <a href="Deconnexion.php" class="nav-link text-danger w-full">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Déconnexion</span>
                </div>
            </a>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow-y: auto;">
        
        <header class="bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between shadow-sm sticky-top">
            <h2 class="h5 mb-0 fw-bold text-dark">Tableau de bord</h2>
            
            <div class="d-flex align-items-center gap-3 text-secondary">
                <button class="btn btn-link text-secondary p-1 position-relative">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
                <button class="btn btn-link text-secondary p-1"><i class="bi bi-question-circle fs-5"></i></button>
                <button class="btn btn-link text-secondary p-1"><i class="bi bi-gear fs-5"></i></button>
            </div>
        </header>

        <main class="p-4 flex-grow-1">
            
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-lg">
                    <div class="card custom-card p-3 bg-white h-100 d-flex flex-row align-items-center gap-3">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-semibold" style="font-size: 11px;">Étudiants</small>
                            <h4 class="mb-0 fw-bold text-dark"><?= $statistiques['etudiant'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="card custom-card p-3 bg-white h-100 d-flex flex-row align-items-center gap-3">
                        <div class="stat-icon bg-purple bg-opacity-10" style="color: #8b5cf6; background-color: #f5f3ff;">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-semibold" style="font-size: 11px;">Demandes totales</small>
                            <h4 class="mb-0 fw-bold text-dark"><?= $statistiques['demandes_totales'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="card custom-card p-3 bg-white h-100 d-flex flex-row align-items-center gap-3">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-semibold" style="font-size: 11px;">En attente</small>
                            <h4 class="mb-0 fw-bold text-dark"><?= $statistiques['en_attente'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="card custom-card p-3 bg-white h-100 d-flex flex-row align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-semibold" style="font-size: 11px;">Validées</small>
                            <h4 class="mb-0 fw-bold text-dark"><?= $statistiques['validees'] ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="card custom-card p-3 bg-white h-100 d-flex flex-row align-items-center gap-3">
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-x-circle fs-5"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-semibold" style="font-size: 11px;">Rejetées</small>
                            <h4 class="mb-0 fw-bold text-dark"><?= $statistiques['rejetees'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card custom-card bg-white p-4 h-100">
                        <h3 class="h6 fw-bold text-secondary mb-4">Demandes récentes</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="border-bottom text-muted">
                                        <th>Étudiant</th>
                                        <th>Type de demande</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php foreach ($demandes_recentes as $demande): ?>
    <tr>
        <td class="fw-medium text-dark"><?= htmlspecialchars($demande['nom']) ?></td>
        <td class="text-secondary"><?= htmlspecialchars($demande['type_demande']) ?></td>
        <td class="text-muted"><?= htmlspecialchars($demande['date_demande']) ?></td>
        <td>
            <?php if ($demande['statut'] === 'Validé'): ?>
                <span class="badge rounded-pill badge-success-custom px-3 py-1.5 font-semibold text-xs">Validé</span>
            <?php elseif ($demande['statut'] === 'Rejeté'): ?>
                <span class="badge rounded-pill bg-danger text-white px-3 py-1.5 font-semibold text-xs">Rejeté</span>
            <?php else: ?>
                <span class="badge rounded-pill badge-waiting px-3 py-1.5 font-semibold text-xs">En attente</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card custom-card bg-white p-4 h-100 d-flex flex-column">
                        <h3 class="h6 fw-bold text-secondary mb-3">Statistiques</h3>
                        
                        <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center">
                            <div style="width: 160px; height: 160px; position: relative;">
                                <canvas id="donutChart"></canvas>
                            </div>
                            
                            <div class="w-100 mt-4 small">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #3b82f6;"></span>
            <span class="text-muted">En attente</span>
        </div>
        <span class="fw-bold text-dark"><?= $statistiques['en_attente'] ?> (<?= $pct_attente ?>%)</span>
    </div>
    
    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #10b981;"></span>
            <span class="text-muted">Validées</span>
        </div>
        <span class="fw-bold text-dark"><?= $statistiques['validees'] ?> (<?= $pct_validees ?>%)</span>
    </div>
    
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #ef4444;"></span>
            <span class="text-muted">Rejetées</span>
        </div>
        <span class="fw-bold text-dark"><?= $statistiques['rejetees'] ?> (<?= $pct_rejetees ?>%)</span>
    </div>
</div>

                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('donutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        <?= $statistiques['en_attente'] ?>, 
                        <?= $statistiques['validees'] ?>, 
                        <?= $statistiques['rejetees'] ?>
                    ],
                    backgroundColor: ['#3b82f6', '#10b981', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });
    </script>
</body>
</html>