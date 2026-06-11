<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
session_start();

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    rediriger('index.php');
}

// Récupérer le livre
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();
if (!$livre) {
    rediriger('index.php');
}

// Gestion de l'upload du PDF
$message = '';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $file = $_FILES['pdf_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $erreur = "Erreur lors de l'upload.";
    } elseif (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'pdf') {
        $erreur = "Le fichier doit être un PDF.";
    } else {
        $uploadDir = __DIR__ . '/../uploads/pdfs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = 'livre_' . $id . '.pdf';
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $message = "PDF associé au livre avec succès.";
        } else {
            $erreur = "Impossible de déplacer le fichier.";
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<style>
    :root {
        --bleu: #0d6efd;
        --rouge: #dc3545;
        --orange: #fd7e14;
        --vert: #198754;
        --brown: #8B4513;
        --gris: #6c757d;
        --cyan: #0dcaf0;
        --violet: #6f42c1;
        --blanc: #ffffff;
    }
    .details-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 20px 35px rgba(0,0,0,0.1);
    }
    h2 {
        color: var(--bleu);
        border-left: 5px solid var(--orange);
        padding-left: 1rem;
    }
    .info-group {
        margin-bottom: 1rem;
    }
    .info-label {
        font-weight: 600;
        color: var(--brown);
        width: 120px;
        display: inline-block;
    }
    .pdf-area {
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: var(--vert); color: white; }
    .btn-primary:hover { background: #0f6848; transform: translateY(-2px); }
    .btn-warning { background: var(--orange); color: white; }
    .btn-warning:hover { background: #e0650b; transform: translateY(-2px); }
    .btn-danger { background: var(--rouge); color: white; }
    .btn-danger:hover { background: #b02a37; }
</style>

<div class="details-container">
    <h2><i class="fas fa-book-open"></i> Détails du livre</h2>
    <div class="info-group"><span class="info-label">Titre :</span> <?= htmlspecialchars($livre['titre']) ?></div>
    <div class="info-group"><span class="info-label">Auteur :</span> <?= htmlspecialchars($livre['auteur']) ?></div>
    <div class="info-group"><span class="info-label">Éditeur :</span> <?= htmlspecialchars($livre['editeur']) ?></div>
    <div class="info-group"><span class="info-label">ISBN :</span> <?= htmlspecialchars($livre['isbn']) ?></div>
    <div class="info-group"><span class="info-label">Année :</span> <?= htmlspecialchars($livre['annee']) ?></div>
    <?php if (!empty($livre['genre'])): ?>
    <div class="info-group"><span class="info-label">Genre :</span> <?= htmlspecialchars($livre['genre']) ?></div>
    <?php endif; ?>

    <div class="pdf-area">
        <h3><i class="fas fa-file-pdf"></i> Fichier PDF associé</h3>
        <?php
        $pdfPath = __DIR__ . '/../uploads/pdfs/livre_' . $id . '.pdf';
        if (file_exists($pdfPath)): ?>
            <p>Un PDF est disponible pour ce livre.</p>
            <a href="download_pdf_livre.php?id=<?= $id ?>" class="btn btn-primary"><i class="fas fa-download"></i> Télécharger le PDF</a>
        <?php else: ?>
            <p>Aucun PDF associé à ce livre.</p>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data" style="margin-top:1rem;">
            <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
                <input type="file" name="pdf_file" accept=".pdf" required>
                <button type="submit" class="btn btn-warning"><i class="fas fa-upload"></i> Importer PDF</button>
            </div>
            <?php if ($message): ?>
                <p style="color: var(--vert);">✅ <?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <?php if ($erreur): ?>
                <p style="color: var(--rouge);">❌ <?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </form>
    </div>

    <div style="margin-top: 2rem;">
        <a href="index.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>