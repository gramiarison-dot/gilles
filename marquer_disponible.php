<?php
// reservations/marquer-disponible.php
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("ID de réservation invalide.");
}

$reservationId = (int)$_GET['id'];

try {
    // Étape 1 : Vérifier si la réservation existe (sans condition sur statut)
    $checkSql = "SELECT id_reservation, id_livre, statut FROM reservations WHERE id_reservation = :id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':id' => $reservationId]);
    $resa = $checkStmt->fetch();

    if (!$resa) {
        die("Aucune réservation trouvée avec l'ID $reservationId.");
    }

    // Étape 2 : Vérifier le statut
    if ($resa['statut'] !== 'reserve') {
        die("La réservation #$reservationId a déjà le statut '{$resa['statut']}', pas 'reserve'.");
    }

    $livreId = $resa['id_livre'];

    // Étape 3 : Vérifier que le livre existe et récupérer son titre
    $livreSql = "SELECT id_livre, titre FROM livres WHERE id_livre = :livre_id";
    $livreStmt = $pdo->prepare($livreSql);
    $livreStmt->execute([':livre_id' => $livreId]);
    $livre = $livreStmt->fetch();

    if (!$livre) {
        die("Le livre avec id_livre = $livreId n'existe pas dans la table livres.");
    }

    $titreLivre = $livre['titre'];

    // Étape 4 : Mettre à jour réservation
    $updateResa = "UPDATE reservations SET statut = 'disponible', date_retour = NOW() WHERE id_reservation = :id";
    $stmtResa = $pdo->prepare($updateResa);
    $stmtResa->execute([':id' => $reservationId]);

    // Étape 5 : Marquer livre disponible
    $updateLivre = "UPDATE livres SET disponible = 1 WHERE id_livre = :livre_id";
    $stmtLivre = $pdo->prepare($updateLivre);
    $stmtLivre->execute([':livre_id' => $livreId]);

    // Étape 6 : Redirection
    header("Location: liste.php?success=Le livre '$titreLivre' a été marqué comme disponible.");
    exit;

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>