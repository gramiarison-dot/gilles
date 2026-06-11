<?php
session_start();
require_once 'config/database.php'; // Ce fichier doit définir $conn (MySQLi)

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirm_mdp = $_POST['confirm_mdp'] ?? '';
    $role = $_POST['role'] ?? 'client';

    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Email invalide.";
    } elseif ($mot_de_passe !== $confirm_mdp) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($mot_de_passe) < 6) {
        $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif (!in_array($role, ['client', 'admin'])) {
        $erreur = "Rôle invalide.";
    } else {
        // Vérifier si $conn est défini
        if (!isset($conn)) {
            $erreur = "Erreur de connexion à la base de données.";
        } else {
            // Vérifier si l'email existe déjà
            $check = mysqli_prepare($conn, "SELECT id FROM utilisateurs WHERE email = ?");
            mysqli_stmt_bind_param($check, 's', $email);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);
            if (mysqli_stmt_num_rows($check) > 0) {
                $erreur = "Cet email est déjà utilisé.";
            } else {
                // Hachage du mot de passe
                $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'sssss', $nom, $prenom, $email, $hash, $role);
                if (mysqli_stmt_execute($stmt)) {
                    $succes = "Compte créé avec succès. Vous pouvez vous connecter.";
                } else {
                    $erreur = "Erreur lors de l'inscription : " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_stmt_close($check);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Bibliothèque</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --bleu: #0d6efd;
            --vert: #198754;
            --rouge: #dc3545;
            --gris: #6c757d;
            --cyan: #0dcaf0;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #e9ecef);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
            border-top: 5px solid var(--cyan);
        }
        h2 {
            color: var(--bleu);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #495057;
        }
        input, select {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: 0.2s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(13,202,240,0.2);
        }
        .btn {
            width: 100%;
            padding: 0.7rem;
            background: var(--bleu);
            color: white;
            border: none;
            border-radius: 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }
        .alert {
            padding: 0.7rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .alert-success {
            background: #d1fae5;
            color: #0a5c3e;
        }
        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link a {
            color: var(--bleu);
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2><i class="fas fa-user-plus"></i> Créer un compte</h2>
    <?php if ($succes): ?>
        <div class="alert alert-success"><?= htmlspecialchars($succes) ?> <a href="login.php">Connectez-vous</a></div>
    <?php endif; ?>
    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Mot de passe (min. 6 caractères)</label>
            <input type="password" name="mot_de_passe" required>
        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirm_mdp" required>
        </div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" required>
                <option value="client" <?= (isset($_POST['role']) && $_POST['role'] === 'client') ? 'selected' : '' ?>>Client (lecteur)</option>
                <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Administrateur (bibliothécaire)</option>
            </select>
        </div>
        <button type="submit" class="btn"><i class="fas fa-check-circle"></i> S'inscrire</button>
    </form>
    <div class="login-link">
        Déjà un compte ? <a href="login.php">Se connecter</a>
    </div>
</div>
</body>
</html>