<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Bibliothèque - Gestion moderne</title>
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ===== STYLES MODERNES (identiques à votre version) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1e293b;
            line-height: 1.5;
        }

        .navbar {
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03), 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .logo a {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            color: #334155;
            font-weight: 500;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links a:hover {
            color: #3b82f6;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #f1f5f9;
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.9rem;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-logout:hover {
            color: #dc2626;
        }

        .container {
            max-width: 1280px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Cartes, table, formulaires (conservés) */
        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.05);
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #edf2f7;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
        }
        .card h3 {
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #0f172a;
        }
        .card p {
            font-size: 2rem;
            font-weight: 700;
            color: #3b82f6;
            margin: 0.5rem 0 0;
        }
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin: 2rem 0;
        }
        .modern-table {
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border-collapse: collapse;
        }
        .modern-table th {
            background: #f8fafc;
            padding: 1rem 1.2rem;
            text-align: left;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }
        .modern-table td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .modern-table tr:hover td {
            background-color: #fefce8;
        }
        .form-card {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #0f172a;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            font-family: inherit;
            transition: 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }
        .btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn:hover {
            background: #2563eb;
            transform: scale(1.02);
        }
        .alert {
            background: #fee2e2;
            border-left: 5px solid #ef4444;
            padding: 1rem;
            border-radius: 16px;
            color: #b91c1c;
            margin-bottom: 1.5rem;
        }
        h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.3px;
        }
        h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
        }
        footer {
            text-align: center;
            margin-top: 4rem;
            padding: 2rem;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        @media (max-width: 700px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .container {
                padding: 0 1rem;
            }
            .modern-table th, .modern-table td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <a href="index.php"><i class="fas fa-book-open"></i> Bibliothèque</a>
    </div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id']) && function_exists('estConnecte') && estConnecte()): ?>
            <!-- Liens communs -->
            <a href="dashboard.php"><i class="fas fa-chalkboard-user"></i> Tableau de bord</a>

            <!-- Liens selon le rôle (bibliothécaire ou adhérent) -->
            <?php if (function_exists('estBibliothecaire') && estBibliothecaire()): ?>
                <a href="/bibliotheque/adherents/"><i class="fas fa-users"></i> Adhérents</a>
                <a href="/bibliotheque/livres/"><i class="fas fa-book"></i> Livres</a>
                <a href="/bibliotheque/exemplaires/"><i class="fas fa-copy"></i> Exemplaires</a>
                <a href="/bibliotheque/emprunts/"><i class="fas fa-hand-holding-heart"></i> Emprunts</a>
                <a href="/bibliotheque/reservations/"><i class="fas fa-calendar-check"></i> Réservations</a>
            <?php else: ?>
                <a href="/bibliotheque/mes-emprunts.php"><i class="fas fa-book-reader"></i> Mes emprunts</a>
                <a href="/bibliotheque/mes-reservations.php"><i class="fas fa-clock"></i> Mes réservations</a>
            <?php endif; ?>

            <!-- Zone utilisateur + déconnexion -->
            <div class="user-info">
                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                <form method="post" action="./logout.php" style="display: inline;">
                   
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                <a href="./compte.php">Creer compte</a>
            </form>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-key"></i> Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<main class="container">