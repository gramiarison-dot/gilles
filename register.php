<?php
include './config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - HERO INFORMATIQUE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 40, 80, 0.7), rgba(0, 40, 80, 0.7)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            width: 450px;
        }
    </style>
</head>
<body>

<div class="register-card p-4 shadow">
    <h4 class="text-center fw-bold mb-4">Créer un Compte</h4>
    
    <form method="POST">
        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-info text-white"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="username" required>
            </div>
        </div>

      <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-info text-white"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
        </div>

 <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-info text-white"><i class="fa-solid fa-lock"></i></span>
                <input type="role" name="role" class="form-control" placeholder="role" required>
            </div>
        </div>

        <div class="mb-4">
            <div class="input-group">
          
            </div>
        </div>

        <button class="btn btn-primary w-100 py-2 fw-bold" name="register">S'INSCRIRE</button>
    </form>
<?php
if(isset($_POST['register'])){
    $p=password_hash($_POST['password'],PASSWORD_DEFAULT);
    mysqli_query($conn,"INSERT INTO users VALUES(NULL,'$_POST[username]','$_POST[password]','$_POST[rôle]')");
    echo "<div class='alert alert-success mt-2'>Compte créé</div>";
    
    }
?>
    
    <div class="text-center mt-3">
        <a href="./login.php" class="small text-decoration-none">Déjà inscrit ? Se connecter</a>
    </div>
</div>

</body>
</html>