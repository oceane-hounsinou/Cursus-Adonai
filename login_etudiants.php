<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion Étudiant | ISM ADONAÏ</title>
<link href="bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    /* Design Rouge pour Étudiant */
    *{ margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
    body{ background:#f5f5f5; }
    .menu{ background:#9b0000; padding:12px 0; box-shadow:0 3px 10px rgba(0,0,0,.2); }
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
    .section-connexion{ min-height:85vh; display:flex; justify-content:center; align-items:center; }
    .carte-connexion{ width:100%; max-width:450px; background:white; padding:35px; border-radius:20px; box-shadow:0 8px 25px rgba(155,0,0,.2); }
    
    .icon-user{ width:80px; height:80px; background:#9b0000; color:white; border-radius:50%; display:flex; justify-content:center; align-items:center; margin:auto; font-size:35px; }
    h2{ text-align:center; color:#9b0000; margin:15px 0 30px; font-weight:bold; }
    
    .form-control{ height:45px; border-radius:25px; }
    .btn-connexion{ width:100%; background:#9b0000; color:white; border:none; border-radius:25px; padding:12px; font-weight:bold; transition:0.3s; margin-top:15px; }
    .btn-connexion:hover{ background:#700000; color:white; }
    
    .liens{ text-align:center; margin-top:20px; }
    .liens a{ text-decoration:none; color:#9b0000; font-weight:600; }
    footer{ background:#7a0000; color:white; text-align:center; padding:15px; }
</style>
</head>

<body>

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

<section class="section-connexion">
    <div class="carte-connexion">
        <?php if(isset($_SESSION["erreur"])): ?>
            <div class="alert alert-danger text-center"><?= $_SESSION["erreur"]; unset($_SESSION["erreur"]); ?></div>
        <?php endif; ?>

        <div class="icon-user">
            <i class="bi bi-person-fill"></i>
        </div>

        <h2>Connexion Étudiant</h2>

        <form action="traitement_etudiants.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Matricule ou Email</label>
                <input type="text" name="identifiant" class="form-control" placeholder="Ex : ISM2026001" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>
            <button type="submit" class="btn-connexion">Se connecter</button>
        </form>

        <div class="liens">
            <p>Mot de passe oublié ? <a href="mot_de_passe_oublie.php">Réinitialiser</a></p>
        </div>
    </div>
</section>


</body>
</html>