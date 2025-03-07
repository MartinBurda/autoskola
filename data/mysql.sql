-- Adminer 4.8.1 MySQL 9.1.0 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `autoskola` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `autoskola`;

DROP TABLE IF EXISTS `about_section`;
CREATE TABLE `about_section` (
  `id` int NOT NULL AUTO_INCREMENT,
  `heading` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt_text` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `about_section` (`id`, `heading`, `image`, `alt_text`, `content`) VALUES
(1,	'O nás',	'/images/about-us.jpg',	'Autoškola Prima',	'Autoškola Prima nabízí kvalitní výuku a přípravu na získání řidičského oprávnění pro různé skupiny. S působností v Teplicích a Bílině klademe důraz na individuální přístup, profesionalitu a flexibilitu, aby každý žák uspěl.');

DROP TABLE IF EXISTS `advantages`;
CREATE TABLE `advantages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `advantages` (`id`, `icon`, `title`, `description`, `ordering`) VALUES
(1,	'fas fa-credit-card',	'Možnost splátek',	'Splátky již od 8 tis. Kč – viz ceník',	1),
(2,	'fas fa-school',	'Moderně zařízená učebna',	'Skvělá dostupnost MHD (Masarykovo náměstí) – viz FOTO',	2),
(3,	'fas fa-map-marker-alt',	'Doprava',	'Jízdy z místa bydliště či dle dohody',	3),
(4,	'fas fa-calendar-alt',	'Flexibilní výcvik',	'Přizpůsobíme výcvik Vašim časovým potřebám',	4),
(5,	'fas fa-book',	'Učební pomůcky',	'Zapůjčení s vratnou zálohou – viz ceník',	5),
(6,	'fas fa-car-side',	'Moderní vozidlo',	'Výcvik na Škoda Fabia III. generace – viz FOTO',	6),
(7,	'fas fa-chalkboard-teacher',	'Zkušení lektoři',	'Výuku a výcvik vedou odborní lektoři s dlouholetou praxí',	7);

DROP TABLE IF EXISTS `contact_info`;
CREATE TABLE `contact_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `ico` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `map_embed` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `contact_info` (`id`, `name`, `address`, `ico`, `phone`, `email`, `map_embed`) VALUES
(1,	'Martin Tkadlec',	'Sukova třída 1556, 530 02 Pardubice',	'60919264',	'737 314 477',	'autoskolaprima@email.cz',	'<iframe \n  title=\"Mapa Autoškola Pardubice - PRIMA\" \n  src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2562.5754126069282!2d15.764816093396476!3d50.03805129807359!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470dcc954aa1d77b%3A0x2e9085f0ca47909d!2sAuto%C5%A1kola%20Pardubice%20-%20PRIMA!5e0!3m2!1scs!2scz!4v1741201095578!5m2!1scs!2scz\" \n  allowfullscreen=\"\" \n  loading=\"lazy\" \n  referrerpolicy=\"no-referrer-when-downgrade\" \n  class=\"w-100\" \n  style=\"min-height:300px; border:0; border-radius:8px;\">\n</iframe>\n');

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` text COLLATE utf8mb4_czech_ci,
  `image` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `remaining_spots` int NOT NULL DEFAULT '0',
  `starting_date` date NOT NULL DEFAULT '2025-01-01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `courses` (`id`, `name`, `description`, `image`, `price`, `remaining_spots`, `starting_date`) VALUES
(1,	'Skupina B',	'Výuka pro osobní automobily',	'/uploads/ridicak.jpg',	15000.00,	0,	'2025-01-01'),
(2,	'Kondiční jízdy',	'Zdokonalení řidičských dovedností',	'/uploads/auto.jpg',	500.00,	0,	'2025-01-01');

DROP TABLE IF EXISTS `hero_section`;
CREATE TABLE `hero_section` (
  `id` int NOT NULL AUTO_INCREMENT,
  `heading` varchar(255) NOT NULL,
  `subheading` text NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `button_link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `hero_section` (`id`, `heading`, `subheading`, `button_text`, `button_link`) VALUES
(1,	'Vítejte v Autoškole Prima',	'Kvalitní výuka od zkušených instruktorů v Pardubicích',	'Zjistit více o kurzech',	'/course-list');

DROP TABLE IF EXISTS `offerings`;
CREATE TABLE `offerings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(10) NOT NULL,
  `content` text NOT NULL,
  `ordering` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `offerings` (`id`, `label`, `content`, `ordering`) VALUES
(1,	'a)',	'motorových vozidel (s výjimkou všech motocyklů), jejichž maximální přípustná hmotnost nepřevyšuje 3500 kg a s nejvýše 8 místy k sezení, kromě místa řidiče, k tomuto motorovému vozidlu smí být připojeno přípojné vozidlo o maximální přípustné hmotnosti nepřevyšující 750 kg.',	1),
(2,	'b)',	'traktorů a pracovních strojů samojízdných, jejichž maximální přípustná hmotnost nepřevyšuje 3500 kg.',	2),
(3,	'c)',	'jízdních souprav složených z motorového vozidla podle písm. a) nebo b) a přípojného vozidla, pokud maximální přípustná hmotnost soupravy nepřevyšuje 3500 kg a maximální přípustná hmotnost přípojného vozidla nepřevyšuje pohotovostní hmotnost motorového vozidla.',	3),
(4,	'd)',	'Opravná zkouška z odborné způsobilosti.',	4);

DROP TABLE IF EXISTS `other_services`;
CREATE TABLE `other_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL,
  `item` varchar(255) NOT NULL,
  `price` varchar(50) NOT NULL,
  `description` text,
  `ordering` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `other_services` (`id`, `section`, `item`, `price`, `description`, `ordering`) VALUES
(1,	'public',	'Veřejnost',	'18 500,- Kč',	'Cena obsahuje kompletní výuku, výcvik a náklady autoškoly na první závěrečnou zkoušku.',	1),
(2,	'public',	'Studenti po slevě',	'18 000,- Kč',	NULL,	2),
(3,	'card',	'Platba kartou (Benefity, Benefit-plus, Pluxee)',	'18 500,- Kč',	'Vždy předem kontaktujte autoškolu. Kurz proběhne v termínu dle volných kapacit. Kartou Benefity, Benefit-plus, Pluxee lze platit částečnou cenu do poloviny ceny kurzu a zbytek v hotovosti doplatit do celkové ceny kurzu. (Možnost domluvy jiné varianty.)',	1),
(4,	'installments',	'Záloha na začátku kurzu',	'8 500,- Kč',	'(Je možno domluvit i jinou variantu splátek.)',	1),
(5,	'installments',	'2.–3. splátka (každá)',	'5 000,- Kč',	NULL,	2),
(6,	'installments',	'Celkem',	'18 500,- Kč',	NULL,	3),
(7,	'other',	'Kondiční–cvičná jízda (45 min.)',	'650,- Kč',	NULL,	1),
(8,	'other',	'Opravná zkouška (jízda)',	'500,- Kč',	NULL,	2),
(9,	'other',	'Opravná zkouška (test)',	'500,- Kč',	NULL,	3),
(10,	'other',	'Zapůjčení učebnice (vratná záloha)',	'250,- Kč',	NULL,	4),
(11,	'other',	'Vrácení ŘO',	'4 000,- Kč',	NULL,	5),
(12,	'other',	'Storno kurzu',	'1 000,- Kč',	NULL,	6),
(13,	'other',	'Přestup z jiné autoškoly',	'1 000,- Kč',	NULL,	7);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','uzivatel') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1,	'admin',	'$2y$10$N5dEPMZoCCuisRpdN5/iE.gD4MApYT2KEaoqVt7NiJs5j6DB/5twm',	'admin');

-- 2025-03-07 16:26:23