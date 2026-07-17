<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mot de passe oublié</title>

<link href="bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

<nav class="navbar menu">
<div class="container">
<a class="navbar-brand logo" href="index.php">ISM ADONAÏ</a>
</div>
</nav>

<section class="hero">
<div class="container">

<div class="row justify-content-center">

<div class="col-md-5">

<h3>Réinitialisation du mot de passe</h3>

<form action="nouveau_mot_de_passe.php" method="POST">

<input type="email" name="email" class="form-control mb-2" placeholder="Votre email" required>

<button class="btn btn-warning w-100">Envoyer</button>

</form>

</div>

</div>

</div>
</section>

</body>
</html>