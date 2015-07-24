-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: 10.0.20.15    Database: servicehub2
-- ------------------------------------------------------
-- Server version	5.5.42-MariaDB-wsrep-log

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
  `accountstatus_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_account_accountstatus1_idx` (`accountstatus_id`),
  KEY `fk_account_brand1_idx` (`brand_id`),
  CONSTRAINT `fk_account_accountstatus1` FOREIGN KEY (`accountstatus_id`) REFERENCES `accountstatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_brand1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_extends_brand_has_widget_use_service`
--

DROP TABLE IF EXISTS `account_extends_brand_has_widget_use_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_extends_brand_has_widget_use_service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `brand_has_widget_use_service_id` bigint(20) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_brand_has_widget_use_service_brand_has_widge_idx` (`brand_has_widget_use_service_id`),
  KEY `fk_account_has_brand_has_widget_use_service_account1_idx` (`account_id`),
  CONSTRAINT `fk_account_has_brand_has_widget_use_service_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_brand_has_widget_use_service_brand_has_widget_1` FOREIGN KEY (`brand_has_widget_use_service_id`) REFERENCES `brand_has_widget_use_service` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_has_brand_has_page_has_widget`
--

DROP TABLE IF EXISTS `account_has_brand_has_page_has_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_has_brand_has_page_has_widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `brand_has_page_has_widget_id` bigint(20) NOT NULL,
  `widget_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_brand_has_page_has_widget1_brand_has_page_ha_idx` (`brand_has_page_has_widget_id`),
  KEY `fk_account_has_brand_has_page_has_widget1_account1_idx` (`account_id`),
  KEY `fk_account_has_brand_has_page_has_widget_widget1_idx` (`widget_id`),
  CONSTRAINT `fk_account_has_brand_has_page_has_widget1_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_brand_has_page_has_widget1_brand_has_page_has_1` FOREIGN KEY (`brand_has_page_has_widget_id`) REFERENCES `brand_has_page_has_widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_brand_has_page_has_widget_widget1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_has_brand_has_widget`
--

DROP TABLE IF EXISTS `account_has_brand_has_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_has_brand_has_widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `brand_has_widget_id` bigint(20) NOT NULL,
  `structure` text,
  `graphic` text,
  `context` text,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_brand_has_widget1_brand_has_widget1_idx` (`brand_has_widget_id`),
  KEY `fk_account_has_brand_has_widget1_account1_idx` (`account_id`),
  CONSTRAINT `fk_account_has_brand_has_widget1_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_brand_has_widget1_brand_has_widget1` FOREIGN KEY (`brand_has_widget_id`) REFERENCES `brand_has_widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_has_menuitem`
--

DROP TABLE IF EXISTS `account_has_menuitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_has_menuitem` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `menuitem_id` bigint(20) NOT NULL,
  `accountmenustatus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_menuitem_menuitem1_idx` (`menuitem_id`),
  KEY `fk_account_has_menuitem_account1_idx` (`account_id`),
  KEY `fk_account_has_menuitem_accountmenustatus1_idx` (`accountmenustatus_id`),
  CONSTRAINT `fk_account_has_menuitem_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_menuitem_menuitem1` FOREIGN KEY (`menuitem_id`) REFERENCES `menuitem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_menuitem_accountmenustatus1` FOREIGN KEY (`accountmenustatus_id`) REFERENCES `accountmenustatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_has_page`
--

DROP TABLE IF EXISTS `account_has_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_has_page` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `page_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_page_page1_idx` (`page_id`),
  KEY `fk_account_has_page_account1_idx` (`account_id`),
  CONSTRAINT `fk_account_has_page_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_page_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `account_has_widgetoption`
--

DROP TABLE IF EXISTS `account_has_widgetoption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_has_widgetoption` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `widgetoption_id` bigint(20) NOT NULL,
  `option` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_account_has_widgetoption_account1_idx` (`account_id`),
  CONSTRAINT `fk_account_has_widgetoption_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accountmenustatus`
--

DROP TABLE IF EXISTS `accountmenustatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accountmenustatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `accountstatus`
--

DROP TABLE IF EXISTS `accountstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accountstatus` (
  `id` int(11) NOT NULL,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_has_page_has_widget`
--

DROP TABLE IF EXISTS `brand_has_page_has_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_has_page_has_widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `page_has_widget_id` bigint(20) NOT NULL,
  `widget_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_brand_has_page_has_widget_page_has_widget1_idx` (`page_has_widget_id`),
  KEY `fk_brand_has_page_has_widget_brand1_idx` (`brand_id`),
  KEY `fk_brand_has_page_has_widget_widget1_idx` (`widget_id`),
  CONSTRAINT `fk_brand_has_page_has_widget_brand1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_brand_has_page_has_widget_page_has_widget1` FOREIGN KEY (`page_has_widget_id`) REFERENCES `page_has_widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_brand_has_page_has_widget_widget1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_has_widget`
--

DROP TABLE IF EXISTS `brand_has_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_has_widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `widget_id` bigint(20) NOT NULL,
  `structure` text,
  `graphic` text,
  `context` text,
  PRIMARY KEY (`id`),
  KEY `fk_brand_has_widget_widget1_idx` (`widget_id`),
  KEY `fk_brand_has_widget_brand1_idx` (`brand_id`),
  CONSTRAINT `fk_brand_has_widget_brand1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_brand_has_widget_widget1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_has_widget_use_service`
--

DROP TABLE IF EXISTS `brand_has_widget_use_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_has_widget_use_service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `widget_use_service_id` bigint(20) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `fk_brand_has_widget_use_service1_widget_use_service1_idx` (`widget_use_service_id`),
  KEY `fk_brand_has_widget_use_service1_brand1_idx` (`brand_id`),
  CONSTRAINT `fk_brand_has_widget_use_service1_brand1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_brand_has_widget_use_service1_widget_use_service1` FOREIGN KEY (`widget_use_service_id`) REFERENCES `widget_use_service` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_has_page`
--

DROP TABLE IF EXISTS `menu_has_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_has_page` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) NOT NULL,
  `page_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_has_page_page1_idx` (`page_id`),
  KEY `fk_menu_has_page_menu1_idx` (`menu_id`),
  CONSTRAINT `fk_menu_has_page_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menuitem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_has_page_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menuitem`
--

DROP TABLE IF EXISTS `menuitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menuitem` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `iconurl` text,
  `url` text NOT NULL,
  `menustatus_id` int(11) NOT NULL,
  `page_id` bigint(20) NOT NULL,
  `menuitem_id` bigint(20) DEFAULT NULL,
  `menutype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_menustatus1_idx` (`menustatus_id`),
  KEY `fk_menu_page1_idx` (`page_id`),
  KEY `fk_menuitem_menuitem1_idx` (`menuitem_id`),
  KEY `fk_menuitem_menutype1_idx` (`menutype_id`),
  CONSTRAINT `fk_menuitem_menutype1` FOREIGN KEY (`menutype_id`) REFERENCES `menutype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menuitem_menuitem1` FOREIGN KEY (`menuitem_id`) REFERENCES `menuitem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_menustatus1` FOREIGN KEY (`menustatus_id`) REFERENCES `menustatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_page1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menustatus`
--

DROP TABLE IF EXISTS `menustatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menustatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menutype`
--

DROP TABLE IF EXISTS `menutype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menutype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `template` varchar(45) NOT NULL,
  `structure` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page_has_widget`
--

DROP TABLE IF EXISTS `page_has_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_has_widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) NOT NULL,
  `widget_id` bigint(20) NOT NULL,
  `widgetsize_id` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_page_has_widget_widget1_idx` (`widget_id`),
  KEY `fk_page_has_widget_page_idx` (`page_id`),
  KEY `fk_page_has_widget_widgetsize1_idx` (`widgetsize_id`),
  CONSTRAINT `fk_page_has_widget_widgetsize1` FOREIGN KEY (`widgetsize_id`) REFERENCES `widgetsize` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_has_widget_page` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_has_widget_widget1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `widget`
--

DROP TABLE IF EXISTS `widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `widgettype_id` int(11) NOT NULL,
  `structure` text NOT NULL,
  `graphic` text NOT NULL,
  `context` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_widget_widgettype1_idx` (`widgettype_id`),
  CONSTRAINT `fk_widget_widgettype1` FOREIGN KEY (`widgettype_id`) REFERENCES `widgettype` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `widget_use_service`
--

DROP TABLE IF EXISTS `widget_use_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget_use_service` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `widget_id` bigint(20) NOT NULL,
  `service_id` bigint(20) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `fk_widget_has_service_service1_idx` (`service_id`),
  KEY `fk_widget_has_service_widget1_idx` (`widget_id`),
  CONSTRAINT `fk_widget_has_service_widget1` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_widget_has_service_service1` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `widgetsize`
--

DROP TABLE IF EXISTS `widgetsize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgetsize` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `widgettype`
--

DROP TABLE IF EXISTS `widgettype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgettype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-14 11:07:22
