<?php
session_start();
require_once 'Config.php'; 

// Récupération sécurisée des données
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$type_demande = isset($_GET['type_demande']) ? $_GET['type_demande'] : '';

// Construction de la requête SQL (sans département car absent de votre table)
$sql = "SELECT * FROM demande WHERE 1=1";
$params = [];

if (!empty($keyword)) {
    $sql .= " AND (description LIKE :keyword)";
    $params[':keyword'] = "%$keyword%";
}

if (!empty($type_demande)) {
    $sql .= " AND type_demande = :type";
    $params[':type'] = $type_demande;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Résultats - Adonaï Cursus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f8fafc; font-family: "Segoe UI", Arial, sans-serif; }
        .menu { background: #9b0000; padding: 12px 0; box-shadow: 0 4px 12px rgba(0,0,0,.15); }
        .logo-main { color: #ffffff; font-size: 20px; font-weight: 800; }
        .logo-sub { color: #ff5500; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .nav-link { color: white !important; font-weight: 500; }
        .result-card { border: none; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: .3s; }
        .result-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark menu">
        <div class="container">
            <div class="logo-block">
                <div class="logo-text">
                    <span class="logo-main">Adonaï</span>
                    <span class="logo-sub">Cursus</span>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacts.php">Contacts</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="mb-4 text-danger"><i class="bi bi-search"></i> Résultats de recherche</h3>
        
        <?php if (count($results) > 0): ?>
            <div class="row">
                <?php foreach ($results as $row): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card p-4 result-card">
                            <h5>Type : <?= htmlspecialchars($row['type_demande']) ?></h5>
                            <p class="text-muted small">Statut : <strong><?= htmlspecialchars($row['statut']) ?></strong> | Date : <?= htmlspecialchars($row['date_demande']) ?></p>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <a href="voir_demande.php?id=<?= $row['id_demande'] ?>" class="btn btn-outline-danger btn-sm w-25">Voir détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Aucun document ne correspond à vos critères.
            </div>
            <a href="index.php" class="btn btn-danger">Retour à l'accueil</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>