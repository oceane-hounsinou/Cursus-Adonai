<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $objet = htmlspecialchars($_POST['objet']);
    $message = htmlspecialchars($_POST['message']);

    // Configuration de l'e-mail
    $destinataire = "contact@ismadonai.edu";
    $sujet = "Nouveau message Adonaï Cursus : " . $objet;
    
    $contenu = "Nom : $nom\n";
    $contenu .= "Email : $email\n\n";
    $contenu .= "Message :\n$message";

    $headers = "From: $email";

    // Envoi de l'e-mail
    if (mail($destinataire, $sujet, $contenu, $headers)) {
        // Redirection vers une page de confirmation ou retour arrière
        session_start();
        $_SESSION['msg_succes'] = "Votre message a été envoyé avec succès !";
        header("Location: contacts.php?success=1");
    } else {
        echo "Erreur lors de l'envoi.";
    }
}
?>