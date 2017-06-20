CREATE DATABASE  IF NOT EXISTS `medianet` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `medianet`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: medianet
-- ------------------------------------------------------
-- Server version	5.7.18-log

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
-- Table structure for table `demande`
--

DROP TABLE IF EXISTS `demande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text,
  `userid` varchar(45) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demande`
--

LOCK TABLES `demande` WRITE;
/*!40000 ALTER TABLE `demande` DISABLE KEYS */;
INSERT INTO `demande` VALUES (1,'../../uploadjpgjellyfish_3jpg','2',775702,'image/jpeg','2017-06-19 14:14:55'),(2,'../../upload\\jpg\\jellyfish_5jpg','2',775702,'image/jpeg','2017-06-19 14:18:11'),(3,'../../upload\\jpg\\jellyfish.jpg','2',775702,'image/jpeg','2017-06-19 14:18:45'),(4,'../../upload\\jpg\\2017_06_20_52.jpg','2',775702,'image/jpeg','2017-06-20 09:33:52'),(5,'../../upload\\jpg\\desert2017_06_20_48.jpg','2',845941,'image/jpeg','2017-06-20 09:35:48'),(6,'../../upload\\jpg\\hydrangeas_2017_06_20_09.jpg','2',595284,'image/jpeg','2017-06-20 12:04:09'),(7,'../../upload\\jpg\\koala_2017_06_20_55.jpg','2',780831,'image/jpeg','2017-06-20 12:04:55'),(8,'../../upload\\jpg\\desert_2017_06_20_55.jpg','2',845941,'image/jpeg','2017-06-20 12:04:55'),(9,'../../upload\\jpg\\tulips_2017_06_20_22.jpg','2',620888,'image/jpeg','2017-06-20 12:08:22'),(10,'../../upload\\jpg\\chrysanthemum_2017_06_20_22.jpg','2',879394,'image/jpeg','2017-06-20 12:08:22'),(11,'../../upload\\jpg\\jellyfish_2017_06_20_00.jpg','2',775702,'image/jpeg','2017-06-20 12:09:00'),(12,'../../upload\\jpg\\tulips_2017_06_20_05.jpg','2',620888,'image/jpeg','2017-06-20 12:11:05'),(13,'../../upload\\jpg\\hydrangeas_2017_06_20_34.jpg','2',595284,'image/jpeg','2017-06-20 12:11:34'),(14,'../../upload\\jpg\\lighthouse_2017_06_20_47.jpg','2',561276,'image/jpeg','2017-06-20 12:12:47'),(15,'../../upload\\jpg\\lighthouse_2017_06_20_33.jpg','2',561276,'image/jpeg','2017-06-20 12:13:33'),(16,'../../upload\\jpg\\jellyfish_2017_06_20_55.jpg','2',775702,'image/jpeg','2017-06-20 12:15:55'),(17,'../../upload\\jpg\\tulips_2017_06_20_08.jpg','2',620888,'image/jpeg','2017-06-20 12:25:08'),(18,'../../upload\\jpg\\chrysanthemum_2017_06_20_42.jpg','2',879394,'image/jpeg','2017-06-20 13:05:42'),(19,'../../upload\\jpg\\hydrangeas_2017_06_20_58.jpg','2',595284,'image/jpeg','2017-06-20 13:07:58'),(20,'../../upload\\jpg\\koala_2017_06_20_47.jpg','2',780831,'image/jpeg','2017-06-20 13:12:47'),(21,'../../upload\\jpg\\koala_2017_06_20_56.jpg','2',780831,'image/jpeg','2017-06-20 13:13:56'),(22,'../../upload\\jpg\\desert_2017_06_20_19.jpg','2',845941,'image/jpeg','2017-06-20 13:14:19'),(23,'../../upload\\jpg\\chrysanthemum_2017_06_20_50.jpg','2',879394,'image/jpeg','2017-06-20 13:27:50'),(24,'../../upload\\jpg\\lighthouse_2017_06_20_20.jpg','2',561276,'image/jpeg','2017-06-20 13:28:20'),(25,'../../upload\\jpg\\koala_2017_06_20_23.jpg','2',780831,'image/jpeg','2017-06-20 13:29:23'),(26,'../../upload\\jpg\\hydrangeas_2017_06_20_56.jpg','2',595284,'image/jpeg','2017-06-20 13:30:56'),(27,'../../upload\\jpg\\tulips_2017_06_20_14.jpg','2',620888,'image/jpeg','2017-06-20 13:33:14'),(28,'../../upload\\jpg\\chrysanthemum_2017_06_20_54.jpg','2',879394,'image/jpeg','2017-06-20 15:35:54'),(29,'../../upload\\jpg\\koala_2017_06_20_54.jpg','2',780831,'image/jpeg','2017-06-20 15:35:54'),(30,'../../upload\\jpg\\desert_2017_06_20_18.jpg','2',845941,'image/jpeg','2017-06-20 15:41:18');
/*!40000 ALTER TABLE `demande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `directory`
--

DROP TABLE IF EXISTS `directory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `parentid` varchar(45) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `access` varchar(255) DEFAULT 'USER',
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `extensions` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directory`
--

LOCK TABLES `directory` WRITE;
/*!40000 ALTER TABLE `directory` DISABLE KEYS */;
INSERT INTO `directory` VALUES (1,'ROOT','0','../../upload','ADMIN',1,2,NULL),(2,'2017','1','../../upload\\2017','USER',NULL,NULL,''),(3,'03','2','../../upload\\2017\\03','USER',NULL,NULL,''),(4,'04','2','../../upload\\2017\\04','USER',NULL,NULL,''),(5,'06','2','../../upload\\2017\\06','USER',NULL,NULL,''),(6,'Exe','1','../../upload\\Exe','ADMIN',NULL,NULL,'application/octet-stream'),(7,'Html','1','../../upload\\Html','USER',NULL,NULL,'text/html'),(8,'Laius_PREVINTER','7','../../upload\\Html\\Laius_PREVINTER','USER',NULL,NULL,'text/html'),(9,'Laius PREVINTER_fichiers','8','../../upload\\Html\\Laius_PREVINTER\\Laius PREVINTER_fichiers','USER',NULL,NULL,'text/html'),(10,'presentation vivinter RH  PRC_fichiers','7','../../upload\\Html\\presentation vivinter RH  PRC_fichiers','USER',NULL,NULL,'text/html'),(11,'Lettre22','1','../../upload\\Lettre22','ADMIN',NULL,NULL,''),(12,'EN','11','../../upload\\Lettre22\\EN','ADMIN',NULL,NULL,''),(13,'css','12','../../upload\\Lettre22\\EN\\css','ADMIN',NULL,NULL,''),(14,'img','12','../../upload\\Lettre22\\EN\\img','ADMIN',NULL,NULL,''),(15,'js','12','../../upload\\Lettre22\\EN\\js','ADMIN',NULL,NULL,''),(16,'FR','11','../../upload\\Lettre22\\FR','ADMIN',NULL,NULL,''),(17,'css','16','../../upload\\Lettre22\\FR\\css','ADMIN',NULL,NULL,''),(18,'img','16','../../upload\\Lettre22\\FR\\img','ADMIN',NULL,NULL,''),(19,'js','16','../../upload\\Lettre22\\FR\\js','ADMIN',NULL,NULL,''),(20,'Lettre23','1','../../upload\\Lettre23','ADMIN',NULL,NULL,''),(21,'EN','20','../../upload\\Lettre23\\EN','ADMIN',NULL,NULL,''),(22,'FR','20','../../upload\\Lettre23\\FR','ADMIN',NULL,NULL,''),(23,'flv','1','../../upload\\flv','USER',NULL,NULL,'video/flv'),(24,'ics','1','../../upload\\ics','USER',NULL,NULL,''),(25,'jpg','1','../../upload\\jpg','USER',NULL,NULL,'image/jpeg'),(26,'mp4','1','../../upload\\mp4','USER',NULL,NULL,'audio/mpeg4'),(27,'pdf','1','../../upload\\pdf','USER',NULL,NULL,'application/pdf'),(28,'pps','1','../../upload\\pps','USER',NULL,NULL,'application/mspowerpoint'),(29,'ppt','1','../../upload\\ppt','USER',NULL,NULL,'application/mspowerpoint'),(30,'swf','1','../../upload\\swf','USER',NULL,NULL,'application/x-shockwave-flash');
/*!40000 ALTER TABLE `directory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'gegeturambar@s2hgroup.com','$2y$12$OqOpDSdCe5MWO4a5EGDjE.AgOrZUH4c1j9tUYdAjHJh3I7XGRTzJ6','ADMIN'),(9,'Dylan@gmail.com','$2y$12$lMB.eoUAgcdg6zltKWXDSOJnKnjpGeM9YdREjgsXiR8OQbSDJoPva','ADMIN');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-20 17:49:48
