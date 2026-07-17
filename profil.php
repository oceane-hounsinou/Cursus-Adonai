<?php
session_start();
require "Config.php";

$id = $_SESSION['utilisateur_id'];

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<h1>Mon profil</h1>

<form method="POST" action="update_profil.php">
    <input type="text" name="nom" value="<?= $user['nom'] ?>" required><br>
    <input type="text" name="prenom" value="<?= $user['prenom'] ?>" required><br>
    <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
    <input type="text" name="telephone" value="<?= $user['telephone'] ?>"><br>

    <button type="submit">Modifier</button>
</form>

<hr>

<h2>Changer mot de passe</h2>
<form method="POST" action="changer_password.php">
    <input type="password" name="ancien">
    <input type="password" name="nouveau">
    <button>Changer</button>
</form>