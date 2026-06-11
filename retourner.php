<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!estConnecte() || !estBibliothecaire()) rediriger('../login.php');

if (isset($_GET['id'])) {
    $id_emprunt = $_GET['id'];
    // Récupérer l'exemplaire
    $stmt = $pdo->prepare("SELECT id_exemplaire FROM emprunts WHERE id_emprunt = ?");
    $stmt->execute([$id_emprunt]);
    $ex = $stmt->fetch();
    if ($ex) {
        $pdo->prepare("UPDATE emprunts SET date_retour_reelle = CURDATE(), statut = 'cloture' WHERE id_emprunt = ?")->execute([$id_emprunt]);
        $pdo->prepare("UPDATE exemplaires SET disponible = 1 WHERE id_exemplaire = ?")->execute([$ex['id_exemplaire']]);
    }
}
rediriger('index.php');
?>