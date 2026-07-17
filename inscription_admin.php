<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Inscription Administrateur | ISM ADONAÏ</title>

<link href="bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


<style>

/* Même style que les autres pages */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI", Arial, sans-serif;
}

body{background:#f5f5f5;}

.menu{
background:#9b0000;
padding:12px 0;
box-shadow:0 3px 10px rgba(0,0,0,.2);
}

.logo{
color:white !important;
font-size:22px;
font-weight:bold;
}

.section-connexion{
min-height:85vh;
display:flex;
justify-content:center;
align-items:center;
}

.carte-connexion{
width:100%;
max-width:450px;
background:white;
padding:35px;
border-radius:20px;
box-shadow:0 8px 25px rgba(155,0,0,.2);
}

.icon-user{
width:80px;
height:80px;
background:#9b0000;
color:white;
border-radius:50%;
display:flex;
justify-content:center;
align-items:center;
margin:auto;
font-size:35px;
}

h2{
text-align:center;
color:#9b0000;
margin:15px 0 30px;
font-weight:bold;
}

.form-control{
height:45px;
border-radius:25px;
}

.btn-connexion{
width:100%;
background:#9b0000;
color:white;
border:none;
border-radius:25px;
padding:12px;
font-weight:bold;
}

.btn-connexion:hover{
background:#700000;
}

footer{
background:#7a0000;
color:white;
text-align:center;
padding:15px;
}

</style>

</head>

<body>

<nav class="navbar menu">
<div class="container">
<a class="navbar-brand logo" href="index.php">
<i class="bi bi-shield-fill-check"></i>
ISM ADONAÏ
</a>
</div>
</nav>


<section class="section-connexion">

<div class="carte-connexion">

<div class="icon-user">
<i class="bi bi-person-lock"></i>
</div>


<h2>Inscription Admin</h2>


<form action="enregistrement_admin.php" method="POST">

<input type="hidden" name="role" value="admin">


<div class="mb-3">
<label>Nom</label>
<input type="text" name="nom" class="form-control" required>
</div>



<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>



<div class="mb-3">
<label>Mot de passe</label>
<input type="password" name="password" class="form-control" required>
</div>


<div class="mb-3">
<label>Code administrateur</label>
<input type="password" name="code_admin" class="form-control" required>
</div>





<button type="submit" class="btn-connexion">
Créer le compte administrateur
</button>

</form>

</div>

</section>



</body>
</html>