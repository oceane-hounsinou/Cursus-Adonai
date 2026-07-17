<?php
// Assurez-vous d'inclure votre fichier de connexion
require_once "Config.php";

// Récupération des filières
$filieres = $pdo->query("SELECT * FROM filiere ORDER BY nom_filiere")->fetchAll(PDO::FETCH_ASSOC);

// Récupération des niveaux
$niveaux = $pdo->query("SELECT * FROM niveaux ORDER BY nom_niveau")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription Étudiant | ISM ADONAÏ</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
        body { background:#f5f5f5; }
        .menu { background:#9b0000; padding:12px 0; box-shadow:0 3px 10px rgba(0,0,0,.2); }
        .logo { color:white !important; font-size:22px; font-weight:bold; }
        .section-connexion { min-height:100vh; display:flex; justify-content:center; align-items:center; padding:30px; }
        .carte-connexion { width:100%; max-width:650px; background:white; padding:35px; border-radius:20px; box-shadow:0 8px 25px rgba(155,0,0,.2); }
        .icon-user { width:80px; height:80px; background:#9b0000; color:white; border-radius:50%; display:flex; justify-content:center; align-items:center; margin:auto; font-size:35px; }
        h2 { text-align:center; color:#9b0000; margin:15px 0 30px; font-weight:bold; }
        .form-control { height:45px; border-radius:25px; }
        .btn-connexion { width:100%; background:#9b0000; color:white; border:none; border-radius:25px; padding:12px; font-weight:bold; cursor:pointer; }
        .btn-connexion:hover { background:#700000; }
        .btn-reset { width:100%; background:#6c757d; color:white; border:none; border-radius:25px; padding:12px; font-weight:bold; margin-top:10px; cursor:pointer; }
        .btn-reset:hover { background:#5a6268; }
        .row-form { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
        @media(max-width:768px) { .row-form { grid-template-columns:1fr; } }
    </style>
</head>
<body>

<nav class="navbar menu">
    <div class="container">
        <a class="navbar-brand logo" href="inscription_etudiant.php">
            <i class="bi bi-shield-fill-check"></i> ISM ADONAÏ
        </a>
    </div>
</nav>

<section class="section-connexion">
    <div class="carte-connexion">
        <div class="icon-user"><i class="bi bi-person-plus-fill"></i></div>
        <h2>Inscription Étudiant</h2>

       <form action="traitement_inscription.php" method="POST">
    <div class="row-form">
        <div class="mb-3"><label>Matricule</label><input type="text" name="matricule" class="form-control" required></div>
        <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
        <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
        
        <div class="mb-3">
            <label>Sexe</label>
            <select name="sexe" class="form-control" required>
                <option value="Masculin">Masculin</option>
                <option value="Féminin">Féminin</option>
            </select>
        </div>

        <div class="mb-3"><label>Date de naissance</label><input type="date" name="date_naissance" class="form-control" required></div>
        <div class="mb-3"><label>Lieu de naissance</label><input type="text" name="lieu_naissance" class="form-control" required></div>
        <div class="mb-3"><label>Nationalité</label><input type="text" name="nationalite" class="form-control" required></div>
        <div class="mb-3"><label>Téléphone</label><input type="text" name="telephone" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        
        <div class="mb-3">
            <label>Filière</label>
            <select name="filiere" class="form-control" required>
                <option value="">-- Choisir une filière --</option>
                <?php foreach($filieres as $f): ?>
                    <option value="<?= htmlspecialchars($f['nom_filiere']) ?>">
                        <?= htmlspecialchars($f['nom_filiere']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Niveau</label>
            <select name="niveau" class="form-control" required>
                <option value="">-- Choisir un niveau --</option>
                <?php foreach($niveaux as $n): ?>
                    <option value="<?= htmlspecialchars($n['nom_niveau']) ?>">
                        <?= htmlspecialchars($n['nom_niveau']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3"><label>Mot de passe</label><input type="password" name="password" class="form-control" required></div>
    </div>
    <button type="submit" class="btn-connexion">S'inscrire</button>
</form>
    </div>
</section>


</body>
</html>