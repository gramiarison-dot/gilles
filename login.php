<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (estConnecte()) {
    rediriger('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        rediriger('dashboard.php');
    } else {
        $erreur = "Identifiants incorrects";
    }
}
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Médiathèque</title>
    <link rel="stylesheet" href="assets/login.css">
    <style>
        /* Styles additionnels pour la mise en page à deux colonnes */
        .login-container {
            display: flex;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 2rem auto;
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
        }

        .login-form {
            flex: 1;
            padding: 2rem;
            background: #ffffff;
        }

        .books-visual {
            flex: 1;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
        }

        /* Adaptation du style existant */
        .form-card {
            box-shadow: none;
            margin: 0;
            padding: 0;
            background: transparent;
        }

        /* Style pour le schéma de livres */
        .books-schema {
            max-width: 100%;
        }

        .books-schema img {
            width: 100%;
            max-width: 350px;
            height: auto;
            border-radius: 1.5rem;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .books-schema img:hover {
            transform: scale(1.02);
        }

        .books-caption {
            margin-top: 1.5rem;
            font-size: 1rem;
            color: var(--brown, #8B4513);
            font-weight: 500;
        }

        .books-caption i {
            color: var(--orange, #fd7e14);
            margin-right: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 1rem;
            }
            .books-visual {
                padding: 1.5rem;
            }
            .books-schema img {
                max-width: 250px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Colonne gauche : formulaire de connexion -->
        <div class="login-form">
            <div class="form-card">
                <h2 style="text-align: center;"><i class="fas fa-lock text-primary"></i> Connexion</h2>
                <?php if (isset($erreur)): ?>
                    <div class="alert"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom d'utilisateur</label>
                        <input type="text" name="username" required autofocus>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" class="btn"><i class="fas fa-arrow-right-to-bracket"></i> Se connecter</button>
                        <a href="register.php" style="display: inline-block; margin-left: 1rem;"><i class="fas fa-user-plus"></i> Créer un compte</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Colonne droite : schéma réel des livres -->
        <div class="books-visual">
            <div class="books-schema">
                <!-- Remplacez le chemin ci-dessous par le chemin de votre propre image -->
                <img src="images/livres.jpg" alt="Schéma réel des livres" 
                     onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Collection+de+livres';">
                <!-- alternative : une pile d'icônes si l'image est absente -->
           
            </div>
            <div class="books-caption">
                <i class="fas fa-book-open"></i> Des milliers d’ouvrages à portée de main
            </div>
            <!-- Vous pouvez aussi intégrer un petit schéma SVG ou une illustration avec des icônes -->
            <div style="margin-top: 1rem; display: flex; gap: 0.8rem; justify-content: center; font-size: 2rem; color: var(--cyan);">
                <i class="fas fa-book"></i>
                <i class="fas fa-bookmark"></i>
                <i class="fas fa-layer-group"></i>
                <i class="fas fa-book-reader"></i>
            </div>
        </div>
    </div>

    <!-- Option : si vous préférez utiliser uniquement des icônes sans image externe, commentez la balise <img> ci-dessus et décommentez ce bloc -->
    <!-- 
    <div class="books-schema" style="text-align: center;">
        <div style="background: #f1f3f5; padding: 2rem; border-radius: 2rem;">
            <i class="fas fa-book fa-4x" style="color: var(--bleu); margin: 0 0.5rem;"></i>
            <i class="fas fa-book-open fa-4x" style="color: var(--orange); margin: 0 0.5rem;"></i>
            <i class="fas fa-bookmark fa-4x" style="color: var(--vert); margin: 0 0.5rem;"></i>
            <p style="margin-top: 1rem;">Bibliothèque visuelle</p>
        </div>
    </div>
    -->
</body>
</html>

<?php include 'includes/footer.php'; ?>