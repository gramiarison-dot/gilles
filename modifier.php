<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$erreur = '';
$succes = '';

// Récupération de l'ID du livre
$id_livre = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_livre) {
    $_SESSION['flash_error'] = "ID de livre invalide.";
    rediriger('index.php');
}

// Récupération des données actuelles
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt->execute([$id_livre]);
$livre = $stmt->fetch();

if (!$livre) {
    $_SESSION['flash_error'] = "Livre introuvable.";
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
        try {
            $sql = "UPDATE livres SET titre = :titre, auteur = :auteur, editeur = :editeur, isbn = :isbn, annee = :annee WHERE id_livre = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre'   => $titre,
                ':auteur'  => $auteur,
                ':editeur' => $editeur,
                ':isbn'    => $isbn,
                ':annee'   => $annee,
                ':id'      => $id_livre
            ]);
            $_SESSION['flash_success'] = "Livre « " . htmlspecialchars($titre) . " » modifié avec succès.";
            rediriger('index.php');
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<style>
    .form-card { max-width: 700px; margin: 0 auto; background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 0.3rem; color: #8B4513; }
    .form-group input { width: 100%; padding: 0.6rem; border: 1px solid #ced4da; border-radius: 0.5rem; }
    .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 2rem; text-decoration: none; font-weight: 500; transition: 0.2s; border: none; cursor: pointer; }
    .btn-primary { background: #0d6efd; color: white; }
    .btn-primary:hover { background: #0b5ed7; transform: translateY(-2px); }
    .btn-secondary { background: #6c757d; color: white; }
    .btn-secondary:hover { background: #5a6268; transform: translateY(-2px); }
    .alert-error { background: #fee2e2; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; color: #b91c1c; }
</style>

<h2><i class="fas fa-edit"></i> Modifier un livre</h2>

<?php if ($erreur): ?>
    <div class="alert-error">❌ <?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="post">
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($livre['titre']) ?>">
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <a href="../dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>

        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>