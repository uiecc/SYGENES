-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: sygenes
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `action` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `metadata` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action`
--

LOCK TABLES `action` WRITE;
/*!40000 ALTER TABLE `action` DISABLE KEYS */;
/*!40000 ALTER TABLE `action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrator` (
  `id` int NOT NULL,
  `university_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_58DF0651309D1878` (`university_id`),
  CONSTRAINT `FK_58DF0651309D1878` FOREIGN KEY (`university_id`) REFERENCES `university` (`id`),
  CONSTRAINT `FK_58DF0651BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator`
--

LOCK TABLES `administrator` WRITE;
/*!40000 ALTER TABLE `administrator` DISABLE KEYS */;
INSERT INTO `administrator` VALUES (1,1);
/*!40000 ALTER TABLE `administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20250218114055','2025-02-18 11:41:05',5485),('DoctrineMigrations\\Version20250316095752','2025-03-16 09:59:53',584),('DoctrineMigrations\\Version20250316210430','2025-03-16 21:04:59',741);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec`
--

DROP TABLE IF EXISTS `ec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ue_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit` int NOT NULL,
  `has_tp` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8DE8BDFF62E883B1` (`ue_id`),
  CONSTRAINT `FK_8DE8BDFF62E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec`
--

LOCK TABLES `ec` WRITE;
/*!40000 ALTER TABLE `ec` DISABLE KEYS */;
INSERT INTO `ec` VALUES (3,5,'Normes, et labélisation en entreprises numériques','info123',3,1),(4,3,'java','isn235',2,1);
/*!40000 ALTER TABLE `ec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field`
--

DROP TABLE IF EXISTS `field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field` (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5BF54558C32A47EE` (`school_id`),
  CONSTRAINT `FK_5BF54558C32A47EE` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field`
--

LOCK TABLES `field` WRITE;
/*!40000 ALTER TABLE `field` DISABLE KEYS */;
INSERT INTO `field` VALUES (1,1,'Création et Design Numérique','CDN'),(2,1,'Igenierie des Systemes Numeriques','ISN'),(3,1,'Igenierie Numerique Sociotechnique','INS');
/*!40000 ALTER TABLE `field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_manager`
--

DROP TABLE IF EXISTS `field_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field_manager` (
  `id` int NOT NULL,
  `field_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_14000019443707B0` (`field_id`),
  CONSTRAINT `FK_14000019443707B0` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`),
  CONSTRAINT `FK_14000019BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_manager`
--

LOCK TABLES `field_manager` WRITE;
/*!40000 ALTER TABLE `field_manager` DISABLE KEYS */;
INSERT INTO `field_manager` VALUES (4,1),(3,2);
/*!40000 ALTER TABLE `field_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `level` (
  `id` int NOT NULL AUTO_INCREMENT,
  `field_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9AEACC13443707B0` (`field_id`),
  CONSTRAINT `FK_9AEACC13443707B0` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level`
--

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;
INSERT INTO `level` VALUES (1,1,'Licence 1','L1'),(2,1,'Licence 2','L2'),(3,1,'Licence 3','L3'),(4,1,'Master 1','M1'),(5,1,'Master 2','M2'),(6,2,'Licence 1','L1'),(7,2,'Licence 2','L2'),(8,2,'Licence 3','L3'),(9,2,'Master 1','M1'),(10,2,'Master 2','M2'),(11,3,'Licence 1','L1'),(12,3,'Licence 2','L2'),(13,3,'Licence 3','L3'),(14,3,'Master 1','M1'),(15,3,'Master 2','M2');
/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level_manager`
--

DROP TABLE IF EXISTS `level_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `level_manager` (
  `id` int NOT NULL,
  `level_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5B8C00A65FB14BA7` (`level_id`),
  CONSTRAINT `FK_5B8C00A65FB14BA7` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`),
  CONSTRAINT `FK_5B8C00A6BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level_manager`
--

LOCK TABLES `level_manager` WRITE;
/*!40000 ALTER TABLE `level_manager` DISABLE KEYS */;
INSERT INTO `level_manager` VALUES (6,1),(275,7);
/*!40000 ALTER TABLE `level_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `note` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `ec_id` int DEFAULT NULL,
  `cc_note` double DEFAULT NULL,
  `tp_note` double DEFAULT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA14CB944F1A` (`student_id`),
  KEY `IDX_CFBDFA1427634BEF` (`ec_id`),
  CONSTRAINT `FK_CFBDFA1427634BEF` FOREIGN KEY (`ec_id`) REFERENCES `ec` (`id`),
  CONSTRAINT `FK_CFBDFA14CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note`
--

LOCK TABLES `note` WRITE;
/*!40000 ALTER TABLE `note` DISABLE KEYS */;
INSERT INTO `note` VALUES (1,234,3,20,15,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(2,235,3,25,6,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(3,236,3,10,5,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(4,237,3,13,15,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(5,238,3,20,13,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(6,239,3,14,15,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(7,240,3,12,13,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(8,241,3,18,1,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(9,242,3,12,17,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(10,243,3,11,11,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(11,244,3,22,11,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(12,245,3,22,3,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(13,246,3,14,15,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(14,247,3,17,19,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(15,248,3,14,1,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(16,249,3,6,15,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(17,250,3,12,14,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(18,251,3,12,18,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(19,252,3,19,2,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(20,253,3,15,2,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02'),(21,254,3,2,2,'2024-2025','2025-03-16 21:32:02','2025-03-16 21:32:02');
/*!40000 ALTER TABLE `note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responsable`
--

DROP TABLE IF EXISTS `responsable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responsable` (
  `id` int NOT NULL,
  `code_resp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_52520D07BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responsable`
--

LOCK TABLES `responsable` WRITE;
/*!40000 ALTER TABLE `responsable` DISABLE KEYS */;
INSERT INTO `responsable` VALUES (2,'DIRECT','Directeur','Academie'),(3,'cep','Chef d\'equipe pedagogique','Academie'),(4,'cep','Chef d\'equipe pedagogique','Academie'),(6,'AATP','Responsale Niveau','academie'),(274,'AATP','AATP','academie'),(275,'AATP','Responsale Niveau','academie');
/*!40000 ALTER TABLE `responsable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `role` VALUES (4,'ROLE_SCHOOL_MANAGER',NULL,NULL),(5,'ROLE_FIELD_MANAGER',NULL,NULL),(7,'ROLE_STUDENT',NULL,NULL),(8,'ROLE_LEVEL_MANAGER',NULL,NULL);
=======
INSERT INTO `role` VALUES (4,'ROLE_SCHOOL_MANAGER',NULL,NULL),(5,'ROLE_FIELD_MANAGER',NULL,NULL),(7,'ROLE_STUDENT',NULL,NULL),(8,'ROLE_LEVEL_MANAGER',NULL,NULL),(9,'ROLE_ADMIN',NULL,NULL);
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school` (
  `id` int NOT NULL AUTO_INCREMENT,
  `university_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F99EDABB309D1878` (`university_id`),
  CONSTRAINT `FK_F99EDABB309D1878` FOREIGN KEY (`university_id`) REFERENCES `university` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school`
--

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;
INSERT INTO `school` VALUES (1,1,'Ecole Superieur Internationale de Genie Numerique','ESIGN','/tmp/phpg75rqkkmb05l2P35yvj');
/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_manager`
--

DROP TABLE IF EXISTS `school_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_manager` (
  `id` int NOT NULL,
  `school_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_680B1721C32A47EE` (`school_id`),
  CONSTRAINT `FK_680B1721BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_680B1721C32A47EE` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_manager`
--

LOCK TABLES `school_manager` WRITE;
/*!40000 ALTER TABLE `school_manager` DISABLE KEYS */;
INSERT INTO `school_manager` VALUES (2,1);
/*!40000 ALTER TABLE `school_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semester` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F7388EED5FB14BA7` (`level_id`),
  CONSTRAINT `FK_F7388EED5FB14BA7` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semester`
--

LOCK TABLES `semester` WRITE;
/*!40000 ALTER TABLE `semester` DISABLE KEYS */;
INSERT INTO `semester` VALUES (3,7,'semestre 1','S1'),(4,7,'semestre 2','S2');
/*!40000 ALTER TABLE `semester` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `id` int NOT NULL,
  `level_id` int NOT NULL,
  `sex` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_B723AF335FB14BA7` (`level_id`),
  CONSTRAINT `FK_B723AF335FB14BA7` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`),
  CONSTRAINT `FK_B723AF33BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (234,7,'F','1997-02-23','YAOUNDE','20G60005','CAMEROUN',NULL,NULL),(235,7,'M','1998-06-21','YAOUNDE','20G60008','CAMEROUN',NULL,NULL),(236,7,'M','2000-05-22','NYE ELE','20G60009','CAMEROUN',NULL,NULL),(237,7,'M','1996-12-22','YAOUNDÉ','20G60010','CAMEROUN',NULL,NULL),(238,7,'M','1995-08-18','ABALA','20G60012','CONGO',NULL,NULL),(239,7,'F','1997-04-03','NGOULEMAKONG','20G60014','CAMEROUN',NULL,NULL),(240,7,'F','1996-05-15','MATERNITE DE KAELE','20G60016','CAMEROUN',NULL,NULL),(241,7,'M','1996-05-15','MATERNITE DE KAELE','20G60017','CAMEROUN',NULL,NULL),(242,7,'M','1999-05-21','POINTE - NOIRE','20G60022','CONGO',NULL,NULL),(243,7,'F','2000-03-18','YAOUNDE','20G60024','CAMEROUN',NULL,NULL),(244,7,'M','2002-08-23','NYEP-BANE','20G60025','CAMEROUN',NULL,NULL),(245,7,'M','2000-01-18','DJOUM','20G60026','CAMEROUN',NULL,NULL),(246,7,'M','2000-11-06','SANGMELIMA','20G60028','CAMEROUN',NULL,NULL),(247,7,'F','1994-05-25','YAOUNDE','20G60033','CAMEROUN',NULL,NULL),(248,7,'M','1999-02-28','GAROUA','20G60034','CAMEROUN',NULL,NULL),(249,7,'M','1999-04-29','BAFIA','20G60035','CAMEROUN',NULL,NULL),(250,7,'F','1999-02-01','YAOUNDE 5','20G60037','CAMEROUN',NULL,NULL),(251,7,'F','2000-04-01','DOUALA','20G60039','CAMEROUN',NULL,NULL),(252,7,'M','2000-01-27','BRAZZAVILLE','20G60040','CONGO',NULL,NULL),(253,7,'F','1998-11-25','BRAZZAVILLE','20G60041','CONGO',NULL,NULL),(254,7,'F','1997-01-16','BRAZZAVILLE','20G60042','CONGO',NULL,NULL);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_ue`
--

DROP TABLE IF EXISTS `student_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_ue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `ue_id` int NOT NULL,
  `registered_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `academic_year` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` double DEFAULT NULL,
  `is_validated` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5AE422D1CB944F1A` (`student_id`),
  KEY `IDX_5AE422D162E883B1` (`ue_id`),
  CONSTRAINT `FK_5AE422D162E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`),
  CONSTRAINT `FK_5AE422D1CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_ue`
--

LOCK TABLES `student_ue` WRITE;
/*!40000 ALTER TABLE `student_ue` DISABLE KEYS */;
INSERT INTO `student_ue` VALUES (6,234,5,'2025-03-16 12:14:04','2024-2025','registered',NULL,NULL),(7,234,3,'2025-03-16 12:14:04','2024-2025','registered',NULL,NULL),(8,234,1,'2025-03-16 12:14:04','2024-2025','registered',NULL,NULL),(9,234,2,'2025-03-16 12:14:04','2024-2025','registered',NULL,NULL),(10,234,4,'2025-03-16 12:14:04','2024-2025','registered',NULL,NULL),(11,234,6,'2025-03-16 12:27:26','2024-2025','registered',NULL,NULL);
/*!40000 ALTER TABLE `student_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher` (
  `id` int NOT NULL,
  `ec_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B0F6A6D527634BEF` (`ec_id`),
  CONSTRAINT `FK_B0F6A6D527634BEF` FOREIGN KEY (`ec_id`) REFERENCES `ec` (`id`),
  CONSTRAINT `FK_B0F6A6D5BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ue`
--

DROP TABLE IF EXISTS `ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semester_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit` int NOT NULL,
  `is_compulsory` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IDX_2E490A9B4A798B6F` (`semester_id`),
  CONSTRAINT `FK_2E490A9B4A798B6F` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ue`
--

LOCK TABLES `ue` WRITE;
/*!40000 ALTER TABLE `ue` DISABLE KEYS */;
INSERT INTO `ue` VALUES (1,3,'Réseaux et virtualisation des systèmes numériques','isn235',5,1),(2,3,'Réseaux et Systèmes Multimédias I','isn235',5,1),(3,3,'Systèmes Numériques Avancées I','isn234',5,1),(4,3,'Projets éducatifs des étudiants','pro235',8,1),(5,3,'Architecture des applications et Optimisation des Bases de Données','arc248',6,1),(6,3,'Outils de Management de la Qualité','isn222',1,0);
/*!40000 ALTER TABLE `ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uemanager`
--

DROP TABLE IF EXISTS `uemanager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uemanager` (
  `id` int NOT NULL,
  `ue_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A37EC62C62E883B1` (`ue_id`),
  CONSTRAINT `FK_A37EC62C62E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`),
  CONSTRAINT `FK_A37EC62CBF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uemanager`
--

LOCK TABLES `uemanager` WRITE;
/*!40000 ALTER TABLE `uemanager` DISABLE KEYS */;
/*!40000 ALTER TABLE `uemanager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `university`
--

DROP TABLE IF EXISTS `university`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `university` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `university`
--

LOCK TABLES `university` WRITE;
/*!40000 ALTER TABLE `university` DISABLE KEYS */;
INSERT INTO `university` VALUES (1,'Universite Inter Etat Congo Cameroun','UIECC','Cmr Sang','universite de technoloie','/tmp/php26gq784ia33j4XzKFiV');
/*!40000 ALTER TABLE `university` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cni` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','$2y$13$1coGHYNNPd9JuehK18V7TuJwj1qIX.uuEBPpNpV3w3MEV9jRy19ie','davyemane1@gmail.com','697379517','photo-2025-02-18-07-37-49-67b4827cb218c.jpg','2087514','Emane','Davy',0,'2025-02-18 12:52:12','administrator'),(2,'davy','$2y$13$b0RZ/fPDO1CgYp6LBtIJJebZUvH1MAmkqLIBV/DDdkNQgYQYezjyy','davyemane2@gmail.com','697379517','/tmp/phpmong39lm2qphff8KmJs','123654789','Emane','Davy',0,'2025-02-18 13:11:53','school_manager'),(3,'davy1','$2y$13$Ib08eBeB3Y473UgEQZBCjeP3iRETfehry6oK12IgTqBitRuJtiCVq','davyemane3@gmail.com','676469014','/tmp/phpdmjeatns1apl1r6C9Td','6945632156','FELICIEN','Aurelle',0,'2025-02-18 13:25:49','field_manager'),(4,'davy2','$2y$13$zJ6m0x/ViJfpEduIxmzhCeXa3CP7T9ydDAvD/gyRdSQI2CJFiUNr.','davyemane@esign.cm','676469014','/tmp/phpa6ooji7s5t1deHwi0rd','6945632156','Amougou','Ngoumou',0,'2025-02-18 13:27:29','field_manager'),(6,'l1','2002','dieuveille253@gmail.com','697379517','/tmp/phpii4u77ik62qt2UdAl6H','152364862','Emane','Davy',0,'2025-02-18 13:46:10','level_manager'),(234,'101190591','$2y$13$WPnYrQXCBNREs1mG/6uDLeiTOeehTCEDIoO0yCN7DzBvfqu39ERiS','lilianabomo7@gmail.com','696825961','photo-2025-02-18-07-37-49-67cf00fae6afb.jpg','101190591','MARMY IMELDA','AMBOGUE',0,'2025-03-10 13:03:14','student'),(235,'545564','$2y$13$Kk.dIZ5GeYseIjUVTUrnRuJVmolvSPF7GPMqO9.6kLZwMtYvgR6fi','adajanice240@gmail.com','656875752','default.jpg','545564','ELIE LUDOVIC','AVOMO',0,'2025-03-10 13:03:15','student'),(236,'101353532','$2y$13$p.WDLlLjphmcT5IsLi3T2erHGQ.vDLPftwgqWZKCjtrx.VBs5aCim','adjebedavid@gmail.com','659065045','default.jpg','101353532','CHARLES GULLIT','AVOTTO',0,'2025-03-10 13:03:16','student'),(237,'114964964','$2y$13$h2a7poLMNP.k20Lts2zgcuFn4tEpV3tG8EuUbGyRuevwJgKMThKLe','filomeneaimanou@gmail.com','691051577','default.jpg','114964964','MARTIN EMMANUEL','AYE\'E',0,'2025-03-10 13:03:17','student'),(238,'OA010403055','$2y$13$atKUu6qUqm3G.cyIBbEvUOaUiqdGW3/kFj8QIoN//pee0/GpRQjPu','ricchroddycfa@icloud.com','653215347','default.jpg','OA010403055','KIBA ISSIE','BALEPOUA',0,'2025-03-10 13:03:18','student'),(239,'117254023','$2y$13$jzmkcM/aOClH1OcFL5aSvOGVyiwDw/x9r5c3W.dc.dXWoBCp3ZTgW','lyberlyalain@gmail.com','693788177','default.jpg','117254023','ANNE CYRIELLE CYNTIA','BELINGA',0,'2025-03-10 13:03:19','student'),(240,'117515228','$2y$13$ag3naWYEWPL6V3/Lx0JA2.LwfXkhaPjBuT4zmh.YT4u.XBxb/OH3C','pierrestephanamang@gmail.com','656981955','default.jpg','117515228','MARTHE SERMENDE','BIANG',0,'2025-03-10 13:03:20','student'),(241,'117117708','$2y$13$UXiKU0O81AJ9fuxuT8Kz4.Atrl5J0TXVqC.i7NToDvluOYIRvVS0G','inecelingaango@gmail.com','655565680','default.jpg','117117708','SAMUEL YVAN','BIANG',0,'2025-03-10 13:03:20','student'),(242,'OA0403144','$2y$13$2aiBhOhS4/Bqz0fVfxSU9eioAv0gAp7mf1xJnZ0hN6u8yc8VE0uZa','papoitesimula@gmail.com','657008483','default.jpg','OA0403144','MABELE MOISE','BOUNGOU',0,'2025-03-10 13:03:21','student'),(243,'100565423','$2y$13$E4U91XKeLYN7t9o.IVlOn.ZrFGb7POfp6CMRvnDOn3cmI1uns//3C','assembenadege@gmail.com','658793599','default.jpg','100565423','JOSEPHINE TENDRESSE','EFA',0,'2025-03-10 13:03:22','student'),(244,'SUD01232I5IWC6T61L6H6','$2y$13$/jf7q4EAN0Jp8dhlKH51ZeYy.HkPF76MCal4SqRwNOVng/2nFgVxm','leoflyatangana@icloud.com','698906791','default.jpg','SUD01232I5IWC6T61L6H6','AMOUGOU JOSEPH DAVY','EKOA',0,'2025-03-10 13:03:23','student'),(245,'790642','$2y$13$paaIsgWTTrPMuyt4O94Uzez8TRtidkjrCvkam2nvWa9Wa2hFe/s/O','mvemefranc4@gmail.com','655104162','default.jpg','790642','BEKALE HENRI FRED','ENGBWANG',0,'2025-03-10 13:03:25','student'),(246,'CE3107I5IT321BKV745','$2y$13$VM6P4hWtq69yUtHbMTZ3cerRQjw3vk8NX59V537r9S3nh4IiEMVce','berlysezame@gmail.com','657326234','default.jpg','CE3107I5IT321BKV745','BOBY STYVE','ESSONO',0,'2025-03-10 13:03:26','student'),(247,'100240084','$2y$13$ONta65wMLz9hy/gcKIpSJOLZQ67HBFXi2Sw6H2d7WbGFnR7ffg1iO','majolieavoto32@gmail.com','697644335','default.jpg','100240084','FOUMANE MARIETTA LORENA','FOUMANE',0,'2025-03-10 13:03:28','student'),(248,'101295845','$2y$13$26am0Kg3lHysDsUVxBlSSOi.52vK/58P7i93kvI7kp0DtaGQgNZ8u','ferdiawa24@gmail.com','690275172','default.jpg','101295845','FERDINAND','GAIMATAKON',0,'2025-03-10 13:03:29','student'),(249,'100891310','$2y$13$3HiQNCBoQvmwvbP85TzC5e3DU0.P/ukcJ9W9NyBDJ1LsM.f4zm0Da','owonalionel402@gmail.com','691235529','default.jpg','100891310','IDI SIMON JUNIOR','GARBA',0,'2025-03-10 13:03:31','student'),(250,'100198813','$2y$13$lwhry8I4gN0EoJqNhSTvCe92SPDe0Kmn3wiEXVVjVtymniCQA.68C','thierryawoumou2003@gmail.com','656828349','default.jpg','100198813','LESLIE ONDOUA ABENG II','JANIS',0,'2025-03-10 13:03:32','student'),(251,'100130662','$2y$13$LPGsapbyg9suVqAZdoKTpOWzN2rpIVngTGu2M1vsE5jxP7Ugyn5aa','azombomarrion173@gmail.com','696105363','default.jpg','100130662','JANE ALEXIA','KOLOKO',0,'2025-03-10 13:03:34','student'),(252,'OA0413292','$2y$13$dXuVzyrkM/Uu9CvePuGHnu2CWOZolGS4ZGKsoXetNsaTaPbWJ3KEa','duclair@gmail.com','653215386','default.jpg','OA0413292','OHOLANGA NDEYA VICTOIRE','LOUFOUMA',0,'2025-03-10 13:03:35','student'),(253,'OA0403084','$2y$13$al1G4.p2jCTgZtbW5ZNUqOSDufS5wbsAUJ.o3h/ZvQG79wgAxTXFy','blondabangui@gmail.com','653215379','default.jpg','OA0403084','EXAUCEE BELGINA LOUISLENE','LOUKOKOBI',0,'2025-03-10 13:03:37','student'),(254,'OA0405418','$2y$13$NF4FP8mLb1GBqjPRaiv2UuTDQmZ9JgZxPE8BsPl4ZdlnKOSyU2Tya','bellasophiereinette@gmail.com','653619184','default.jpg','OA0405418','MOLOU MARIE FAUSTINE','MAFOULA',0,'2025-03-10 13:03:38','student'),(275,'bile','$2y$13$uDVCueBBekzrm60IjJLA5OPbWqca4rDa.fCwdnhlkVPgwHmoZChbC','merbyjosee1@gmail.com','676469014','dav-67d6af8a66b59.jpg','123456789','Emane','davy',0,'2025-03-16 11:01:29','level_manager');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  KEY `IDX_2DE8C6A3D60322AC` (`role_id`),
  CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `user_role` VALUES (2,4),(3,5),(4,5),(234,7),(235,7),(236,7),(237,7),(238,7),(239,7),(240,7),(241,7),(242,7),(243,7),(244,7),(245,7),(246,7),(247,7),(248,7),(249,7),(250,7),(251,7),(252,7),(253,7),(254,7),(275,8);
=======
INSERT INTO `user_role` VALUES (1,9),(2,4),(3,5),(4,5),(234,7),(235,7),(236,7),(237,7),(238,7),(239,7),(240,7),(241,7),(242,7),(243,7),(244,7),(245,7),(246,7),(247,7),(248,7),(249,7),(250,7),(251,7),(252,7),(253,7),(254,7),(275,8);
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_code`
--

DROP TABLE IF EXISTS `verification_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_used` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E821C39FA76ED395` (`user_id`),
  CONSTRAINT `FK_E821C39FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_code`
--

LOCK TABLES `verification_code` WRITE;
/*!40000 ALTER TABLE `verification_code` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `verification_code` VALUES (18,234,'22515','2025-03-10 14:00:16',1),(19,3,'14954','2025-03-15 19:56:55',0),(20,3,'78246','2025-03-15 19:57:17',0),(21,3,'19906','2025-03-15 19:57:54',0),(22,3,'43951','2025-03-15 19:57:58',0),(23,3,'51169','2025-03-15 20:04:05',1),(24,234,'98782','2025-03-15 20:06:21',0),(25,234,'97172','2025-03-15 20:06:28',1),(26,3,'38169','2025-03-16 10:54:34',1),(27,3,'11554','2025-03-16 10:54:38',0),(28,4,'30657','2025-03-16 10:56:41',0),(29,4,'29334','2025-03-16 10:56:44',0),(30,3,'42041','2025-03-16 10:59:32',0),(31,3,'36928','2025-03-16 10:59:35',0),(32,275,'44778','2025-03-16 11:05:07',1),(33,275,'04644','2025-03-16 11:05:11',0),(34,234,'28073','2025-03-16 11:44:02',0),(35,234,'00053','2025-03-16 11:44:05',1),(36,275,'17655','2025-03-16 13:14:00',0),(37,275,'70032','2025-03-16 13:14:04',1),(38,275,'39360','2025-03-16 13:16:37',0),(39,275,'06571','2025-03-16 13:16:41',1),(40,275,'46419','2025-03-16 13:17:01',0),(41,275,'53005','2025-03-16 13:27:05',0),(42,275,'30824','2025-03-16 13:27:08',0),(43,275,'82430','2025-03-16 13:32:34',1),(44,3,'77095','2025-03-16 15:22:31',0),(45,3,'93003','2025-03-16 15:22:34',1),(46,2,'75294','2025-03-16 15:39:32',0),(47,2,'58832','2025-03-16 15:39:35',1);
=======
INSERT INTO `verification_code` VALUES (18,234,'22515','2025-03-10 14:00:16',1),(19,3,'14954','2025-03-15 19:56:55',0),(20,3,'78246','2025-03-15 19:57:17',0),(21,3,'19906','2025-03-15 19:57:54',0),(22,3,'43951','2025-03-15 19:57:58',0),(23,3,'51169','2025-03-15 20:04:05',1),(24,234,'98782','2025-03-15 20:06:21',0),(25,234,'97172','2025-03-15 20:06:28',1),(26,3,'38169','2025-03-16 10:54:34',1),(27,3,'11554','2025-03-16 10:54:38',0),(28,4,'30657','2025-03-16 10:56:41',0),(29,4,'29334','2025-03-16 10:56:44',0),(30,3,'42041','2025-03-16 10:59:32',0),(31,3,'36928','2025-03-16 10:59:35',0),(32,275,'44778','2025-03-16 11:05:07',1),(33,275,'04644','2025-03-16 11:05:11',0),(34,234,'28073','2025-03-16 11:44:02',0),(35,234,'00053','2025-03-16 11:44:05',1),(36,275,'17655','2025-03-16 13:14:00',0),(37,275,'70032','2025-03-16 13:14:04',1),(38,275,'39360','2025-03-16 13:16:37',0),(39,275,'06571','2025-03-16 13:16:41',1),(40,275,'46419','2025-03-16 13:17:01',0),(41,275,'53005','2025-03-16 13:27:05',0),(42,275,'30824','2025-03-16 13:27:08',0),(43,275,'82430','2025-03-16 13:32:34',1),(44,3,'77095','2025-03-16 15:22:31',0),(45,3,'93003','2025-03-16 15:22:34',1),(46,2,'75294','2025-03-16 15:39:32',0),(47,2,'58832','2025-03-16 15:39:35',1),(48,3,'78335','2025-03-17 22:09:10',0),(49,3,'79664','2025-03-17 22:09:14',1);
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
/*!40000 ALTER TABLE `verification_code` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

<<<<<<< HEAD
-- Dump completed on 2025-03-17 17:09:23
=======
-- Dump completed on 2025-03-18  8:54:35
>>>>>>> 4117cc895355e7ff531cd379c0ed07b14cf280de
