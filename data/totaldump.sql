-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: localhost    Database: smiletomove_learning
-- ------------------------------------------------------
-- Server version	5.6.23

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
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passwordsha1` varchar(40) NOT NULL,
  `insert_date` datetime NOT NULL,
  `last_access` datetime DEFAULT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_account_client1_idx` (`client_id`),
  KEY `fk_account_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_account_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,1,'test','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','2015-07-25 23:59:59','2015-07-26 00:00:00',1);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activationstatus`
--

DROP TABLE IF EXISTS `activationstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activationstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activationstatus`
--

LOCK TABLES `activationstatus` WRITE;
/*!40000 ALTER TABLE `activationstatus` DISABLE KEYS */;
INSERT INTO `activationstatus` VALUES (0,'inactive'),(1,'active');
/*!40000 ALTER TABLE `activationstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `businessname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(45) NOT NULL,
  `city` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `vatnumber` varchar(45) DEFAULT NULL,
  `fiscode` varchar(45) DEFAULT NULL,
  `insert_date` datetime NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_client_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'test businessname','test address road','LW1 3C2','London','Greater London','Uk','+44 111 1111111','a.giacomella@test.com',NULL,'AA0000001',NULL,'2015-07-25 23:50:00',1);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_has_course`
--

DROP TABLE IF EXISTS `client_has_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_has_course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_has_course_course1_idx` (`course_id`),
  KEY `fk_client_has_course_client1_idx` (`client_id`),
  KEY `fk_client_has_course_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_client_has_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_course_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_has_course`
--

LOCK TABLES `client_has_course` WRITE;
/*!40000 ALTER TABLE `client_has_course` DISABLE KEYS */;
INSERT INTO `client_has_course` VALUES (1,1,1,'2015-07-25 23:55:00',1);
/*!40000 ALTER TABLE `client_has_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_has_student`
--

DROP TABLE IF EXISTS `client_has_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_has_student` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) NOT NULL,
  `student_id` bigint(20) NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  `insert_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_has_student_student1_idx` (`student_id`),
  KEY `fk_client_has_student_client1_idx` (`client_id`),
  KEY `fk_client_has_student_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_client_has_student_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_student_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_has_student`
--

LOCK TABLES `client_has_student` WRITE;
/*!40000 ALTER TABLE `client_has_student` DISABLE KEYS */;
INSERT INTO `client_has_student` VALUES (1,1,1,1,'2015-07-26 13:29:00');
/*!40000 ALTER TABLE `client_has_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientconfiguration`
--

DROP TABLE IF EXISTS `clientconfiguration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientconfiguration` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) NOT NULL,
  `maxusers` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_clientconfiguration_client1_idx` (`client_id`),
  CONSTRAINT `fk_clientconfiguration_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientconfiguration`
--

LOCK TABLES `clientconfiguration` WRITE;
/*!40000 ALTER TABLE `clientconfiguration` DISABLE KEYS */;
INSERT INTO `clientconfiguration` VALUES (1,1,20);
/*!40000 ALTER TABLE `clientconfiguration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `periodicityweek` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `durationweek` int(11) NOT NULL,
  `insert_date` datetime NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  `emailtemplateurl` varchar(255) NOT NULL,
  `totalexams` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_course_weekday1_idx` (`weekday_id`),
  KEY `fk_course_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_weekday1` FOREIGN KEY (`weekday_id`) REFERENCES `weekday` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (1,'Test course','This is the test course',2,1,8,'2015-07-25 23:50:00',1,'',4);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `imageurl` varchar(255) DEFAULT NULL,
  `points_if_completed` int(11) NOT NULL,
  `reduce_percentage_outtime` int(11) DEFAULT NULL,
  `course_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `mandatory` int(11) NOT NULL,
  `totalitems` int(11) NOT NULL,
  `prog_on_course` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_exam_course1_idx` (`course_id`),
  CONSTRAINT `fk_exam_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam`
--

LOCK TABLES `exam` WRITE;
/*!40000 ALTER TABLE `exam` DISABLE KEYS */;
INSERT INTO `exam` VALUES (1,'First exam of the course','This is the first exam session of the course','',100,30,1,'2015-07-26 13:39:00',1,1,1);
/*!40000 ALTER TABLE `exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_has_item`
--

DROP TABLE IF EXISTS `exam_has_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_has_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exam_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `progressive` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_exam_has_item_item1_idx` (`item_id`),
  KEY `fk_exam_has_item_exam1_idx` (`exam_id`),
  CONSTRAINT `fk_exam_has_item_exam1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_has_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_has_item`
--

LOCK TABLES `exam_has_item` WRITE;
/*!40000 ALTER TABLE `exam_has_item` DISABLE KEYS */;
INSERT INTO `exam_has_item` VALUES (1,1,1,'2015-07-27 00:00:00',1);
/*!40000 ALTER TABLE `exam_has_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `url` varchar(255) NOT NULL,
  `mediatype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_image_mediatype1_idx` (`mediatype_id`),
  CONSTRAINT `fk_image_mediatype1` FOREIGN KEY (`mediatype_id`) REFERENCES `mediatype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `itemtype_id` int(11) NOT NULL,
  `maxtries` int(11) NOT NULL DEFAULT '2',
  `maxsecs` int(11) DEFAULT NULL,
  `context` text NOT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_itemtype1_idx` (`itemtype_id`),
  KEY `fk_item_item1_idx` (`item_id`),
  CONSTRAINT `fk_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_itemtype1` FOREIGN KEY (`itemtype_id`) REFERENCES `itemtype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (1,'La corretta traduzione di \"balza\" e\'...',1,2,200,'Il termine corretto Ã¨ flounce, anche se volant, di derivazione francese, Ã¨ comunque accettato.',NULL),(2,'Il termine \"collar\" indica esclusivamente il collare per cani',2,1,200,'FALSO: Collar, nell\'ambito fashion, indica il colletto, il bavero, negli indumenti.',NULL),(3,'Metti in ordine i seguenti vocaboli partendo dallâ€™indumento che si indossa piÃ¹ in alto e finendo con quello che si indossa piÃ¹ in basso:',3,1,200,'L\'ordine giusto Ã¨ Balaclava (passamontagna) in testa, Glove (guanti), Carter (giarrettiera)',NULL);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_has_image`
--

DROP TABLE IF EXISTS `item_has_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_has_image` (
  `item_id` bigint(20) NOT NULL,
  `image_id` bigint(20) NOT NULL,
  PRIMARY KEY (`item_id`,`image_id`),
  KEY `fk_item_has_image_image1_idx` (`image_id`),
  KEY `fk_item_has_image_item_idx` (`item_id`),
  CONSTRAINT `fk_item_has_image_image1` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_image_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_has_image`
--

LOCK TABLES `item_has_image` WRITE;
/*!40000 ALTER TABLE `item_has_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_has_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_has_itemoption`
--

DROP TABLE IF EXISTS `item_has_itemoption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_has_itemoption` (
  `item_id` bigint(20) NOT NULL,
  `itemoption_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`,`itemoption_id`),
  KEY `fk_item_has_itemoption_itemoption1_idx` (`itemoption_id`),
  KEY `fk_item_has_itemoption_item1_idx` (`item_id`),
  CONSTRAINT `fk_item_has_itemoption_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_itemoption_itemoption1` FOREIGN KEY (`itemoption_id`) REFERENCES `itemoption` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_has_itemoption`
--

LOCK TABLES `item_has_itemoption` WRITE;
/*!40000 ALTER TABLE `item_has_itemoption` DISABLE KEYS */;
INSERT INTO `item_has_itemoption` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `item_has_itemoption` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemoption`
--

DROP TABLE IF EXISTS `itemoption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemoption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemoption`
--

LOCK TABLES `itemoption` WRITE;
/*!40000 ALTER TABLE `itemoption` DISABLE KEYS */;
INSERT INTO `itemoption` VALUES (1,'Opzione 1',0),(2,'Opzione 2',100),(3,'Opzione 3',200);
/*!40000 ALTER TABLE `itemoption` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemtype`
--

DROP TABLE IF EXISTS `itemtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemtype` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemtype`
--

LOCK TABLES `itemtype` WRITE;
/*!40000 ALTER TABLE `itemtype` DISABLE KEYS */;
INSERT INTO `itemtype` VALUES (1,'Multiple'),(2,'True False'),(3,'Reorder'),(4,'Insert');
/*!40000 ALTER TABLE `itemtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mediatype`
--

DROP TABLE IF EXISTS `mediatype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mediatype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mediatype`
--

LOCK TABLES `mediatype` WRITE;
/*!40000 ALTER TABLE `mediatype` DISABLE KEYS */;
INSERT INTO `mediatype` VALUES (1,'Image'),(2,'Video'),(3,'Image slideshow');
/*!40000 ALTER TABLE `mediatype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordsha1` varchar(40) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `identifier_UNIQUE` (`identifier`),
  KEY `fk_student_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_student_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,'ALESSANDRO','GIACOMELLA','a.giacomella@gmail.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','12341234',1);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_has_answered_to_item`
--

DROP TABLE IF EXISTS `student_has_answered_to_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_has_answered_to_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_has_course_has_exam_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `option_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `insert_date` datetime NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_has_answered_to_item_student_has_course_has_exam_idx` (`student_has_course_has_exam_id`),
  KEY `fk_student_has_answered_to_item_item1_idx` (`item_id`),
  CONSTRAINT `fk_student_has_answered_to_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_answered_to_item_student_has_course_has_exam1` FOREIGN KEY (`student_has_course_has_exam_id`) REFERENCES `student_has_course_has_exam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_has_answered_to_item`
--

LOCK TABLES `student_has_answered_to_item` WRITE;
/*!40000 ALTER TABLE `student_has_answered_to_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_has_answered_to_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_has_course`
--

DROP TABLE IF EXISTS `student_has_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_has_course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_has_course_course1_idx` (`course_id`),
  KEY `fk_student_has_course_student1_idx` (`student_id`),
  KEY `fk_student_has_course_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_student_has_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_has_course`
--

LOCK TABLES `student_has_course` WRITE;
/*!40000 ALTER TABLE `student_has_course` DISABLE KEYS */;
INSERT INTO `student_has_course` VALUES (1,1,1,'2015-07-25 23:55:00',1);
/*!40000 ALTER TABLE `student_has_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_has_course_has_exam`
--

DROP TABLE IF EXISTS `student_has_course_has_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_has_course_has_exam` (
  `id` bigint(20) NOT NULL,
  `student_has_course_id` bigint(20) NOT NULL,
  `exam_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `completed` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `answer` text,
  `token` varchar(45) NOT NULL,
  `progressive` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_student_has_course_has_exam_exam1_idx` (`exam_id`),
  KEY `fk_student_has_course_has_exam_student_has_course1_idx` (`student_has_course_id`),
  CONSTRAINT `fk_student_has_course_has_exam_exam1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_has_exam_student_has_course1` FOREIGN KEY (`student_has_course_id`) REFERENCES `student_has_course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_has_course_has_exam`
--

LOCK TABLES `student_has_course_has_exam` WRITE;
/*!40000 ALTER TABLE `student_has_course_has_exam` DISABLE KEYS */;
INSERT INTO `student_has_course_has_exam` VALUES (1,1,1,'2015-07-27 00:30:00','2015-07-27 00:30:00','2015-07-29 00:30:00',0,0,NULL,'12j21921dj90d9h39f3',0);
/*!40000 ALTER TABLE `student_has_course_has_exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studentgroup`
--

DROP TABLE IF EXISTS `studentgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `studentgroup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `insert_date` datetime DEFAULT NULL,
  `activationstatus_id` int(11) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_studentgroup_activationstatus1_idx` (`activationstatus_id`),
  KEY `fk_studentgroup_client1_idx` (`client_id`),
  CONSTRAINT `fk_studentgroup_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_studentgroup_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studentgroup`
--

LOCK TABLES `studentgroup` WRITE;
/*!40000 ALTER TABLE `studentgroup` DISABLE KEYS */;
INSERT INTO `studentgroup` VALUES (1,'First group','2015-07-26 13:32:00',1,1);
/*!40000 ALTER TABLE `studentgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studentgroup_has_student`
--

DROP TABLE IF EXISTS `studentgroup_has_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `studentgroup_has_student` (
  `id` bigint(20) NOT NULL,
  `studentgroup_id` bigint(20) NOT NULL,
  `student_id` bigint(20) NOT NULL,
  `insert_date` datetime NOT NULL,
  `activationstatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_studentgroup_has_student_student1_idx` (`student_id`),
  KEY `fk_studentgroup_has_student_studentgroup1_idx` (`studentgroup_id`),
  KEY `fk_studentgroup_has_student_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_studentgroup_has_student_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_studentgroup_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_studentgroup_has_student_studentgroup1` FOREIGN KEY (`studentgroup_id`) REFERENCES `studentgroup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studentgroup_has_student`
--

LOCK TABLES `studentgroup_has_student` WRITE;
/*!40000 ALTER TABLE `studentgroup_has_student` DISABLE KEYS */;
INSERT INTO `studentgroup_has_student` VALUES (1,1,1,'2015-07-26 13:35:00',1);
/*!40000 ALTER TABLE `studentgroup_has_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weekday`
--

DROP TABLE IF EXISTS `weekday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weekday` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weekday`
--

LOCK TABLES `weekday` WRITE;
/*!40000 ALTER TABLE `weekday` DISABLE KEYS */;
INSERT INTO `weekday` VALUES (1,'Monday'),(2,'Tuesday'),(3,'Wednesday'),(4,'Thursday'),(5,'Friday'),(6,'Saturday'),(7,'Sunday');
/*!40000 ALTER TABLE `weekday` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-31 17:05:10
