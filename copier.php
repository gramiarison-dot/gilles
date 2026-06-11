<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$erreur = '';
$succes = '';

// Récupération de l'ID du livre à copier
$id_livre = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_livre) {
    rediriger('index.php');
}

// Récupération des données du livre original
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$livre = $stmt->fetch();

if (!$livre) {
    rediriger('index.php');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre   = trim($_POST['titre'] ?? '');
    $auteur  = trim($_POST['auteur'] ?? '');
    $editeur = trim($_POST['editeur'] ?? '');
    $isbn    = trim($_POST['isbn'] ?? '');
    $annee   = trim($_POST['annee'] ?? '');

    if (empty($titre) || empty($auteur)) {
        $erreur = "Le titre et l'auteur sont obligatoires.";
    } else {
        // Vérifier si un livre avec ce titre existe déjà (optionnel)
        $check = $pdo->prepare("SELECT COUNT(*) FROM livres WHERE titre = ? AND auteur = ?");
        $check->execute([$titre, $auteur]);
        if ($check->fetchColumn() > 0) {
            $erreur = "Un livre avec ce titre et cet auteur existe déjà. Modifiez le titre.";
        } else {
            $sql = "INSERT INTO livres (titre, auteur, editeur, isbn, annee) VALUES (:titre, :auteur, :editeur, :isbn, :annee)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre'   => $titre,
                ':auteur'  => $auteur,
                ':editeur' => $editeur,
                ':isbn'    => $isbn,
                ':annee'   => $annee
            ]);
            $_SESSION['flash_success'] = "Livre « " . htmlspecialchars($titre) . " » copié avec succès.";
            rediriger('index.php');
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<h2><i class="fas fa-copy"></i> Copier un livre</h2>

<p style="margin-bottom: 1.5rem;">Vous êtes en train de copier le livre : <strong><?= htmlspecialchars($livre['titre']) ?></strong> (auteur : <?= htmlspecialchars($livre['auteur']) ?>).</p>

<?php if ($erreur): ?>
    <div class="alert alert-error" style="background:#fee2e2; padding:1rem; border-radius:12px; margin-bottom:1rem; color:#b91c1c;">
        ❌ <?= htmlspecialchars($erreur) ?>
    </div>
<?php endif; ?>

<div class="form-card" style="max-width: 700px; margin: 0 auto;">
    <form method="post">
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($livre['titre'] . ' (copie)') ?>">
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" required value="<?= htmlspecialchars($livre['auteur']) ?>">
        </div>

        <div class="form-group">
            <label for="editeur">Éditeur</label>
            <input type="text" id="editeur" name="editeur" value="<?= htmlspecialchars($livre['editeur'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($livre['isbn'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="annee">Année de publication</label>
            <input type="number" id="annee" name="annee" value="<?= htmlspecialchars($livre['annee'] ?? '') ?>" min="0" max="<?= date('Y') ?>">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn"><i class="fas fa-copy"></i> Copier le livre</button>
            <a href="index.php" class="btn" style="background:#6c757d;"><i class="fas fa-times"></i> Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>