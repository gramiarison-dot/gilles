-- Création de la base de données
CREATE DATABASE IF NOT EXISTS bibliotheque;
USE bibliotheque;

-- Table des adhérents
CREATE TABLE adherents (
    id_adhérent INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    date_inscription DATE DEFAULT CURRENT_DATE,
    quota_max INT DEFAULT 5,
    actif BOOLEAN DEFAULT TRUE
);

-- Table des livres
CREATE TABLE livres (
    id_livre INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(200) NOT NULL,
    auteur VARCHAR(100),
    editeur VARCHAR(100),
    isbn VARCHAR(13),
    annee INT
);

-- Table des exemplaires
CREATE TABLE exemplaires (
    id_exemplaire INT PRIMARY KEY AUTO_INCREMENT,
    id_livre INT NOT NULL,
    code_barre VARCHAR(20) UNIQUE NOT NULL,
    etat ENUM('neuf', 'bon', 'abime') DEFAULT 'bon',
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_livre) REFERENCES livres(id_livre) ON DELETE CASCADE
);

-- Table des emprunts
CREATE TABLE emprunts (
    id_emprunt INT PRIMARY KEY AUTO_INCREMENT,
    id_adhérent INT NOT NULL,
    id_exemplaire INT NOT NULL,
    date_emprunt DATE DEFAULT CURRENT_DATE,
    date_retour_prevue DATE,
    date_retour_reelle DATE,
    statut ENUM('en cours', 'cloture') DEFAULT 'en cours',
    FOREIGN KEY (id_adhérent) REFERENCES adherents(id_adhérent),
    FOREIGN KEY (id_exemplaire) REFERENCES exemplaires(id_exemplaire)
);

-- Table des réservations
CREATE TABLE reservations (
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    id_adhérent INT NOT NULL,
    id_livre INT NOT NULL,
    date_reservation DATE DEFAULT CURRENT_DATE,
    notifie BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_adhérent) REFERENCES adherents(id_adhérent),
    FOREIGN KEY (id_livre) REFERENCES livres(id_livre) ON DELETE CASCADE
);

-- Création d'un compte bibliothécaire par défaut (mot de passe : admin123)
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('bibliothecaire', 'adherent') DEFAULT 'bibliothecaire'
);
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bibliothecaire');
-- Le hash correspond à "password" (pour test, en production changez-le)
-- Pour admin123 : hash à générer avec password_hash('admin123', PASSWORD_DEFAULT)

-- Quelques données de test
INSERT INTO livres (titre, auteur, editeur, isbn, annee) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Gallimard', '9782070612758', 1943),
('1984', 'George Orwell', 'Gallimard', '9782070368228', 1949),
('Les Misérables', 'Victor Hugo', 'Livre de Poche', '9782253004221', 1862);

INSERT INTO exemplaires (id_livre, code_barre, etat, disponible) VALUES
(1, 'LP1001', 'neuf', TRUE),
(1, 'LP1002', 'bon', TRUE),s
(2, '1984001', 'bon', TRUE),
(3, 'LM001', 'abime', TRUE);