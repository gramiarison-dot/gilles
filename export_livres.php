<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Vérification des droits
if (!estConnecte() || !estBibliothecaire()) {
    rediriger('login.php');
}

$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'csv';

// Récupération de tous les livres
$stmt = $pdo->query("SELECT * FROM livres ORDER BY id_livre");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour convertir en CSV
function exportCSV($data) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="livres_export.csv"');
    $output = fopen('php://output', 'w');
    // Entête
    if (!empty($data)) {
        fputcsv($output, array_keys($data[0]));
    }
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Fonction pour convertir en JSON
function exportJSON($data) {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="livres_export.json"');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Fonction pour convertir en XML
function exportXML($data) {
    header('Content-Type: application/xml; charset=utf-8');
    header('Content-Disposition: attachment; filename="livres_export.xml"');
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;
    $root = $xml->createElement('livres');
    $xml->appendChild($root);
    foreach ($data as $livre) {
        $item = $xml->createElement('livre');
        foreach ($livre as $key => $value) {
            $child = $xml->createElement($key, htmlspecialchars($value));
            $item->appendChild($child);
        }
        $root->appendChild($item);
    }
    echo $xml->saveXML();
    exit;
}

// Fonction pour exporter en Excel XLSX (nécessite PhpSpreadsheet)
// Si la librairie n'est pas installée, on propose une alternative.
function exportXLSX($data) {
    // Vérifier si PhpSpreadsheet est disponible
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Entêtes
        if (!empty($data)) {
            $col = 1;
            foreach (array_keys($data[0]) as $header) {
                $sheet->setCellValueByColumnAndRow($col, 1, $header);
                $col++;
            }
            // Données
            $row = 2;
            foreach ($data as $ligne) {
                $col = 1;
                foreach ($ligne as $value) {
                    $sheet->setCellValueByColumnAndRow($col, $row, $value);
                    $col++;
                }
                $row++;
            }
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="livres_export.xlsx"');
        $writer->save('php://output');
        exit;
    } else {
        // Fallback : CSV si PhpSpreadsheet absent
        exportCSV($data);
    }
}

// Choix du format
switch ($format) {
    case 'csv':
        exportCSV($livres);
        break;
    case 'json':
        exportJSON($livres);
        break;
    case 'xml':
        exportXML($livres);
        break;
    case 'xlsx':
        exportXLSX($livres);
        break;
    default:
        die("Format non supporté.");
}
?>