<?php
function estConnecte() {
    return isset($_SESSION['user_id']);
}

function estBibliothecaire() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'bibliothecaire';
}

function rediriger($url) {
    header("Location: $url");
    exit();
}

function afficherSucces($message) {
    return "<div class='alert alert-success'>$message</div>";
}

function afficherErreur($message) {
    return "<div class='alert alert-danger'>$message</div>";
}

function nombreEmpruntsEnCours($pdo, $id_adhérent) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM emprunts WHERE id_adhérent = ? AND statut = 'en cours'");
    $stmt->execute([$id_adhérent]);
    return $stmt->fetchColumn();
}

function verifierQuota($pdo, $id_adhérent) {
    $stmt = $pdo->prepare("SELECT quota_max FROM adherents WHERE id_adhérent = ?");
    $stmt->execute([$id_adhérent]);
    $quota = $stmt->fetchColumn();
    return nombreEmpruntsEnCours($pdo, $id_adhérent) < $quota;
}
?>