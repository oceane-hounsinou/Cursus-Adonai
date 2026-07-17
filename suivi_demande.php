<?php
session_start();
require "Config.php";

$id = $_SESSION['utilisateur_id'];

$stmt = $pdo->prepare("SELECT * FROM demandes WHERE etudiant_id = ? ORDER BY date_creation DESC");
$stmt->execute([$id]);
$demandes = $stmt->fetchAll();
?>

<h1>Suivi des demandes</h1>

<table border="1">
<tr>
    <th>Type</th>
    <th>Status</th>
    <th>Date</th>
    <th>Observation</th>
</tr>

<?php foreach ($demandes as $d): ?>
<tr>
    <td><?= $d['type_demande'] ?></td>
    <td><?= $d['status'] ?></td>
    <td><?= $d['date_creation'] ?></td>
    <td><?= $d['observation'] ?? '-' ?></td>
</tr>
<?php endforeach; ?>
</table>