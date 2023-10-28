-- MariaDB dump 10.19-11.1.2-MariaDB, for osx10.17 (x86_64)
--
-- Host: localhost    Database: Garage_Vincent_Parrot
-- ------------------------------------------------------
-- Server version	11.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garage_id` int(11) NOT NULL,
  `license_plate` varchar(9) NOT NULL,
  `registration_date` datetime NOT NULL,
  `mileage` int(11) NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `modified_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `car_engine` varchar(60) NOT NULL,
  `car_model` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `car_constructor` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_773DE69DF5AA79D0` (`license_plate`),
  KEY `IDX_773DE69DC4FFF555` (`garage_id`),
  CONSTRAINT `FK_773DE69DC4FFF555` FOREIGN KEY (`garage_id`) REFERENCES `garage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car`
--

LOCK TABLES `car` WRITE;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` VALUES
(122,9,'hg-514-rz','1998-02-18 13:00:15',153998,40629,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hydrogene','208',1,'Peugeot'),
(123,9,'of-577-ou','2001-07-11 13:30:09',106872,17576,'2023-10-28 19:45:21','2023-10-28 19:45:21','Diesel','308',1,'Peugeot'),
(124,9,'xh-626-tr','2022-07-02 19:52:52',101271,34053,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hydrogene','3008',1,'Peugeot'),
(125,9,'wi-411-fw','1999-02-24 23:05:01',198130,29364,'2023-10-28 19:45:21','2023-10-28 19:45:21','Diesel','Clio',1,'Renault'),
(126,9,'bw-394-dy','2007-12-30 16:39:33',194363,31123,'2023-10-28 19:45:21','2023-10-28 19:45:21','Diesel','Megane',1,'Renault'),
(127,9,'ns-932-vz','1998-11-06 21:12:12',162602,29271,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hybrid','Captur',1,'Renault'),
(128,9,'fm-436-ky','2023-07-04 12:50:03',190467,44688,'2023-10-28 19:45:21','2023-10-28 19:45:21','Diesel','Sandero Stepway',1,'Dacia'),
(129,9,'ij-867-ni','2000-01-26 19:26:45',25861,22676,'2023-10-28 19:45:21','2023-10-28 19:45:21','Electrique','Duster',1,'Dacia'),
(130,9,'vd-288-hx','2010-09-15 11:46:26',103095,12902,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hybrid','Logan',1,'Dacia'),
(131,9,'tb-320-xv','2019-09-04 23:02:11',99953,35060,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hydrogene','Picasso',1,'Citroën'),
(132,9,'sd-202-pq','2009-06-02 09:30:50',197496,42144,'2023-10-28 19:45:21','2023-10-28 19:45:21','Essence','C3',1,'Citroën'),
(133,9,'gu-144-sk','2023-06-29 07:35:41',2506,39658,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hybrid','C4',1,'Citroën'),
(134,9,'na-142-cz','1997-06-10 11:01:10',153909,33608,'2023-10-28 19:45:21','2023-10-28 19:45:21','Electrique','Golf',1,'Volkswagen'),
(135,9,'yc-654-ty','2006-06-14 14:33:15',137834,41312,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hybrid','Polo',1,'Volkswagen'),
(136,9,'ks-618-os','2002-12-29 17:26:29',1601,20996,'2023-10-28 19:45:21','2023-10-28 19:45:21','Hybrid','Passat',1,'Volkswagen');
/*!40000 ALTER TABLE `car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_message`
--

DROP TABLE IF EXISTS `contact_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(180) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `subject` varchar(180) NOT NULL,
  `message` longtext NOT NULL,
  `terms_accepted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_by` varchar(180) NOT NULL,
  `is_read_by_staff` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_message`
--

LOCK TABLES `contact_message` WRITE;
/*!40000 ALTER TABLE `contact_message` DISABLE KEYS */;
INSERT INTO `contact_message` VALUES
(6,'Gervaise','Macquart','gervaise.macquart@wanadoo.fr','0645291499','Demande d\'informations','Bonjour, je souhaiterais savoir si vous proposez des véhicules pendant le temps de réparation ?',1,'2023-10-28 19:45:22','visiteur anonyme',1);
/*!40000 ALTER TABLE `contact_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_message_car`
--

DROP TABLE IF EXISTS `contact_message_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_message_car` (
  `contact_message_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  PRIMARY KEY (`contact_message_id`),
  KEY `IDX_30A4D3CFC3C6F69F` (`car_id`),
  CONSTRAINT `FK_30A4D3CF94C34ABE` FOREIGN KEY (`contact_message_id`) REFERENCES `contact_message` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_30A4D3CFC3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_message_car`
--

LOCK TABLES `contact_message_car` WRITE;
/*!40000 ALTER TABLE `contact_message_car` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_message_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `garage`
--

DROP TABLE IF EXISTS `garage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `garage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9F26610BA40BC2D5` (`schedule_id`),
  CONSTRAINT `FK_9F26610BA40BC2D5` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garage`
--

LOCK TABLES `garage` WRITE;
/*!40000 ALTER TABLE `garage` DISABLE KEYS */;
INSERT INTO `garage` VALUES
(9,1,'Siege Social','7 avenue du vase de Soissons, 31000 Toulouse','0123456789','vincentParrot@VP.com');
/*!40000 ALTER TABLE `garage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_car`
--

DROP TABLE IF EXISTS `image_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FD736CEC3C6F69F` (`car_id`),
  CONSTRAINT `FK_2FD736CEC3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_car`
--

LOCK TABLES `image_car` WRITE;
/*!40000 ALTER TABLE `image_car` DISABLE KEYS */;
INSERT INTO `image_car` VALUES
(318,122,'peugeot208-1.webp','peugeot208-1.webp'),
(319,122,'peugeot208-2.webp','peugeot208-2.webp'),
(320,122,'peugeot208-3.webp','peugeot208-3.webp'),
(321,123,'peugeot308-1.webp','peugeot308-1.webp'),
(322,123,'peugeot308-2.webp','peugeot308-2.webp'),
(323,123,'peugeot308-3.webp','peugeot308-3.webp'),
(324,124,'peugeot3008-1.webp','peugeot3008-1.webp'),
(325,124,'peugeot3008-2.webp','peugeot3008-2.webp'),
(326,124,'peugeot3008-3.webp','peugeot3008-3.webp'),
(327,125,'RenaultClio-1.webp','RenaultClio-1.webp'),
(328,125,'RenaultClio-2.webp','RenaultClio-2.webp'),
(329,125,'RenaultClio-3.webp','RenaultClio-3.webp'),
(330,126,'renaultMegane-1.webp','renaultMegane-1.webp'),
(331,126,'renaultMegane-2.webp','renaultMegane-2.webp'),
(332,127,'RenaultCaptur-1.webp','RenaultCaptur-1.webp'),
(333,127,'RenaultCaptur-2.webp','RenaultCaptur-2.webp'),
(334,128,'DaciaSanderoStepway-1.webp','DaciaSanderoStepway-1.webp'),
(335,128,'DaciaSanderoStepway-2.webp','DaciaSanderoStepway-2.webp'),
(336,128,'DaciaSanderoStepway-3.webp','DaciaSanderoStepway-3.webp'),
(337,128,'DaciaSanderoStepway-4.webp','DaciaSanderoStepway-4.webp'),
(338,129,'DaciaDuster-1.webp','DaciaDuster-1.webp'),
(339,129,'DaciaDuster-2.webp','DaciaDuster-2.webp'),
(340,129,'DaciaDuster-3.webp','DaciaDuster-3.webp'),
(341,130,'DaciaLogan-1.webp','DaciaLogan-1.webp'),
(342,131,'CitroënPicasso-1.jpg','CitroënPicasso-1.jpg'),
(343,131,'CitroënPicasso-2.jpg','CitroënPicasso-2.jpg'),
(344,132,'CItroënC3-2.jpg','CItroënC3-2.jpg'),
(345,132,'CitroënC3-1.jpg','CitroënC3-1.jpg'),
(346,133,'CitroënC4-1.jpg','CitroënC4-1.jpg'),
(347,133,'CitroënC4-2.jpg','CitroënC4-2.jpg'),
(348,134,'VolkswagenGolf-1.webp','VolkswagenGolf-1.webp'),
(349,134,'VolkswagenGolf-2.webp','VolkswagenGolf-2.webp'),
(350,134,'VolkswagenGolf-3.webp','VolkswagenGolf-3.webp'),
(351,135,'VolkswagenPolo-1.webp','VolkswagenPolo-1.webp'),
(352,135,'VolkswagenPolo-2.webp','VolkswagenPolo-2.webp'),
(353,135,'VolkswagenPolo-3.webp','VolkswagenPolo-3.webp'),
(354,136,'VolkswagenPassat-1.webp','VolkswagenPassat-1.webp'),
(355,136,'VolkswagenPassat-2.webp','VolkswagenPassat-2.webp'),
(356,136,'VolkswagenPassat-3.webp','VolkswagenPassat-3.webp');
/*!40000 ALTER TABLE `image_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_service`
--

DROP TABLE IF EXISTS `image_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_748DCD0EED5CA9E6` (`service_id`),
  CONSTRAINT `FK_748DCD0EED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_service`
--

LOCK TABLES `image_service` WRITE;
/*!40000 ALTER TABLE `image_service` DISABLE KEYS */;
INSERT INTO `image_service` VALUES
(79,43,'Entretien et vidange.webp','Entretien et vidange'),
(80,44,'Révision.webp','Révision'),
(81,45,'Courroie de distribution.webp','Courroie de distribution'),
(82,46,'Pneumatique.webp','Pneumatique'),
(83,47,'Plaquettes de frein.webp','Freinage disque et plaquettes');
/*!40000 ALTER TABLE `image_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `opened_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`opened_days`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
/*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
INSERT INTO `schedule` VALUES
(1,'{\"1\":\"Lun : 08h00 - 12h00, 13h00 - 17h00\",\"2\":\"Mar : 08h00 - 12h00, 13h00 - 17h00\",\"3\":\"Mer : 10h00 - 13h00, 14h00 - 18h00\",\"4\":\"Jeu : 08h00 - 12h00, 13h00 - 17h00\",\"5\":\"Ven : 08h00 - 12h00, 13h00 - 17h00\",\"6\":\"Sam : 10h00 - 12h00, 13h00 - 16h00\",\"7\":\"Dim : ferm\\u00e9\"}');
/*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `description` longtext DEFAULT NULL,
  `price` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES
(43,'Entretien et vidange','Offrez à votre véhicule le soin qu\'il mérite avec notre service d\'entretien et de vidange de premier ordre. \n                Notre équipe d\'experts qualifiés veillera à ce que votre voiture reste en parfait état de marche. \n                Nous utilisons les meilleures huiles et filtres pour garantir une performance optimale et une longévité accrue de votre moteur. \n                Ne négligez pas l\'entretien de votre précieux véhicule, confiez-le à des professionnels qui en prendront soin comme s\'il était le leur.',80,1),
(44,'Révision','\n            Votre sécurité sur la route est notre priorité numéro un, c\'est pourquoi notre service de révision est conçu\n             pour vous offrir une tranquillité d\'esprit totale. Nos mécaniciens certifiés inspecteront minutieusement \n             chaque composant de votre véhicule, en effectuant les ajustements nécessaires et en remplaçant les pièces usées. \n             Que vous prévoyiez un long voyage ou simplement que vous souhaitiez rouler en toute confiance au quotidien, \n             notre service de révision vous assure que votre véhicule est en parfait état.\n            ',90,1),
(45,'Courroie de distribution','\n            La courroie de distribution est l\'un des éléments les plus critiques de votre moteur, et son remplacement à \n            intervalles réguliers est essentiel pour éviter les pannes coûteuses. Laissez notre équipe de spécialistes \n            prendre en charge cette tâche délicate. Nous utilisons uniquement des pièces de qualité supérieure pour garantir \n            la fiabilité de votre véhicule. Avec notre service de changement de courroie de distribution, \n            vous pouvez conduire l\'esprit tranquille, en sachant que votre moteur est entre de bonnes mains.\n            ',499,1),
(46,'Pneumatiques','\n            Les pneus sont la seule liaison entre votre véhicule et la route. Assurez-vous d\'avoir les pneus adaptés à \n            votre conduite et aux conditions routières. Chez nous, vous trouverez un large choix de pneumatiques de haute qualité, \n            adaptés à tous les budgets. Nous vous offrons également un service de montage professionnel pour vous garantir une adhérence optimale, \n            une tenue de route exceptionnelle et une durée de vie prolongée de vos pneus. Roulez en toute sécurité avec nos pneumatiques de qualité supérieure.\n            ',80,1),
(47,'Freinage - disque et/ou plaquettes','\n            La sécurité de votre véhicule dépend en grande partie de la performance de votre système de freinage. \n            Notre service de freinage et de remplacement de disque de frein est conçu pour garantir un freinage efficace, \n            sans compromis. Nos techniciens expérimentés utilisent uniquement des pièces de rechange de haute qualité \n            pour assurer la réactivité de vos freins dans toutes les situations. Vous pouvez compter sur nous pour maintenir \n            vos freins en parfait état, vous offrant une tranquillité d\'esprit à chaque trajet.\n            ',80,1),
(49,'test','et',80,1);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_garage`
--

DROP TABLE IF EXISTS `service_garage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_garage` (
  `service_id` int(11) NOT NULL,
  `garage_id` int(11) NOT NULL,
  PRIMARY KEY (`service_id`,`garage_id`),
  KEY `IDX_A1E1643DED5CA9E6` (`service_id`),
  KEY `IDX_A1E1643DC4FFF555` (`garage_id`),
  CONSTRAINT `FK_A1E1643DC4FFF555` FOREIGN KEY (`garage_id`) REFERENCES `garage` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A1E1643DED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_garage`
--

LOCK TABLES `service_garage` WRITE;
/*!40000 ALTER TABLE `service_garage` DISABLE KEYS */;
INSERT INTO `service_garage` VALUES
(43,9),
(44,9),
(45,9),
(46,9),
(47,9);
/*!40000 ALTER TABLE `service_garage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonial`
--

DROP TABLE IF EXISTS `testimonial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(60) NOT NULL,
  `comment` longtext NOT NULL,
  `note` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `created_by` varchar(180) NOT NULL,
  `is_read_by_staff` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial`
--

LOCK TABLES `testimonial` WRITE;
/*!40000 ALTER TABLE `testimonial` DISABLE KEYS */;
INSERT INTO `testimonial` VALUES
(7,'Jean-Luc','Toujours souriant et profesionnel. j\'approuve !',5,'2023-10-28 19:45:22','2023-10-28 19:45:22',0,'visiteur anonyme',0);
/*!40000 ALTER TABLE `testimonial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonial_approved`
--

DROP TABLE IF EXISTS `testimonial_approved`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonial_approved` (
  `testimonial_id` int(11) NOT NULL,
  `approved_by_id` int(11) DEFAULT NULL,
  `approved_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`testimonial_id`),
  KEY `IDX_C01CB7372D234F6A` (`approved_by_id`),
  CONSTRAINT `FK_C01CB7371D4EC6B1` FOREIGN KEY (`testimonial_id`) REFERENCES `testimonial` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C01CB7372D234F6A` FOREIGN KEY (`approved_by_id`) REFERENCES `user_staff_member` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial_approved`
--

LOCK TABLES `testimonial_approved` WRITE;
/*!40000 ALTER TABLE `testimonial_approved` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonial_approved` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_customer`
--

DROP TABLE IF EXISTS `user_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(180) NOT NULL,
  `lastname` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(60) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_61B46A09E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_customer`
--

LOCK TABLES `user_customer` WRITE;
/*!40000 ALTER TABLE `user_customer` DISABLE KEYS */;
INSERT INTO `user_customer` VALUES
(38,'Cloud','Strife','cloud.strife@gmail.com','[\"ROLE_CLIENT\"]','$2y$13$4fOq3C3dcXAuDE9KKfSLM.Py0i4PJi9Q2O7qrCZmVadaz43jKacBK',1);
/*!40000 ALTER TABLE `user_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_staff_member`
--

DROP TABLE IF EXISTS `user_staff_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_staff_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `garage_id` int(11) DEFAULT NULL,
  `firstname` varchar(180) NOT NULL,
  `lastname` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F55534C1E7927C74` (`email`),
  KEY `IDX_F55534C1C4FFF555` (`garage_id`),
  CONSTRAINT `FK_F55534C1C4FFF555` FOREIGN KEY (`garage_id`) REFERENCES `garage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_staff_member`
--

LOCK TABLES `user_staff_member` WRITE;
/*!40000 ALTER TABLE `user_staff_member` DISABLE KEYS */;
INSERT INTO `user_staff_member` VALUES
(18,9,'Vincent','Parrot','vincentParrot@VP.com','[\"ROLE_SUPER_ADMIN\"]','$2y$13$4IYLSB9yHfhDTuDSg7/3h.G6z9PQOgOcJYmjyWMsMQ8uqW9oMPTVq'),
(19,9,'John','Doe','johnDoe@VP.com','[\"ROLE_ADMIN\"]','$2y$13$uRUslSAHGy3uzg0.54ujtef1Ozqjy7xG7JurHwhO.tjR4PjoJhZDm');
/*!40000 ALTER TABLE `user_staff_member` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-02  1:27:04
