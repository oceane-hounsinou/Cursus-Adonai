<?php

session_start();

require "Config.php";


if(isset($_SESSION['utilisateur_id'])){


$id = $_SESSION['utilisateur_id'];


$requete = $pdo->prepare(
"UPDATE notifications
SET statut='lu'
WHERE utilisateur_id = ?"
);


$requete->execute([$id]);

}


header("Location: notifications.php");

exit();

?>