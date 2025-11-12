-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 15 mai 2025 à 17:09
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `karabusiness`
--

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `idd` int(11) NOT NULL,
  `nomd` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departements`
--

INSERT INTO `departements` (`idd`, `nomd`) VALUES
(1, 'informatique'),
(2, 'RH'),
(3, 'comptabilite'),
(4, 'direction'),
(5, 'commercial');

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `ide` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `idd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`ide`, `nom`, `prenom`, `email`, `telephone`, `idd`) VALUES
(1, 'dupont', 'jean', 'jean.dupont@karabusiness.com', '0123456789', 1),
(2, 'martin', 'sophie', 'sophie.martin@karabusiness.com', '0123456790', 2),
(3, 'durand', 'pierre', 'pierre.durand@karabusiness.com', '0123456791', 3),
(4, 'lefebvre', 'marie', 'marie.lefebvre@karabusiness.com', '0123456792', 4),
(5, 'Rang Nation', 'THOMAS', 'thomas.moreau@karabusiness.com', '0123456793', 5);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `idu` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `lastlogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`idu`, `username`, `password`, `nom`, `prenom`, `email`, `role`, `lastlogin`) VALUES
(1, 'admin', 'admin123', 'admin', 'admin', 'admin@karabusiness.com', 'admin', '2025-05-15 15:05:24'),
(3, 'petitmass', 'user123', 'user', 'user', 'user@karabusiness.com', 'user', '2025-05-15 13:52:30');

-- --------------------------------------------------------

--
-- Structure de la table `visiteurs`
--

CREATE TABLE `visiteurs` (
  `idv` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `type_piece` varchar(50) NOT NULL,
  `numero_piece` varchar(50) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `date_entree` datetime NOT NULL,
  `date_sortie` datetime DEFAULT NULL,
  `ide` int(11) DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `visiteurs`
--

INSERT INTO `visiteurs` (`idv`, `nom`, `prenom`, `telephone`, `email`, `type_piece`, `numero_piece`, `motif`, `date_entree`, `date_sortie`, `ide`, `statut`) VALUES
(1, 'smith', 'john', '0687654321', 'john@example.com', 'passeport', 'AB123456', 'reunion commerciale', '2025-05-10 08:55:56', '2025-05-11 08:55:56', 5, 'sorti'),
(2, 'johnson', 'bob', '0687654322', 'bob@example.com', 'cni', '123AB456789', 'entretien embauche', '2025-05-11 08:55:56', '2025-05-14 16:50:20', 2, 'sorti'),
(3, 'williams', 'anna', '0687654323', 'anna@example.com', 'permis', 'P123456', 'maintenance', '2025-05-12 08:55:56', '2025-05-14 16:49:09', 1, 'sorti'),
(4, 'Diagne', 'Massamba', '777578012', 'masccompte133@gmail.com', 'cni', '15661568468', 'chill', '2025-05-14 10:44:27', '2025-05-14 16:49:00', 5, 'sorti'),
(5, 'enouani', 'jerem', '8948958', 'jeremie@visiteur.com', 'passeport', '998595952', 'jouer à la play', '2025-05-14 17:28:13', '2025-05-14 16:44:15', 4, 'sorti');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`idd`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`ide`),
  ADD KEY `idd` (`idd`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`idu`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `visiteurs`
--
ALTER TABLE `visiteurs`
  ADD PRIMARY KEY (`idv`),
  ADD KEY `ide` (`ide`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `departements`
--
ALTER TABLE `departements`
  MODIFY `idd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `ide` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `visiteurs`
--
ALTER TABLE `visiteurs`
  MODIFY `idv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `employes_ibfk_1` FOREIGN KEY (`idd`) REFERENCES `departements` (`idd`);

--
-- Contraintes pour la table `visiteurs`
--
ALTER TABLE `visiteurs`
  ADD CONSTRAINT `visiteurs_ibfk_1` FOREIGN KEY (`ide`) REFERENCES `employes` (`ide`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
