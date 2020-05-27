

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(300) NOT NULL,
  `mot_de_passe` varchar(300) NOT NULL,
  `jeton` varchar(600) NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE (`nom_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `utilisateur` (`nom_utilisateur`, `mot_de_passe`, `jeton`) VALUES  
('covid', 'mechant',  '68dgthhjukhlphrhtjzbddcw'),
('corona',  'chocolat', 'çofjfjjhn');

CREATE TABLE `iteme` (
  `id_iteme` int(10) NOT NULL AUTO_INCREMENT,
  `id_liste` int(10) NOT NULL,
  `nom` text NOT NULL,
  `Description` text,
  `Image` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `Tarification` decimal(8,2) DEFAULT NULL,
  `Jeton_prive` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `Reservation` tinyint(1) NOT NULL DEFAULT '0',
  `nom_participant` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `cagnotte` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_iteme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `iteme` (`id`, `id_liste`, `nom`, `Description`, `url`, `Tarification`, `Jeton_prive`) VALUES
(1,	2,	'Diner romantique ', 'Diner dans une salle decoree (Apéritif / Entrée / Plat / Vin / Dessert / Café)', '', 40.00, 'Jeton_prive1'),

(2,	2,	'Soiree de dance', 'Soiree avec deux artistes de rap, Trinitaire Metz , Genit Nancy,.', '',  30.00, 'Jeton_prive2');

(3,	2,	'Projection',	'projection histoire',	'',	14.00, 'jeton_prive3'),

(4,	3,	'Dessert',	'Dessert aux fruits', '',	11.00, 'jeton_prive4'),

(5,	3,	'Exposition',	'Expoition film romantique', '',	9.00, 'Jeton_prive5'),

(6,	2,	'Fleurs romantique',	'Fleurs roses romantique', '',	12.00, 'Jeton_prive6'),

(7,	2,	'Diner Groupe',	'Diner de choix commun (Entrée / Plat / Thé / Dessert / Fruit / Digestif)', '',	50.00, 'Jeton_prive7'),

(8,	3,	'Dance',	'spectacle e dance classique', '',	15.00, 'Jeton_prive8'),

(9,	3,	'Anniversaire',	'gateau + Cadeaux', '',	40.00, 'Jeton_prive9'),

(10,	2,	'Coca', 'Bouteille de Coca + flutes + jeux de devinette', '', 15.00, 'Jeton_prive10'),

(11,	0,	'Decouvert',	'Decouvert guider des sites de Nancy',	'',	11.00, 'Jeton_prive11'),

(12,	2,	'Habillement',	'Ensemble complet','',	29.00, 'Jeton_prive12'),

(19,	0,	'Puzzle',	'Casse tete à une personne', '',	3.00, 'Jeton_prive13'),

(22,	0,	'Lotterie',	'loto pour gain de million', '',	25.00, 'jeton_prive14'),

(23,	1,	'Chambre ',	'Chambre meubler', 'pres de la gare', '',	30.00, 'Jeton_prive15'),

(24,	2,	'Appartement meubler',	'Appartemenetz', 'en face du centre pompidou', '',	200.00, 'Jeton_prive16'),

(25,	1,	'Salle sport',	'Musculation, fitnes.', '',	20.00, 'Jeton_prive17'),

(26,	1,	'Piscine',	'guide, enseignement', '',	14.00, 'Jeton_prive19'),

(27,	1,	'Instruments',  'jouer un guitar,jouer un piano.', '',  10.00, 'jeton_prive2'),


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id_roles` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `val` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_roles`),
  UNIQUE KEY `roles_val_unique` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `roles_utilisateur`;
CREATE TABLE `roles_utilisateur` (
  `id_roles` int(12) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_roles`)
  `id_utilisateur` int(12) unsigned NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `liste` (
  `numero` int(10) NOT NULL AUTO_INCREMENT,
  `numero_utilisateur` int(10) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `expir` date DEFAULT NULL,
  `jeton_prive` varchar(255) COLLATE utf8_unicode_ci NOT NULL, 
  `jeton_publique` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, 
  `publier` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `liste` (`numero`, `titre`, `description`, `expir`, `jeton_prive`, `jeton_publique`) VALUES
(1,	'Pour fêter la certification !',	'un nuit à Metz pour se divertir. ',	'2020-07-18',	'jeton_prive1', 'jeton_publique1'),
(4,	'C\'est l\'anniversaire de Mouhamadou',	'Pour lui faire un plaisir ',	'2020-10-11',	'jeton_prive4', 'jeton_publique4');
INSERT INTO `liste` (`numero`, `titre`, `description`, `expir`, `jeton_prive`, `jeton_publique`) VALUES
(3,	'Liste Anniversaire d\'Adrian et Mouhamadou',	'Nous souhaitons passer une soiree à Nancy :)',	'2020-08-20',	'jeton-prive3', 'jeton-publique3');

CREATE TABLE `message` (
  `id_message` int(12) NOT NULL AUTO_INCREMENT,
  `numero_liste` int(11) NOT NULL,
  `message` text,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `rappels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(10) unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code_activation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `enreg_liste`;
CREATE TABLE `enreg_liste` (
	`id` int(12) NOT NULL AUTO_INCREMENT,
	`numero_utilisateur` int(12) NOT NULL,
	`numero_liste` int(12) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `Cagnotte` (
  `id_participation` int(10) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(10) DEFAULT NULL,
  `name` text NOT NULL,
  `iteme_id` int(10) NOT NULL,
  `montant` int(12) NOT NULL DEFAULT '0',
  `message` text,
  PRIMARY KEY (`id_participation`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



CREATE TABLE `persistences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persistences_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
