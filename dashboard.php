<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
if (!estConnecte()) rediriger('login.php');
include 'includes/header.php';
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
        --jaune: #ffc107;
        --blanc: #ffffff;
    }

    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    h2 {
        color: var(--bleu);
        border-left: 6px solid var(--orange);
        padding-left: 1rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    h3 {
        color: var(--violet);
        margin: 2rem 0 1rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    /* Cartes statistiques */
    .cards-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .card {
        flex: 1;
        min-width: 200px;
        background: var(--blanc);
        border-radius: 1.2rem;
        padding: 1.5rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-top: 5px solid;
        text-align: center;
    }

    .card:nth-child(1) { border-top-color: var(--cyan); }
    .card:nth-child(2) { border-top-color: var(--jaune); }
    .card:nth-child(3) { border-top-color: var(--rouge); }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1);
    }

    .card h3 {
        margin: 0 0 0.5rem;
        font-size: 1.2rem;
        color: var(--brown);
    }

    .card p {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        color: var(--gris);
    }

    .card small {
        color: var(--gris);
        font-size: 0.8rem;
    }

    /* Genres */
    .genres-section {
        background: var(--blanc);
        border-radius: 1.2rem;
        padding: 1.2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .genres-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--brown);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .genre-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .genre-btn {
        background: var(--gris-clair, #e9ecef);
        padding: 0.5rem 1.2rem;
        border-radius: 3rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        color: var(--gris);
        border: 1px solid #dee2e6;
    }

    .genre-btn:hover {
        background: var(--violet);
        color: white;
        transform: translateY(-2px);
    }

    /* Import/Export */
    .import-export {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--blanc);
        border-radius: 1.2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .import-export .btn {
        background: var(--gris-clair, #e9ecef);
        color: var(--gris);
        border: none;
    }

    .import-export .btn:hover {
        background: var(--cyan);
        color: white;
    }

    /* Tableau moderne */
    .modern-table {
        width: 100%;
        background: var(--blanc);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-collapse: collapse;
    }

    .modern-table thead tr {
        background: linear-gradient(135deg, var(--bleu), var(--violet));
        color: white;
    }

    .modern-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .modern-table td {
        padding: 0.8rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .modern-table tbody tr:hover {
        background-color: #fff3cd;
        transition: 0.2s;
    }

    /* Boutons génériques */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cards-grid {
            flex-direction: column;
        }
        .modern-table, .modern-table thead, .modern-table tbody, .modern-table th, .modern-table td, .modern-table tr {
            display: block;
        }
        .modern-table thead tr {
            display: none;
        }
        .modern-table tr {
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--cyan);
            border-radius: 12px;
            padding: 0.5rem;
            background: white;
        }
        .modern-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        .modern-table td:before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--brown);
        }
    }
</style>

<div class="dashboard-container">
    <h2><i class="fas fa-tachometer-alt" style="color: var(--orange);"></i> Tableau de bord</h2>

    <!-- Cartes statistiques -->
    <div class="cards-grid">
        <div class="card">
            <h3><i class="fas fa-book"></i> Livres</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM livres");
            $nbLivres = $stmt->fetchColumn();
            echo "<p>$nbLivres</p><small>ouvrages disponibles</small>";
            ?>
        </div>
        <div class="card">
            <h3><i class="fas fa-users"></i> Adhérents</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM adherents");
            $nbAdherents = $stmt->fetchColumn();
            echo "<p>$nbAdherents</p><small>adhérents actifs</small>";
            ?>
        </div>
        <div class="card">
            <h3><i class="fas fa-hand-holding-heart"></i> Emprunts en cours</h3>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE statut='en cours'");
            $nbEmprunts = $stmt->fetchColumn();
            echo "<p>$nbEmprunts</p><small>emprunts non retournés</small>";
            ?>
        </div>
    </div>

    <!-- Filtres par genre -->
    <div class="genres-section">
        <div class="genres-title"><i class="fas fa-tags" style="color: var(--orange);"></i> Filtrer par genre</div>
        <div class="genre-buttons">
            <?php
            // Liste des genres disponibles (depuis la table ou statique)
            // On suppose qu'il existe une colonne 'genre' dans la table livres
            $genres = ['Romans', 'Sciences', 'Histoire', 'Jeunesse', 'Informatique', 'Arts'];
            foreach ($genres as $genre) {
                echo '<a href="livres/index.php?genre=' . urlencode($genre) . '" class="genre-btn">' . htmlspecialchars($genre) . '</a>';
            }
            // Bouton "Tous"
            echo '<a href="livres/index.php" class="genre-btn" style="background: var(--violet); color:white;">Tous les livres</a>';
            ?>
        </div>
        <p class="small text-muted" style="margin-top:0.8rem; color: var(--gris);"><i class="fas fa-info-circle"></i> Cliquez sur un genre pour voir les livres correspondants.</p>
    </div>

    <!-- Import / Export -->
    <div class="import-export">
        <span style="font-weight:600; margin-right:1rem;"><i class="fas fa-exchange-alt"></i> Import / Export :</span>
        <a href="export_livres.php?format=csv" class="btn"><i class="fas fa-file-csv"></i> CSV</a>
        <a href="export_livres.php?format=json" class="btn"><i class="fas fa-code"></i> JSON</a>
        <a href="export_livres.php?format=xml" class="btn"><i class="fas fa-file-code"></i> XML</a>
        <a href="export_livres.php?format=xlsx" class="btn"><i class="fas fa-file-excel"></i> Excel (XLSX)</a>
        <button onclick="document.getElementById('importForm').style.display='block'" class="btn"><i class="fas fa-upload"></i> Importer (CSV)</button>
    </div>

    <!-- Formulaire d'import (caché par défaut) -->
    <div id="importForm" style="display:none; background:var(--blanc); border-radius:1rem; padding:1rem; margin-bottom:2rem;">
        <form method="post" action="import_livres.php" enctype="multipart/form-data">
            <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
                <input type="file" name="csv_file" accept=".csv" required>
                <button type="submit" class="btn" style="background: var(--vert); color:white;"><i class="fas fa-upload"></i> Importer</button>
                <button type="button" onclick="document.getElementById('importForm').style.display='none'" class="btn">Annuler</button>
            </div>
            <small>Format CSV attendu : titre, auteur, editeur, isbn, annee, genre (séparateur virgule, en-têtes optionnels)</small>
        </form>
    </div>

    <!-- Derniers emprunts -->
    <h3><i class="fas fa-clock" style="color: var(--rouge);"></i> Derniers emprunts</h3>
    <table class="modern-table">
        <thead>
            <tr><th>Adhérent</th><th>Livre</th><th>Date emprunt</th><th>Retour prévu</th></tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT a.nom, a.prenom, l.titre, e.date_emprunt, e.date_retour_prevue 
                FROM emprunts e
                JOIN adherents a ON e.id_adhérent = a.id_adhérent
                JOIN exemplaires ex ON e.id_exemplaire = ex.id_exemplaire
                JOIN livres l ON ex.id_livre = l.id_livre
                WHERE e.statut = 'en cours'
                ORDER BY e.date_emprunt DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch()):
        ?>
            <tr>
                <td data-label="Adhérent"><?= htmlspecialchars($row['nom'] . ' ' . $row['prenom']) ?></td>
                <td data-label="Livre"><?= htmlspecialchars($row['titre']) ?></td>
                <td data-label="Date emprunt"><?= $row['date_emprunt'] ?></td>
                <td data-label="Retour prévu"><?= $row['date_retour_prevue'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    // Afficher le formulaire d'import
    function showImport() {
        document.getElementById('importForm').style.display = 'block';
    }
</script>

<?php include 'includes/footer.php'; ?>