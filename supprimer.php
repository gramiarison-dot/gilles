<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$id_livre = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_livre) {
    rediriger('index.php');
}

// Vérifier si le livre existe et s'il n'a pas d'exemplaires associés (ou d'emprunts)
$stmt = $pdo->prepare("SELECT titre FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$livre = $stmt->fetch();

if (!$livre) {
    rediriger('index.php');
}

// Vérifier s'il existe des exemplaires liés à ce livre
$stmt = $pdo->prepare("SELECT COUNT(*) FROM exemplaires WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$nbExemplaires = $stmt->fetchColumn();

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
        if ($nbExemplaires > 0) {
            $erreur = "Impossible de supprimer ce livre car il possède $nbExemplaires exemplaire(s) associé(s). Supprimez d'abord les exemplaires.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM livres WHERE id_livre = ?");
            $stmt->execute([$id_livre]);
            $_SESSION['flash_success'] = "Livre « " . htmlspecialchars($livre['titre']) . " » supprimé avec succès.";
            rediriger('index.php');
        }
    } else {
        rediriger('index.php');
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h2><i class="fas fa-trash-alt"></i> Supprimer un livre</h2>

<?php if ($erreur): ?>
    <div class="alert alert-error" style="background:#fee2e2; padding:1rem; border-radius:12px; margin-bottom:1rem; color:#b91c1c;">
        ❌ <?= htmlspecialchars($erreur) ?>
    </div>
    <div style="margin-top: 1rem;">
        <a href="index.php" class="btn">Retour à la liste</a>
    </div>
<?php else: ?>
    <div class="form-card" style="max-width: 500px; margin: 0 auto; text-align: center;">
        <p style="font-size: 1.2rem; margin-bottom: 1rem;">
            Êtes-vous sûr de vouloir supprimer le livre :<br>
            <strong><?= htmlspecialchars($livre['titre']) ?></strong> ?
        </p>
        <?php if ($nbExemplaires > 0): ?>
            <div style="background:#fef3c7; padding:1rem; border-radius:12px; margin:1rem 0; color:#92400e;">
                ⚠️ Attention : Ce livre possède <?= $nbExemplaires ?> exemplaire(s). Vous devez d'abord supprimer les exemplaires avant de supprimer le livre.
            </div>
            <a href="index.php" class="btn">Retour</a>
        <?php else: ?>
            <form method="post">
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button type="submit" name="confirm" value="oui" class="btn" style="background:#ef4444;"><i class="fas fa-check"></i> Oui, supprimer</button>
                    <a href="index.php" class="btn" style="background:#6c757d;"><i class="fas fa-times"></i> Non, annuler</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>