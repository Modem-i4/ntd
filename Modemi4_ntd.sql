-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: Modemi4_ntd
-- ------------------------------------------------------
-- Server version	10.5.12-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `Modemi4_ntd`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `Modemi4_ntd` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `Modemi4_ntd`;

--
-- Table structure for table `ans_sur`
--

DROP TABLE IF EXISTS `ans_sur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ans_sur` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `session_id` mediumint(9) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `reason_n` tinyint(4) DEFAULT NULL,
  `val_before` tinyint(4) DEFAULT NULL,
  `val_after` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sa_reason` (`scenario_slug`,`sur_n`,`reason_n`),
  KEY `fk_sa_session` (`session_id`),
  CONSTRAINT `fk_sa_reason` FOREIGN KEY (`scenario_slug`, `sur_n`, `reason_n`) REFERENCES `reasons` (`scenario_slug`, `sur_n`, `number`),
  CONSTRAINT `fk_sa_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`),
  CONSTRAINT `fk_sa_sur` FOREIGN KEY (`scenario_slug`, `sur_n`) REFERENCES `surveys` (`scenario_slug`, `sur_n`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ans_sur`
--

LOCK TABLES `ans_sur` WRITE;
/*!40000 ALTER TABLE `ans_sur` DISABLE KEYS */;
INSERT INTO `ans_sur` VALUES (1,1,'vitovt',1,NULL,5,NULL,'2025-09-24 08:08:21'),(2,2,'vitovt',2,1,6,7,'2025-09-24 08:47:16'),(3,3,'vitovt',1,NULL,5,NULL,'2025-09-24 08:26:34'),(4,4,'vitovt',2,NULL,8,NULL,'2025-09-24 08:43:47'),(5,5,'vitovt',2,NULL,5,NULL,'2025-09-24 08:57:22'),(6,6,'vitovt',2,NULL,7,NULL,'2025-09-24 09:59:35'),(7,7,'vitovt',3,NULL,6,NULL,'2025-09-24 10:09:44'),(8,8,'vitovt',3,NULL,6,NULL,'2025-09-24 10:11:20'),(9,9,'vitovt',1,NULL,4,NULL,'2025-09-24 10:14:40'),(10,10,'vitovt',1,NULL,4,NULL,'2025-09-24 10:44:00'),(11,11,'vitovt',1,NULL,3,NULL,'2025-09-24 10:45:53'),(12,12,'vitovt',3,NULL,1,NULL,'2025-09-24 10:59:32'),(13,13,'vitovt',1,1,10,10,'2025-09-24 11:43:30'),(14,14,'vitovt',1,NULL,NULL,NULL,'2025-09-24 11:33:15'),(15,15,'vitovt',3,3,10,10,'2025-09-24 11:41:53'),(16,16,'vitovt',3,3,10,10,'2025-09-24 11:38:21'),(17,17,'vitovt',2,1,7,7,'2025-09-24 11:44:00'),(18,18,'vitovt',2,3,5,9,'2025-09-24 11:42:45'),(19,19,'vitovt',3,NULL,NULL,NULL,'2025-09-24 11:34:31'),(20,20,'vitovt',1,3,7,7,'2025-09-24 11:44:02'),(21,21,'vitovt',3,3,10,10,'2025-09-24 11:46:42'),(22,22,'vitovt',1,3,NULL,10,'2025-09-24 11:43:46'),(23,23,'vitovt',3,3,10,7,'2025-09-24 11:42:30'),(24,24,'danylo',3,NULL,4,NULL,'2025-09-24 20:32:14'),(25,25,'danylo',1,NULL,10,NULL,'2025-09-24 21:15:14'),(26,26,'vitovt',3,NULL,1,NULL,'2025-09-25 05:21:07'),(27,27,'orsha',2,NULL,1,NULL,'2025-09-25 05:23:13'),(28,28,'orsha',2,NULL,4,NULL,'2025-09-25 06:24:30'),(29,29,'vitovt',1,3,NULL,6,'2025-09-25 06:37:27'),(30,30,'orsha',3,NULL,4,NULL,'2025-09-25 06:52:31'),(31,32,'danylo',3,NULL,2,NULL,'2025-09-25 07:07:53'),(32,34,'danylo',1,3,1,10,'2025-09-25 07:43:32'),(33,36,'danylo',2,1,5,10,'2025-09-25 07:56:09'),(34,37,'danylo',2,3,10,10,'2025-09-25 07:47:22'),(35,38,'orsha',1,1,8,8,'2025-09-25 07:49:11'),(36,40,'danylo',1,3,NULL,10,'2025-09-25 07:52:24'),(37,41,'orsha',2,1,7,7,'2025-09-25 07:49:15'),(38,44,'danylo',3,3,NULL,10,'2025-09-25 07:53:18'),(39,46,'danylo',2,1,5,5,'2025-09-25 07:53:45'),(40,47,'danylo',1,3,1,9,'2025-09-25 07:46:48'),(41,48,'danylo',3,NULL,5,NULL,'2025-09-25 07:42:19'),(42,50,'vitovt',2,3,5,10,'2025-09-25 07:49:11'),(43,52,'vitovt',3,1,10,8,'2025-09-25 07:51:24'),(44,53,'danylo',2,3,6,8,'2025-09-25 07:57:02'),(45,54,'vitovt',3,1,10,7,'2025-09-25 08:03:19'),(46,55,'orsha',3,NULL,4,NULL,'2025-09-25 07:54:02'),(47,56,'orsha',1,NULL,10,NULL,'2025-09-25 07:54:40'),(48,57,'vitovt',1,3,10,10,'2025-09-25 08:02:05'),(49,58,'vitovt',3,NULL,NULL,NULL,'2025-09-25 07:55:13'),(50,59,'vitovt',1,1,7,9,'2025-09-25 08:03:42'),(51,60,'vitovt',2,3,10,8,'2025-09-25 08:02:04'),(52,61,'vitovt',3,3,10,10,'2025-09-25 08:00:06'),(53,62,'vitovt',1,3,10,8,'2025-09-25 08:08:15'),(54,63,'orsha',2,NULL,10,NULL,'2025-09-25 07:57:03'),(55,64,'vitovt',3,3,8,10,'2025-09-25 08:06:39'),(56,65,'orsha',3,NULL,5,NULL,'2025-09-25 07:57:46'),(57,66,'orsha',3,1,9,10,'2025-09-25 08:02:00'),(58,67,'orsha',2,NULL,10,NULL,'2025-09-25 08:07:51'),(61,73,'danylo',2,NULL,5,NULL,'2025-09-26 13:06:12'),(62,74,'danylo',1,NULL,5,NULL,'2025-09-26 15:35:13'),(63,75,'danylo',1,NULL,10,NULL,'2025-09-27 10:04:30'),(64,76,'vitovt',2,NULL,1,NULL,'2025-09-27 10:08:20'),(65,77,'orsha',3,NULL,10,NULL,'2025-09-27 10:09:59'),(66,78,'vitovt',1,NULL,3,NULL,'2025-09-28 13:51:42'),(67,79,'vitovt',2,NULL,7,NULL,'2025-09-28 16:11:02'),(68,80,'vitovt',2,NULL,5,NULL,'2025-09-30 16:42:07'),(69,81,'vitovt',2,NULL,8,NULL,'2025-09-30 16:47:01');
/*!40000 ALTER TABLE `ans_sur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ans_test`
--

DROP TABLE IF EXISTS `ans_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ans_test` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `session_id` mediumint(9) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `test_n` tinyint(4) NOT NULL,
  `first_option_n` tinyint(4) DEFAULT NULL,
  `final_option_n` tinyint(4) DEFAULT NULL,
  `delta` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ta_final` (`scenario_slug`,`metric`,`test_n`,`final_option_n`),
  KEY `fk_ta_first` (`scenario_slug`,`metric`,`test_n`,`first_option_n`),
  KEY `fk_ta_session` (`session_id`),
  CONSTRAINT `fk_ta_final` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`, `final_option_n`) REFERENCES `test_opts` (`scenario_slug`, `metric`, `test_n`, `number`),
  CONSTRAINT `fk_ta_first` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`, `first_option_n`) REFERENCES `test_opts` (`scenario_slug`, `metric`, `test_n`, `number`),
  CONSTRAINT `fk_ta_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`),
  CONSTRAINT `fk_ta_test` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`) REFERENCES `tests` (`scenario_slug`, `metric`, `number`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ans_test`
--

LOCK TABLES `ans_test` WRITE;
/*!40000 ALTER TABLE `ans_test` DISABLE KEYS */;
INSERT INTO `ans_test` VALUES (25,13,'vitovt','critical',2,2,2,0,'2025-09-24 11:43:30'),(26,13,'vitovt','narratives',3,2,2,0,'2025-09-24 11:43:30'),(27,14,'vitovt','critical',2,2,NULL,NULL,'2025-09-24 11:33:15'),(28,14,'vitovt','narratives',2,1,NULL,NULL,'2025-09-24 11:33:15'),(29,15,'vitovt','critical',1,3,3,0,'2025-09-24 11:41:53'),(30,15,'vitovt','narratives',1,2,2,0,'2025-09-24 11:41:53'),(31,16,'vitovt','critical',1,1,3,1,'2025-09-24 11:38:21'),(32,16,'vitovt','narratives',1,2,2,0,'2025-09-24 11:38:21'),(33,17,'vitovt','critical',1,3,3,0,'2025-09-24 11:44:00'),(34,17,'vitovt','narratives',1,2,2,0,'2025-09-24 11:44:00'),(35,18,'vitovt','critical',2,1,1,0,'2025-09-24 11:42:45'),(36,18,'vitovt','narratives',1,2,2,0,'2025-09-24 11:42:45'),(37,19,'vitovt','critical',2,2,NULL,NULL,'2025-09-24 11:34:31'),(38,19,'vitovt','narratives',2,1,NULL,NULL,'2025-09-24 11:34:31'),(39,20,'vitovt','critical',2,1,2,1,'2025-09-24 11:44:02'),(40,20,'vitovt','narratives',3,2,2,0,'2025-09-24 11:44:02'),(41,21,'vitovt','critical',2,2,2,0,'2025-09-24 11:46:42'),(42,21,'vitovt','narratives',2,2,2,0,'2025-09-24 11:46:42'),(43,22,'vitovt','critical',3,2,3,1,'2025-09-24 11:43:46'),(44,22,'vitovt','narratives',1,2,2,0,'2025-09-24 11:43:46'),(45,23,'vitovt','critical',3,1,3,1,'2025-09-24 11:42:30'),(46,23,'vitovt','narratives',3,1,2,1,'2025-09-24 11:42:30'),(47,26,'vitovt','critical',1,1,NULL,NULL,'2025-09-25 05:21:07'),(48,26,'vitovt','narratives',3,1,NULL,NULL,'2025-09-25 05:21:07'),(49,27,'orsha','critical',1,2,NULL,NULL,'2025-09-25 05:23:13'),(50,27,'orsha','narratives',1,3,NULL,NULL,'2025-09-25 05:23:13'),(51,28,'orsha','critical',2,1,NULL,NULL,'2025-09-25 06:24:30'),(52,28,'orsha','narratives',3,2,NULL,NULL,'2025-09-25 06:24:30'),(53,30,'orsha','critical',1,2,NULL,NULL,'2025-09-25 06:52:31'),(54,30,'orsha','narratives',2,3,NULL,NULL,'2025-09-25 06:52:31'),(55,31,'danylo','critical',2,2,NULL,NULL,'2025-09-25 07:05:34'),(56,31,'danylo','narratives',2,3,NULL,NULL,'2025-09-25 07:05:34'),(57,34,'danylo','critical',1,1,2,-1,'2025-09-25 07:43:32'),(58,36,'danylo','narratives',1,2,2,0,'2025-09-25 07:56:09'),(59,38,'orsha','critical',2,3,1,1,'2025-09-25 07:49:11'),(60,38,'orsha','narratives',2,2,2,0,'2025-09-25 07:49:11'),(61,39,'danylo','narratives',2,3,2,1,'2025-09-25 07:47:37'),(62,40,'danylo','critical',3,2,1,1,'2025-09-25 07:52:24'),(63,40,'danylo','narratives',1,1,2,1,'2025-09-25 07:52:24'),(64,41,'orsha','critical',2,3,2,0,'2025-09-25 07:49:15'),(65,41,'orsha','narratives',1,1,3,0,'2025-09-25 07:49:15'),(66,42,'danylo','critical',1,3,2,0,'2025-09-25 07:54:45'),(67,43,'danylo','narratives',2,2,2,0,'2025-09-25 07:55:28'),(68,44,'danylo','critical',3,2,1,1,'2025-09-25 07:53:18'),(69,45,'danylo','narratives',2,1,3,0,'2025-09-25 07:56:01'),(70,46,'danylo','narratives',1,1,2,1,'2025-09-25 07:53:45'),(71,48,'danylo','critical',2,1,NULL,NULL,'2025-09-25 07:42:19'),(72,49,'danylo','critical',1,2,2,0,'2025-09-25 07:51:09'),(73,50,'vitovt','critical',2,1,2,1,'2025-09-25 07:49:11'),(74,50,'vitovt','narratives',2,2,3,0,'2025-09-25 07:49:11'),(75,51,'danylo','critical',2,1,NULL,NULL,'2025-09-25 07:45:38'),(76,52,'vitovt','critical',1,1,3,1,'2025-09-25 07:51:24'),(77,52,'vitovt','narratives',2,2,1,1,'2025-09-25 07:51:24'),(78,53,'danylo','critical',2,1,3,1,'2025-09-25 07:57:02'),(79,53,'danylo','narratives',2,3,2,1,'2025-09-25 07:57:02'),(80,54,'vitovt','critical',2,1,2,1,'2025-09-25 08:03:19'),(81,54,'vitovt','narratives',3,1,2,1,'2025-09-25 08:03:19'),(82,55,'orsha','critical',1,1,NULL,NULL,'2025-09-25 07:54:02'),(83,55,'orsha','narratives',1,1,NULL,NULL,'2025-09-25 07:54:02'),(84,56,'orsha','critical',1,1,NULL,NULL,'2025-09-25 07:54:40'),(85,56,'orsha','narratives',3,2,NULL,NULL,'2025-09-25 07:54:40'),(86,57,'vitovt','critical',2,1,2,1,'2025-09-25 08:02:05'),(87,57,'vitovt','narratives',3,2,2,0,'2025-09-25 08:02:05'),(88,58,'vitovt','critical',2,1,NULL,NULL,'2025-09-25 07:55:13'),(89,58,'vitovt','narratives',3,2,NULL,NULL,'2025-09-25 07:55:13'),(90,59,'vitovt','critical',3,2,2,0,'2025-09-25 08:03:42'),(91,59,'vitovt','narratives',3,1,3,0,'2025-09-25 08:03:42'),(92,60,'vitovt','critical',2,2,1,-1,'2025-09-25 08:02:04'),(93,60,'vitovt','narratives',3,1,1,0,'2025-09-25 08:02:04'),(94,61,'vitovt','critical',2,3,3,0,'2025-09-25 08:00:06'),(95,61,'vitovt','narratives',1,2,2,0,'2025-09-25 08:00:06'),(96,62,'vitovt','critical',3,1,3,1,'2025-09-25 08:08:15'),(97,62,'vitovt','narratives',1,2,2,0,'2025-09-25 08:08:15'),(98,63,'orsha','critical',1,1,NULL,NULL,'2025-09-25 07:57:03'),(99,63,'orsha','narratives',2,3,NULL,NULL,'2025-09-25 07:57:03'),(100,64,'vitovt','critical',2,2,2,0,'2025-09-25 08:06:39'),(101,64,'vitovt','narratives',3,3,2,1,'2025-09-25 08:06:39'),(102,65,'orsha','critical',2,3,NULL,NULL,'2025-09-25 07:57:46'),(103,65,'orsha','narratives',3,2,NULL,NULL,'2025-09-25 07:57:46'),(104,66,'orsha','critical',2,1,3,-1,'2025-09-25 08:02:00'),(105,66,'orsha','narratives',1,2,2,0,'2025-09-25 08:02:00'),(106,67,'orsha','critical',1,3,NULL,NULL,'2025-09-25 08:07:51'),(107,67,'orsha','narratives',2,3,NULL,NULL,'2025-09-25 08:07:51'),(114,73,'danylo','critical',1,2,NULL,NULL,'2025-09-26 13:06:12'),(115,73,'danylo','narratives',1,3,NULL,NULL,'2025-09-26 13:06:12'),(116,74,'danylo','critical',2,1,NULL,NULL,'2025-09-26 15:35:13'),(117,74,'danylo','narratives',1,1,NULL,NULL,'2025-09-26 15:35:13'),(118,75,'danylo','critical',2,3,NULL,NULL,'2025-09-27 10:04:30'),(119,75,'danylo','narratives',1,2,NULL,NULL,'2025-09-27 10:04:30'),(120,76,'vitovt','critical',3,3,NULL,NULL,'2025-09-27 10:08:20'),(121,76,'vitovt','narratives',3,3,NULL,NULL,'2025-09-27 10:08:20'),(122,77,'orsha','critical',1,3,NULL,NULL,'2025-09-27 10:09:59'),(123,77,'orsha','narratives',2,3,NULL,NULL,'2025-09-27 10:09:59'),(124,78,'vitovt','critical',1,1,NULL,NULL,'2025-09-28 13:51:42'),(125,78,'vitovt','narratives',2,1,NULL,NULL,'2025-09-28 13:51:42'),(126,79,'vitovt','critical',2,2,NULL,NULL,'2025-09-28 16:11:02'),(127,79,'vitovt','narratives',3,2,NULL,NULL,'2025-09-28 16:11:02'),(128,80,'vitovt','critical',1,3,NULL,NULL,'2025-09-30 16:42:07'),(129,80,'vitovt','narratives',1,2,NULL,NULL,'2025-09-30 16:42:07'),(130,81,'vitovt','critical',1,3,NULL,NULL,'2025-09-30 16:47:01'),(131,81,'vitovt','narratives',2,1,NULL,NULL,'2025-09-30 16:47:01');
/*!40000 ALTER TABLE `ans_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metrics`
--

DROP TABLE IF EXISTS `metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metrics` (
  `metric` varchar(64) NOT NULL,
  PRIMARY KEY (`metric`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metrics`
--

LOCK TABLES `metrics` WRITE;
/*!40000 ALTER TABLE `metrics` DISABLE KEYS */;
INSERT INTO `metrics` VALUES ('critical'),('narratives');
/*!40000 ALTER TABLE `metrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reasons`
--

DROP TABLE IF EXISTS `reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reasons` (
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `option_text` text DEFAULT NULL,
  PRIMARY KEY (`scenario_slug`,`sur_n`,`number`),
  CONSTRAINT `fk_reasons_sur` FOREIGN KEY (`scenario_slug`, `sur_n`) REFERENCES `surveys` (`scenario_slug`, `sur_n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reasons`
--

LOCK TABLES `reasons` WRITE;
/*!40000 ALTER TABLE `reasons` DISABLE KEYS */;
INSERT INTO `reasons` VALUES ('danylo',1,1,'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),('danylo',1,2,'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),('danylo',1,3,'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),('danylo',2,1,'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),('danylo',2,2,'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),('danylo',2,3,'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),('danylo',3,1,'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),('danylo',3,2,'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),('danylo',3,3,'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),('orsha',1,1,'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),('orsha',1,2,'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),('orsha',1,3,'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.'),('orsha',2,1,'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),('orsha',2,2,'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),('orsha',2,3,'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.'),('orsha',3,1,'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),('orsha',3,2,'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),('orsha',3,3,'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.'),('vitovt',1,1,'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),('vitovt',1,2,'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),('vitovt',1,3,'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат'),('vitovt',2,1,'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),('vitovt',2,2,'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),('vitovt',2,3,'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат'),('vitovt',3,1,'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),('vitovt',3,2,'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),('vitovt',3,3,'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат');
/*!40000 ALTER TABLE `reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenarios`
--

DROP TABLE IF EXISTS `scenarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenarios` (
  `slug` varchar(12) NOT NULL,
  PRIMARY KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenarios`
--

LOCK TABLES `scenarios` WRITE;
/*!40000 ALTER TABLE `scenarios` DISABLE KEYS */;
INSERT INTO `scenarios` VALUES ('danylo'),('orsha'),('vitovt');
/*!40000 ALTER TABLE `scenarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_code` char(6) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `game_started_at` timestamp NULL DEFAULT NULL,
  `game_ended_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sess_scenario` (`scenario_slug`),
  KEY `fk_sess_user` (`user_code`),
  CONSTRAINT `fk_sess_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`),
  CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_code`) REFERENCES `users` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (1,'730059','vitovt','2025-09-24 05:08:13','2025-09-24 05:08:21',NULL,NULL),(2,'601162','vitovt','2025-09-24 05:24:31','2025-09-24 05:25:24','2025-09-24 05:45:58','2025-09-24 05:47:16'),(3,'510191','vitovt','2025-09-24 05:24:37','2025-09-24 05:26:32',NULL,NULL),(4,'860570','vitovt','2025-09-24 05:43:43','2025-09-24 05:43:47',NULL,NULL),(5,'017073','vitovt','2025-09-24 05:53:37','2025-09-24 05:57:21',NULL,NULL),(6,'669249','vitovt','2025-09-24 06:59:32','2025-09-24 06:59:35',NULL,NULL),(7,'765148','vitovt','2025-09-24 07:09:37','2025-09-24 07:09:44',NULL,NULL),(8,'787372','vitovt','2025-09-24 07:11:13','2025-09-24 07:11:19',NULL,NULL),(9,'787372','vitovt','2025-09-24 07:14:23','2025-09-24 07:14:39',NULL,NULL),(10,'650641','vitovt','2025-09-24 07:43:50','2025-09-24 07:43:59',NULL,NULL),(11,'609634','vitovt','2025-09-24 07:45:48','2025-09-24 07:45:53',NULL,NULL),(12,'510191','vitovt','2025-09-24 07:59:24','2025-09-24 07:59:30',NULL,NULL),(13,'911505','vitovt','2025-09-24 08:31:28','2025-09-24 08:33:05','2025-09-24 08:42:10','2025-09-24 08:43:30'),(14,'842609','vitovt','2025-09-24 08:31:39','2025-09-24 08:33:15',NULL,NULL),(15,'926952','vitovt','2025-09-24 08:32:03','2025-09-24 08:33:18','2025-09-24 08:40:13','2025-09-24 08:41:52'),(16,'564038','vitovt','2025-09-24 08:32:08','2025-09-24 08:33:29','2025-09-24 08:37:24','2025-09-24 08:38:20'),(17,'472795','vitovt','2025-09-24 08:33:02','2025-09-24 08:33:52','2025-09-24 08:43:06','2025-09-24 08:44:00'),(18,'821727','vitovt','2025-09-24 08:33:15','2025-09-24 08:34:04','2025-09-24 08:42:08','2025-09-24 08:42:44'),(19,'582608','vitovt','2025-09-24 08:32:44','2025-09-24 08:34:31',NULL,NULL),(20,'590486','vitovt','2025-09-24 08:33:04','2025-09-24 08:34:42','2025-09-24 08:43:28','2025-09-24 08:44:01'),(21,'432851','vitovt','2025-09-24 08:33:07','2025-09-24 08:34:55','2025-09-24 08:45:36','2025-09-24 08:46:43'),(22,'323470','vitovt','2025-09-24 08:32:46','2025-09-24 08:35:17','2025-09-24 08:43:16','2025-09-24 08:43:47'),(23,'345781','vitovt','2025-09-24 08:32:59','2025-09-24 08:35:38','2025-09-24 08:39:22','2025-09-24 08:42:29'),(24,'787372','danylo','2025-09-24 17:32:08','2025-09-24 17:32:13',NULL,NULL),(25,'695685','danylo','2025-09-24 18:14:53','2025-09-24 18:15:13',NULL,NULL),(26,'787372','vitovt','2025-09-25 02:20:54','2025-09-25 02:21:06',NULL,NULL),(27,'787372','orsha','2025-09-25 02:22:26','2025-09-25 02:23:13',NULL,NULL),(28,'673097','orsha','2025-09-25 03:24:13','2025-09-25 03:24:28',NULL,NULL),(29,'787372','vitovt','2025-09-25 06:37:27',NULL,'2025-09-25 03:37:11','2025-09-25 03:37:26'),(30,'787372','orsha','2025-09-25 03:52:24','2025-09-25 03:52:30',NULL,NULL),(31,'787372','danylo','2025-09-25 04:05:27','2025-09-25 04:05:33',NULL,NULL),(32,'414493','danylo','2025-09-25 04:07:49','2025-09-25 04:07:52',NULL,NULL),(33,'485299','danylo','2025-09-25 04:39:31','2025-09-25 04:39:54','2025-09-25 04:49:08','2025-09-25 04:49:19'),(34,'099388','danylo','2025-09-25 04:39:09','2025-09-25 04:39:54','2025-09-25 04:43:11','2025-09-25 04:43:32'),(35,'797399','danylo','2025-09-25 04:39:46','2025-09-25 04:40:15','2025-09-25 04:52:13','2025-09-25 04:52:49'),(36,'569388','danylo','2025-09-25 04:39:46','2025-09-25 04:40:27','2025-09-25 04:55:01','2025-09-25 04:56:08'),(37,'732929','danylo','2025-09-25 04:39:56','2025-09-25 04:40:41','2025-09-25 04:47:05','2025-09-25 04:47:22'),(38,'938730','orsha','2025-09-25 04:39:47','2025-09-25 04:40:41','2025-09-25 04:48:50','2025-09-25 04:49:10'),(39,'650502','danylo','2025-09-25 04:40:06','2025-09-25 04:40:46','2025-09-25 04:47:09','2025-09-25 04:47:36'),(40,'912098','danylo','2025-09-25 04:40:04','2025-09-25 04:40:49','2025-09-25 04:51:06','2025-09-25 04:52:22'),(41,'008296','orsha','2025-09-25 04:39:58','2025-09-25 04:40:53','2025-09-25 04:48:15','2025-09-25 04:49:15'),(42,'234494','danylo','2025-09-25 04:39:47','2025-09-25 04:41:23','2025-09-25 04:54:05','2025-09-25 04:54:45'),(43,'661450','danylo','2025-09-25 04:40:56','2025-09-25 04:41:24','2025-09-25 04:55:00','2025-09-25 04:55:27'),(44,'378653','danylo','2025-09-25 04:40:44','2025-09-25 04:41:31','2025-09-25 04:52:22','2025-09-25 04:53:18'),(45,'186328','danylo','2025-09-25 04:40:17','2025-09-25 04:41:22','2025-09-25 04:54:43','2025-09-25 04:55:35'),(46,'999205','danylo','2025-09-25 04:39:59','2025-09-25 04:41:54','2025-09-25 04:53:12','2025-09-25 04:53:45'),(47,'358743','danylo','2025-09-25 04:41:24','2025-09-25 04:42:04','2025-09-25 04:45:57','2025-09-25 04:46:48'),(48,'155643','danylo','2025-09-25 04:41:26','2025-09-25 04:42:19',NULL,NULL),(49,'983910','danylo','2025-09-25 04:42:52','2025-09-25 04:43:55','2025-09-25 04:50:19','2025-09-25 04:51:08'),(50,'099388','vitovt','2025-09-25 04:44:38','2025-09-25 04:44:44','2025-09-25 04:48:50','2025-09-25 04:49:10'),(51,'102795','danylo','2025-09-25 04:45:31','2025-09-25 04:45:39',NULL,NULL),(52,'358743','vitovt','2025-09-25 04:47:45','2025-09-25 04:48:03','2025-09-25 04:51:06','2025-09-25 04:51:24'),(53,'155643','danylo','2025-09-25 04:50:48','2025-09-25 04:51:12','2025-09-25 04:56:47','2025-09-25 04:57:02'),(54,'485299','vitovt','2025-09-25 04:53:39','2025-09-25 04:53:46','2025-09-25 05:02:58','2025-09-25 05:03:19'),(55,'679855','orsha','2025-09-25 04:53:35','2025-09-25 04:54:02',NULL,NULL),(56,'912098','orsha','2025-09-25 04:53:59','2025-09-25 04:54:39',NULL,NULL),(57,'938730','vitovt','2025-09-25 04:55:04','2025-09-25 04:55:10','2025-09-25 05:01:29','2025-09-25 05:02:04'),(58,'378653','vitovt','2025-09-25 04:54:49','2025-09-25 04:55:13',NULL,NULL),(59,'999205','vitovt','2025-09-25 04:55:07','2025-09-25 04:55:28','2025-09-25 05:03:31','2025-09-25 05:03:42'),(60,'008296','vitovt','2025-09-25 04:55:09','2025-09-25 04:55:49','2025-09-25 05:01:52','2025-09-25 05:02:04'),(61,'732929','vitovt','2025-09-25 04:56:17','2025-09-25 04:56:20','2025-09-25 04:59:48','2025-09-25 05:00:06'),(62,'661450','vitovt','2025-09-25 04:56:21','2025-09-25 04:56:52','2025-09-25 05:07:40','2025-09-25 05:08:14'),(63,'234494','orsha','2025-09-25 04:55:48','2025-09-25 04:57:03',NULL,NULL),(64,'186328','vitovt','2025-09-25 04:56:30','2025-09-25 04:56:53','2025-09-25 05:05:31','2025-09-25 05:06:13'),(65,'569388','orsha','2025-09-25 04:57:03','2025-09-25 04:57:46',NULL,NULL),(66,'155643','orsha','2025-09-25 04:57:45','2025-09-25 04:57:51','2025-09-25 05:01:50','2025-09-25 05:02:00'),(67,'186328','orsha','2025-09-25 05:07:18','2025-09-25 05:07:25',NULL,NULL),(73,'186328','danylo','2025-09-26 10:05:25','2025-09-26 10:05:46',NULL,NULL),(74,'246388','danylo','2025-09-26 12:34:48','2025-09-26 12:35:13',NULL,NULL),(75,'476035','danylo','2025-09-27 07:02:59','2025-09-27 07:04:30',NULL,NULL),(76,'476035','vitovt','2025-09-27 07:08:15','2025-09-27 07:08:20',NULL,NULL),(77,'476035','orsha','2025-09-27 07:09:39','2025-09-27 07:09:58',NULL,NULL),(78,'953486','vitovt','2025-09-28 10:51:34','2025-09-28 10:51:41',NULL,NULL),(79,'953486','vitovt','2025-09-28 13:10:25','2025-09-28 13:11:01',NULL,NULL),(80,'157830','vitovt','2025-09-30 13:41:25','2025-09-30 13:42:06',NULL,NULL),(81,'157830','vitovt','2025-09-30 13:46:26','2025-09-30 13:47:00',NULL,NULL);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surveys`
--

DROP TABLE IF EXISTS `surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surveys` (
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `question_text` text DEFAULT NULL,
  PRIMARY KEY (`scenario_slug`,`sur_n`),
  CONSTRAINT `fk_surs_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surveys`
--

LOCK TABLES `surveys` WRITE;
/*!40000 ALTER TABLE `surveys` DISABLE KEYS */;
INSERT INTO `surveys` VALUES ('danylo',1,'Я готовий діяти рішуче, коли хочу щось змінити в школі чи навколо.'),('danylo',2,'Я готовий шукати однодумців, бо разом легше досягати мети.'),('danylo',3,'Мій голос важливий: загалом, в громаді, в школі.'),('orsha',1,'Я готовий діяти й давати ідеї, коли хочу щось змінити в школі чи навколо.'),('orsha',2,'Мій голос важливий: загалом, в громаді, в школі.'),('orsha',3,'Я готовий брати відповідальність за свої рішення, навіть якщо вони впливають на інших.'),('vitovt',1,'Я готов[ий/а] діяти й давати ідеї, коли хочу щось змінити в школі чи навколо.'),('vitovt',2,'Якщо є офіційний спосіб розв’язати проблему (через школу чи громаду), я ним скористаюся.'),('vitovt',3,'Я хочу вирішувати справи чесно й відкрито, зважаючи на правила і докази.');
/*!40000 ALTER TABLE `surveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_opts`
--

DROP TABLE IF EXISTS `test_opts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_opts` (
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `test_n` tinyint(4) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `option_text` text DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`scenario_slug`,`metric`,`test_n`,`number`),
  CONSTRAINT `fk_to_test` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`) REFERENCES `tests` (`scenario_slug`, `metric`, `number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_opts`
--

LOCK TABLES `test_opts` WRITE;
/*!40000 ALTER TABLE `test_opts` DISABLE KEYS */;
INSERT INTO `test_opts` VALUES ('danylo','critical',1,1,'Щоб отримати допомогу європейських держав і скликали хрестовий похід.',1),('danylo','critical',1,2,'Щоб мати рівний статус з іншими правителям католицьких держав.',0),('danylo','critical',1,3,'Щоб вільно торгувати та взаємодіяти із католицькими державами.',0),('danylo','critical',2,1,'Бо Данило не виконав свою обіцянку щодо укладення церковної унії.',0),('danylo','critical',2,2,'Бо європейці хотіли спробувати провести переговори з монгольською імперією.',0),('danylo','critical',2,3,'Бо європейські правителі були зайняті власними війнами та конфліктами.',1),('danylo','critical',3,1,'Данило займався загальнодержавними справами, а Василько його підтримував, правлячи на Волині.',1),('danylo','critical',3,2,'Вони вели боротьбу за вплив, намагаючись перетягнути на свій бік бояр та сусідні країни.',0),('danylo','critical',3,3,'Вони розділили державу на дві незалежні частини та правили в них, щоб уникнути конфліктів за владу.',0),('danylo','narratives',1,1,'Свідченням повної втрати державності та особистим приниженням правителя.',0),('danylo','narratives',1,2,'Дипломатичним кроком, що дозволив зберегти державу',1),('danylo','narratives',1,3,'Спробою укласти військовий союз із Золотою Ордою проти угорських та польських нападників',0),('danylo','narratives',2,1,'Невеликою частиною «руського світу», залежною від сильніших сусідів.',0),('danylo','narratives',2,2,'Головним спадкоємцем Русі-України та впливовим європейським королівством.',1),('danylo','narratives',2,3,'Сильно залежною державою, що страждало від постійних внутрішніх боярських воєн',0),('orsha','critical',1,1,'Боротьба за спадщину Русі-України та контроль над руськими землями.',1),('orsha','critical',1,2,'Суперечка через важливу торгівельну фортецю Смоленськ та шлях із «варягів у греки».',0),('orsha','critical',1,3,'Особистий конфлікт та образа між правителями обох держав.',0),('orsha','critical',2,1,'Вона зупинила наступ ворога та прославила військо в Європі.',1),('orsha','critical',2,2,'Вона призвела до капітуляції москви та завершення війни.',0),('orsha','critical',2,3,'Вона забезпечила тривале перемир’я з москвою та повернення втрачених земель.',0),('orsha','narratives',1,1,'Культурну місію зі збереження спадщини та традицій Риму.',0),('orsha','narratives',1,2,'Ідеологічне обґрунтування права на зверхність та розширення свого впливу.',1),('orsha','narratives',1,3,'Зміцнення авторитету церкви та правителя всередині самої держави.',0),('orsha','narratives',2,1,'Забезпечення релігійної свободи для одновірців на всій колишній території Русі.',0),('orsha','narratives',2,2,'Створення формального приводу для втручання у внутрішні справи ВКЛ та агресії.',1),('orsha','narratives',2,3,'Привернення уваги Константинопольського патріарха до проблем православ\'я.',0),('orsha','narratives',3,1,'Спробу зміцнення авторитету своєї влади.',0),('orsha','narratives',3,2,'Закріплення факту володіння більшістю земель Русі.',0),('orsha','narratives',3,3,'Претензію на землі колишньої Русі-України',1),('vitovt','critical',1,1,'Щоб довести свою зверхність над сусідніми державами.',0),('vitovt','critical',1,2,'Щоб вона підтверджувала багатство правителя і держави.',0),('vitovt','critical',1,3,'Щоб зміцнити незалежність держави в очах інших правителів.',1),('vitovt','critical',2,1,'Бо Ягайло, як його король, наказав Вітовту приєднатися до свого війська.',0),('vitovt','critical',2,2,'Бо у них з\'явився спільний ворог, Тевтонський орден.',1),('vitovt','critical',2,3,'Бо як двоюрідні брати вони часто діяли разом.',0),('vitovt','critical',3,1,'Бо ці землі платили найбільші податки, і їхні гроші були потрібні для утримання війська.',0),('vitovt','critical',3,2,'Бо руська культура вважалася давнішою, і її прийняття давало правителю більше авторитету.',0),('vitovt','critical',3,3,'Бо руське населення становило більшість у державі, і правителі мали рахуватися з його думкою.',1),('vitovt','narratives',1,1,'Знищували, щоб насадити свою культуру',0),('vitovt','narratives',1,2,'Взяли її за одну з основ для створення власної держави',1),('vitovt','narratives',1,3,'Не втручалися і були байдужими до неї',0),('vitovt','narratives',2,1,'Велике князівство Литовське стало одним із головних політичних та культурних спадкоємців Русі, об\'єднавши більшість її земель.',1),('vitovt','narratives',2,2,'московія забрала більшість земель Русі після монгольської навали, тому претендувала на її спадщину.',0),('vitovt','narratives',2,3,'Після монгольської навали спадщина Русі була переважно втрачена, тому ніхто не міг претендувати на її відновлення.',0),('vitovt','narratives',3,1,'Це була окупація, де литовська знать цілеспрямовано знищувала місцеві культуру, мову та віру.',0),('vitovt','narratives',3,2,'Це був багатоетнічний союз, де руські землі становили більшість і зберігали автономію, закони, віру та мову.',1),('vitovt','narratives',3,3,'Це було злиття етносів, де руські та литовські землі перетворювалися на єдиний народ.',0);
/*!40000 ALTER TABLE `test_opts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `question_text` text DEFAULT NULL,
  PRIMARY KEY (`scenario_slug`,`metric`,`number`),
  KEY `fk_metr_test` (`metric`),
  CONSTRAINT `fk_metr_test` FOREIGN KEY (`metric`) REFERENCES `metrics` (`metric`),
  CONSTRAINT `fk_tests_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES ('danylo','critical',1,'Для чого Данило Романович прийняв королівську корону від Папи Римського?'),('danylo','critical',2,'Чому обіцяний Папою Римським хрестовий похід проти монголів так і не відбувся?'),('danylo','critical',3,'Як Данило та Василько Романовичі організували спільне правління Волинсько-Галицькою державою?'),('danylo','narratives',1,'Поїздка Данила в Золоту Орду та визнання влади хана була:'),('danylo','narratives',2,'Волинсько-Галицьке князівство у XIII столітті було:'),('orsha','critical',1,'Якою була головна причина війни між московією та Великим князівством Литовським на межі XV–XVI ст.?'),('orsha','critical',2,'Яке значення мала битва під Оршею для Великого князівства Литовського?'),('orsha','narratives',1,'Що означала для московських правителів ідея «москва — третій Рим»?'),('orsha','narratives',2,'москва говорила про захист православних у ВКЛ, адже хотіла:'),('orsha','narratives',3,'Титул московського правителя «володар всієї Русі» у міжнародній політиці означав:'),('vitovt','critical',1,'Чому звичайна корона була настільки важлива для князя Вітовта та Великого князівства Литовського?'),('vitovt','critical',2,'Чому князь Вітовт та король Ягайло, бувши суперниками, змогли разом воювати під Грюнвальдом?'),('vitovt','critical',3,'Чому підтримка руських земель була важливою для претендентів на владу у Великому князівстві Литовському?'),('vitovt','narratives',1,'Як правителі Великого князівства Литовського ставилися до руської спадщини під час приєднання руських земель?'),('vitovt','narratives',2,'Яке твердження про спадщину Русі-України є найбільш точним?'),('vitovt','narratives',3,'Як найкраще описати становище руських земель у складі Великого князівства Литовського?');
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_installations`
--

DROP TABLE IF EXISTS `user_installations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_installations` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_code` char(6) NOT NULL,
  `fid` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ui_user` (`user_code`),
  CONSTRAINT `fk_ui_user` FOREIGN KEY (`user_code`) REFERENCES `users` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_installations`
--

LOCK TABLES `user_installations` WRITE;
/*!40000 ALTER TABLE `user_installations` DISABLE KEYS */;
INSERT INTO `user_installations` VALUES (1,'730059','c9hNOhWSeFCfx7y4iLesD5'),(2,'164235','fnRBh4I-WfG9aK969zMnmg'),(3,'601162','f35KziKo5KC-Kg7cvXeozw'),(4,'787372','cpyXxcpYTnmcjz81517tCX'),(5,'510191','fYVv50gZaz-FqDp5wd3OTQ'),(6,'860570','ce0skXVSKO3FsOunNlTsP3'),(7,'017073','eiKYcuE8pN51puyctu1X9E'),(8,'681401','dGSJFBDyLRB7jDS3daFBxU'),(9,'654481','eBIq0LvB3LN-OJcEVJSv-J'),(10,'598189','dY1GsNxY3dVeFDWscgLLfy'),(11,'946228','dauUlCJyODZEqUhPI_psDH'),(12,'383843','fQmHKrMGaZG8rC5YIACPdo'),(13,'154357','fSBHMrJ_VGnnQOnQBofKFm'),(14,'456659','egMHKR08nq13299IU7G7ah'),(15,'868679','cChNJ-H7jTLhc1PDBo4KCw'),(16,'646613','fXhF0noc0WoT5BLVPZ2u77'),(17,'669249','d5Tdo0ZTJkwdg5FuLOST49'),(18,'765148','cwhIrisQAShhOwGrHVbHKo'),(19,'050309','cxa30GPFkn8s5ewpmfRN2m'),(20,'650641','c0zjbBDuk00YswDkYVMFQ8'),(21,'609634','fz3t2OTXxVgCI1GY5uXFOY'),(22,'926952','d5hgCipWIiGE-PyD1VZ7Hy'),(23,'362515','frv2cNSGxBLpD3F2VoF8Jo'),(24,'323470','eQcfSKi6llH5AWeV0o-Kzu'),(25,'564038','dXMllxGPK_LNwindPV1io-'),(26,'911505','dIuRVRnRC9UIGoNddNmPwh'),(27,'842609','cHa0R7JGQ_6CSK1ftsNkeO'),(28,'582608','cYhJW74_vknAdfWSN8fbpB'),(29,'590486','fV82WnykSzhWdLyyloiyuy'),(30,'345781','dx3SDxeHFP-xSDG1UAyAG3'),(31,'432851','dOA0VEHge5SvWmtnHhNhXD'),(32,'472795','cZ6lY_vd1uFzpPbVMTeu6k'),(33,'821727','e4VVcvSt2bEcB4G-0I9U3z'),(34,'479673','frl2spzHc-byraEd2eo4De'),(35,'695685','eCWUUZco8P8fxzTj8YwIgB'),(36,'673097','dN_psCjuGe1Z0gfeDU5Jaf'),(37,'531814','dv6MObhxvkKtqhrh45ENQJ'),(38,'354427','dDoB2ZE3Pg1L2usRgJaE2S'),(39,'414493','e7tPs2enpz9zYvKNUy0AZp'),(40,'650502','cfxErHICrdPCeyMFuu7Woq'),(41,'797399','dCmRL1HbzNypzwzTsYQ3AB'),(42,'234494','cCOQgGqkpaLqOE9neQCpdj'),(43,'137730','cgrdRhwF8xuIaJ-erlyk-4'),(44,'186328','ePwSGQ-EprLGwdR2sVqSYe'),(45,'241534','dvqaB8hwHhj9pHj7VqFMRG'),(46,'569388','fwsDPVsbk8m6LxgJa5yZ2w'),(47,'099388','eeJnS9VwOdRksUroX608k7'),(48,'008296','d41hJg4GoJydlJu5p8PDay'),(49,'938730','diGk8wlKZAzWInGWWQiZnF'),(50,'155643','d82bVqD7EwwL5RZ3VNEbyT'),(51,'358743','fQcE4Lizy7-ZEeNMukhrXy'),(52,'999205','ddUY50xu1Cuz73DZT28hSO'),(53,'378653','eAlUpFTg7-AIvmziHV3SdN'),(54,'485299','flR57U4cY0Vx9il0AcDACs'),(55,'983910','dbvciC_YWGOTtD-P1_sx8R'),(56,'912098','frmR-ho1T_jUzpMqrwEDR0'),(57,'661450','fBuK6z4lE182aTwTJ7Cdiw'),(58,'732929','cXW6BDliUXkO3nBxUSfDVm'),(59,'102795','cQyqm2ERRd7lAHo5G8i3mj'),(60,'679855','c88q1u_0qZyVwjvC8kk-Et'),(64,'399611','crFZcV3Kvk3dhlEa6wtuHC'),(65,'992875','dXbVmEW8OI_F-OflcIpyoT'),(66,'246388','fX26oOEersUrLlfbpB5aoG'),(67,'476035','comFH-RjGDHWUPoHz8p5rA'),(68,'644765','fy-iM0RLDoelxAJ3SZxL5L'),(69,'953486','c2uF-owDo0ltVbSilhazSm'),(70,'157830','dsztRR67DfhI_ZtLwbWJFr');
/*!40000 ALTER TABLE `user_installations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `code` char(6) NOT NULL,
  `saves` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('008296','{\"gender\":\"he\"}','2025-09-25 07:38:14'),('017073','{\"gender\":\"she\"}','2025-09-24 08:52:58'),('050309','{\"gender\":\"she\"}','2025-09-24 10:15:46'),('099388','{\"gender\":\"he\"}','2025-09-25 07:38:06'),('102795','{\"gender\":\"she\"}','2025-09-25 07:45:02'),('137730','{\"gender\":\"he\"}','2025-09-25 07:37:53'),('154357','{\"gender\":\"she\"}','2025-09-24 09:43:10'),('155643','{\"gender\":\"he\"}','2025-09-25 07:38:15'),('157830','{\"gender\":\"he\"}','2025-09-30 16:17:59'),('164235','{\"gender\":\"he\"}','2025-09-24 08:17:16'),('186328','{\"gender\":\"she\"}','2025-09-25 07:38:00'),('234494','{\"gender\":\"she\"}','2025-09-25 07:37:52'),('241534',NULL,'2025-09-25 07:38:04'),('246388','{\"gender\":\"she\"}','2025-09-26 15:33:47'),('323470','{\"gender\":\"he\"}','2025-09-24 11:30:31'),('345781','{\"gender\":\"he\"}','2025-09-24 11:30:40'),('354427',NULL,'2025-09-25 07:01:43'),('358743','{\"gender\":\"he\"}','2025-09-25 07:38:17'),('362515','{\"gender\":\"she\"}','2025-09-24 11:30:28'),('378653','{\"gender\":\"he\"}','2025-09-25 07:38:29'),('383843','{\"gender\":\"she\"}','2025-09-24 09:38:53'),('399611',NULL,'2025-09-26 11:40:38'),('414493','{\"gender\":\"he\"}','2025-09-25 07:07:38'),('432851','{\"gender\":\"she\"}','2025-09-24 11:31:29'),('456659','{\"gender\":\"he\"}','2025-09-24 09:44:46'),('472795','{\"gender\":\"she\"}','2025-09-24 11:31:32'),('476035','{\"gender\":\"she\"}','2025-09-27 10:01:58'),('479673','{\"gender\":\"he\"}','2025-09-24 15:43:04'),('485299','{\"gender\":\"he\"}','2025-09-25 07:38:49'),('510191','{\"gender\":\"he\"}','2025-09-24 08:23:40'),('531814','{\"gender\":\"he\"}','2025-09-25 06:57:53'),('564038','{\"gender\":\"he\"}','2025-09-24 11:30:32'),('569388','{\"gender\":\"she\"}','2025-09-25 07:38:05'),('582608','{\"gender\":\"she\"}','2025-09-24 11:30:36'),('590486','{\"gender\":\"she\"}','2025-09-24 11:30:38'),('598189','{\"gender\":\"she\"}','2025-09-24 09:25:53'),('601162','{\"gender\":\"she\"}','2025-09-24 08:21:57'),('609634','{\"gender\":\"he\"}','2025-09-24 10:45:23'),('644765',NULL,'2025-09-27 22:36:56'),('646613','{\"gender\":\"she\"}','2025-09-24 09:49:18'),('650502','{\"gender\":\"he\"}','2025-09-25 07:37:51'),('650641','{\"gender\":\"he\"}','2025-09-24 10:43:32'),('654481','{\"gender\":\"she\"}','2025-09-24 09:15:29'),('661450','{\"gender\":\"she\"}','2025-09-25 07:39:07'),('669249','{\"gender\":\"she\"}','2025-09-24 09:56:44'),('673097','{\"gender\":\"he\"}','2025-09-25 06:23:49'),('679855','{\"gender\":\"she\"}','2025-09-25 07:52:56'),('681401','{\"gender\":\"she\"}','2025-09-24 09:10:43'),('695685','{\"gender\":\"she\"}','2025-09-24 21:13:27'),('730059','{\"gender\":\"he\"}','2025-09-24 08:08:00'),('732929','{\"gender\":\"he\"}','2025-09-25 07:39:14'),('765148','{\"gender\":\"she\"}','2025-09-24 10:09:11'),('787372','{\"gender\":\"he\"}','2025-09-24 08:22:57'),('797399','{\"gender\":\"she\"}','2025-09-25 07:37:51'),('821727','{\"gender\":\"she\"}','2025-09-24 11:32:18'),('842609','{\"gender\":\"he\"}','2025-09-24 11:30:34'),('860570','{\"gender\":\"she\"}','2025-09-24 08:43:26'),('868679','{\"gender\":\"she\"}','2025-09-24 09:46:47'),('911505','{\"gender\":\"he\"}','2025-09-24 11:30:32'),('912098','{\"gender\":\"he\"}','2025-09-25 07:38:59'),('926952','{\"gender\":\"she\"}','2025-09-24 11:30:27'),('938730','{\"gender\":\"he\"}','2025-09-25 07:38:15'),('946228','{\"gender\":\"she\"}','2025-09-24 09:37:40'),('953486','{\"gender\":\"he\"}','2025-09-28 13:50:19'),('983910','{\"gender\":\"he\"}','2025-09-25 07:38:56'),('992875',NULL,'2025-09-26 15:17:23'),('999205','{\"gender\":\"he\"}','2025-09-25 07:38:17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'Modemi4_ntd'
--

--
-- Dumping routines for database 'Modemi4_ntd'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-01 18:52:41
