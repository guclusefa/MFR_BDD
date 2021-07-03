-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 03 juil. 2021 à 08:55
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_mfr`
--

-- --------------------------------------------------------

--
-- Structure de la table `alternances`
--

DROP TABLE IF EXISTS `alternances`;
CREATE TABLE IF NOT EXISTS `alternances` (
  `alt_id` int(11) NOT NULL AUTO_INCREMENT,
  `alt_idEleve` int(11) DEFAULT NULL,
  `alt_idEtab` int(11) DEFAULT NULL,
  `alt_idSite` int(11) DEFAULT '0',
  `alt_idFormation` int(11) DEFAULT '0',
  `alt_idContact` int(11) DEFAULT '0',
  `alt_anneeFormation` int(11) DEFAULT '1',
  `alt_debut` date DEFAULT NULL,
  `alt_fin` date DEFAULT NULL,
  `alt_actif` int(11) DEFAULT '1',
  `alt_raison` varchar(1000) DEFAULT NULL,
  `alt_ruptureDate` date DEFAULT NULL,
  PRIMARY KEY (`alt_id`),
  UNIQUE KEY `alt_id` (`alt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `collab_cfa`
--

DROP TABLE IF EXISTS `collab_cfa`;
CREATE TABLE IF NOT EXISTS `collab_cfa` (
  `collab_id` int(11) NOT NULL AUTO_INCREMENT,
  `collab_idEtab` int(11) NOT NULL,
  `collab_idCFA` int(11) NOT NULL,
  `collab_raison` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`collab_id`),
  UNIQUE KEY `collab_id` (`collab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_idEtab` int(11) DEFAULT NULL,
  `contact_idSite` int(11) DEFAULT '0',
  `contact_idFonction` int(11) DEFAULT '15',
  `contact_nom` varchar(255) DEFAULT NULL,
  `contact_prenom` varchar(255) DEFAULT NULL,
  `contact_tel` varchar(255) DEFAULT NULL,
  `contact_mail` varchar(255) DEFAULT NULL,
  `contact_telpro` varchar(255) DEFAULT NULL,
  `contact_mailpro` varchar(255) DEFAULT NULL,
  `contact_actif` int(11) DEFAULT '1',
  `contact_raison` varchar(1000) DEFAULT NULL,
  `contact_portable` varchar(255) DEFAULT NULL,
  `contact_portablepro` varchar(255) DEFAULT NULL,
  `contact_sexe` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  UNIQUE KEY `contact_id` (`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `contact_idEtab`, `contact_idSite`, `contact_idFonction`, `contact_nom`, `contact_prenom`, `contact_tel`, `contact_mail`, `contact_telpro`, `contact_mailpro`, `contact_actif`, `contact_raison`, `contact_portable`, `contact_portablepro`, `contact_sexe`) VALUES
(0, 0, 0, 0, '0', '0', '0', '0', '0', '0', 0, '0', '0', '0', '0');

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

DROP TABLE IF EXISTS `departement`;
CREATE TABLE IF NOT EXISTS `departement` (
  `departement_id` int(11) NOT NULL AUTO_INCREMENT,
  `departement_code` varchar(3) DEFAULT NULL,
  `departement_nom` varchar(255) DEFAULT NULL,
  `departement_nom_uppercase` varchar(255) DEFAULT NULL,
  `departement_slug` varchar(255) DEFAULT NULL,
  `departement_nom_soundex` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`departement_id`),
  KEY `departement_slug` (`departement_slug`),
  KEY `departement_code` (`departement_code`),
  KEY `departement_nom_soundex` (`departement_nom_soundex`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`departement_id`, `departement_code`, `departement_nom`, `departement_nom_uppercase`, `departement_slug`, `departement_nom_soundex`) VALUES
(1, '01', 'Ain', 'AIN', 'ain', 'A500'),
(2, '02', 'Aisne', 'AISNE', 'aisne', 'A250'),
(3, '03', 'Allier', 'ALLIER', 'allier', 'A460'),
(5, '05', 'Hautes-Alpes', 'HAUTES-ALPES', 'hautes-alpes', 'H32412'),
(4, '04', 'Alpes-de-Haute-Provence', 'ALPES-DE-HAUTE-PROVENCE', 'alpes-de-haute-provence', 'A412316152'),
(6, '06', 'Alpes-Maritimes', 'ALPES-MARITIMES', 'alpes-maritimes', 'A41256352'),
(7, '07', 'Ardèche', 'ARDÈCHE', 'ardeche', 'A632'),
(8, '08', 'Ardennes', 'ARDENNES', 'ardennes', 'A6352'),
(9, '09', 'Ariège', 'ARIÈGE', 'ariege', 'A620'),
(10, '10', 'Aube', 'AUBE', 'aube', 'A100'),
(11, '11', 'Aude', 'AUDE', 'aude', 'A300'),
(12, '12', 'Aveyron', 'AVEYRON', 'aveyron', 'A165'),
(13, '13', 'Bouches-du-Rhône', 'BOUCHES-DU-RHÔNE', 'bouches-du-rhone', 'B2365'),
(14, '14', 'Calvados', 'CALVADOS', 'calvados', 'C4132'),
(15, '15', 'Cantal', 'CANTAL', 'cantal', 'C534'),
(16, '16', 'Charente', 'CHARENTE', 'charente', 'C653'),
(17, '17', 'Charente-Maritime', 'CHARENTE-MARITIME', 'charente-maritime', 'C6535635'),
(18, '18', 'Cher', 'CHER', 'cher', 'C600'),
(19, '19', 'Corrèze', 'CORRÈZE', 'correze', 'C620'),
(20, '2a', 'Corse-du-sud', 'CORSE-DU-SUD', 'corse-du-sud', 'C62323'),
(21, '2b', 'Haute-corse', 'HAUTE-CORSE', 'haute-corse', 'H3262'),
(22, '21', 'Côte-d\'or', 'CÔTE-D\'OR', 'cote-dor', 'C360'),
(23, '22', 'Côtes-d\'armor', 'CÔTES-D\'ARMOR', 'cotes-darmor', 'C323656'),
(24, '23', 'Creuse', 'CREUSE', 'creuse', 'C620'),
(25, '24', 'Dordogne', 'DORDOGNE', 'dordogne', 'D6325'),
(26, '25', 'Doubs', 'DOUBS', 'doubs', 'D120'),
(27, '26', 'Drôme', 'DRÔME', 'drome', 'D650'),
(28, '27', 'Eure', 'EURE', 'eure', 'E600'),
(29, '28', 'Eure-et-Loir', 'EURE-ET-LOIR', 'eure-et-loir', 'E6346'),
(30, '29', 'Finistère', 'FINISTÈRE', 'finistere', 'F5236'),
(31, '30', 'Gard', 'GARD', 'gard', 'G630'),
(32, '31', 'Haute-Garonne', 'HAUTE-GARONNE', 'haute-garonne', 'H3265'),
(33, '32', 'Gers', 'GERS', 'gers', 'G620'),
(34, '33', 'Gironde', 'GIRONDE', 'gironde', 'G653'),
(35, '34', 'Hérault', 'HÉRAULT', 'herault', 'H643'),
(36, '35', 'Ile-et-Vilaine', 'ILE-ET-VILAINE', 'ile-et-vilaine', 'I43145'),
(37, '36', 'Indre', 'INDRE', 'indre', 'I536'),
(38, '37', 'Indre-et-Loire', 'INDRE-ET-LOIRE', 'indre-et-loire', 'I536346'),
(39, '38', 'Isère', 'ISÈRE', 'isere', 'I260'),
(40, '39', 'Jura', 'JURA', 'jura', 'J600'),
(41, '40', 'Landes', 'LANDES', 'landes', 'L532'),
(42, '41', 'Loir-et-Cher', 'LOIR-ET-CHER', 'loir-et-cher', 'L6326'),
(43, '42', 'Loire', 'LOIRE', 'loire', 'L600'),
(44, '43', 'Haute-Loire', 'HAUTE-LOIRE', 'haute-loire', 'H346'),
(45, '44', 'Loire-Atlantique', 'LOIRE-ATLANTIQUE', 'loire-atlantique', 'L634532'),
(46, '45', 'Loiret', 'LOIRET', 'loiret', 'L630'),
(47, '46', 'Lot', 'LOT', 'lot', 'L300'),
(48, '47', 'Lot-et-Garonne', 'LOT-ET-GARONNE', 'lot-et-garonne', 'L3265'),
(49, '48', 'Lozère', 'LOZÈRE', 'lozere', 'L260'),
(50, '49', 'Maine-et-Loire', 'MAINE-ET-LOIRE', 'maine-et-loire', 'M346'),
(51, '50', 'Manche', 'MANCHE', 'manche', 'M200'),
(52, '51', 'Marne', 'MARNE', 'marne', 'M650'),
(53, '52', 'Haute-Marne', 'HAUTE-MARNE', 'haute-marne', 'H3565'),
(54, '53', 'Mayenne', 'MAYENNE', 'mayenne', 'M000'),
(55, '54', 'Meurthe-et-Moselle', 'MEURTHE-ET-MOSELLE', 'meurthe-et-moselle', 'M63524'),
(56, '55', 'Meuse', 'MEUSE', 'meuse', 'M200'),
(57, '56', 'Morbihan', 'MORBIHAN', 'morbihan', 'M615'),
(58, '57', 'Moselle', 'MOSELLE', 'moselle', 'M240'),
(59, '58', 'Nièvre', 'NIÈVRE', 'nievre', 'N160'),
(60, '59', 'Nord', 'NORD', 'nord', 'N630'),
(61, '60', 'Oise', 'OISE', 'oise', 'O200'),
(62, '61', 'Orne', 'ORNE', 'orne', 'O650'),
(63, '62', 'Pas-de-Calais', 'PAS-DE-CALAIS', 'pas-de-calais', 'P23242'),
(64, '63', 'Puy-de-Dôme', 'PUY-DE-DÔME', 'puy-de-dome', 'P350'),
(65, '64', 'Pyrénées-Atlantiques', 'PYRÉNÉES-ATLANTIQUES', 'pyrenees-atlantiques', 'P65234532'),
(66, '65', 'Hautes-Pyrénées', 'HAUTES-PYRÉNÉES', 'hautes-pyrenees', 'H321652'),
(67, '66', 'Pyrénées-Orientales', 'PYRÉNÉES-ORIENTALES', 'pyrenees-orientales', 'P65265342'),
(68, '67', 'Bas-Rhin', 'BAS-RHIN', 'bas-rhin', 'B265'),
(69, '68', 'Haut-Rhin', 'HAUT-RHIN', 'haut-rhin', 'H365'),
(70, '69', 'Rhône', 'RHÔNE', 'rhone', 'R500'),
(71, '70', 'Haute-Saône', 'HAUTE-SAÔNE', 'haute-saone', 'H325'),
(72, '71', 'Saône-et-Loire', 'SAÔNE-ET-LOIRE', 'saone-et-loire', 'S5346'),
(73, '72', 'Sarthe', 'SARTHE', 'sarthe', 'S630'),
(74, '73', 'Savoie', 'SAVOIE', 'savoie', 'S100'),
(75, '74', 'Haute-Savoie', 'HAUTE-SAVOIE', 'haute-savoie', 'H321'),
(76, '75', 'Paris', 'PARIS', 'paris', 'P620'),
(77, '76', 'Seine-Maritime', 'SEINE-MARITIME', 'seine-maritime', 'S5635'),
(78, '77', 'Seine-et-Marne', 'SEINE-ET-MARNE', 'seine-et-marne', 'S53565'),
(79, '78', 'Yvelines', 'YVELINES', 'yvelines', 'Y1452'),
(80, '79', 'Deux-Sèvres', 'DEUX-SÈVRES', 'deux-sevres', 'D2162'),
(81, '80', 'Somme', 'SOMME', 'somme', 'S500'),
(82, '81', 'Tarn', 'TARN', 'tarn', 'T650'),
(83, '82', 'Tarn-et-Garonne', 'TARN-ET-GARONNE', 'tarn-et-garonne', 'T653265'),
(84, '83', 'Var', 'VAR', 'var', 'V600'),
(85, '84', 'Vaucluse', 'VAUCLUSE', 'vaucluse', 'V242'),
(86, '85', 'Vendée', 'VENDÉE', 'vendee', 'V530'),
(87, '86', 'Vienne', 'VIENNE', 'vienne', 'V500'),
(88, '87', 'Haute-Vienne', 'HAUTE-VIENNE', 'haute-vienne', 'H315'),
(89, '88', 'Vosges', 'VOSGES', 'vosges', 'V200'),
(90, '89', 'Yonne', 'YONNE', 'yonne', 'Y500'),
(91, '90', 'Territoire de Belfort', 'TERRITOIRE DE BELFORT', 'territoire-de-belfort', 'T636314163'),
(92, '91', 'Essonne', 'ESSONNE', 'essonne', 'E250'),
(93, '92', 'Hauts-de-Seine', 'HAUTS-DE-SEINE', 'hauts-de-seine', 'H32325'),
(94, '93', 'Seine-Saint-Denis', 'SEINE-SAINT-DENIS', 'seine-saint-denis', 'S525352'),
(95, '94', 'Val-de-Marne', 'VAL-DE-MARNE', 'val-de-marne', 'V43565'),
(96, '95', 'Val-d\'oise', 'VAL-D\'OISE', 'val-doise', 'V432'),
(97, '976', 'Mayotte', 'MAYOTTE', 'mayotte', 'M300'),
(98, '971', 'Guadeloupe', 'GUADELOUPE', 'guadeloupe', 'G341'),
(99, '973', 'Guyane', 'GUYANE', 'guyane', 'G500'),
(100, '972', 'Martinique', 'MARTINIQUE', 'martinique', 'M6352'),
(101, '974', 'Réunion', 'RÉUNION', 'reunion', 'R500');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `eleve_id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_nom` varchar(255) DEFAULT NULL,
  `eleve_prenom` varchar(255) DEFAULT NULL,
  `eleve_mailperso` varchar(255) DEFAULT NULL,
  `eleve_mailpro` varchar(255) DEFAULT NULL,
  `eleve_telperso` varchar(255) DEFAULT NULL,
  `eleve_telpro` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`eleve_id`),
  UNIQUE KEY `eleve_id` (`eleve_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `entretiens`
--

DROP TABLE IF EXISTS `entretiens`;
CREATE TABLE IF NOT EXISTS `entretiens` (
  `entretien_id` int(11) NOT NULL AUTO_INCREMENT,
  `entretien_idContact` int(11) DEFAULT '0',
  `entretien_idMembre` int(11) DEFAULT '15',
  `entretien_contenu` varchar(1000) DEFAULT NULL,
  `entretien_dateAppel` datetime DEFAULT CURRENT_TIMESTAMP,
  `entretien_dateRappel` datetime DEFAULT NULL,
  `entretien_dateRappel2` datetime DEFAULT NULL,
  `entretien_reponse` int(11) DEFAULT '1',
  `entretien_idNextEntretien` int(11) DEFAULT '0',
  PRIMARY KEY (`entretien_id`),
  UNIQUE KEY `entretien_id` (`entretien_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etablissements`
--

DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE IF NOT EXISTS `etablissements` (
  `etab_id` int(11) NOT NULL AUTO_INCREMENT,
  `etab_raisonSocial` varchar(255) DEFAULT NULL,
  `etab_idSecteur` int(11) DEFAULT '0',
  `etab_idType` int(11) DEFAULT '0',
  `etab_idOrigine` int(11) DEFAULT '0',
  `etab_idDepartement` int(11) DEFAULT '0',
  `etab_adresse` varchar(255) DEFAULT NULL,
  `etab_ville` varchar(255) DEFAULT NULL,
  `etab_cp` varchar(255) DEFAULT NULL,
  `etab_tel` varchar(255) DEFAULT NULL,
  `etab_mail` varchar(255) DEFAULT NULL,
  `etab_donnateur` int(11) DEFAULT '0',
  `etab_interest` int(11) DEFAULT '1',
  `etab_raison` varchar(1000) DEFAULT NULL,
  `etab_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`etab_id`),
  UNIQUE KEY `etab_id` (`etab_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_idEtab` int(11) DEFAULT '0',
  `event_idSite` int(11) DEFAULT '0',
  `event_idTypeEvent` int(11) DEFAULT '1',
  `event_commentaire` varchar(1000) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `event_id` (`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fonctions_contacts`
--

DROP TABLE IF EXISTS `fonctions_contacts`;
CREATE TABLE IF NOT EXISTS `fonctions_contacts` (
  `fonction_id` int(11) NOT NULL AUTO_INCREMENT,
  `fonction_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`fonction_id`),
  UNIQUE KEY `fonction_id` (`fonction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `fonctions_contacts`
--

INSERT INTO `fonctions_contacts` (`fonction_id`, `fonction_intitule`) VALUES
(1, 'RH'),
(2, 'RH Formation'),
(3, 'RH Alternance'),
(4, 'RH Assistante'),
(5, 'Responsable maintenance'),
(6, 'Responsable téchnique'),
(7, 'Tuteur'),
(8, 'DSI'),
(15, 'Autre'),
(10, 'Service informatique'),
(11, 'PDG'),
(12, 'Directeur régional'),
(13, 'Directeur agence'),
(14, 'Assistant'),
(16, 'Gérant');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

DROP TABLE IF EXISTS `formations`;
CREATE TABLE IF NOT EXISTS `formations` (
  `formation_id` int(11) NOT NULL AUTO_INCREMENT,
  `formation_cat` varchar(255) NOT NULL,
  `formation_niv` varchar(255) NOT NULL,
  `formation_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`formation_id`),
  UNIQUE KEY `formation_id` (`formation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`formation_id`, `formation_cat`, `formation_niv`, `formation_intitule`) VALUES
(1, 'MAINTENANCE\r\nINFORMATIQUE ET RÉSEAUX', 'Bac Pro SN', 'Systèmes Numériques option RISC'),
(2, 'MAINTENANCE\r\nINFORMATIQUE ET RÉSEAUX', 'BTS SIO', 'Services Informatiques \r\naux Organisations option SISR'),
(3, 'MAINTENANCE\r\nINFORMATIQUE ET RÉSEAUX', 'AIS', 'Administrateur d\'Infrastructures Sécurisées'),
(4, 'MAINTENANCE\r\nÉNERGÉTIQUE ET CLIMATIQUE', 'Bac Pro TMSEC', 'Technicien de Maintenance des Systèmes Energétiques et ClimatiquesTechnicien de Maintenance des Systèmes Energétiques et Climatiques'),
(5, 'MAINTENANCE\r\nÉNERGÉTIQUE ET CLIMATIQUE', 'BTS  MS SEF', 'Maintenance des Systèmes\r\nénergétiques et fluidiques'),
(6, 'MAINTENANCE\r\nINDUSTRIELLE', 'Bac Pro MEI', 'Maintenance des Equipements\r\nIndustriels'),
(7, 'MAINTENANCE\r\nINDUSTRIELLE', 'BTS MS SP', 'Maintenance des Systèmes\r\nde production'),
(8, 'Autre', 'Autre', '');

-- --------------------------------------------------------

--
-- Structure de la table `formation_cfa`
--

DROP TABLE IF EXISTS `formation_cfa`;
CREATE TABLE IF NOT EXISTS `formation_cfa` (
  `formationCfa_id` int(11) NOT NULL AUTO_INCREMENT,
  `formationCfa_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`formationCfa_id`),
  UNIQUE KEY `formationCfa_id` (`formationCfa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `formation_cfa`
--

INSERT INTO `formation_cfa` (`formationCfa_id`, `formationCfa_intitule`) VALUES
(1, 'CAP'),
(2, 'BAC PRO'),
(3, 'BTS'),
(4, 'LICENCE'),
(5, 'MASTER'),
(0, 'Pas précisée');

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

DROP TABLE IF EXISTS `membres`;
CREATE TABLE IF NOT EXISTS `membres` (
  `memb_id` int(11) NOT NULL AUTO_INCREMENT,
  `memb_nom` varchar(255) NOT NULL,
  `memb_prenom` varchar(255) NOT NULL,
  `memb_pseudo` varchar(255) NOT NULL,
  `memb_mail` varchar(255) NOT NULL,
  `memb_mdp` varchar(255) NOT NULL,
  `memb_admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`memb_id`),
  UNIQUE KEY `memb_id` (`memb_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `offres_alternances`
--

DROP TABLE IF EXISTS `offres_alternances`;
CREATE TABLE IF NOT EXISTS `offres_alternances` (
  `offreAlt_id` int(11) NOT NULL AUTO_INCREMENT,
  `offreAlt_idEtab` int(11) NOT NULL,
  `offreAlt_idSite` int(11) NOT NULL,
  `offreAlt_idFormation` int(11) NOT NULL,
  `offreAlt_anneeFormation` int(11) NOT NULL,
  `offreAlt_nom` varchar(255) DEFAULT NULL,
  `offreAlt_prenom` varchar(255) DEFAULT NULL,
  `offreAlt_debut` date NOT NULL,
  `offreAlt_fin` date NOT NULL,
  `offreAlt_demande` int(11) NOT NULL DEFAULT '0',
  `offreAlt_idAlt` varchar(11) NOT NULL,
  `offreAlt_idContact` int(11) NOT NULL,
  `offreAlt_mailperso` varchar(255) DEFAULT NULL,
  `offreAlt_mailpro` varchar(255) DEFAULT NULL,
  `offreAlt_telperso` varchar(255) DEFAULT NULL,
  `offreAlt_telpro` varchar(255) DEFAULT NULL,
  `offreAlt_raison` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`offreAlt_id`),
  UNIQUE KEY `offreAlt_id` (`offreAlt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `origines_entreprise`
--

DROP TABLE IF EXISTS `origines_entreprise`;
CREATE TABLE IF NOT EXISTS `origines_entreprise` (
  `origineEnt_id` int(11) NOT NULL AUTO_INCREMENT,
  `origineEnt_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`origineEnt_id`),
  UNIQUE KEY `origineEnt_id` (`origineEnt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `origines_entreprise`
--

INSERT INTO `origines_entreprise` (`origineEnt_id`, `origineEnt_intitule`) VALUES
(4, 'Partenaire'),
(3, 'Prospect'),
(2, 'Relation'),
(5, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `participation_collab`
--

DROP TABLE IF EXISTS `participation_collab`;
CREATE TABLE IF NOT EXISTS `participation_collab` (
  `participationCollab_id` int(11) NOT NULL AUTO_INCREMENT,
  `participationCollab_idCollab` int(11) NOT NULL,
  `participationCollab_idFormationCfa` int(11) NOT NULL,
  PRIMARY KEY (`participationCollab_id`),
  UNIQUE KEY `participationCollab_id` (`participationCollab_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `participation_event`
--

DROP TABLE IF EXISTS `participation_event`;
CREATE TABLE IF NOT EXISTS `participation_event` (
  `participation_id` int(11) NOT NULL AUTO_INCREMENT,
  `participation_idEvent` int(11) NOT NULL,
  `participation_idContact` int(11) NOT NULL,
  PRIMARY KEY (`participation_id`),
  UNIQUE KEY `participation_id` (`participation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `secteurs_entreprise`
--

DROP TABLE IF EXISTS `secteurs_entreprise`;
CREATE TABLE IF NOT EXISTS `secteurs_entreprise` (
  `secteur_id` int(11) NOT NULL AUTO_INCREMENT,
  `secteur_nom` varchar(255) NOT NULL,
  PRIMARY KEY (`secteur_id`),
  UNIQUE KEY `secteur_id` (`secteur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `secteurs_entreprise`
--

INSERT INTO `secteurs_entreprise` (`secteur_id`, `secteur_nom`) VALUES
(1, 'Agroalimentaire'),
(2, 'Banque / Assurance'),
(3, 'Bois / Papier / Carton / Imprimerie'),
(4, 'BTP / Matériaux de construction'),
(5, 'Chimie / Parachimie'),
(6, 'Commerce / Négoce / Distribution'),
(7, 'Édition / Communication / Multimédia'),
(8, 'Électronique / Électricité'),
(9, 'Études et conseils'),
(10, 'Industrie pharmaceutique'),
(11, 'Informatique / Télécoms'),
(12, 'Machines et équipements / Automobile'),
(13, 'Métallurgie / Travail du métal'),
(14, 'Plastique / Caoutchouc'),
(15, 'Services aux entreprises'),
(16, 'Textile / Habillement / Chaussure'),
(17, 'Transports / Logistique'),
(18, 'Autre'),
(19, 'Fonction publique'),
(20, 'Energie');

-- --------------------------------------------------------

--
-- Structure de la table `sites_entreprise`
--

DROP TABLE IF EXISTS `sites_entreprise`;
CREATE TABLE IF NOT EXISTS `sites_entreprise` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_idEtab` int(11) NOT NULL,
  `site_idTypeSite` int(11) NOT NULL,
  `site_idDepartement` int(11) NOT NULL,
  `site_adresse` varchar(255) NOT NULL,
  `site_ville` varchar(255) NOT NULL,
  `site_cp` varchar(255) NOT NULL,
  `site_tel` varchar(255) NOT NULL,
  `site_mail` varchar(255) NOT NULL,
  PRIMARY KEY (`site_id`),
  UNIQUE KEY `site_id` (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sites_entreprise`
--

INSERT INTO `sites_entreprise` (`site_id`, `site_idEtab`, `site_idTypeSite`, `site_idDepartement`, `site_adresse`, `site_ville`, `site_cp`, `site_tel`, `site_mail`) VALUES
(-1, -1, -1, -1, '-1', '-1', '-1', '-1', '-1'),
(0, 0, 0, 0, '0', '0', '0', '0', '0');

-- --------------------------------------------------------

--
-- Structure de la table `types_entreprise`
--

DROP TABLE IF EXISTS `types_entreprise`;
CREATE TABLE IF NOT EXISTS `types_entreprise` (
  `typeEnt_id` int(11) NOT NULL AUTO_INCREMENT,
  `typeEnt_intitule` varchar(255) NOT NULL,
  `typeEnt_nbrSalaries` varchar(255) NOT NULL,
  PRIMARY KEY (`typeEnt_id`),
  UNIQUE KEY `typeEnt_id` (`typeEnt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `types_entreprise`
--

INSERT INTO `types_entreprise` (`typeEnt_id`, `typeEnt_intitule`, `typeEnt_nbrSalaries`) VALUES
(1, 'Micro', '1 à 9'),
(2, 'Petite', '10 à 49 '),
(3, 'Moyenne', '50 à 249'),
(4, 'Grande', '+ de 250'),
(5, 'CFA', '');

-- --------------------------------------------------------

--
-- Structure de la table `types_evenements`
--

DROP TABLE IF EXISTS `types_evenements`;
CREATE TABLE IF NOT EXISTS `types_evenements` (
  `typeEvent_id` int(11) NOT NULL AUTO_INCREMENT,
  `typeEvent_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`typeEvent_id`),
  UNIQUE KEY `typeEvent_id` (`typeEvent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `types_evenements`
--

INSERT INTO `types_evenements` (`typeEvent_id`, `typeEvent_intitule`) VALUES
(1, 'RDV PRO'),
(2, 'Manifestation '),
(3, 'Recrutement');

-- --------------------------------------------------------

--
-- Structure de la table `types_sites`
--

DROP TABLE IF EXISTS `types_sites`;
CREATE TABLE IF NOT EXISTS `types_sites` (
  `typeSite_id` int(11) NOT NULL AUTO_INCREMENT,
  `typeSite_intitule` varchar(255) NOT NULL,
  PRIMARY KEY (`typeSite_id`),
  UNIQUE KEY `typeSite_id` (`typeSite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `types_sites`
--

INSERT INTO `types_sites` (`typeSite_id`, `typeSite_intitule`) VALUES
(1, 'Production'),
(2, 'Administratif'),
(3, 'Agence');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
