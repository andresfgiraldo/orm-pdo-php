-- MariaDB dump 10.17  Distrib 10.4.6-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: orm_pdo_php
-- ------------------------------------------------------
-- Server version	10.4.6-MariaDB

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
-- Current Database: `orm_pdo_php`
--

/*!40000 DROP DATABASE IF EXISTS `orm_pdo_php`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `orm_pdo_php` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci */;

USE `orm_pdo_php`;

--
-- Table structure for table `gr_pais`
--

DROP TABLE IF EXISTS `gr_pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gr_pais` (
  `alfa2` varchar(2) COLLATE latin1_spanish_ci NOT NULL,
  `alfa3` varchar(3) COLLATE latin1_spanish_ci NOT NULL,
  `numerico` varchar(3) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `user_created` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `user_updated` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`alfa2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gr_pais`
--

LOCK TABLES `gr_pais` WRITE;
/*!40000 ALTER TABLE `gr_pais` DISABLE KEYS */;
INSERT INTO `gr_pais` VALUES ('CO','COL','170','Colombia',NULL,NULL,NULL,NULL),('DO','DOM','214','Republica Dominicana',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `gr_pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'orm_pdo_php'
--

--
-- Dumping routines for database 'orm_pdo_php'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-05 19:09:42
