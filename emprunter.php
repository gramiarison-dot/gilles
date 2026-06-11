<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!estConnecte() || !estBibliothecaire()) {
    rediriger('../login.php');
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyer et sécuriser les entrées
    $id_adherent = filter_input(INPUT_POST, 'id_adherent', FILTER_VALIDATE_INT);
    $code_barre   = trim($_POST['code_barre'] ?? '');

    if (!$id_adherent) {
        $erreur = "ID adhérent invalide.";
    } elseif (empty($code_barre)) {
        $erreur = "Code barre requis.";
    } else {
        // Vérifier l'adhérent (en supposant que la colonne s'appelle id_adherent sans accent)
        $stmt = $pdo->prepare("SELECT * FROM adherents WHERE id_adhérent = ? AND actif = 1");
        $stmt->execute([$id_adhérent]);
        $adhérent = $stmt->fetch();

        if (!$adhérent) {
            $erreur = "Adhérent introuvable ou inactif.";
        } elseif (!verifierQuota($pdo, $id_adhérent)) {
            $erreur = "Quota d'emprunts atteint pour cet adhérent.";
        } else {
            // Vérifier l'exemplaire
            $stmt = $pdo->prepare("
                SELECT e.*, l.titre 
                FROM exemplaires e 
                JOIN livres l ON e.id_livre = l.id_livre 
                WHERE e.code_barre = ? AND e.disponible = 1
            ");
            $stmt->execute([$code_barre]);
            $exemplaire = $stmt->fetch();

            if (!$exemplaire) {
                $erreur = "Exemplaire non disponible (code barre invalide ou déjà emprunté).";
            } else {
                // Créer l'emprunt
                $date_retour_prevue = date('Y-m-d', strtotime('+15 days'));
                $statut = 'en_cours'; // Valeur par défaut pour un emprunt actif

                $stmt = $pdo->prepare("
                    INSERT INTO emprunts (id_adherent, id_exemplaire, date_emprunt, date_retour_prevue, statut) 
                    VALUES (?, ?, NOW(), ?, ?)
                ");
                $stmt->execute([$id_adherent, $exemplaire['id_exemplaire'], $date_retour_prevue, $statut]);

                // Marquer l'exemplaire comme indisponible
                $pdo->prepare("UPDATE exemplaires SET disponible = 0 WHERE id_exemplaire = ?")
                    ->execute([$exemplaire['id_exemplaire']]);

                // Message de succès et redirection
                $_SESSION['flash_success'] = "Emprunt enregistré - Livre : " . htmlspecialchars($exemplaire['titre']);
                rediriger('liste_emprunts.php'); // ou 'index.php' selon votre structure
            }
        }
    }
}

include '../includes/header.php';
?>

<h2><i class="fas fa-book-open"></i> Enregistrer un emprunt</h2>

<?php if ($succes): ?>
    <div class="alert alert-success"><?= $succes ?></div>
<?php endif; ?>

<?php if ($erreur): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<div class="form-card" style="max-width: 500px;">
    <form method="post">
        <div class="form-group">
            <label for="id_adherent">ID Adhérent *</label>
            <input type="number" id="id_adherent" name="id_adherent" required 
                   value="<?= htmlspecialchars($_POST['id_adherent'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="code_barre">Code barre de l'exemplaire *</label>
            <input type="text" id="code_barre" name="code_barre" required 
                   value="<?= htmlspecialchars($_POST['code_barre'] ?? '') ?>">
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn"><i class="fas fa-check"></i> Emprunter</button>
            <a href="liste_emprunts.php" class="btn" style="background:#6c757d;">Annuler</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>