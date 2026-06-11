<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!estConnecte() || !estBibliothecaire()) rediriger('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_adhérent = $_POST['id_adhérent'];
    $id_livre = $_POST['id_livre'];
    $stmt = $pdo->prepare("INSERT INTO reservations (id_adhérent, id_livre) VALUES (?, ?)");
    $stmt->execute([$id_adhérent, $id_livre]);
    rediriger('index.php');
}
$adherents = $pdo->query("SELECT id_adhérent, nom, prenom FROM adherents")->fetchAll();
$livres = $pdo->query("SELECT id_livre, titre FROM livres")->fetchAll();
include '../includes/header.php';
?>
<h2>Réservation d'un livre</h2>
<form method="post">
    <div><label>Adhérent :</label>
        <select name="id_adhérent"><?php foreach($adherents as $a): ?><option value="<?= $a['id_adhérent'] ?>"><?= htmlspecialchars($a['nom'].' '.$a['prenom']) ?></option><?php endforeach; ?></select>
    </div>
    <div><label>Livre :</label>
        <select name="id_livre"><?php foreach($livres as $l): ?><option value="<?= $l['id_livre'] ?>"><?= htmlspecialchars($l['titre']) ?></option><?php endforeach; ?></select>
    </div>
    <div><button type="submit" class="btn">Réserver</button></div>
</form>
<?php include '../includes/footer.php'; ?>