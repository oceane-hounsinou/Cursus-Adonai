<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$dossier_destination = 'uploads/'; 
$extensions_autorisees = ['pdf', 'docx', 'doc', 'jpg', 'png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    
    $file = $_FILES['fileToUpload'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensions_autorisees)) {
        die("Erreur : Type de fichier non autorisé.");
    }

    $nouveau_nom = uniqid('doc_') . '.' . $extension;
    $chemin_complet = $dossier_destination . $nouveau_nom;

    if (move_uploaded_file($file['tmp_name'], $chemin_complet)) {
        
        // 1. Connexion (indispensable ici)
        $pdo = new PDO('mysql:host=localhost;dbname=ism_adonai;charset=utf8', 'root', '');
        
        // 2. Préparation (le stmt est créé ici)
        // Dans upload.php
$sql = "INSERT INTO documents (nom_document, chemin_stockage, date_ajout, categorie, demande_id) VALUES (?, ?, NOW(), ?, NULL)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$file['name'], $nouveau_nom, $categorie]);
        
        // 3. Exécution (on utilise le stmt créé juste au-dessus)
        $stmt->execute([$file['name'], $nouveau_nom, $_POST['categorie']]);
        
        header("Location: gestion_documents.php?success=1");
        exit;
    } else {
        die("Erreur lors du transfert. Vérifiez les droits du dossier 'uploads/'.");
    }
} else {
    die("Aucun fichier envoyé.");
}
?>