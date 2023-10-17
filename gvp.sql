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

# DROP DATABASE IF EXISTS  `Garage_Vincent_Parrot`;
# CREATE DATABASE `Garage_Vincent_Parrot`;
# USE `Garage_Vincent_Parrot`;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car`
--

LOCK TABLES `car` WRITE;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` VALUES
(1,1,'bw-606-vr','1994-01-14 01:12:55',147774,40868,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hybrid','208',1,'Peugeot'),
(2,1,'et-342-he','2009-12-01 10:33:03',23384,28946,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hydrogene','308',1,'Peugeot'),
(3,1,'ul-791-vg','2012-12-04 19:41:41',7432,13384,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hydrogene','3008',1,'Peugeot'),
(4,1,'ww-566-yd','2023-04-15 11:09:17',32855,21944,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hydrogene','Clio',1,'Renault'),
(5,1,'ya-702-lk','2013-11-15 20:12:01',159676,48451,'2023-10-16 06:35:13','2023-10-16 06:35:13','Essence','Megane',1,'Renault'),
(6,1,'tj-509-lx','2017-10-22 04:16:50',197751,44698,'2023-10-16 06:35:13','2023-10-16 06:35:13','Diesel','Captur',1,'Renault'),
(7,1,'ws-658-tc','1994-07-09 14:24:34',73422,13148,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hybrid','Sandero Stepway',1,'Dacia'),
(8,1,'gz-517-wr','2022-06-07 01:46:47',156662,35773,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','Duster',1,'Dacia'),
(9,1,'ua-155-ky','2016-07-06 01:05:34',3266,15143,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','Logan',1,'Dacia'),
(10,1,'wy-609-my','2016-03-17 12:41:18',26062,15669,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','Picasso',1,'Citroën'),
(11,1,'ja-681-ju','1997-09-26 07:53:09',161206,29621,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','C3',1,'Citroën'),
(12,1,'yc-593-nx','2017-04-06 19:43:30',113990,28319,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hybrid','C4',1,'Citroën'),
(13,1,'gi-728-vy','2018-06-25 12:19:49',7323,25884,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','Golf',1,'Volkswagen'),
(14,1,'lw-695-ql','2020-01-29 02:32:23',146858,24042,'2023-10-16 06:35:13','2023-10-16 06:35:13','Electrique','Polo',1,'Volkswagen'),
(15,1,'ck-532-pz','2010-01-29 20:28:54',69005,27698,'2023-10-16 06:35:13','2023-10-16 06:35:13','Hybrid','Passat',1,'Volkswagen');
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_message`
--

LOCK TABLES `contact_message` WRITE;
/*!40000 ALTER TABLE `contact_message` DISABLE KEYS */;
INSERT INTO `contact_message` VALUES
(1,'aze','aze','aze@aze.com',NULL,'aze','aze',1,'2023-10-16 07:11:56','Vincent Parrot');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garage`
--

LOCK TABLES `garage` WRITE;
/*!40000 ALTER TABLE `garage` DISABLE KEYS */;
INSERT INTO `garage` VALUES
(1,1,'Siege Social','7 avenue du vase de Soissons, 31000 Toulouse','0123456789','vincentParrot@VP.com');
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_car`
--

LOCK TABLES `image_car` WRITE;
/*!40000 ALTER TABLE `image_car` DISABLE KEYS */;
INSERT INTO `image_car` VALUES
(1,1,'peugeot208-1.webp','peugeot208-1.webp'),
(2,1,'peugeot208-2.webp','peugeot208-2.webp'),
(3,1,'peugeot208-3.webp','peugeot208-3.webp'),
(4,2,'peugeot308-1.webp','peugeot308-1.webp'),
(5,2,'peugeot308-2.webp','peugeot308-2.webp'),
(6,2,'peugeot308-3.webp','peugeot308-3.webp'),
(7,3,'peugeot3008-1.webp','peugeot3008-1.webp'),
(8,3,'peugeot3008-2.webp','peugeot3008-2.webp'),
(9,3,'peugeot3008-3.webp','peugeot3008-3.webp'),
(10,4,'RenaultClio-1.webp','RenaultClio-1.webp'),
(11,4,'RenaultClio-2.webp','RenaultClio-2.webp'),
(12,4,'RenaultClio-3.webp','RenaultClio-3.webp'),
(13,5,'renaultMegane-1.webp','renaultMegane-1.webp'),
(14,5,'renaultMegane-2.webp','renaultMegane-2.webp'),
(15,6,'RenaultCaptur-1.webp','RenaultCaptur-1.webp'),
(16,6,'RenaultCaptur-2.webp','RenaultCaptur-2.webp'),
(17,7,'DaciaSanderoStepway-1.webp','DaciaSanderoStepway-1.webp'),
(18,7,'DaciaSanderoStepway-2.webp','DaciaSanderoStepway-2.webp'),
(19,7,'DaciaSanderoStepway-3.webp','DaciaSanderoStepway-3.webp'),
(20,7,'DaciaSanderoStepway-4.webp','DaciaSanderoStepway-4.webp'),
(21,8,'DaciaDuster-1.webp','DaciaDuster-1.webp'),
(22,8,'DaciaDuster-2.webp','DaciaDuster-2.webp'),
(23,8,'DaciaDuster-3.webp','DaciaDuster-3.webp'),
(24,9,'DaciaLogan-1.webp','DaciaLogan-1.webp'),
(25,10,'CitroënPicasso-1.jpg','CitroënPicasso-1.jpg'),
(26,10,'CitroënPicasso-2.jpg','CitroënPicasso-2.jpg'),
(27,11,'CItroënC3-2.jpg','CItroënC3-2.jpg'),
(28,11,'CitroënC3-1.jpg','CitroënC3-1.jpg'),
(29,12,'CitroënC4-1.jpg','CitroënC4-1.jpg'),
(30,12,'CitroënC4-2.jpg','CitroënC4-2.jpg'),
(31,13,'VolkswagenGolf-1.webp','VolkswagenGolf-1.webp'),
(32,13,'VolkswagenGolf-2.webp','VolkswagenGolf-2.webp'),
(33,13,'VolkswagenGolf-3.webp','VolkswagenGolf-3.webp'),
(34,14,'VolkswagenPolo-1.webp','VolkswagenPolo-1.webp'),
(35,14,'VolkswagenPolo-2.webp','VolkswagenPolo-2.webp'),
(36,14,'VolkswagenPolo-3.webp','VolkswagenPolo-3.webp'),
(37,15,'VolkswagenPassat-1.webp','VolkswagenPassat-1.webp'),
(38,15,'VolkswagenPassat-2.webp','VolkswagenPassat-2.webp'),
(39,15,'VolkswagenPassat-3.webp','VolkswagenPassat-3.webp');
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
  UNIQUE KEY `UNIQ_748DCD0EED5CA9E6` (`service_id`),
  CONSTRAINT `FK_748DCD0EED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_service`
--

LOCK TABLES `image_service` WRITE;
/*!40000 ALTER TABLE `image_service` DISABLE KEYS */;
INSERT INTO `image_service` VALUES
(1,1,'Entretien et vidange.webp','Entretien et vidange'),
(2,2,'Révision.webp','Révision'),
(3,3,'Courroie de distribution.webp','Courroie de distribution'),
(4,4,'Pneumatique.webp','Pneumatique'),
(5,5,'Plaquettes de frein.webp','Freinage disque et plaquettes');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES
(1,'Entretien et vidange','Quia omnis quod ipsa commodi voluptatibus error inventore. Exercitationem ut sit at iure tenetur. Eligendi vero cupiditate est quaerat. Qui vel cumque qui ut sit id qui. Fugit saepe ut tenetur sapiente delectus. Fugit atque optio molestiae ullam modi cumque voluptatem.',80,1),
(2,'Révision','Officiis ipsa sint aut velit. Inventore sint reiciendis nobis. Voluptas possimus rem rerum nisi perspiciatis delectus eligendi. Illum voluptatibus velit molestiae. Sit recusandae iure nihil ut qui. Architecto qui dignissimos velit.',90,1),
(3,'Courroie de distribution','Nam expedita consequatur et tempore. Facilis perferendis commodi mollitia neque. Voluptas laboriosam ut quos eos eaque. Beatae consequatur iure debitis. Et voluptatem quisquam eius ut et laudantium et aut. Voluptatem quis perferendis nisi placeat quibusdam ut. Eveniet aut quaerat in ducimus.',499,1),
(4,'Pneumatiques','Non itaque aperiam deserunt necessitatibus a. Earum id hic vitae possimus voluptatem non quia harum. Ullam recusandae aliquid asperiores id quisquam. Esse vel officia tempore minus ducimus. Non aut cupiditate est alias asperiores. Aut sed quam recusandae ut nostrum dolor.',80,1),
(5,'Freinage - disque et/ou plaquettes','Qui est tempore autem enim soluta eligendi eligendi. Minus autem possimus repudiandae est voluptatem laudantium. Consequatur adipisci autem quas itaque harum. Sint natus laboriosam ut odit. Ducimus saepe saepe repudiandae repellat quaerat vel qui. Qui molestiae quod cum architecto tempore debitis.',80,1);
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
(1,1),
(2,1),
(3,1),
(4,1),
(5,1);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial`
--

LOCK TABLES `testimonial` WRITE;
/*!40000 ALTER TABLE `testimonial` DISABLE KEYS */;
INSERT INTO `testimonial` VALUES
(4,'Elodie','bou',1,'2023-10-16 19:42:19','2023-10-16 19:42:19',0,'Elodie Weiler');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_customer`
--

LOCK TABLES `user_customer` WRITE;
/*!40000 ALTER TABLE `user_customer` DISABLE KEYS */;
INSERT INTO `user_customer` VALUES
(1,'Elodie','Weiler','elodie.weiler78@gmail.com','[\"ROLE_CLIENT\"]','$2y$13$W3MV664R.y7BV2WEOHOmouAj5E1LSKkgD.RBQZFvuxN8O3quAloHW',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_staff_member`
--

LOCK TABLES `user_staff_member` WRITE;
/*!40000 ALTER TABLE `user_staff_member` DISABLE KEYS */;
INSERT INTO `user_staff_member` VALUES
(1,1,'Vincent','Parrot','vincentParrot@VP.com','[\"ROLE_SUPER_ADMIN\"]','$2y$13$.lTvjQlf9Bp0AteWp.EJkuL1uM2U.EqHu2TlssgWCs64o.371gAle');
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

-- Dump completed on 2023-10-16 23:02:47
