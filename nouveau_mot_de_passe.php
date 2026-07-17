<?php
require "Config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    $newPassword = password_hash("123456", PASSWORD_DEFAULT);

    $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newPassword, $email]);

    echo "Mot de passe réinitialisé. Nouveau mot de passe : 123456";
}
?>