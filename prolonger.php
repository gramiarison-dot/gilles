<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!estConnecte() || !estBibliothecaire()) rediriger('../login.php');

if (isset($_GET['id'])) {
    $pdo->prepare("UPDATE emprunts SET date_retour_prevue = DATE_ADD(date_retour_prevue, INTERVAL 15 DAY) WHERE id_emprunt = ? AND statut = 'en cours'")->execute([$_GET['id']]);
}
rediriger('index.php');
?>