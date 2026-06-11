<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('login.php');
}

$message = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $erreur = "Erreur lors de l'upload du fichier.";
    } elseif (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
        $erreur = "Veuillez uploader un fichier CSV.";
    } else {
        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            $erreur = "Impossible d'ouvrir le fichier.";
        } else {
            $ligne = 0;
            $success = 0;
            $errors = [];

            // Lire la première ligne (en-têtes éventuels)
            $headers = fgetcsv($handle, 0, ',');
            // Si la première ligne ne contient pas 'titre' on la traite comme donnée
            $hasHeader = ($headers && in_array('titre', $headers));
            if (!$hasHeader) {
                // Remettre le pointeur au début
                rewind($handle);
            }

            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                if ($hasHeader) {
                    // Associer les colonnes
                    $row = array_combine($headers, $data);
                    if ($row === false) continue;
                    $titre   = $row['titre'] ?? '';
                    $auteur  = $row['auteur'] ?? '';
                    $editeur = $row['editeur'] ?? '';
                    $isbn    = $row['isbn'] ?? '';
                    $annee   = $row['annee'] ?? '';
                    $genre   = $row['genre'] ?? '';
                } else {
                    // Colonnes fixes : titre, auteur, editeur, isbn, annee, genre
                    if (count($data) < 2) continue;
                    $titre   = $data[0] ?? '';
                    $auteur  = $data[1] ?? '';
                    $editeur = $data[2] ?? '';
                    $isbn    = $data[3] ?? '';
                    $annee   = $data[4] ?? '';
                    $genre   = $data[5] ?? '';
                }

                if (empty($titre) || empty($auteur)) {
                    $errors[] = "Ligne " . ($ligne + 1) . " : titre ou auteur manquant.";
                    $ligne++;
                    continue;
                }

                // Insertion ou mise à jour (par ISBN ou titre+auteur)
                try {
                    // Vérifier si le livre existe déjà (par ISBN si présent)
                    if (!empty($isbn)) {
                        $check = $pdo->prepare("SELECT id_livre FROM livres WHERE isbn = ?");
                        $check->execute([$isbn]);
                        $existing = $check->fetch();
                    } else {
                        $check = $pdo->prepare("SELECT id_livre FROM livres WHERE titre = ? AND auteur = ?");
                        $check->execute([$titre, $auteur]);
                        $existing = $check->fetch();
                    }

                    if ($existing) {
                        // Mise à jour
                        $sql = "UPDATE livres SET titre=?, auteur=?, editeur=?, isbn=?, annee=?, genre=? WHERE id_livre=?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$titre, $auteur, $editeur, $isbn, $annee, $genre, $existing['id_livre']]);
                    } else {
                        // Insertion
                        $sql = "INSERT INTO livres (titre, auteur, editeur, isbn, annee, genre) VALUES (?,?,?,?,?,?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$titre, $auteur, $editeur, $isbn, $annee, $genre]);
                    }
                    $success++;
                } catch (PDOException $e) {
                    $errors[] = "Ligne " . ($ligne + 1) . " : " . $e->getMessage();
                }
                $ligne++;
            }
            fclose($handle);
            $message = "$success livre(s) importé(s) / mis à jour. " . count($errors) . " erreur(s).";
            if (!empty($errors)) {
                $erreur = implode('<br>', array_slice($errors, 0, 10));
            }
        }
    }
}

// Redirection avec message flash
if ($message) {
    $_SESSION['flash_success'] = $message;
}
if ($erreur) {
    $_SESSION['flash_error'] = $erreur;
}
header("Location: dashboard.php");
exit;
?>