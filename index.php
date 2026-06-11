<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!estConnecte() || !estBibliothecaire()) rediriger('../login.php');
include '../includes/header.php';

$sql = "SELECT e.id_emprunt, a.nom, a.prenom, l.titre, e.date_emprunt, e.date_retour_prevue, e.date_retour_reelle, e.statut
        FROM emprunts e
        JOIN adherents a ON e.id_adhérent = a.id_adhérent
        JOIN exemplaires ex ON e.id_exemplaire = ex.id_exemplaire
        JOIN livres l ON ex.id_livre = l.id_livre
        ORDER BY e.date_emprunt DESC";
$stmt = $pdo->query($sql);
?>
<h2>Liste des emprunts</h2>
<a href="emprunter.php" class="btn">➕ Nouvel emprunt</a>
<table>
    <tr><th>Adhérent</th><th>Livre</th><th>Emprunt le</th><th>Retour prévu</th><th>Retour réel</th><th>Statut</th><th>Action</th></tr>
    <?php while($row = $stmt->fetch()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nom'].' '.$row['prenom']) ?></td>
        <td><?= htmlspecialchars($row['titre']) ?></td>
        <td><?= $row['date_emprunt'] ?></td>
        <td><?= $row['date_retour_prevue'] ?></td>
        <td><?= $row['date_retour_reelle'] ?? '-' ?></td>
        <td><?= $row['statut'] ?></td>
        <td>
            <?php if($row['statut'] == 'en cours'): ?>
                <a href="retourner.php?id=<?= $row['id_emprunt'] ?>" class="btn btn-success" onclick="return confirm('Valider le retour ?')">Retour</a>
                <a href="prolonger.php?id=<?= $row['id_emprunt'] ?>" class="btn btn-warning">Prolonger</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>