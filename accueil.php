<?php
session_start();
require_once 'includes/functions.php';
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
        --gris-clair: #f8f9fa;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    }

    /* Hero section */
    .hero {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, var(--bleu), var(--violet));
        border-radius: 2rem;
        margin: 2rem 1.5rem;
        padding: 3rem 2rem;
        color: var(--blanc);
        box-shadow: 0 20px 35px rgba(0,0,0,0.2);
    }

    .hero-content {
        flex: 1;
        min-width: 250px;
    }

    .hero-content h2 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .hero-description {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.5;
        opacity: 0.95;
    }

    .btn-large {
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
        background: var(--jaune);
        color: #1e2a3a;
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    }

    .btn-large:hover {
        background: var(--orange);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.25);
    }

    .hero-illustration {
        flex: 0.5;
        text-align: center;
    }

    .books-stack i {
        font-size: 3.5rem;
        margin: 0 0.3rem;
        filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3));
        transition: transform 0.2s;
    }

    .books-stack i:hover {
        transform: scale(1.1);
    }

    /* Features grid */
    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        margin: 3rem 1.5rem;
    }

    .feature-card {
        background: var(--blanc);
        border-radius: 1.5rem;
        padding: 1.8rem;
        text-align: center;
        flex: 1;
        min-width: 200px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-bottom: 4px solid var(--cyan);
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.1);
        border-bottom-color: var(--orange);
    }

    .feature-card i {
        color: var(--violet);
        margin-bottom: 1rem;
        transition: color 0.3s;
    }

    .feature-card:hover i {
        color: var(--rouge);
    }

    .feature-card h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        color: var(--brown);
    }

    .feature-card p {
        color: var(--gris);
    }

    /* Books showcase */
    .books-showcase {
        background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
        border-radius: 2rem;
        margin: 2rem 1.5rem;
        padding: 2rem;
        text-align: center;
    }

    .books-showcase h3 {
        font-size: 1.8rem;
        color: var(--bleu);
        margin-bottom: 1.5rem;
    }

    .book-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.2rem;
    }

    .book-item {
        background: var(--blanc);
        padding: 1rem 1.5rem;
        border-radius: 3rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 500;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        transition: all 0.2s;
        cursor: default;
        border: 1px solid var(--cyan);
    }

    .book-item i {
        font-size: 1.4rem;
        color: var(--orange);
    }

    .book-item:hover {
        transform: scale(1.05);
        background: var(--jaune);
        color: #1e2a3a;
        border-color: var(--rouge);
    }

    .book-item:hover i {
        color: var(--rouge);
    }

    @media (max-width: 768px) {
        .hero {
            flex-direction: column;
            text-align: center;
        }
        .hero-content h2 {
            font-size: 1.8rem;
        }
        .features {
            flex-direction: column;
        }
    }
</style>

<div class="hero">
    <div class="hero-content">
        <h2>Bienvenue à la Médiathèque</h2>
        <p class="hero-description">
            Gérez vos emprunts, réservez des livres et découvrez des milliers d'ouvrages en quelques clics.
            Une plateforme moderne dédiée aux bibliothécaires et aux lecteurs.
        </p>
        <?php if (!isset($_SESSION['user_id']) || !estConnecte()): ?>
            <a href="login.php" class="btn-large"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn-large"><i class="fas fa-chalkboard-user"></i> Accéder au tableau de bord</a>
        <?php endif; ?>
    </div>
    <div class="hero-illustration">
        <div class="books-stack">
            <i class="fas fa-book-open"></i>
            <i class="fas fa-book"></i>
            <i class="fas fa-book-reader"></i>
            <i class="fas fa-bookmark"></i>
            <i class="fas fa-layer-group"></i>
        </div>
    </div>
</div>

<div class="features">
    <div class="feature-card">
        <i class="fas fa-users fa-3x"></i>
        <h3>Gestion des adhérents</h3>
        <p>Inscriptions, modifications, suivi des prêts.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-book fa-3x"></i>
        <h3>Catalogue complet</h3>
        <p>Recherche avancée par titre, auteur, genre.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-hand-holding-heart fa-3x"></i>
        <h3>Emprunts & retours</h3>
        <p>Gestion simplifiée des prêts et des prolongations.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-bell fa-3x"></i>
        <h3>Réservations</h3>
        <p>Réservez un livre indisponible, soyez notifié.</p>
    </div>
</div>


<div class="books-showcase">
    <h3><i class="fas fa-book-open"></i> Notre catalogue en images</h3>
    <div class="book-grid">
        <div class="book-item"><i class="fas fa-book"></i><span>Romans</span></div>
        <div class="book-item"><i class="fas fa-flask"></i><span>Sciences</span></div>
        <div class="book-item"><i class="fas fa-history"></i><span>Histoire</span></div>
        <div class="book-item"><i class="fas fa-child"></i><span>Jeunesse</span></div>
        <div class="book-item"><i class="fas fa-laptop-code"></i><span>Informatique</span></div>
        <div class="book-item"><i class="fas fa-palette"></i><span>Arts</span></div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>