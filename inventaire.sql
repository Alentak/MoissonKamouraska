-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 28 avr. 2020 à 03:11
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `inventaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_client`
--

DROP TABLE IF EXISTS `t_client`;
CREATE TABLE IF NOT EXISTS `t_client` (
  `CLI_ID` int(5) NOT NULL AUTO_INCREMENT,
  `CLI_DATE` date NOT NULL,
  `CLI_NOM` varchar(50) COLLATE latin1_bin NOT NULL,
  `CLI_PRENOM` varchar(50) COLLATE latin1_bin NOT NULL,
  `CLI_AGE` int(5) NOT NULL,
  `CLI_ADRESSE` varchar(100) COLLATE latin1_bin NOT NULL,
  `CLI_VILLE` varchar(50) COLLATE latin1_bin NOT NULL,
  `CLI_CP` int(5) NOT NULL,
  `CLI_TEL` varchar(50) COLLATE latin1_bin NOT NULL,
  `CLI_NBADULTE` int(5) NOT NULL,
  `CLI_NBENFANT` int(5) NOT NULL,
  `CLI_TAILLEFAMILLE` int(5) NOT NULL,
  `CLI_AIDESOC` int(10) NOT NULL,
  `CLI_CHOMAGE` int(10) NOT NULL,
  `CLI_PRETBOURSE` int(10) NOT NULL,
  `CLI_REVENUSAUTRES` int(10) NOT NULL,
  `CLI_REVENUSTOTAL` int(10) NOT NULL,
  `CLI_REFERENCE` varchar(50) COLLATE latin1_bin NOT NULL,
  `CLI_LOYER` int(10) NOT NULL,
  `CLI_ELEC` int(10) NOT NULL,
  `CLI_ASSURANCE` int(10) NOT NULL,
  `CLI_DEPTEL` int(10) NOT NULL,
  `CLI_DEPAUTRE` int(10) NOT NULL,
  `CLI_DEPTOTAL` int(10) NOT NULL,
  `CLI_AIDEALIM` tinyint(1) NOT NULL,
  `CLI_BENEVOLAT` tinyint(1) NOT NULL,
  `CLI_SIGNATURE` tinyint(1) NOT NULL,
  `CLI_DATESIGN` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CLI_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_client`
--

INSERT INTO `t_client` (`CLI_ID`, `CLI_DATE`, `CLI_NOM`, `CLI_PRENOM`, `CLI_AGE`, `CLI_ADRESSE`, `CLI_VILLE`, `CLI_CP`, `CLI_TEL`, `CLI_NBADULTE`, `CLI_NBENFANT`, `CLI_TAILLEFAMILLE`, `CLI_AIDESOC`, `CLI_CHOMAGE`, `CLI_PRETBOURSE`, `CLI_REVENUSAUTRES`, `CLI_REVENUSTOTAL`, `CLI_REFERENCE`, `CLI_LOYER`, `CLI_ELEC`, `CLI_ASSURANCE`, `CLI_DEPTEL`, `CLI_DEPAUTRE`, `CLI_DEPTOTAL`, `CLI_AIDEALIM`, `CLI_BENEVOLAT`, `CLI_SIGNATURE`, `CLI_DATESIGN`) VALUES
(15, '2020-04-01', 'Tasquier', 'Eléna', 20, 'La Closerie Place de l\'Europe', 'Saint Martin du Manoir', 76290, '0695203282', 2, 1, 2, 1, 1, 1, 1, 1, '1', 1, 1, 1, 1, 1, 1, 0, 1, 1, '0000-00-00 00:00:00'),
(16, '2020-04-01', 'Tasquier', 'Eléna', 21, 'La Closerie Place de l\'Europe', 'Saint Martin du Manoir', 76290, '0695203282', 2, 3, 5, 1, 1, 1, 1, 1, '1', 1, 1, 1, 1, 1, 1, 0, 1, 1, '0000-00-00 00:00:00'),
(17, '2020-04-09', 'Tasquier', 'Eléna', 22, 'La Closerie Place de l\'Europe', 'Saint Martin du Manoir', 76290, '0695203282', 2, 5, 7, 1, 1, 1, 1, 1, '1', 1, 1, 1, 1, 1, 1, 0, 1, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `t_clientautre`
--

DROP TABLE IF EXISTS `t_clientautre`;
CREATE TABLE IF NOT EXISTS `t_clientautre` (
  `CLIA_ID` int(10) NOT NULL AUTO_INCREMENT,
  `CLI_ID` int(10) NOT NULL,
  `CLIA_NOMPRENOM` varchar(100) COLLATE latin1_bin NOT NULL,
  `CLIA_DDN` date NOT NULL,
  `CLIA_LIEN` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`CLIA_ID`),
  KEY `CLI_ID` (`CLI_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_clientautre`
--

INSERT INTO `t_clientautre` (`CLIA_ID`, `CLI_ID`, `CLIA_NOMPRENOM`, `CLIA_DDN`, `CLIA_LIEN`) VALUES
(6, 16, 'Paul', '2020-04-01', 'dadxs'),
(7, 16, 'Maeva', '2020-04-02', 'fji'),
(8, 17, 'momo', '2020-04-01', 'bidule'),
(9, 17, 'jojo', '2020-04-01', 'machin'),
(10, 17, 'coco', '2020-04-08', 'chouette'),
(11, 17, 'dodo', '2020-04-18', 'zut');

-- --------------------------------------------------------

--
-- Structure de la table `t_groupe`
--

DROP TABLE IF EXISTS `t_groupe`;
CREATE TABLE IF NOT EXISTS `t_groupe` (
  `GRO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `GRO_Libelle` varchar(38) COLLATE latin1_bin DEFAULT NULL,
  `PRI_ID` int(11) DEFAULT NULL,
  `MRC_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`GRO_ID`),
  KEY `FK_Groupe_Priorite` (`PRI_ID`),
  KEY `FK_Groupe_MRC` (`MRC_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_groupe`
--

INSERT INTO `t_groupe` (`GRO_ID`, `GRO_Libelle`, `PRI_ID`, `MRC_ID`) VALUES
(1, 'Test', NULL, NULL),
(2, 'grp', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_historique`
--

DROP TABLE IF EXISTS `t_historique`;
CREATE TABLE IF NOT EXISTS `t_historique` (
  `HIS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TPR_ID` int(11) DEFAULT NULL,
  `GRO_ID` int(11) DEFAULT NULL,
  `HIS_PoidsUnitaire` float DEFAULT NULL,
  `HIS_DLC` date DEFAULT NULL,
  `HIS_NbProduit` int(11) DEFAULT NULL,
  `HIS_TypeEchange` char(1) COLLATE latin1_bin DEFAULT NULL,
  `HIS_Date` date DEFAULT NULL,
  PRIMARY KEY (`HIS_ID`),
  KEY `fk_historique_typeproduit` (`TPR_ID`),
  KEY `fk_historique_groupe` (`GRO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_historique`
--

INSERT INTO `t_historique` (`HIS_ID`, `TPR_ID`, `GRO_ID`, `HIS_PoidsUnitaire`, `HIS_DLC`, `HIS_NbProduit`, `HIS_TypeEchange`, `HIS_Date`) VALUES
(1, 1, 1, 0.6, '2020-04-17', 5, 'A', '2020-04-10'),
(2, 1, NULL, 0.6, '2020-04-17', 1, 'D', '2020-04-10'),
(3, 1, 2, 0.6, '2020-04-17', 1, 'D', '2020-04-10'),
(4, 1, 1, 0.6, '2020-04-30', 5, 'A', '2020-04-11'),
(5, 2, 1, 0.15, '2020-04-30', 35, 'A', '2020-04-11'),
(6, 1, 1, 0.6, '2020-06-28', 7, 'A', '2020-04-11'),
(7, 3, 1, 0.4, '2020-06-19', 15, 'A', '2020-04-12');

-- --------------------------------------------------------

--
-- Structure de la table `t_lot`
--

DROP TABLE IF EXISTS `t_lot`;
CREATE TABLE IF NOT EXISTS `t_lot` (
  `LOT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TPR_ID` int(11) DEFAULT NULL,
  `GRO_ID` int(11) DEFAULT NULL,
  `LOT_PoidsUnitaire` float DEFAULT NULL,
  `LOT_DLC` date DEFAULT NULL,
  `LOT_NbProduit` int(11) DEFAULT NULL,
  `LOT_Date` date DEFAULT NULL,
  PRIMARY KEY (`LOT_ID`),
  KEY `fk_lot_typeproduit` (`TPR_ID`),
  KEY `fk_lot_groupe` (`GRO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_lot`
--

INSERT INTO `t_lot` (`LOT_ID`, `TPR_ID`, `GRO_ID`, `LOT_PoidsUnitaire`, `LOT_DLC`, `LOT_NbProduit`, `LOT_Date`) VALUES
(1, 1, 1, 0.6, '2020-04-17', 3, '2020-04-10'),
(2, 1, 1, 0.6, '2020-04-30', 5, '2020-04-11'),
(3, 2, 1, 0.15, '2020-04-30', 35, '2020-04-11'),
(4, 1, 1, 0.6, '2020-06-28', 7, '2020-04-11'),
(5, 3, 1, 0.4, '2020-06-19', 15, '2020-04-12');

-- --------------------------------------------------------

--
-- Structure de la table `t_mois`
--

DROP TABLE IF EXISTS `t_mois`;
CREATE TABLE IF NOT EXISTS `t_mois` (
  `MOI_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MOI_Libelle` varchar(9) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`MOI_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_mois`
--

INSERT INTO `t_mois` (`MOI_ID`, `MOI_Libelle`) VALUES
(1, 'Janvier'),
(2, 'Fevrier'),
(3, 'Mars'),
(4, 'Avril'),
(5, 'Mai'),
(6, 'Juin'),
(7, 'Juillet'),
(8, 'Aout'),
(9, 'Septembre'),
(10, 'Octobre'),
(11, 'Novembre'),
(12, 'Decembre');

-- --------------------------------------------------------

--
-- Structure de la table `t_mrc`
--

DROP TABLE IF EXISTS `t_mrc`;
CREATE TABLE IF NOT EXISTS `t_mrc` (
  `MRC_ID` int(11) NOT NULL,
  `MRC_Libelle` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`MRC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_mrc`
--

INSERT INTO `t_mrc` (`MRC_ID`, `MRC_Libelle`) VALUES
(1, 'L\'Islet'),
(2, 'Montmagny'),
(3, 'Rivière-du-Loup'),
(4, 'Trois-Pistoles'),
(5, 'Témiscouata'),
(6, 'Kamouraska');

-- --------------------------------------------------------

--
-- Structure de la table `t_portion`
--

DROP TABLE IF EXISTS `t_portion`;
CREATE TABLE IF NOT EXISTS `t_portion` (
  `TPR_ID` int(11) NOT NULL,
  `PRI_ID` int(11) NOT NULL,
  `POR_Pourcentage` float DEFAULT NULL,
  PRIMARY KEY (`TPR_ID`,`PRI_ID`),
  KEY `FK_Portion_Priorite` (`PRI_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_portion`
--

INSERT INTO `t_portion` (`TPR_ID`, `PRI_ID`, `POR_Pourcentage`) VALUES
(1, 1, 25),
(1, 2, 50),
(1, 3, 25),
(2, 1, 10),
(2, 2, 80),
(2, 3, 10),
(3, 1, 25),
(3, 2, 25),
(3, 3, 50);

-- --------------------------------------------------------

--
-- Structure de la table `t_priorite`
--

DROP TABLE IF EXISTS `t_priorite`;
CREATE TABLE IF NOT EXISTS `t_priorite` (
  `PRI_ID` int(11) NOT NULL,
  `PRI_Libelle` varchar(50) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`PRI_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_priorite`
--

INSERT INTO `t_priorite` (`PRI_ID`, `PRI_Libelle`) VALUES
(1, 'Haute'),
(2, 'Moyenne'),
(3, 'Petite');

-- --------------------------------------------------------

--
-- Structure de la table `t_typeproduit`
--

DROP TABLE IF EXISTS `t_typeproduit`;
CREATE TABLE IF NOT EXISTS `t_typeproduit` (
  `TPR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TPR_Libelle` varchar(38) COLLATE latin1_bin DEFAULT NULL,
  `TPR_Prix` float DEFAULT NULL,
  `TPR_PourcentMoisson` float NOT NULL,
  `TPR_NbJourAlerte` int(11) NOT NULL,
  PRIMARY KEY (`TPR_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `t_typeproduit`
--

INSERT INTO `t_typeproduit` (`TPR_ID`, `TPR_Libelle`, `TPR_Prix`, `TPR_PourcentMoisson`, `TPR_NbJourAlerte`) VALUES
(1, 'Poulet', 10, 5, 10),
(2, 'Carotte', 0.5, 10, 30),
(3, 'Pomme', 0.6, 15, 15);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_clientautre`
--
ALTER TABLE `t_clientautre`
  ADD CONSTRAINT `t_clientautre_ibfk_1` FOREIGN KEY (`CLI_ID`) REFERENCES `t_client` (`CLI_ID`);

--
-- Contraintes pour la table `t_groupe`
--
ALTER TABLE `t_groupe`
  ADD CONSTRAINT `FK_Groupe_MRC` FOREIGN KEY (`MRC_ID`) REFERENCES `t_mrc` (`MRC_ID`),
  ADD CONSTRAINT `FK_Groupe_Priorite` FOREIGN KEY (`PRI_ID`) REFERENCES `t_priorite` (`PRI_ID`);

--
-- Contraintes pour la table `t_historique`
--
ALTER TABLE `t_historique`
  ADD CONSTRAINT `fk_historique_groupe` FOREIGN KEY (`GRO_ID`) REFERENCES `t_groupe` (`GRO_ID`),
  ADD CONSTRAINT `fk_historique_typeproduit` FOREIGN KEY (`TPR_ID`) REFERENCES `t_typeproduit` (`TPR_ID`);

--
-- Contraintes pour la table `t_lot`
--
ALTER TABLE `t_lot`
  ADD CONSTRAINT `fk_lot_groupe` FOREIGN KEY (`GRO_ID`) REFERENCES `t_groupe` (`GRO_ID`),
  ADD CONSTRAINT `fk_lot_typeproduit` FOREIGN KEY (`TPR_ID`) REFERENCES `t_typeproduit` (`TPR_ID`);

--
-- Contraintes pour la table `t_portion`
--
ALTER TABLE `t_portion`
  ADD CONSTRAINT `FK_Portion_Priorite` FOREIGN KEY (`PRI_ID`) REFERENCES `t_priorite` (`PRI_ID`),
  ADD CONSTRAINT `FK_Portion_TypeProduit` FOREIGN KEY (`TPR_ID`) REFERENCES `t_typeproduit` (`TPR_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
