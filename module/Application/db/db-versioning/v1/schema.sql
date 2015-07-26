-- MySQL dump 10.13  Distrib 5.6.12, for osx10.7 (i386)
--
-- Host: localhost    Database: smiletomove_learning
-- ------------------------------------------------------
-- Server version	5.6.12

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
  CONSTRAINT `fk_account_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_client_has_course_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_client_has_student_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_has_student_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  KEY `fk_course_weekday1_idx` (`weekday_id`),
  KEY `fk_course_activationstatus1_idx` (`activationstatus_id`),
  CONSTRAINT `fk_course_weekday1` FOREIGN KEY (`weekday_id`) REFERENCES `weekday` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `mandatory` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_exam_course1_idx` (`course_id`),
  CONSTRAINT `fk_exam_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  KEY `fk_exam_has_item_item1_idx` (`item_id`),
  KEY `fk_exam_has_item_exam1_idx` (`exam_id`),
  CONSTRAINT `fk_exam_has_item_exam1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exam_has_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_item_itemtype1` FOREIGN KEY (`itemtype_id`) REFERENCES `itemtype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_item_has_image_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_image_image1` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_student_has_course_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `token` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_has_course_has_exam_exam1_idx` (`exam_id`),
  KEY `fk_student_has_course_has_exam_student_has_course1_idx` (`student_has_course_id`),
  CONSTRAINT `fk_student_has_course_has_exam_student_has_course1` FOREIGN KEY (`student_has_course_id`) REFERENCES `student_has_course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_course_has_exam_exam1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  CONSTRAINT `fk_studentgroup_has_student_studentgroup1` FOREIGN KEY (`studentgroup_id`) REFERENCES `studentgroup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_studentgroup_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_studentgroup_has_student_activationstatus1` FOREIGN KEY (`activationstatus_id`) REFERENCES `activationstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-24 23:08:28
