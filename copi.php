<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/bibliotheque/assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-book-open"></i> Bibliothèque Municipale</h1>
            <nav>
                <?php if(estConnecte()): ?>
                    <a href="/bibliotheque/dashboard.php"><i class="fas fa-chalkboard-user"></i> Tableau de bord</a>
                    <?php if(estBibliothecaire()): ?>
                        <a href="/bibliotheque/adherents/"><i class="fas fa-users"></i> Adhérents</a>
                        <a href="/bibliotheque/livres/"><i class="fas fa-book"></i> Livres</a>
                        <a href="/bibliotheque/exemplaires/"><i class="fas fa-copy"></i> Exemplaires</a>
                        <a href="/bibliotheque/emprunts/"><i class="fas fa-hand-holding-heart"></i> Emprunts</a>
                        <a href="/bibliotheque/reservations/"><i class="fas fa-calendar-check"></i> Réservations</a>
                    <?php else: ?>
                        <a href="/bibliotheque/mes-emprunts.php"><i class="fas fa-book-reader"></i> Mes emprunts</a>
                        <a href="/bibliotheque/mes-reservations.php"><i class="fas fa-clock"></i> Mes réservations</a>
                    <?php endif; ?>
                    <a href="/bibliotheque/logout.php"><i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($_SESSION['username']) ?> (déconnexion)</a>
                <?php else: ?>
                    <a href="/bibliotheque/login.php"><i class="fas fa-key"></i> Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">