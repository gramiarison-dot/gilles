<?php
session_start();
require_once 'includes/functions.php';
include 'includes/header.php';
?>
<h2>Bienvenue à la bibliothèque</h2>
<p>Ce système permet la gestion des adhérents, des livres, des emprunts et des réservations.</p>
<p>Veuillez vous <a href="login.php">connecter</a> pour accéder à l'administration.</p>
<?php include 'includes/footer.php'; ?>