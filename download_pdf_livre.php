<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID invalide.");
}

$pdfFile = __DIR__ . '/../uploads/pdfs/livre_' . $id . '.pdf';
if (file_exists($pdfFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="livre_' . $id . '.pdf"');
    readfile($pdfFile);
    exit;
} else {
    die("Fichier PDF non trouvé pour ce livre.");
}
?>