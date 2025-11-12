-- Création de la base de données
CREATE DATABASE IF NOT EXISTS karabusiness;
USE karabusiness;

-- Table des départements
CREATE TABLE IF NOT EXISTS departements (
    idd INT AUTO_INCREMENT PRIMARY KEY,
    nomd VARCHAR(100) NOT NULL
);

-- Table des employés
CREATE TABLE IF NOT EXISTS employes (
    ide INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telephone VARCHAR(20),
    idd INT,
    FOREIGN KEY (idd) REFERENCES departements(idd)
);

-- Table des visiteurs
CREATE TABLE IF NOT EXISTS visiteurs (
    idv INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    type_piece VARCHAR(50) NOT NULL,
    numero_piece VARCHAR(50) NOT NULL,
    motif VARCHAR(255) NOT NULL,
    date_entree DATETIME NOT NULL,
    date_sortie DATETIME,
    ide INT,
    statut VARCHAR(20) DEFAULT 'actif', -- actif, sorti, archive
    FOREIGN KEY (ide) REFERENCES employes(ide)
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    idu INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(20) NOT NULL DEFAULT 'user', -- admin, user
    lastlogin DATETIME
);

-- Insertion de données initiales pour les tests
-- Départements
INSERT INTO departements (nomd) VALUES 
('informatique'),
('rh'),
('comptabilite'),
('direction'),
('commercial');

-- Employés
INSERT INTO employes (nom, prenom, email, telephone, idd) VALUES
('dupont', 'jean', 'jean.dupont@karabusiness.com', '0123456789', 1),
('martin', 'sophie', 'sophie.martin@karabusiness.com', '0123456790', 2),
('durand', 'pierre', 'pierre.durand@karabusiness.com', '0123456791', 3),
('lefebvre', 'marie', 'marie.lefebvre@karabusiness.com', '0123456792', 4),
('moreau', 'thomas', 'thomas.moreau@karabusiness.com', '0123456793', 5);

-- Utilisateurs (mot de passe sans hashage comme demandé)
INSERT INTO utilisateurs (username, password, nom, prenom, email, role) VALUES
('admin', 'admin123', 'admin', 'admin', 'admin@karabusiness.com', 'admin'),
('user', 'user123', 'user', 'standard', 'user@karabusiness.com', 'user');

-- Quelques visiteurs pour les tests
INSERT INTO visiteurs (nom, prenom, telephone, email, type_piece, numero_piece, motif, date_entree, date_sortie, ide, statut) VALUES
('smith', 'john', '0687654321', 'john@example.com', 'passeport', 'AB123456', 'reunion commerciale', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 1 DAY, 5,  'sorti'),
('johnson', 'bob', '0687654322', 'bob@example.com', 'cni', '123AB456789', 'entretien embauche', NOW() - INTERVAL 1 DAY, NULL, 2, 'actif'),
('williams', 'anna', '0687654323', 'anna@example.com', 'permis', 'P123456', 'maintenance', NOW(), NULL, 1,  'actif');