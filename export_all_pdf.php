<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

if (!file_exists(__DIR__ . '/../libs/fpdf.php')) {
    die("La bibliothèque FPDF est manquante. Placez fpdf.php dans le dossier libs/");
}
require_once __DIR__ . '/../libs/fpdf.php';

$stmt = $pdo->query("SELECT * FROM livres ORDER BY titre");
$livres = $stmt->fetchAll();

class PDF extends FPDF
{
    function Header()
    {
        // Utilisation de Courier au lieu de Helvetica
        $this->SetFont('Courier', 'B', 12);
        $this->Cell(0, 10, 'Liste des livres de la bibliothèque', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Courier', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(10, 8, 'ID', 1);
$pdf->Cell(60, 8, 'Titre', 1);
$pdf->Cell(50, 8, 'Auteur', 1);
$pdf->Cell(40, 8, 'Editeur', 1);
$pdf->Cell(30, 8, 'Annee', 1);
$pdf->Ln();

$pdf->SetFont('Courier', '', 9);
foreach ($livres as $livre) {
    $pdf->Cell(10, 6, $livre['id_livre'], 1);
    $pdf->Cell(60, 6, utf8_decode($livre['titre']), 1);
    $pdf->Cell(50, 6, utf8_decode($livre['auteur']), 1);
    $pdf->Cell(40, 6, utf8_decode($livre['editeur']), 1);
    $pdf->Cell(30, 6, $livre['annee'], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'tous_les_livres.pdf');
?>