-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: butcherhut_erp
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'default','created','App\\Models\\Invoice','created',1,'App\\Models\\User',1,'{\"attributes\":{\"status\":\"draft\",\"amount_paid\":\"0.00\",\"total_amount\":\"2500.00\"}}',NULL,'2026-05-19 10:43:44','2026-05-19 10:43:44'),(2,'default','Created invoice #INV20260001','App\\Models\\Invoice',NULL,1,'App\\Models\\User',1,'[]',NULL,'2026-05-19 10:43:45','2026-05-19 10:43:45'),(3,'default','updated','App\\Models\\Invoice','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"status\":\"sent\"},\"old\":{\"status\":\"draft\"}}',NULL,'2026-05-19 11:00:46','2026-05-19 11:00:46'),(4,'default','created','App\\Models\\Invoice','created',2,'App\\Models\\User',1,'{\"attributes\":{\"status\":\"draft\",\"amount_paid\":\"0.00\",\"total_amount\":\"30000.00\"}}',NULL,'2026-05-19 11:07:44','2026-05-19 11:07:44'),(5,'default','Created invoice #INV20260002','App\\Models\\Invoice',NULL,2,'App\\Models\\User',1,'[]',NULL,'2026-05-19 11:07:44','2026-05-19 11:07:44'),(6,'default','updated','App\\Models\\Invoice','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"status\":\"sent\"},\"old\":{\"status\":\"draft\"}}',NULL,'2026-05-19 11:09:42','2026-05-19 11:09:42'),(7,'default','updated','App\\Models\\Invoice','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"status\":\"paid\",\"amount_paid\":\"30000.00\"},\"old\":{\"status\":\"sent\",\"amount_paid\":\"0.00\"}}',NULL,'2026-05-19 11:09:50','2026-05-19 11:09:50'),(8,'default','created','App\\Models\\User','created',3,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"cashier\",\"email\":\"cashier@butcherhut.ng\",\"shop_id\":1,\"is_active\":true,\"scope\":\"branch\"}}',NULL,'2026-05-22 08:51:14','2026-05-22 08:51:14'),(9,'default','Created user: cashier with role cashier','App\\Models\\User',NULL,3,'App\\Models\\User',1,'[]',NULL,'2026-05-22 08:51:15','2026-05-22 08:51:15'),(10,'default','created','App\\Models\\User','created',4,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Blessing\",\"email\":\"blessing@gmail.com\",\"shop_id\":1,\"is_active\":true,\"scope\":\"branch\"}}',NULL,'2026-05-22 08:52:39','2026-05-22 08:52:39'),(11,'default','Created user: Blessing with role pos-attendant','App\\Models\\User',NULL,4,'App\\Models\\User',1,'[]',NULL,'2026-05-22 08:52:39','2026-05-22 08:52:39'),(12,'default','created','App\\Models\\User','created',5,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Matthew\",\"email\":\"matthew@gmail.com\",\"shop_id\":1,\"is_active\":true,\"scope\":\"branch\"}}',NULL,'2026-05-22 08:53:31','2026-05-22 08:53:31'),(13,'default','Created user: Matthew with role manager','App\\Models\\User',NULL,5,'App\\Models\\User',1,'[]',NULL,'2026-05-22 08:53:31','2026-05-22 08:53:31'),(14,'default','Updated shop: Abis HQ General Paint','App\\Models\\Shop',NULL,1,'App\\Models\\User',1,'[]',NULL,'2026-05-22 08:54:40','2026-05-22 08:54:40'),(15,'default','created','App\\Models\\Product','created',1,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"zobo\",\"price\":\"1200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:15','2026-05-23 12:12:15'),(16,'default','created','App\\Models\\Product','created',2,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"YO BERRY SMALL\",\"price\":\"700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(17,'default','created','App\\Models\\Product','created',3,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"YO BERRY MEDI\",\"price\":\"1000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(18,'default','created','App\\Models\\Product','created',4,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"YO BERRY BIG\",\"price\":\"1500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(19,'default','created','App\\Models\\Product','created',5,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"YO BERRY\",\"price\":\"1500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(20,'default','created','App\\Models\\Product','created',6,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"WATER\",\"price\":\"200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(21,'default','created','App\\Models\\Product','created',7,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Vee-Saugage\",\"price\":\"700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(22,'default','created','App\\Models\\Product','created',8,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Vee-Pie\",\"price\":\"800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(23,'default','created','App\\Models\\Product','created',9,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Vee-Eggroll\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:16','2026-05-23 12:12:16'),(24,'default','created','App\\Models\\Product','created',10,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"VEE-DOUGHNUT\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(25,'default','created','App\\Models\\Product','created',11,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"VEE-BUNS\",\"price\":\"200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(26,'default','created','App\\Models\\Product','created',12,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY WINGS\",\"price\":\"11000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(27,'default','created','App\\Models\\Product','created',13,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY LOCAL\",\"price\":\"9100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(28,'default','created','App\\Models\\Product','created',14,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY LIVE\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(29,'default','created','App\\Models\\Product','created',15,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY LAPS\",\"price\":\"2600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(30,'default','created','App\\Models\\Product','created',16,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GIZZARD\",\"price\":\"8500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(31,'default','created','App\\Models\\Product','created',17,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY BLANKET\",\"price\":\"9500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:17','2026-05-23 12:12:17'),(32,'default','created','App\\Models\\Product','created',18,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TURKEY ASSORTED\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(33,'default','created','App\\Models\\Product','created',19,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GIZZARD\",\"price\":\"9600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(34,'default','created','App\\Models\\Product','created',20,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"transportation of goods & serv\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(35,'default','created','App\\Models\\Product','created',21,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TISANE TEA\",\"price\":\"9200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(36,'default','created','App\\Models\\Product','created',22,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Tigger-Nut Drinks\",\"price\":\"1700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(37,'default','created','App\\Models\\Product','created',23,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"teem\",\"price\":\"350.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:18','2026-05-23 12:12:18'),(38,'default','created','App\\Models\\Product','created',24,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SPRITE\",\"price\":\"450.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(39,'default','created','App\\Models\\Product','created',25,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SOFT DRINKS\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(40,'default','created','App\\Models\\Product','created',26,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SMOOVE\",\"price\":\"150.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(41,'default','created','App\\Models\\Product','created',27,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"seafood shrimp\",\"price\":\"4000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(42,'default','created','App\\Models\\Product','created',28,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Prawn\",\"price\":\"22000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(43,'default','created','App\\Models\\Product','created',29,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SeaFood Periwinkle\",\"price\":\"1800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:19','2026-05-23 12:12:19'),(44,'default','created','App\\Models\\Product','created',30,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"seafood crab\",\"price\":\"3500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(45,'default','created','App\\Models\\Product','created',31,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RAM MEAT\",\"price\":\"10000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(46,'default','created','App\\Models\\Product','created',32,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LIVE RAM\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(47,'default','created','App\\Models\\Product','created',33,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RAM LEG 4 PIECES\",\"price\":\"1000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(48,'default','created','App\\Models\\Product','created',34,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LAMB CHOP\",\"price\":\"17100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(49,'default','created','App\\Models\\Product','created',35,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RAM HEAD\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:20','2026-05-23 12:12:20'),(50,'default','created','App\\Models\\Product','created',36,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RAM BONES\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(51,'default','created','App\\Models\\Product','created',37,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RAM ASSORTED\",\"price\":\"5100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(52,'default','created','App\\Models\\Product','created',38,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"PREPARATION\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(53,'default','created','App\\Models\\Product','created',39,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"PEPSI LITE\",\"price\":\"250.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(54,'default','created','App\\Models\\Product','created',40,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"PEPSI\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(55,'default','created','App\\Models\\Product','created',41,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"PAFE\",\"price\":\"2500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(56,'default','created','App\\Models\\Product','created',42,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NYLON\",\"price\":\"100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:21','2026-05-23 12:12:21'),(57,'default','created','App\\Models\\Product','created',43,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NUTRIYO\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(58,'default','created','App\\Models\\Product','created',44,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NUTRIPINEAPPLE\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(59,'default','created','App\\Models\\Product','created',45,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NUTRIMILK\",\"price\":\"600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(60,'default','created','App\\Models\\Product','created',46,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NUTRICHOCO\",\"price\":\"800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(61,'default','created','App\\Models\\Product','created',47,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"NUTRIAPPLE\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(62,'default','created','App\\Models\\Product','created',48,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"MIRINDA\",\"price\":\"350.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:22','2026-05-23 12:12:22'),(63,'default','created','App\\Models\\Product','created',49,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"MALTINA PLASTICS\",\"price\":\"700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(64,'default','created','App\\Models\\Product','created',50,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"MALTINA CAN\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(65,'default','created','App\\Models\\Product','created',51,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"MALTA GUINESS\",\"price\":\"800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(66,'default','created','App\\Models\\Product','created',52,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ladder\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(67,'default','created','App\\Models\\Product','created',53,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LACACERA\",\"price\":\"300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(68,'default','created','App\\Models\\Product','created',54,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 500ml\",\"price\":\"6500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:23','2026-05-23 12:12:23'),(69,'default','created','App\\Models\\Product','created',55,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"KOMANDO\",\"price\":\"250.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:24','2026-05-23 12:12:24'),(70,'default','created','App\\Models\\Product','created',56,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ice block sales\",\"price\":\"400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:24','2026-05-23 12:12:24'),(71,'default','created','App\\Models\\Product','created',57,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"HIBISCUS TEA\",\"price\":\"4500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:25','2026-05-23 12:12:25'),(72,'default','created','App\\Models\\Product','created',58,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GUINEA LIVE\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:26','2026-05-23 12:12:26'),(73,'default','created','App\\Models\\Product','created',59,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GUINEA FOWL\",\"price\":\"3000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:27','2026-05-23 12:12:27'),(74,'default','created','App\\Models\\Product','created',60,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GRANDING\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:27','2026-05-23 12:12:27'),(75,'default','created','App\\Models\\Product','created',61,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT UNPROCESSED ASSORTED\",\"price\":\"3600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:27','2026-05-23 12:12:27'),(76,'default','created','App\\Models\\Product','created',62,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT MEAT BONELESS\",\"price\":\"15000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:27','2026-05-23 12:12:27'),(77,'default','created','App\\Models\\Product','created',63,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT MEAT\",\"price\":\"9500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:27','2026-05-23 12:12:27'),(78,'default','created','App\\Models\\Product','created',64,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LIVE GOAT\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:28','2026-05-23 12:12:28'),(79,'default','created','App\\Models\\Product','created',65,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT LEG\",\"price\":\"400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:28','2026-05-23 12:12:28'),(80,'default','created','App\\Models\\Product','created',66,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT HEAD\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:28','2026-05-23 12:12:28'),(81,'default','created','App\\Models\\Product','created',67,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"503\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:28','2026-05-23 12:12:28'),(82,'default','created','App\\Models\\Product','created',68,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT BONES\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:28','2026-05-23 12:12:28'),(83,'default','created','App\\Models\\Product','created',69,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT ASSORTED\",\"price\":\"5100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:29','2026-05-23 12:12:29'),(84,'default','created','App\\Models\\Product','created',70,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"GOAT MEAT\",\"price\":\"9500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:30','2026-05-23 12:12:30'),(85,'default','created','App\\Models\\Product','created',71,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Full Cow Leg\",\"price\":\"7800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:30','2026-05-23 12:12:30'),(86,'default','created','App\\Models\\Product','created',72,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FRESH YOGURT\",\"price\":\"600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:30','2026-05-23 12:12:30'),(87,'default','created','App\\Models\\Product','created',73,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FREE\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:30','2026-05-23 12:12:30'),(88,'default','created','App\\Models\\Product','created',74,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TITUS PINK ROPE FISH\",\"price\":\"1900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:30','2026-05-23 12:12:30'),(89,'default','created','App\\Models\\Product','created',75,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH TITUS\",\"price\":\"7900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:31','2026-05-23 12:12:31'),(90,'default','created','App\\Models\\Product','created',76,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TILAPIA\",\"price\":\"4600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:31','2026-05-23 12:12:31'),(91,'default','created','App\\Models\\Product','created',77,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ROCK FISH\",\"price\":\"3150.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:31','2026-05-23 12:12:31'),(92,'default','created','App\\Models\\Product','created',78,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH REDPACU\",\"price\":\"4500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:31','2026-05-23 12:12:31'),(93,'default','created','App\\Models\\Product','created',79,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH LADY\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:31','2026-05-23 12:12:31'),(94,'default','created','App\\Models\\Product','created',80,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH HMK\",\"price\":\"4800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:32','2026-05-23 12:12:32'),(95,'default','created','App\\Models\\Product','created',81,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH SHAWA\",\"price\":\"3700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:32','2026-05-23 12:12:32'),(96,'default','created','App\\Models\\Product','created',82,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"HAKEFIISH\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:32','2026-05-23 12:12:32'),(97,'default','created','App\\Models\\Product','created',83,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"HAKE FISH\",\"price\":\"4900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:33','2026-05-23 12:12:33'),(98,'default','created','App\\Models\\Product','created',84,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH CROCKER\",\"price\":\"5600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:33','2026-05-23 12:12:33'),(99,'default','created','App\\Models\\Product','created',85,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CASABLACA\",\"price\":\"3000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:34','2026-05-23 12:12:34'),(100,'default','created','App\\Models\\Product','created',86,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CARTFISH\",\"price\":\"4400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:35','2026-05-23 12:12:35'),(101,'default','created','App\\Models\\Product','created',87,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH BWHITING\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:35','2026-05-23 12:12:35'),(102,'default','created','App\\Models\\Product','created',88,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BLUE WHITING FISH\",\"price\":\"1500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:36','2026-05-23 12:12:36'),(103,'default','created','App\\Models\\Product','created',89,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FISH\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:36','2026-05-23 12:12:36'),(104,'default','created','App\\Models\\Product','created',90,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"FEARLESS\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:36','2026-05-23 12:12:36'),(105,'default','created','App\\Models\\Product','created',91,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Fanta Orange\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:37','2026-05-23 12:12:37'),(106,'default','created','App\\Models\\Product','created',92,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Fanta  apple\",\"price\":\"450.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:37','2026-05-23 12:12:37'),(107,'default','created','App\\Models\\Product','created',93,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ABATOIR  FACILTY CHARGE\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:37','2026-05-23 12:12:37'),(108,'default','created','App\\Models\\Product','created',94,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"DUBIC MALT\",\"price\":\"250.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:38','2026-05-23 12:12:38'),(109,'default','created','App\\Models\\Product','created',95,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Dog food\",\"price\":\"2100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:38','2026-05-23 12:12:38'),(110,'default','created','App\\Models\\Product','created',96,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Dog food\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:39','2026-05-23 12:12:39'),(111,'default','created','App\\Models\\Product','created',97,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"DELIVERY CHARGE\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:39','2026-05-23 12:12:39'),(112,'default','created','App\\Models\\Product','created',98,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COWSKIN\\/LEGS\",\"price\":\"3300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:39','2026-05-23 12:12:39'),(113,'default','created','App\\Models\\Product','created',99,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW UNPROCESSED SKIN\",\"price\":\"3400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:39','2026-05-23 12:12:39'),(114,'default','created','App\\Models\\Product','created',100,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"UNPROCESSED ASSO\",\"price\":\"3800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:40','2026-05-23 12:12:40'),(115,'default','created','App\\Models\\Product','created',101,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW TOZO\",\"price\":\"8200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:40','2026-05-23 12:12:40'),(116,'default','created','App\\Models\\Product','created',102,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TOPSIDE\",\"price\":\"8700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:41','2026-05-23 12:12:41'),(117,'default','created','App\\Models\\Product','created',103,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW TOPRUN\",\"price\":\"7800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:41','2026-05-23 12:12:41'),(118,'default','created','App\\Models\\Product','created',104,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LCow TONGUE\",\"price\":\"7400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:42','2026-05-23 12:12:42'),(119,'default','created','App\\Models\\Product','created',105,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Cow Throat\",\"price\":\"4100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:42','2026-05-23 12:12:42'),(120,'default','created','App\\Models\\Product','created',106,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"T-BONE\",\"price\":\"25000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:43','2026-05-23 12:12:43'),(121,'default','created','App\\Models\\Product','created',107,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COWTAIL\",\"price\":\"7900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:43','2026-05-23 12:12:43'),(122,'default','created','App\\Models\\Product','created',108,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW SPLEEN\",\"price\":\"3000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:44','2026-05-23 12:12:44'),(123,'default','created','App\\Models\\Product','created',109,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"TOPSIDE-RUN-ROSTO-FILLET\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:44','2026-05-23 12:12:44'),(124,'default','created','App\\Models\\Product','created',110,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SHEREDDED BEEF\",\"price\":\"9100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:45','2026-05-23 12:12:45'),(125,'default','created','App\\Models\\Product','created',111,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SHORT-RIB\",\"price\":\"7700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:45','2026-05-23 12:12:45'),(126,'default','created','App\\Models\\Product','created',112,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SHIN\",\"price\":\"8200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:46','2026-05-23 12:12:46'),(127,'default','created','App\\Models\\Product','created',113,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"cow shaki\",\"price\":\"10000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:46','2026-05-23 12:12:46'),(128,'default','created','App\\Models\\Product','created',114,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ROSTOR\",\"price\":\"7700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:46','2026-05-23 12:12:46'),(129,'default','created','App\\Models\\Product','created',115,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ROSTO\",\"price\":\"8700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:47','2026-05-23 12:12:47'),(130,'default','created','App\\Models\\Product','created',116,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"RIB-EYE\",\"price\":\"25000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:47','2026-05-23 12:12:47'),(131,'default','created','App\\Models\\Product','created',117,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW SKIN\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:48','2026-05-23 12:12:48'),(132,'default','created','App\\Models\\Product','created',118,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW SKIN\",\"price\":\"5100.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:48','2026-05-23 12:12:48'),(133,'default','created','App\\Models\\Product','created',119,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW SKIN\",\"price\":\"4900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:48','2026-05-23 12:12:48'),(134,'default','created','App\\Models\\Product','created',120,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW PENIS\",\"price\":\"4500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:49','2026-05-23 12:12:49'),(135,'default','created','App\\Models\\Product','created',121,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"OX TAIL SPECIAL\",\"price\":\"11000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:49','2026-05-23 12:12:49'),(136,'default','created','App\\Models\\Product','created',122,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Organ (p)\",\"price\":\"4600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:49','2026-05-23 12:12:49'),(137,'default','created','App\\Models\\Product','created',123,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"OFFALS\",\"price\":\"7300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:49','2026-05-23 12:12:49'),(138,'default','created','App\\Models\\Product','created',124,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW NECK\",\"price\":\"4900.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:50','2026-05-23 12:12:50'),(139,'default','created','App\\Models\\Product','created',125,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"MINCED BEEF\",\"price\":\"8700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:50','2026-05-23 12:12:50'),(140,'default','created','App\\Models\\Product','created',126,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LIVER\",\"price\":\"7400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:50','2026-05-23 12:12:50'),(141,'default','created','App\\Models\\Product','created',127,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Live Cow\",\"price\":\"0.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:50','2026-05-23 12:12:50'),(142,'default','created','App\\Models\\Product','created',128,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Boneless Cow leg\",\"price\":\"8600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:51','2026-05-23 12:12:51'),(143,'default','created','App\\Models\\Product','created',129,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BONELESS COW LEG\",\"price\":\"8600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:51','2026-05-23 12:12:51'),(144,'default','created','App\\Models\\Product','created',130,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW LEG\",\"price\":\"5800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:51','2026-05-23 12:12:51'),(145,'default','created','App\\Models\\Product','created',131,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"KIDNEY\",\"price\":\"5500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:52','2026-05-23 12:12:52'),(146,'default','created','App\\Models\\Product','created',132,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW FAT(Ishon)\",\"price\":\"4600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:52','2026-05-23 12:12:52'),(147,'default','created','App\\Models\\Product','created',133,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW HORN\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:52','2026-05-23 12:12:52'),(148,'default','created','App\\Models\\Product','created',134,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW HEAD 2\",\"price\":\"3500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:53','2026-05-23 12:12:53'),(149,'default','created','App\\Models\\Product','created',135,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LCow Head\",\"price\":\"7500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:54','2026-05-23 12:12:54'),(150,'default','created','App\\Models\\Product','created',136,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW FILLET\",\"price\":\"8700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:55','2026-05-23 12:12:55'),(151,'default','created','App\\Models\\Product','created',137,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW FAT 1\",\"price\":\"1200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:55','2026-05-23 12:12:55'),(152,'default','created','App\\Models\\Product','created',138,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW FAT 2\",\"price\":\"2200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:55','2026-05-23 12:12:55'),(153,'default','created','App\\Models\\Product','created',139,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW CARCASS\",\"price\":\"5800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:55','2026-05-23 12:12:55'),(154,'default','created','App\\Models\\Product','created',140,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW BRISKET BONE\",\"price\":\"4500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:55','2026-05-23 12:12:55'),(155,'default','created','App\\Models\\Product','created',141,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LCOW BONES\",\"price\":\"1600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(156,'default','created','App\\Models\\Product','created',142,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW BLOOD\",\"price\":\"4000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(157,'default','created','App\\Models\\Product','created',143,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW BEEF\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(158,'default','created','App\\Models\\Product','created',144,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW BEEF\",\"price\":\"7400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(159,'default','created','App\\Models\\Product','created',145,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COW BEEF\",\"price\":\"7400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(160,'default','created','App\\Models\\Product','created',146,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"COKE\",\"price\":\"500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:56','2026-05-23 12:12:56'),(161,'default','created','App\\Models\\Product','created',147,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN WINGS\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:57','2026-05-23 12:12:57'),(162,'default','created','App\\Models\\Product','created',148,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Chicken Thigh\",\"price\":\"4350.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:57','2026-05-23 12:12:57'),(163,'default','created','App\\Models\\Product','created',149,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SHREDED CHICKEN\",\"price\":\"9000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:57','2026-05-23 12:12:57'),(164,'default','created','App\\Models\\Product','created',150,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN NECK\\/LEG\\/HEAD\",\"price\":\"1700.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:57','2026-05-23 12:12:57'),(165,'default','created','App\\Models\\Product','created',151,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"OROBO\",\"price\":\"6000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:57','2026-05-23 12:12:57'),(166,'default','created','App\\Models\\Product','created',152,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN OLD LAYERS\",\"price\":\"7000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(167,'default','created','App\\Models\\Product','created',153,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN LOCAL\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(168,'default','created','App\\Models\\Product','created',154,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN LOCAL\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(169,'default','created','App\\Models\\Product','created',155,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN LIVE\",\"price\":\"9000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(170,'default','created','App\\Models\\Product','created',156,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"LAPS\",\"price\":\"5400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(171,'default','created','App\\Models\\Product','created',157,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN GIZZARD\",\"price\":\"6500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:58','2026-05-23 12:12:58'),(172,'default','created','App\\Models\\Product','created',158,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Chicken Drumstick\",\"price\":\"6000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:59','2026-05-23 12:12:59'),(173,'default','created','App\\Models\\Product','created',159,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN CUT 4\",\"price\":\"5200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:59','2026-05-23 12:12:59'),(174,'default','created','App\\Models\\Product','created',160,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN COCKEREL LIV\",\"price\":\"9000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:12:59','2026-05-23 12:12:59'),(175,'default','created','App\\Models\\Product','created',161,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN COCKEREL\",\"price\":\"5500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:00','2026-05-23 12:13:00'),(176,'default','created','App\\Models\\Product','created',162,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN CARCAS\",\"price\":\"5400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:00','2026-05-23 12:13:00'),(177,'default','created','App\\Models\\Product','created',163,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN BROILERS\",\"price\":\"5200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:00','2026-05-23 12:13:00'),(178,'default','created','App\\Models\\Product','created',164,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BREAST\",\"price\":\"5400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:00','2026-05-23 12:13:00'),(179,'default','created','App\\Models\\Product','created',165,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BONLESS CHICKEN\",\"price\":\"6200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:00','2026-05-23 12:13:00'),(180,'default','created','App\\Models\\Product','created',166,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CHICKEN GIZZARD\",\"price\":\"4000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:01','2026-05-23 12:13:01'),(181,'default','created','App\\Models\\Product','created',167,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"CAKE PARFAIT\",\"price\":\"2500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:01','2026-05-23 12:13:01'),(182,'default','created','App\\Models\\Product','created',168,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BITER LEMON\",\"price\":\"200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:01','2026-05-23 12:13:01'),(183,'default','created','App\\Models\\Product','created',169,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BIGITROPICAL\",\"price\":\"300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:01','2026-05-23 12:13:01'),(184,'default','created','App\\Models\\Product','created',170,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BIGICOLA\",\"price\":\"300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:01','2026-05-23 12:13:01'),(185,'default','created','App\\Models\\Product','created',171,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"beef Top Shin\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:02','2026-05-23 12:13:02'),(186,'default','created','App\\Models\\Product','created',172,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"BEEF\",\"price\":\"8200.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:02','2026-05-23 12:13:02'),(187,'default','created','App\\Models\\Product','created',173,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Whiteseseme seed\",\"price\":\"2500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:02','2026-05-23 12:13:02'),(188,'default','created','App\\Models\\Product','created',174,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Whitefonio 4kg\",\"price\":\"4800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:03','2026-05-23 12:13:03'),(189,'default','created','App\\Models\\Product','created',175,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Whitefonio 3KG\",\"price\":\"9000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:03','2026-05-23 12:13:03'),(190,'default','created','App\\Models\\Product','created',176,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Whitefonio 2kg\",\"price\":\"2400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:03','2026-05-23 12:13:03'),(191,'default','created','App\\Models\\Product','created',177,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Whitefonio 1kg\",\"price\":\"6850.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:03','2026-05-23 12:13:03'),(192,'default','created','App\\Models\\Product','created',178,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Tamarind\",\"price\":\"3500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:03','2026-05-23 12:13:03'),(193,'default','created','App\\Models\\Product','created',179,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"SMOKED CATFISH\",\"price\":\"4400.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(194,'default','created','App\\Models\\Product','created',180,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Peanut butter\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(195,'default','created','App\\Models\\Product','created',181,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Panga fish half carton\",\"price\":\"22500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(196,'default','created','App\\Models\\Product','created',182,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Panga fish full carton\",\"price\":\"45000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(197,'default','created','App\\Models\\Product','created',183,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Panga fish 5pieces\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(198,'default','created','App\\Models\\Product','created',184,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Panga fish 10pieces\",\"price\":\"12500.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:04','2026-05-23 12:13:04'),(199,'default','created','App\\Models\\Product','created',185,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ABIS METE ZEYTIN OIL\",\"price\":\"60000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:05','2026-05-23 12:13:05'),(200,'default','created','App\\Models\\Product','created',186,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli oil 5lt\",\"price\":\"28000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:05','2026-05-23 12:13:05'),(201,'default','created','App\\Models\\Product','created',187,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli oil 500ml\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:05','2026-05-23 12:13:05'),(202,'default','created','App\\Models\\Product','created',188,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli oil 2.5lt\",\"price\":\"10000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:05','2026-05-23 12:13:05'),(203,'default','created','App\\Models\\Product','created',189,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli oil 2.5lt\",\"price\":\"14000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:06','2026-05-23 12:13:06'),(204,'default','created','App\\Models\\Product','created',190,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli oil 1Ltr\",\"price\":\"10000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:06','2026-05-23 12:13:06'),(205,'default','created','App\\Models\\Product','created',191,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli small\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:06','2026-05-23 12:13:06'),(206,'default','created','App\\Models\\Product','created',192,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Kuli kuli big\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:06','2026-05-23 12:13:06'),(207,'default','created','App\\Models\\Product','created',193,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey comb 1Litr\",\"price\":\"30000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:06','2026-05-23 12:13:06'),(208,'default','created','App\\Models\\Product','created',194,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 5ltrs\",\"price\":\"65000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:07','2026-05-23 12:13:07'),(209,'default','created','App\\Models\\Product','created',195,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 500ml\",\"price\":\"9800.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:07','2026-05-23 12:13:07'),(210,'default','created','App\\Models\\Product','created',196,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 4ltrs\",\"price\":\"16000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:07','2026-05-23 12:13:07'),(211,'default','created','App\\Models\\Product','created',197,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 3ltrs\",\"price\":\"30000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:07','2026-05-23 12:13:07'),(212,'default','created','App\\Models\\Product','created',198,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 2ltrs\",\"price\":\"28000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(213,'default','created','App\\Models\\Product','created',199,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Honey 1ltr\",\"price\":\"19600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(214,'default','created','App\\Models\\Product','created',200,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 5Ltrs\",\"price\":\"18600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(215,'default','created','App\\Models\\Product','created',201,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 500ml\",\"price\":\"1000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(216,'default','created','App\\Models\\Product','created',202,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 4ltrs\",\"price\":\"8000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(217,'default','created','App\\Models\\Product','created',203,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 3ltrs\",\"price\":\"6000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(218,'default','created','App\\Models\\Product','created',204,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 2ltrs\",\"price\":\"8600.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:08','2026-05-23 12:13:08'),(219,'default','created','App\\Models\\Product','created',205,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ABIS GROUNDOIL 2.5l\",\"price\":\"9300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:09','2026-05-23 12:13:09'),(220,'default','created','App\\Models\\Product','created',206,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnut 1ltr\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:09','2026-05-23 12:13:09'),(221,'default','created','App\\Models\\Product','created',207,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnutoil 10Ltrs\",\"price\":\"19000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:09','2026-05-23 12:13:09'),(222,'default','created','App\\Models\\Product','created',208,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Groundnut paste\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(223,'default','created','App\\Models\\Product','created',209,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Ghee 500gm\",\"price\":\"4000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(224,'default','created','App\\Models\\Product','created',210,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 5ltrs\",\"price\":\"25000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(225,'default','created','App\\Models\\Product','created',211,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 500ml\",\"price\":\"4000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(226,'default','created','App\\Models\\Product','created',212,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 4ltrs\",\"price\":\"20000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(227,'default','created','App\\Models\\Product','created',213,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 3ltrs\",\"price\":\"15000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(228,'default','created','App\\Models\\Product','created',214,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 2ltrs\",\"price\":\"10000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(229,'default','created','App\\Models\\Product','created',215,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Coconut oil 1ltr\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(230,'default','created','App\\Models\\Product','created',216,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Brown fonio 4kg\",\"price\":\"3850.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:10','2026-05-23 12:13:10'),(231,'default','created','App\\Models\\Product','created',217,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"ABIS BROWNFONIO 3KG\",\"price\":\"6300.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:11','2026-05-23 12:13:11'),(232,'default','created','App\\Models\\Product','created',218,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Brown Fonio 2kg\",\"price\":\"2000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:11','2026-05-23 12:13:11'),(233,'default','created','App\\Models\\Product','created',219,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Brown Fonio 1kg\",\"price\":\"5000.00\",\"stock_quantity\":0,\"is_active\":true}}',NULL,'2026-05-23 12:13:11','2026-05-23 12:13:11');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `hours_worked` decimal(5,2) DEFAULT NULL,
  `status` enum('present','absent','late','half_day','leave','holiday') NOT NULL DEFAULT 'present',
  `note` text DEFAULT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_id_date_unique` (`user_id`,`date`),
  KEY `attendances_shop_id_foreign` (`shop_id`),
  KEY `attendances_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `attendances_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendances_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_product_sales`
--

DROP TABLE IF EXISTS `batch_product_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_product_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_product_id` bigint(20) unsigned NOT NULL,
  `batch_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `saleable_type` varchar(255) NOT NULL,
  `saleable_id` bigint(20) unsigned NOT NULL,
  `quantity` decimal(12,3) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `cost_allocated` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `gross_profit` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `sold_by` bigint(20) unsigned NOT NULL,
  `sold_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_product_sales_sold_by_foreign` (`sold_by`),
  KEY `batch_product_sales_batch_id_index` (`batch_id`),
  KEY `batch_product_sales_batch_product_id_index` (`batch_product_id`),
  KEY `batch_product_sales_saleable_type_saleable_id_index` (`saleable_type`,`saleable_id`),
  KEY `batch_product_sales_shop_id_sold_at_index` (`shop_id`,`sold_at`),
  CONSTRAINT `batch_product_sales_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `supply_batches` (`id`),
  CONSTRAINT `batch_product_sales_batch_product_id_foreign` FOREIGN KEY (`batch_product_id`) REFERENCES `batch_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_product_sales_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  CONSTRAINT `batch_product_sales_sold_by_foreign` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_product_sales`
--

LOCK TABLES `batch_product_sales` WRITE;
/*!40000 ALTER TABLE `batch_product_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_product_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_products`
--

DROP TABLE IF EXISTS `batch_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `batch_item_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `animal_type` varchar(255) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `receipt_name` varchar(255) NOT NULL,
  `pricing_type` enum('fixed','weight') NOT NULL DEFAULT 'fixed',
  `unit` varchar(255) NOT NULL DEFAULT 'piece',
  `quantity_available` decimal(12,3) NOT NULL DEFAULT 0.000,
  `quantity_sold` decimal(12,3) NOT NULL DEFAULT 0.000,
  `quantity_wasted` decimal(12,3) NOT NULL DEFAULT 0.000,
  `target_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_price` decimal(12,2) DEFAULT NULL,
  `cost_allocation_per_unit` decimal(12,4) NOT NULL DEFAULT 0.0000,
  `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_products_batch_item_id_foreign` (`batch_item_id`),
  KEY `batch_products_shop_id_is_active_index` (`shop_id`,`is_active`),
  KEY `batch_products_batch_id_animal_type_index` (`batch_id`,`animal_type`),
  KEY `batch_products_shop_id_batch_id_index` (`shop_id`,`batch_id`),
  CONSTRAINT `batch_products_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `supply_batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_products_batch_item_id_foreign` FOREIGN KEY (`batch_item_id`) REFERENCES `supply_batch_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `batch_products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_products`
--

LOCK TABLES `batch_products` WRITE;
/*!40000 ALTER TABLE `batch_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#C0392B',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_shop_id_foreign` (`shop_id`),
  CONSTRAINT `categories_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,1,'Imported','#C0392B',1,'2026-05-23 12:12:14','2026-05-23 12:12:14');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_debts`
--

DROP TABLE IF EXISTS `customer_debts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_debts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `amount_owed` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `status` enum('outstanding','partial','settled','written_off') NOT NULL DEFAULT 'outstanding',
  `notes` text DEFAULT NULL,
  `settled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_debts_shop_id_foreign` (`shop_id`),
  KEY `customer_debts_customer_id_foreign` (`customer_id`),
  KEY `customer_debts_sale_id_foreign` (`sale_id`),
  KEY `customer_debts_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `customer_debts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_debts_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`),
  CONSTRAINT `customer_debts_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_debts_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_debts`
--

LOCK TABLES `customer_debts` WRITE;
/*!40000 ALTER TABLE `customer_debts` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_debts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `loyalty_points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_spent` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_debt` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date_of_birth` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_shop_id_foreign` (`shop_id`),
  CONSTRAINT `customers_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,1,'Call customers',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: CALLCUSTOMER',1,'2026-05-22 10:59:40','2026-05-22 10:59:40',NULL),(2,1,'ONLINE ORDERS',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ONLINE ORDERS',1,'2026-05-22 10:59:40','2026-05-22 10:59:40',NULL),(3,1,'MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: MAISUYA',1,'2026-05-22 10:59:41','2026-05-22 10:59:41',NULL),(4,1,'MUSTAPHA MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: MUSTAPHA MEAT',1,'2026-05-22 10:59:41','2026-05-22 10:59:41',NULL),(5,1,'OMAJE',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: OMAJE',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(6,1,'LATEEF MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: LATEEF MEAT',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(7,1,'KAMILU MEAt',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: KAMILU MEAt',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(8,1,'baba bolu',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: baba bolu',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(9,1,'zayyanu meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: zayyanu meat',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(10,1,'Toyin meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Toyin meat',1,'2026-05-22 10:59:42','2026-05-22 10:59:42',NULL),(11,1,'AWAISU MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AWAISU MEAT',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(12,1,'CZARS BUKKA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: CZARS BUKKA',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(13,1,'TAWA UNPROCESSED',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: TAWA UNPROCESSED',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(14,1,'AISHA UNPROCESSED',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AISHA UNPROCESSED',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(15,1,'IYA MUJIB',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA MUJIB',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(16,1,'wdwdfe',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya abi',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(17,1,'TAIWO MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: TAIWO MEAT',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(18,1,'Abbey Ajah Meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Abbey Ajah Meat',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(19,1,'IBRAHIM MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IBRAHIM MEAT',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(20,1,'SHEHU MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SHEHU MEAT',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(21,1,'JVGUHUIKU',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: YINKA',1,'2026-05-22 10:59:43','2026-05-22 10:59:43',NULL),(22,1,'FIGO',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: FIGO',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(23,1,'baba tunde meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: baba tunde meat',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(24,1,'Ope meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Ope meat',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(25,1,'Abana Meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Abana Meat',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(26,1,'iya ahmed meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya ahmed meat',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(27,1,'KATE ACHODA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: KAREEN MEAT',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(28,1,'UMAR MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: UMAR MEAT',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(29,1,'-W',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: FISH SALES',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(30,1,'ILA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ILA',1,'2026-05-22 10:59:44','2026-05-22 10:59:44',NULL),(31,1,'Tolu',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Tolu',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(32,1,'SK MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SK MEAT',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(33,1,'Moyisola',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Moyisola',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(34,1,'Ariyo unprocessed',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Ariyo unprocessed',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(35,1,'IYA NEIMU',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: KAUSARAT',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(36,1,'ola adetunji meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ola adetunji',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(37,1,'SADIQ MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SADIQ MEAT',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(38,1,'ALAJA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ALAJA',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(39,1,'IYA  KADIJA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA  KADIJA',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(40,1,'ola leken meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ola leken meat',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(41,1,'mr balogun',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: mr balogun',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(42,1,'ALAJI',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ALAJI',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(43,1,'SAHEED',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SAHEED',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(44,1,'IYA ALBARKA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA ALBARKA',1,'2026-05-22 10:59:45','2026-05-22 10:59:45',NULL),(45,1,'CHAIRMAN',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: CHAIRMAN',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(46,1,'BALOGUN 2',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BALOGUN 2',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(47,1,'AYO MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AYO MEAT',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(48,1,'IYA SALAMI',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA SALAMI',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(49,1,'BISI COW SKIN',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BISI COW SKIN',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(50,1,'BRIGHTESS B GRILLS',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BUDGETFOODSTUFF',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(51,1,'ENKAY FOODIES',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ENKAY FOODIES',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(52,1,'AUTHORITY',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AUTHORITY',1,'2026-05-22 10:59:46','2026-05-22 10:59:46',NULL),(53,1,'ODUNAYO',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ODUNAYO',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(54,1,'Mojisola unprocess',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Mojisola unprocess',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(55,1,'BLESSING UNPROCESED',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BLESSING',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(56,1,'Hassan Abis',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Hassan Abis',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(57,1,'iya alfa meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya alfa meat',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(58,1,'TOPKE',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: TOpe meat',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(59,1,'iya fatiya meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya fatiya meat',1,'2026-05-22 10:59:47','2026-05-22 10:59:47',NULL),(60,1,'HADIZAT MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: HADIZAT MEAT',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(61,1,'Sunday',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Sunday Ajah',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(62,1,'iya aduni meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya aduni meat',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(63,1,'staff',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: staff',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(64,1,'USMAN MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: USMAN MEAT',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(65,1,'P.K',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: P.K',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(66,1,'SHEGU MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SHEGU MEAT',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(67,1,'Shu\'ibu Meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Bola unprocess',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(68,1,'IDERA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IDERA',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(69,1,'iYA FARUCK',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iYA FARUCK',1,'2026-05-22 10:59:48','2026-05-22 10:59:48',NULL),(70,1,'AROLE MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AROLE MEAT',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(71,1,'SARAH',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: SARAH',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(72,1,'RILWAN MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: RILWAN MEAT',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(73,1,'UNPROCESSED COWSKIN',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: UNPROCESSED COWSKIN',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(74,1,'1123 BUKKA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: 1123 BUKKA',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(75,1,'shukurat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: shukurat',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(76,1,'ridman',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ridwan meat',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(77,1,'IYA ROMOKE',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA ROMOKE',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(78,1,'Alhaji Meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Alhaji Usman Meat',1,'2026-05-22 10:59:49','2026-05-22 10:59:49',NULL),(79,1,'amala campus',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: amala campus',1,'2026-05-22 10:59:50','2026-05-22 10:59:50',NULL),(80,1,'HARDEST',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: HARDEST',1,'2026-05-22 10:59:50','2026-05-22 10:59:50',NULL),(81,1,'AHMED MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AFEEZ MEAT',1,'2026-05-22 10:59:51','2026-05-22 10:59:51',NULL),(82,1,'Annabela',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Annabela',1,'2026-05-22 10:59:51','2026-05-22 10:59:51',NULL),(83,1,'AMISU MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AMISU MEAT',1,'2026-05-22 10:59:51','2026-05-22 10:59:51',NULL),(84,1,'Vivian SR Bar',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Vivian SR Bar',1,'2026-05-22 10:59:52','2026-05-22 10:59:52',NULL),(85,1,'Royal taste',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Royal taste',1,'2026-05-22 10:59:54','2026-05-22 10:59:54',NULL),(86,1,'A.Y OGOMBO',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: A.Y OGOMBO',1,'2026-05-22 10:59:54','2026-05-22 10:59:54',NULL),(87,1,'Regular customers',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: REGULARCUSTOMERS',1,'2026-05-22 10:59:54','2026-05-22 10:59:54',NULL),(88,1,'ALJIYA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ALJIYA',1,'2026-05-22 10:59:54','2026-05-22 10:59:54',NULL),(89,1,'ARE MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ARE MEAT',1,'2026-05-22 10:59:55','2026-05-22 10:59:55',NULL),(90,1,'BOKOTO FOOD AFFAIR',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BOKOTO FOOD AFFAIR',1,'2026-05-22 10:59:55','2026-05-22 10:59:55',NULL),(91,1,'m',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: madam oby',1,'2026-05-22 10:59:55','2026-05-22 10:59:55',NULL),(92,1,'J BOY',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: J BOY',1,'2026-05-22 10:59:56','2026-05-22 10:59:56',NULL),(93,1,'ALIU',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ALIU',1,'2026-05-22 10:59:57','2026-05-22 10:59:57',NULL),(94,1,'-',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: -',1,'2026-05-22 10:59:57','2026-05-22 10:59:57',NULL),(95,1,'RAZAQH BONE',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: RAZAQH BONE',1,'2026-05-22 10:59:58','2026-05-22 10:59:58',NULL),(96,1,'WALK IN CUSTOMER',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: WALK IN CUSTOMER',1,'2026-05-22 10:59:59','2026-05-22 10:59:59',NULL),(97,1,'NIKE MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: NIKE MEAT',1,'2026-05-22 10:59:59','2026-05-22 10:59:59',NULL),(98,1,'Olaide',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Olaide',1,'2026-05-22 10:59:59','2026-05-22 10:59:59',NULL),(99,1,'IYA MAMUBA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA MAMUBA',1,'2026-05-22 11:00:00','2026-05-22 11:00:00',NULL),(100,1,'ISMAIL MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ISMAIL MEAT',1,'2026-05-22 11:00:00','2026-05-22 11:00:00',NULL),(101,1,'Mohamed',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Mohamed',1,'2026-05-22 11:00:00','2026-05-22 11:00:00',NULL),(102,1,'ALAUSA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: ALAUSA',1,'2026-05-22 11:00:01','2026-05-22 11:00:01',NULL),(103,1,'femi driver',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: femi driver',1,'2026-05-22 11:00:03','2026-05-22 11:00:03',NULL),(104,1,'jamiu meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: jamiu meat',1,'2026-05-22 11:00:04','2026-05-22 11:00:04',NULL),(105,1,'WAHEED MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: WAHEED MEAT',1,'2026-05-22 11:00:06','2026-05-22 11:00:06',NULL),(106,1,'AKONI MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AKONI MUSTAPHA',1,'2026-05-22 11:00:07','2026-05-22 11:00:07',NULL),(107,1,'melody meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: melody meat',1,'2026-05-22 11:00:08','2026-05-22 11:00:08',NULL),(108,1,'IYA IBRAhim meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: IYA IBRAhim meat',1,'2026-05-22 11:00:08','2026-05-22 11:00:08',NULL),(109,1,'idomu',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: idomu',1,'2026-05-22 11:00:10','2026-05-22 11:00:10',NULL),(110,1,'TOHEEB MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: TOHEEB MEAT',1,'2026-05-22 11:00:10','2026-05-22 11:00:10',NULL),(111,1,'iya isameila',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya isameila',1,'2026-05-22 11:00:10','2026-05-22 11:00:10',NULL),(112,1,'R',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: R',1,'2026-05-22 11:00:11','2026-05-22 11:00:11',NULL),(113,1,'TOBI MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: TOBI MEAT',1,'2026-05-22 11:00:11','2026-05-22 11:00:11',NULL),(114,1,'iya dumi',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: iya dumi',1,'2026-05-22 11:00:12','2026-05-22 11:00:12',NULL),(115,1,'LAIDE MEAT',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: LAIDE MEAT',1,'2026-05-22 11:00:13','2026-05-22 11:00:13',NULL),(116,1,'Radix Event',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: BOBO',1,'2026-05-22 11:00:13','2026-05-22 11:00:13',NULL),(117,1,'AFEEZ ILA',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: AFEEZ ILA',1,'2026-05-22 11:00:13','2026-05-22 11:00:13',NULL),(118,1,'Alade meat',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Alade meat',1,'2026-05-22 11:00:13','2026-05-22 11:00:13',NULL),(119,1,'Mausa',NULL,NULL,NULL,0.00,0.00,0.00,NULL,'Imported from Sage50. ID: Mausa',1,'2026-05-22 11:00:14','2026-05-22 11:00:14',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#C0392B',
  `accepts_orders` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_shop_id_foreign` (`shop_id`),
  CONSTRAINT `departments_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('rent','utilities','salaries','supplies','maintenance','transport','marketing','equipment','taxes','other') NOT NULL DEFAULT 'other',
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_shop_id_foreign` (`shop_id`),
  KEY `expenses_recorded_by_foreign` (`recorded_by`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`),
  CONSTRAINT `expenses_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'piece',
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,NULL,'Cow Head',1.000,'kg',2500.00,0.00,2500.00,0,'2026-05-19 10:43:45','2026-05-19 10:43:45'),(2,2,NULL,'25 Kilos of paint',1.000,'kg',30000.00,0.00,30000.00,0,'2026-05-19 11:07:44','2026-05-19 11:07:44');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `type` enum('invoice','proforma','receipt','quote') NOT NULL DEFAULT 'invoice',
  `status` enum('draft','sent','paid','partial','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `client_name` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `client_address` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_shop_id_foreign` (`shop_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_customer_id_foreign` (`customer_id`),
  KEY `invoices_sale_id_foreign` (`sale_id`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,1,NULL,NULL,'INV20260001','invoice','sent','Tracy','09090988787','tracy@gmail.com',NULL,2500.00,0.00,0.00,0.00,2500.00,0.00,NULL,NULL,'2026-05-19',NULL,NULL,'2026-05-19 10:43:43','2026-05-19 11:00:46',NULL),(2,1,1,NULL,NULL,'INV20260002','invoice','paid','Maxwel','09065654543','maxwell@gmail.com',NULL,30000.00,0.00,0.00,0.00,30000.00,30000.00,NULL,NULL,'2026-05-19',NULL,'2026-05-19 11:09:50','2026-05-19 11:07:44','2026-05-19 11:09:50',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kitchen_orders`
--

DROP TABLE IF EXISTS `kitchen_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kitchen_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `taken_by` bigint(20) unsigned NOT NULL,
  `table_number` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','cooking','ready','dispatched','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `fired_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ready_at` timestamp NULL DEFAULT NULL,
  `dispatched_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kitchen_orders_sale_id_foreign` (`sale_id`),
  KEY `kitchen_orders_shop_id_foreign` (`shop_id`),
  KEY `kitchen_orders_taken_by_foreign` (`taken_by`),
  KEY `kitchen_orders_department_id_foreign` (`department_id`),
  CONSTRAINT `kitchen_orders_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kitchen_orders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kitchen_orders_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kitchen_orders_taken_by_foreign` FOREIGN KEY (`taken_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kitchen_orders`
--

LOCK TABLES `kitchen_orders` WRITE;
/*!40000 ALTER TABLE `kitchen_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `kitchen_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `type` enum('annual','sick','maternity','paternity','emergency','unpaid','other') NOT NULL DEFAULT 'annual',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_requested` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_user_id_foreign` (`user_id`),
  KEY `leave_requests_shop_id_foreign` (`shop_id`),
  KEY `leave_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_requests`
--

LOCK TABLES `leave_requests` WRITE;
/*!40000 ALTER TABLE `leave_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_16_160718_create_permission_tables',1),(5,'2026_04_16_160733_create_activity_log_table',1),(6,'2026_04_16_160734_add_event_column_to_activity_log_table',1),(7,'2026_04_16_160735_add_batch_uuid_column_to_activity_log_table',1),(8,'2026_04_17_132122_add_shop_fields_to_users_table',1),(9,'2026_04_18_031341_create_shops_table',2),(10,'2026_04_18_031614_add_foreign_key_shop_id_to_users_table',2),(11,'2026_04_18_073317_create_categories_table',2),(12,'2026_04_18_073327_create_products_table',2),(13,'2026_04_18_073334_create_till_sessions_table',2),(14,'2026_04_18_073342_create_sales_table',2),(15,'2026_04_18_073352_create_sale_items_table',2),(16,'2026_04_18_073400_create_stock_movements_table',2),(17,'2026_04_18_073407_create_refund_requests_table',2),(18,'2026_04_18_073414_create_kitchen_orders_table',2),(19,'2026_04_20_091810_create_departments_table',2),(20,'2026_04_20_091819_create_customers_table',2),(21,'2026_04_20_091827_create_customer_debts_table',2),(22,'2026_04_20_091835_create_invoices_table',2),(23,'2026_04_20_091844_create_invoice_items_table',2),(24,'2026_04_20_091851_create_expenses_table',2),(25,'2026_04_20_091859_create_staff_profiles_table',2),(26,'2026_04_20_091906_create_attendances_table',2),(27,'2026_04_20_091913_create_leave_requests_table',2),(28,'2026_04_20_091921_create_payrolls_table',2),(29,'2026_04_20_091928_create_projects_table',2),(30,'2026_04_20_091937_create_project_items_table',2),(31,'2026_04_20_091945_create_online_orders_table',2),(32,'2026_04_21_140047_add_department_id_to_kitchen_orders_table',2),(33,'2026_04_22_221436_add_branding_to_shops_table',2),(34,'2026_05_01_093744_create_personal_access_tokens_table',2),(35,'2026_05_09_000001_create_suppliers_table',2),(36,'2026_05_09_000002_create_supply_batches_table',2),(37,'2026_05_09_000003_create_supply_batch_items_table',2),(38,'2026_05_09_000004_create_batch_products_table',2),(39,'2026_05_09_000005_create_batch_product_sales_table',2),(40,'2026_05_09_000006_create_supplier_payments_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',2),(2,'App\\Models\\User',1),(3,'App\\Models\\User',5),(6,'App\\Models\\User',3),(7,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_orders`
--

DROP TABLE IF EXISTS `online_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash_on_delivery','bank_transfer','card','wallet') NOT NULL DEFAULT 'cash_on_delivery',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `status` enum('new','confirmed','preparing','ready','dispatched','delivered','cancelled') NOT NULL DEFAULT 'new',
  `notes` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `online_orders_order_number_unique` (`order_number`),
  KEY `online_orders_shop_id_foreign` (`shop_id`),
  KEY `online_orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `online_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `online_orders_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_orders`
--

LOCK TABLES `online_orders` WRITE;
/*!40000 ALTER TABLE `online_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `online_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payrolls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `processed_by` bigint(20) unsigned NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` year(4) NOT NULL,
  `days_worked` int(11) NOT NULL DEFAULT 0,
  `days_absent` int(11) NOT NULL DEFAULT 0,
  `days_late` int(11) NOT NULL DEFAULT 0,
  `base_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','paid') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payrolls_user_id_month_year_unique` (`user_id`,`month`,`year`),
  KEY `payrolls_shop_id_foreign` (`shop_id`),
  KEY `payrolls_processed_by_foreign` (`processed_by`),
  CONSTRAINT `payrolls_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payrolls_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'pos.sell','web','2026-05-09 12:53:40','2026-05-09 12:53:40'),(2,'pos.refund.request','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(3,'pos.refund.approve.small','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(4,'pos.refund.approve.any','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(5,'pos.void','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(6,'pos.discount.small','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(7,'pos.discount.any','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(8,'pos.credit','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(9,'inventory.view','web','2026-05-09 12:53:41','2026-05-09 12:53:41'),(10,'inventory.create','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(11,'inventory.adjust','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(12,'inventory.transfer','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(13,'inventory.restock.request','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(14,'till.open','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(15,'till.close','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(16,'till.reconcile','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(17,'till.view.own','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(18,'till.view.all','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(19,'kot.create','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(20,'kot.modify','web','2026-05-09 12:53:42','2026-05-09 12:53:42'),(21,'kot.cancel','web','2026-05-09 12:53:43','2026-05-09 12:53:43'),(22,'kot.monitor','web','2026-05-09 12:53:43','2026-05-09 12:53:43'),(23,'kot.dispatch','web','2026-05-09 12:53:43','2026-05-09 12:53:43'),(24,'tables.view','web','2026-05-09 12:53:43','2026-05-09 12:53:43'),(25,'tables.assign','web','2026-05-09 12:53:43','2026-05-09 12:53:43'),(26,'tables.manage','web','2026-05-09 12:53:44','2026-05-09 12:53:44'),(27,'hr.staff.view','web','2026-05-09 12:53:44','2026-05-09 12:53:44'),(28,'hr.staff.create','web','2026-05-09 12:53:44','2026-05-09 12:53:44'),(29,'hr.attendance.view','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(30,'hr.attendance.manage','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(31,'hr.shifts.manage','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(32,'hr.leave.approve','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(33,'hr.payroll.run','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(34,'hr.payroll.view','web','2026-05-09 12:53:45','2026-05-09 12:53:45'),(35,'hr.disciplinary','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(36,'staff.suspend','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(37,'staff.send.home','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(38,'finance.expense.create','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(39,'finance.expense.approve','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(40,'finance.pl.branch','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(41,'finance.pl.all','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(42,'finance.export','web','2026-05-09 12:53:46','2026-05-09 12:53:46'),(43,'crm.view','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(44,'crm.manage','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(45,'crm.debt.record','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(46,'reports.branch','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(47,'reports.all','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(48,'system.shops.manage','web','2026-05-09 12:53:47','2026-05-09 12:53:47'),(49,'system.users.manage','web','2026-05-09 12:53:48','2026-05-09 12:53:48'),(50,'system.roles.manage','web','2026-05-09 12:53:48','2026-05-09 12:53:48'),(51,'system.logs.all','web','2026-05-09 12:53:48','2026-05-09 12:53:48'),(52,'system.impersonate','web','2026-05-09 12:53:49','2026-05-09 12:53:49');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `unit` enum('piece','kg','gram','litre','ml','plate','portion','pack','bottle') NOT NULL DEFAULT 'piece',
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `track_stock` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_shop_id_foreign` (`shop_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'zobo','Zobo',1200.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:15','2026-05-23 12:12:15',NULL),(2,1,1,'YO BERRY SMALL','YO BERRY SMALL',700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:15','2026-05-23 12:12:15',NULL),(3,1,1,'YO BERRY MEDI','YO BERRY MEDI',1000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(4,1,1,'YO BERRY BIG','YO BERRY BIG',1500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(5,1,1,'YO BERRY','YO BERRY',1500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(6,1,1,'WATER','WATER',200.00,60.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(7,1,1,'Vee-Saugage','Vee-Saugage',700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(8,1,1,'Vee-Pie','Vee-Pie',800.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(9,1,1,'Vee-Eggroll','Vee-EggRoll',500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:16','2026-05-23 12:12:16',NULL),(10,1,1,'VEE-DOUGHNUT','VEE-DOUGHNUT',500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(11,1,1,'VEE-BUNS','VEE-BUNS',200.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(12,1,1,'TURKEY WINGS','TURKEY WINGS',11000.00,3350.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(13,1,1,'TURKEY LOCAL','TURKEY LOCAL',9100.00,4499.94,0,5,'kg',NULL,1,1,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(14,1,1,'TURKEY LIVE','TURKEY LIVE',0.00,19801.98,0,5,'kg',NULL,1,0,'2026-05-23 12:12:17','2026-05-23 15:51:58',NULL),(15,1,1,'TURKEY LAPS','TURKEY LAPS',2600.00,2200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(16,1,1,'GIZZARD','TURKEY GIZZARD',8500.00,2800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(17,1,1,'TURKEY BLANKET','TURKEY BLANKET',9500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:17','2026-05-23 12:12:17',NULL),(18,1,1,'TURKEY ASSORTED','TURKEY ASSORTED',0.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(19,1,1,'GIZZARD','TURKEY',9600.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(20,1,1,'transportation of goods & serv','TRANSPORTS CHARGE',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:18','2026-05-23 15:51:44',NULL),(21,1,1,'TISANE TEA','TISANE TEA',9200.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(22,1,1,'Tigger-Nut Drinks','Tigger-Nut',1700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(23,1,1,'teem','Teem',350.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(24,1,1,'SPRITE','SPRITE',450.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:18','2026-05-23 12:12:18',NULL),(25,1,1,'SOFT DRINKS','SOFT DRINKS SALES',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(26,1,1,'SMOOVE','SMOOV',150.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(27,1,1,'seafood shrimp','Shrimps',4000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(28,1,1,'Prawn','SEAFOOD PRAWN',22000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(29,1,1,'SeaFood Periwinkle','SeaFood Periwinkle',1800.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(30,1,1,'seafood crab','Seafood Crab',3500.00,2552.73,0,5,'kg',NULL,1,1,'2026-05-23 12:12:19','2026-05-23 12:12:19',NULL),(31,1,1,'RAM MEAT','RAM MEAT',10000.00,2045.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:20','2026-05-23 12:12:20',NULL),(32,1,1,'LIVE RAM','RAM LIVE\\',0.00,85800.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:20','2026-05-23 15:51:58',NULL),(33,1,1,'RAM LEG 4 PIECES','RAM LEG',1000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:20','2026-05-23 12:12:20',NULL),(34,1,1,'LAMB CHOP','RAM LAMB CHOP',17100.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:20','2026-05-23 12:12:20',NULL),(35,1,1,'RAM HEAD','RAM HEAD',5000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:20','2026-05-23 12:12:20',NULL),(36,1,1,'RAM BONES','RAM BONES',0.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:20','2026-05-23 12:12:20',NULL),(37,1,1,'RAM ASSORTED','RAM ASSORTED',5100.00,2045.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(38,1,1,'PREPARATION','PREPARATION',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:21','2026-05-23 15:51:44',NULL),(39,1,1,'PEPSI LITE','pepsi lite',250.00,87.50,0,5,'kg',NULL,1,1,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(40,1,1,'PEPSI','PEPSI',500.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(41,1,1,'PAFE','PAFE',2500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(42,1,1,'NYLON','NYLON',100.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(43,1,1,'NUTRIYO','NUTRYO',500.00,220.83,0,5,'kg',NULL,1,1,'2026-05-23 12:12:21','2026-05-23 12:12:21',NULL),(44,1,1,'NUTRIPINEAPPLE','NUTRIPINAPPLE',500.00,183.33,0,5,'kg',NULL,1,1,'2026-05-23 12:12:22','2026-05-23 12:12:22',NULL),(45,1,1,'NUTRIMILK','NUTRIMILK',600.00,241.67,0,5,'kg',NULL,1,1,'2026-05-23 12:12:22','2026-05-23 12:12:22',NULL),(46,1,1,'NUTRICHOCO','NUTRICHOCO',800.00,300.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:22','2026-05-23 12:12:22',NULL),(47,1,1,'NUTRIAPPLE','NUTRIAPPLE',500.00,200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:22','2026-05-23 12:12:22',NULL),(48,1,1,'MIRINDA','MIRINDA',350.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:22','2026-05-23 12:12:22',NULL),(49,1,1,'MALTINA PLASTICS','MALTINAPLASTICS',700.00,191.67,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(50,1,1,'MALTINA CAN','MALTINACAN',500.00,220.83,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(51,1,1,'MALTA GUINESS','MALTAGUINESS',800.00,220.83,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(52,1,1,'ladder','LADDER',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:23','2026-05-23 15:51:44',NULL),(53,1,1,'LACACERA','LACASERA',300.00,100.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(54,1,1,'Honey 500ml','kulikuli oil',6500.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(55,1,1,'KOMANDO','KOMANDO',250.00,116.67,0,5,'kg',NULL,1,1,'2026-05-23 12:12:23','2026-05-23 12:12:23',NULL),(56,1,1,'ice block sales','ICE BLOCK SALES',400.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:24','2026-05-23 12:12:24',NULL),(57,1,1,'HIBISCUS TEA','HIBISCUS TEA',4500.00,4500.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:24','2026-05-23 12:12:24',NULL),(58,1,1,'GUINEA LIVE','GUINEA FOWL LIVE',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:25','2026-05-23 15:51:58',NULL),(59,1,1,'GUINEA FOWL','GUINEA',3000.00,7075.47,0,5,'kg',NULL,1,1,'2026-05-23 12:12:26','2026-05-23 12:12:26',NULL),(60,1,1,'GRANDING','GRANDING',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:27','2026-05-23 15:51:44',NULL),(61,1,1,'GOAT UNPROCESSED ASSORTED','GOATUNPROCESSEDASSOR',3600.00,2476.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:27','2026-05-23 12:12:27',NULL),(62,1,1,'GOAT MEAT BONELESS','GOAT MEAT BONELESS',15000.00,2725.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:27','2026-05-23 12:12:27',NULL),(63,1,1,'GOAT MEAT','GOAT MEAT',9500.00,2725.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:27','2026-05-23 12:12:27',NULL),(64,1,1,'LIVE GOAT','GOAT LIVE',0.00,89363.64,0,5,'kg',NULL,1,0,'2026-05-23 12:12:27','2026-05-23 15:51:58',NULL),(65,1,1,'GOAT LEG','GOAT LEG',400.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:28','2026-05-23 12:12:28',NULL),(66,1,1,'GOAT HEAD','GOAT HEAD',5000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:28','2026-05-23 12:12:28',NULL),(67,1,1,'503','GOAT BRISKETBONE',2000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:28','2026-05-23 12:12:28',NULL),(68,1,1,'GOAT BONES','GOAT BONES',0.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:28','2026-05-23 12:12:28',NULL),(69,1,1,'GOAT ASSORTED','GOAT ASSORTED',5100.00,2476.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:29','2026-05-23 12:12:29',NULL),(70,1,1,'GOAT MEAT','GOAT',9500.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:29','2026-05-23 15:51:33',NULL),(71,1,1,'Full Cow Leg','FULL COW LEG',7800.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:30','2026-05-23 12:12:30',NULL),(72,1,1,'FRESH YOGURT','FRESH YOGURT',600.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:30','2026-05-23 12:12:30',NULL),(73,1,1,'FREE','FREE',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:30','2026-05-23 15:51:44',NULL),(74,1,1,'TITUS PINK ROPE FISH','FISH TITUS PINK ROPE',1900.00,1352.50,0,5,'kg',NULL,1,1,'2026-05-23 12:12:30','2026-05-23 12:12:30',NULL),(75,1,1,'FISH TITUS','FISH TITUS',7900.00,1802.50,0,5,'kg',NULL,1,1,'2026-05-23 12:12:31','2026-05-23 12:12:31',NULL),(76,1,1,'TILAPIA','FISH TILAPIA',4600.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:31','2026-05-23 12:12:31',NULL),(77,1,1,'ROCK FISH','FISH ROCK',3150.00,1750.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:31','2026-05-23 12:12:31',NULL),(78,1,1,'FISH REDPACU','FISH REDPACU',4500.00,1650.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:31','2026-05-23 12:12:31',NULL),(79,1,1,'FISH LADY','FISH LADY',0.00,1200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:31','2026-05-23 12:12:31',NULL),(80,1,1,'FISH HMK','FISH HMK',4800.00,940.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:32','2026-05-23 12:12:32',NULL),(81,1,1,'FISH SHAWA','FISH HERRING',3700.00,935.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:32','2026-05-23 12:12:32',NULL),(82,1,1,'HAKEFIISH','FISH HAQKE',0.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:32','2026-05-23 12:12:32',NULL),(83,1,1,'HAKE FISH','FISH HAKE',4900.00,1500.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:32','2026-05-23 12:12:32',NULL),(84,1,1,'FISH CROCKER','FISH CROCKER',5600.00,2250.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:33','2026-05-23 12:12:33',NULL),(85,1,1,'CASABLACA','FISH CASABLANCA',3000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:33','2026-05-23 12:12:33',NULL),(86,1,1,'CARTFISH','FISH CART',4400.00,1500.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:34','2026-05-23 12:12:34',NULL),(87,1,1,'FISH BWHITING','FISH BWHITING',0.00,1150.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:35','2026-05-23 12:12:35',NULL),(88,1,1,'BLUE WHITING FISH','FISH BLUE WHITING',1500.00,1175.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:35','2026-05-23 12:12:35',NULL),(89,1,1,'FISH','FISH',0.00,1540.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:36','2026-05-23 12:12:36',NULL),(90,1,1,'FEARLESS','FEARLESS',500.00,195.83,0,5,'kg',NULL,1,1,'2026-05-23 12:12:36','2026-05-23 12:12:36',NULL),(91,1,1,'Fanta Orange','FANTAORANGE',500.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:37','2026-05-23 12:12:37',NULL),(92,1,1,'Fanta  apple','FANTAAPPLE',450.00,125.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:37','2026-05-23 12:12:37',NULL),(93,1,1,'ABATOIR  FACILTY CHARGE','FACILITY CHARGE',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:37','2026-05-23 15:51:44',NULL),(94,1,1,'DUBIC MALT','DUBICMALT',250.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:38','2026-05-23 12:12:38',NULL),(95,1,1,'Dog food','Dog Food',2100.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:38','2026-05-23 12:12:38',NULL),(96,1,1,'Dog food','Dof Food',2000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:38','2026-05-23 12:12:38',NULL),(97,1,1,'DELIVERY CHARGE','DELIVERY CHARGE',0.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:39','2026-05-23 15:51:44',NULL),(98,1,1,'COWSKIN/LEGS','COWSKIN/LEGS',3300.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:39','2026-05-23 12:12:39',NULL),(99,1,1,'COW UNPROCESSED SKIN','COW UNPROCESSED SKIN',3400.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:39','2026-05-23 12:12:39',NULL),(100,1,1,'UNPROCESSED ASSO','COW UNPROCESSED ASSO',3800.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:40','2026-05-23 12:12:40',NULL),(101,1,1,'COW TOZO','COW TOZO',8200.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:40','2026-05-23 12:12:40',NULL),(102,1,1,'TOPSIDE','COW TOPSIDE',8700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:40','2026-05-23 12:12:40',NULL),(103,1,1,'COW TOPRUN','COW TOPRUN',7800.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:41','2026-05-23 12:12:41',NULL),(104,1,1,'LCow TONGUE','COW TONGUE',7400.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:41','2026-05-23 12:12:41',NULL),(105,1,1,'Cow Throat','Cow Throat',4100.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:42','2026-05-23 12:12:42',NULL),(106,1,1,'T-BONE','COW T-BONE',25000.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:42','2026-05-23 12:12:42',NULL),(107,1,1,'COWTAIL','COW TAILS',7900.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:43','2026-05-23 12:12:43',NULL),(108,1,1,'COW SPLEEN','COW SPLEEN',3000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:44','2026-05-23 12:12:44',NULL),(109,1,1,'TOPSIDE-RUN-ROSTO-FILLET','COW SPECIAL-PARTS',8000.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:44','2026-05-23 12:12:44',NULL),(110,1,1,'SHEREDDED BEEF','COW SHREDEDED',9100.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:44','2026-05-23 12:12:44',NULL),(111,1,1,'SHORT-RIB','COW SHORT-RIB',7700.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:45','2026-05-23 12:12:45',NULL),(112,1,1,'SHIN','COW SHIN',8200.00,1545.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:46','2026-05-23 12:12:46',NULL),(113,1,1,'cow shaki','COW SHAKI',10000.00,3215.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:46','2026-05-23 12:12:46',NULL),(114,1,1,'ROSTOR','COW ROSTOR',7700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:46','2026-05-23 12:12:46',NULL),(115,1,1,'ROSTO','COW ROSTO',8700.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:46','2026-05-23 12:12:46',NULL),(116,1,1,'RIB-EYE','COW RIB-EYE',25000.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:47','2026-05-23 12:12:47',NULL),(117,1,1,'COW SKIN','COW PONMO WHITE',5000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:48','2026-05-23 12:12:48',NULL),(118,1,1,'COW SKIN','COW PONMO BROWN',5100.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:48','2026-05-23 12:12:48',NULL),(119,1,1,'COW SKIN','COW PONMO',4900.00,1706.72,0,5,'kg',NULL,1,1,'2026-05-23 12:12:48','2026-05-23 12:12:48',NULL),(120,1,1,'COW PENIS','COW PENIS',4500.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:48','2026-05-23 12:12:48',NULL),(121,1,1,'OX TAIL SPECIAL','COW OX TAIL',11000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:49','2026-05-23 12:12:49',NULL),(122,1,1,'Organ (p)','COW ORGAN (P)',4600.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:49','2026-05-23 12:12:49',NULL),(123,1,1,'OFFALS','COW OFFALS',7300.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:49','2026-05-23 12:12:49',NULL),(124,1,1,'COW NECK','COW NECK',4900.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:50','2026-05-23 12:12:50',NULL),(125,1,1,'MINCED BEEF','COW MINCED',8700.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:50','2026-05-23 12:12:50',NULL),(126,1,1,'LIVER','COW LIVER',7400.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:50','2026-05-23 12:12:50',NULL),(127,1,1,'Live Cow','COW LIVE',0.00,649200.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:50','2026-05-23 15:51:58',NULL),(128,1,1,'Boneless Cow leg','COW LEGS DEBONE',8600.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:51','2026-05-23 12:12:51',NULL),(129,1,1,'BONELESS COW LEG','COW LEG BONELESS',8600.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:51','2026-05-23 12:12:51',NULL),(130,1,1,'COW LEG','COW LEG',5800.00,1700.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:51','2026-05-23 12:12:51',NULL),(131,1,1,'KIDNEY','COW KIDNEY',5500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:51','2026-05-23 12:12:51',NULL),(132,1,1,'COW FAT(Ishon)','COW Ishon',4600.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:52','2026-05-23 12:12:52',NULL),(133,1,1,'COW HORN','COW HORN',500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:52','2026-05-23 12:12:52',NULL),(134,1,1,'COW HEAD 2','COW HEAD 2',3500.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:52','2026-05-23 12:12:52',NULL),(135,1,1,'LCow Head','COW HEAD',7500.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:54','2026-05-23 12:12:54',NULL),(136,1,1,'COW FILLET','COW FILLET',8700.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:54','2026-05-23 12:12:54',NULL),(137,1,1,'COW FAT 1','COW FAT 1',1200.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:55','2026-05-23 12:12:55',NULL),(138,1,1,'COW FAT 2','COW FAT',2200.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:55','2026-05-23 12:12:55',NULL),(139,1,1,'COW CARCASS','COW CARCASS',5800.00,1545.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:55','2026-05-23 12:12:55',NULL),(140,1,1,'COW BRISKET BONE','COW BRISKET BONE',4500.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:55','2026-05-23 12:12:55',NULL),(141,1,1,'LCOW BONES','COW BONES',1600.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:55','2026-05-23 12:12:55',NULL),(142,1,1,'COW BLOOD','COW BLOOD',4000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(143,1,1,'COW BEEF','COW BEEF',8000.00,1703.28,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(144,1,1,'COW BEEF','COW BEE 2',7400.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(145,1,1,'COW BEEF','COW BEE',7400.00,1449.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(146,1,1,'COKE','COKE',500.00,154.17,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(147,1,1,'CHICKEN WINGS','CHICKEN WINGS',8000.00,1042.45,0,5,'kg',NULL,1,1,'2026-05-23 12:12:56','2026-05-23 12:12:56',NULL),(148,1,1,'Chicken Thigh','CHICKEN THIGH',4350.00,94.44,0,5,'kg',NULL,1,1,'2026-05-23 12:12:57','2026-05-23 12:12:57',NULL),(149,1,1,'SHREDED CHICKEN','CHICKEN SHREDDED',9000.00,2476.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:57','2026-05-23 12:12:57',NULL),(150,1,1,'CHICKEN NECK/LEG/HEAD','CHICKEN PARTS',1700.00,1042.65,0,5,'kg',NULL,1,1,'2026-05-23 12:12:57','2026-05-23 12:12:57',NULL),(151,1,1,'OROBO','CHICKEN OROBO',6000.00,1800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:57','2026-05-23 12:12:57',NULL),(152,1,1,'CHICKEN OLD LAYERS','CHICKEN OLD LAYERS',7000.00,1400.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(153,1,1,'CHICKEN LOCAL','CHICKEN OCAL',8000.00,2729.26,0,5,'kg',NULL,1,1,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(154,1,1,'CHICKEN LOCAL','CHICKEN LOCAL',8000.00,2729.26,0,5,'kg',NULL,1,1,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(155,1,1,'CHICKEN LIVE','CHICKEN LIVE',9000.00,1052.63,0,5,'kg',NULL,1,1,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(156,1,1,'LAPS','CHICKEN LAPS',5400.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(157,1,1,'CHICKEN GIZZARD','CHICKEN GIZZARD',6500.00,1800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:58','2026-05-23 12:12:58',NULL),(158,1,1,'Chicken Drumstick','CHICKEN DRUMSTICK',6000.00,94.44,0,5,'kg',NULL,1,1,'2026-05-23 12:12:59','2026-05-23 12:12:59',NULL),(159,1,1,'CHICKEN CUT 4','CHICKEN CUT 4',5200.00,5200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:12:59','2026-05-23 12:12:59',NULL),(160,1,1,'CHICKEN COCKEREL LIV','CHICKEN COCKEREL LIV',9000.00,2729.26,0,5,'kg',NULL,1,1,'2026-05-23 12:12:59','2026-05-23 12:12:59',NULL),(161,1,1,'CHICKEN COCKEREL','CHICKEN COCKEREL',5500.00,2729.26,0,5,'kg',NULL,1,1,'2026-05-23 12:13:00','2026-05-23 12:13:00',NULL),(162,1,1,'CHICKEN CARCAS','CHICKEN CARCAS',5400.00,1800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:00','2026-05-23 12:13:00',NULL),(163,1,1,'CHICKEN BROILERS','CHICKEN BROILERS',5200.00,1800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:00','2026-05-23 12:13:00',NULL),(164,1,1,'BREAST','CHICKEN BREAST',5400.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:13:00','2026-05-23 12:13:00',NULL),(165,1,1,'BONLESS CHICKEN','CHICKEN BONELESS',6200.00,2476.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:00','2026-05-23 12:13:00',NULL),(166,1,1,'CHICKEN GIZZARD','CHICKEN',4000.00,1800.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(167,1,1,'CAKE PARFAIT','CAKE PARFAIT',2500.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(168,1,1,'BITER LEMON','BITTERLEMON',200.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(169,1,1,'BIGITROPICAL','BIGITROPICAL',300.00,108.30,0,5,'kg',NULL,1,1,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(170,1,1,'BIGICOLA','BIGICOLA',300.00,108.30,0,5,'kg',NULL,1,1,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(171,1,1,'beef Top Shin','beef Shin',8000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:13:01','2026-05-23 12:13:01',NULL),(172,1,1,'BEEF','BEEF',8200.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:02','2026-05-23 12:13:02',NULL),(173,1,1,'Whiteseseme seed','ABIS WHTESESAME SEED',2500.00,1550.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:02','2026-05-23 12:13:02',NULL),(174,1,1,'Whitefonio 4kg','ABIS WHTEFONIO 4KG',4800.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:02','2026-05-23 12:13:02',NULL),(175,1,1,'Whitefonio 3KG','ABIS WHTEFONIO 3KG',9000.00,1200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:03','2026-05-23 12:13:03',NULL),(176,1,1,'Whitefonio 2kg','ABIS WHTEFONIO 2KG',2400.00,2400.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:03','2026-05-23 12:13:03',NULL),(177,1,1,'Whitefonio 1kg','ABIS WHTEFONIO 1KG',6850.00,1200.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:03','2026-05-23 12:13:03',NULL),(178,1,1,'Tamarind','ABIS SWEETTAMARIND',3500.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:03','2026-05-23 12:13:03',NULL),(179,1,1,'SMOKED CATFISH','ABIS SMOKED CATFISH',4400.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:13:03','2026-05-23 12:13:03',NULL),(180,1,1,'Peanut butter','ABIS PEANUTBUTTER 2L',2000.00,1600.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:04','2026-05-23 12:13:04',NULL),(181,1,1,'Panga fish half carton','ABIS PANGA HALF CART',22500.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:04','2026-05-23 12:13:04',NULL),(182,1,1,'Panga fish full carton','ABIS PANGA FULL CART',45000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:04','2026-05-23 12:13:04',NULL),(183,1,1,'Panga fish 5pieces','ABIS PANGA 5PIECES',5000.00,5000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:04','2026-05-23 12:13:04',NULL),(184,1,1,'Panga fish 10pieces','ABIS PANGA 10 PIECES',12500.00,10000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:04','2026-05-23 12:13:04',NULL),(185,1,1,'ABIS METE ZEYTIN OIL','ABIS METE OIL',60000.00,0.00,0,5,'kg',NULL,1,0,'2026-05-23 12:13:05','2026-05-23 12:13:05',NULL),(186,1,1,'Kuli kuli oil 5lt','ABIS KULI OIL 5lt',28000.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:05','2026-05-23 12:13:05',NULL),(187,1,1,'Kuli kuli oil 500ml','ABIS KULI OIL 500ml',2000.00,1000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:05','2026-05-23 12:13:05',NULL),(188,1,1,'Kuli kuli oil 2.5lt','ABIS KULI OIL 2ltr',10000.00,4000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:05','2026-05-23 12:13:05',NULL),(189,1,1,'Kuli kuli oil 2.5lt','ABIS KULI OIL 2.5lt',14000.00,4000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:05','2026-05-23 12:13:05',NULL),(190,1,1,'Kuli kuli oil 1Ltr','ABIS KULI OIL 1ltr',10000.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:06','2026-05-23 12:13:06',NULL),(191,1,1,'Kuli kuli small','ABIS KULI KULI SMALL',2000.00,500.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:06','2026-05-23 12:13:06',NULL),(192,1,1,'Kuli kuli big','ABIS KULI KULI BIG',2000.00,1000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:06','2026-05-23 12:13:06',NULL),(193,1,1,'Honey comb 1Litr','ABIS HONEY COMB',30000.00,5000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:06','2026-05-23 12:13:06',NULL),(194,1,1,'Honey 5ltrs','ABIS HONEY 5LTRS',65000.00,10000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:06','2026-05-23 12:13:06',NULL),(195,1,1,'Honey 500ml','ABIS HONEY 500ML',9800.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:07','2026-05-23 12:13:07',NULL),(196,1,1,'Honey 4ltrs','ABIS HONEY 4LTRS',16000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:07','2026-05-23 12:13:07',NULL),(197,1,1,'Honey 3ltrs','ABIS HONEY 3LTRS',30000.00,12000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:07','2026-05-23 12:13:07',NULL),(198,1,1,'Honey 2ltrs','ABIS HONEY 2LTRS',28000.00,8000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:07','2026-05-23 12:13:07',NULL),(199,1,1,'Honey 1ltr','ABIS HONEY 1LTR',19600.00,4000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(200,1,1,'Groundnutoil 5Ltrs','ABIS GROUNDOIL 5LTRS',18600.00,10000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(201,1,1,'Groundnutoil 500ml','ABIS GROUNDOIL 500ML',1000.00,1000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(202,1,1,'Groundnutoil 4ltrs','ABIS GROUNDOIL 4LTRS',8000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(203,1,1,'Groundnutoil 3ltrs','ABIS GROUNDOIL 3LTRS',6000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(204,1,1,'Groundnutoil 2ltrs','ABIS GROUNDOIL 2LTRS',8600.00,4000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(205,1,1,'ABIS GROUNDOIL 2.5l','ABIS GROUNDOIL 2.5l',9300.00,4000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:08','2026-05-23 12:13:08',NULL),(206,1,1,'Groundnut 1ltr','ABIS GROUNDOIL 1LTR',2000.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:09','2026-05-23 12:13:09',NULL),(207,1,1,'Groundnutoil 10Ltrs','ABIS GROUNDOIL 10LTS',19000.00,19000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:09','2026-05-23 12:13:09',NULL),(208,1,1,'Groundnut paste','ABIS GROUNDNUT PASTE',2000.00,1600.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:09','2026-05-23 12:13:09',NULL),(209,1,1,'Ghee 500gm','ABIS GHEE 500G',4000.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(210,1,1,'Coconut oil 5ltrs','ABIS COCONOIL 5LTRS',25000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(211,1,1,'Coconut oil 500ml','ABIS COCONOIL 500ML',4000.00,1500.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(212,1,1,'Coconut oil 4ltrs','ABIS COCONOIL 4LTRS',20000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(213,1,1,'Coconut oil 3ltrs','ABIS COCONOIL 3LTRS',15000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(214,1,1,'Coconut oil 2ltrs','ABIS COCONOIL 2LTRS',10000.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(215,1,1,'Coconut oil 1ltr','ABIS COCONOIL 1LTR',5000.00,3000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(216,1,1,'Brown fonio 4kg','ABIS BROWNFONIO 4KG',3850.00,0.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(217,1,1,'ABIS BROWNFONIO 3KG','ABIS BROWNFONIO 3KG',6300.00,1000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:10','2026-05-23 12:13:10',NULL),(218,1,1,'Brown Fonio 2kg','ABIS BROWNFONIO 2KG',2000.00,2000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:11','2026-05-23 12:13:11',NULL),(219,1,1,'Brown Fonio 1kg','ABIS BROWNFONIO 1KG',5000.00,1000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:11','2026-05-23 12:13:11',NULL),(220,1,1,'Atili 5ltrs','ABIS ATILI 5LTRS',13000.00,12000.00,0,5,'kg',NULL,1,1,'2026-05-23 12:13:11','2026-05-23 12:13:11',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_items`
--

DROP TABLE IF EXISTS `project_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'item',
  `quantity` decimal(10,3) NOT NULL DEFAULT 1.000,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `category` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_items_project_id_foreign` (`project_id`),
  CONSTRAINT `project_items_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_items`
--

LOCK TABLES `project_items` WRITE;
/*!40000 ALTER TABLE `project_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `budget` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_boq` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','quoted','approved','in_progress','on_hold','completed','cancelled') NOT NULL DEFAULT 'draft',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `completion_percent` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_shop_id_foreign` (`shop_id`),
  KEY `projects_created_by_foreign` (`created_by`),
  KEY `projects_customer_id_foreign` (`customer_id`),
  CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `projects_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_requests`
--

DROP TABLE IF EXISTS `refund_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refund_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_requests_sale_id_foreign` (`sale_id`),
  KEY `refund_requests_shop_id_foreign` (`shop_id`),
  KEY `refund_requests_requested_by_foreign` (`requested_by`),
  KEY `refund_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `refund_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  CONSTRAINT `refund_requests_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refund_requests_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_requests`
--

LOCK TABLES `refund_requests` WRITE;
/*!40000 ALTER TABLE `refund_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(1,3),(1,5),(1,6),(1,7),(2,1),(2,5),(2,6),(2,7),(3,1),(3,5),(4,1),(4,2),(4,3),(5,1),(5,2),(5,3),(5,5),(6,1),(6,5),(7,1),(7,2),(7,3),(8,1),(8,2),(8,3),(8,5),(8,6),(9,1),(9,2),(9,3),(9,5),(9,6),(10,1),(10,2),(10,3),(11,1),(11,2),(11,3),(11,5),(12,1),(12,2),(12,3),(13,1),(13,5),(13,7),(14,1),(14,5),(14,6),(15,1),(15,5),(15,6),(16,1),(16,2),(16,3),(16,5),(17,1),(17,5),(17,6),(18,1),(18,2),(18,3),(19,1),(19,7),(20,1),(20,7),(21,1),(21,3),(21,5),(22,1),(22,3),(22,5),(23,1),(23,5),(24,1),(24,3),(24,5),(24,7),(25,1),(25,3),(25,5),(25,7),(26,1),(26,2),(26,3),(26,5),(27,1),(27,2),(27,3),(27,4),(27,5),(28,1),(28,2),(28,4),(29,1),(29,2),(29,3),(29,4),(29,5),(30,1),(30,3),(30,4),(30,5),(31,1),(31,4),(32,1),(32,4),(33,1),(33,2),(33,4),(34,1),(34,2),(34,4),(35,1),(35,2),(35,3),(35,4),(36,1),(36,2),(36,3),(37,1),(37,3),(37,5),(38,1),(38,3),(38,5),(39,1),(39,2),(39,3),(40,1),(40,2),(40,3),(41,1),(41,2),(42,1),(42,2),(42,3),(42,4),(43,1),(43,2),(43,3),(43,5),(43,6),(44,1),(44,2),(44,3),(45,1),(45,2),(45,3),(45,5),(45,6),(46,1),(46,2),(46,3),(46,4),(46,5),(47,1),(47,2),(48,1),(48,2),(49,1),(49,2),(50,1),(50,2),(51,1),(51,2),(52,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'site-admin','web','2026-05-09 12:53:53','2026-05-09 12:53:53'),(2,'owner','web','2026-05-09 12:54:00','2026-05-09 12:54:00'),(3,'manager','web','2026-05-09 12:54:02','2026-05-09 12:54:02'),(4,'hr','web','2026-05-09 12:54:03','2026-05-09 12:54:03'),(5,'supervisor','web','2026-05-09 12:54:03','2026-05-09 12:54:03'),(6,'cashier','web','2026-05-09 12:54:04','2026-05-09 12:54:04'),(7,'pos-attendant','web','2026-05-09 12:54:05','2026-05-09 12:54:05');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `till_session_id` bigint(20) unsigned DEFAULT NULL,
  `served_by` bigint(20) unsigned NOT NULL,
  `collected_by` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `change_given` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','transfer','split','credit') NOT NULL DEFAULT 'cash',
  `status` enum('completed','pending','refunded','voided') NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_receipt_number_unique` (`receipt_number`),
  KEY `sales_shop_id_foreign` (`shop_id`),
  KEY `sales_till_session_id_foreign` (`till_session_id`),
  KEY `sales_served_by_foreign` (`served_by`),
  KEY `sales_collected_by_foreign` (`collected_by`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  CONSTRAINT `sales_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_served_by_foreign` FOREIGN KEY (`served_by`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_till_session_id_foreign` FOREIGN KEY (`till_session_id`) REFERENCES `till_sessions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('restaurant','market','butchery','hybrid') NOT NULL DEFAULT 'restaurant',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `address_full` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `invoice_prefix` varchar(255) NOT NULL DEFAULT 'INV',
  `invoice_footer` text DEFAULT NULL,
  `default_tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `currency` varchar(10) NOT NULL DEFAULT 'NGN',
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shops_manager_id_foreign` (`manager_id`),
  CONSTRAINT `shops_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
INSERT INTO `shops` VALUES (1,'Abis HQ General Paint','restaurant','Lagos, Nigeria','Lekki Lagos','08000000000','abis@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,'INV',NULL,0.00,1,'NGN',5,NULL,'2026-05-09 12:54:09','2026-05-22 08:54:40',NULL);
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_profiles`
--

DROP TABLE IF EXISTS `staff_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `pay_type` enum('salary','daily','commission','mixed') NOT NULL DEFAULT 'salary',
  `base_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `hire_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `next_of_kin` varchar(255) DEFAULT NULL,
  `next_of_kin_phone` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_profiles_user_id_unique` (`user_id`),
  UNIQUE KEY `staff_profiles_employee_id_unique` (`employee_id`),
  KEY `staff_profiles_shop_id_foreign` (`shop_id`),
  KEY `staff_profiles_department_id_foreign` (`department_id`),
  CONSTRAINT `staff_profiles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_profiles_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_profiles`
--

LOCK TABLES `staff_profiles` WRITE;
/*!40000 ALTER TABLE `staff_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('sale','purchase','adjustment','transfer_in','transfer_out','waste','return') NOT NULL,
  `quantity_before` int(11) NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `quantity_after` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_shop_id_foreign` (`shop_id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  KEY `stock_movements_sale_id_foreign` (`sale_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_payments`
--

DROP TABLE IF EXISTS `supplier_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `batch_id` bigint(20) unsigned DEFAULT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','transfer','cheque') NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_payments_recorded_by_foreign` (`recorded_by`),
  KEY `supplier_payments_supplier_id_index` (`supplier_id`),
  KEY `supplier_payments_batch_id_index` (`batch_id`),
  KEY `supplier_payments_shop_id_index` (`shop_id`),
  CONSTRAINT `supplier_payments_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `supply_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `supplier_payments_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`),
  CONSTRAINT `supplier_payments_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`),
  CONSTRAINT `supplier_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_payments`
--

LOCK TABLES `supplier_payments` WRITE;
/*!40000 ALTER TABLE `supplier_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_terms` enum('cash','credit') NOT NULL DEFAULT 'cash',
  `credit_days` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `total_supplied` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_shop_id_is_active_index` (`shop_id`,`is_active`),
  CONSTRAINT `suppliers_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,1,'ABIS AGO PALACE',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:52','2026-05-23 15:53:52',NULL),(2,1,'ABIS FISH FARM',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:53','2026-05-23 15:53:53',NULL),(3,1,'ABIS MARKET RANCH',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,222983610.77,0.00,1,NULL,'2026-05-23 15:53:53','2026-05-23 15:53:53',NULL),(4,1,'ABIS MKT',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:53','2026-05-23 15:53:53',NULL),(5,1,'ABIS RANCH POTISKUM',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:53','2026-05-23 15:53:53',NULL),(6,1,'AGEGE MEAT MARKET',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,22674600.00,0.00,1,NULL,'2026-05-23 15:53:53','2026-05-23 15:53:53',NULL),(7,1,'Cheldaro farms',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(8,1,'CIC LTD',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,832.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(9,1,'COTONOU TURKEY AND CO',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(10,1,'drinks vendor',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,79907.50,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(11,1,'Final Touch Kitcken',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(12,1,'KAYBOL VENTURES',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,10800.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(13,1,'LA`AVIDORENG FOODIES',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(14,1,'MADAM EZINNE',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,208510.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(15,1,'MAIDUGURI RANCH',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(16,1,'MALLAM AND CO.',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,0.75,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(17,1,'MAMA DELE TURKEY',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,2000.78,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(18,1,'MARVYLM FARMS',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(19,1,'MOHAMMED ABDULLAHI DADI',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(20,1,'MRS OBI AND CO',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(21,1,'OG FARMS',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(22,1,'ROSE VALLEY FARMS',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(23,1,'SHAANU MONICA',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(24,1,'SNAIL',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:54','2026-05-23 15:53:54',NULL),(25,1,'TARABAROZ FISHERIES',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:55','2026-05-23 15:53:55',NULL),(26,1,'TIDAL DERIVATIVES LTD',NULL,'09090341220',NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:55','2026-05-23 15:53:55',NULL),(27,1,'TJ AND CO',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:55','2026-05-23 15:53:55',NULL),(28,1,'TOAFFEK',NULL,NULL,NULL,NULL,NULL,'cash',0,NULL,NULL,NULL,0.00,0.00,1,NULL,'2026-05-23 15:53:55','2026-05-23 15:53:55',NULL),(29,1,'TONAD FARM',NULL,NULL,NULL,NULL,NULL,'credit',30,NULL,NULL,NULL,1.11,0.00,1,NULL,'2026-05-23 15:53:55','2026-05-23 15:53:55',NULL);
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supply_batch_items`
--

DROP TABLE IF EXISTS `supply_batch_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_batch_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint(20) unsigned NOT NULL,
  `shop_id` bigint(20) unsigned NOT NULL,
  `animal_type` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL,
  `processing_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `other_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cost_per_head` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_batch_items_shop_id_foreign` (`shop_id`),
  KEY `supply_batch_items_batch_id_animal_type_index` (`batch_id`,`animal_type`),
  CONSTRAINT `supply_batch_items_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `supply_batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supply_batch_items_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply_batch_items`
--

LOCK TABLES `supply_batch_items` WRITE;
/*!40000 ALTER TABLE `supply_batch_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `supply_batch_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supply_batches`
--

DROP TABLE IF EXISTS `supply_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `batch_code` varchar(255) NOT NULL,
  `batch_label` varchar(255) NOT NULL,
  `batch_date` date NOT NULL,
  `status` enum('draft','receiving','active','closed') NOT NULL DEFAULT 'draft',
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `payment_due_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_batches_batch_code_unique` (`batch_code`),
  KEY `supply_batches_supplier_id_foreign` (`supplier_id`),
  KEY `supply_batches_received_by_foreign` (`received_by`),
  KEY `supply_batches_shop_id_status_index` (`shop_id`,`status`),
  KEY `supply_batches_shop_id_supplier_id_index` (`shop_id`,`supplier_id`),
  CONSTRAINT `supply_batches_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `supply_batches_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supply_batches_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply_batches`
--

LOCK TABLES `supply_batches` WRITE;
/*!40000 ALTER TABLE `supply_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `supply_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `till_sessions`
--

DROP TABLE IF EXISTS `till_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `till_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `opening_float` decimal(10,2) NOT NULL DEFAULT 0.00,
  `expected_cash` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actual_cash` decimal(10,2) DEFAULT NULL,
  `discrepancy` decimal(10,2) DEFAULT NULL,
  `status` enum('open','closed','flagged') NOT NULL DEFAULT 'open',
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint(20) unsigned DEFAULT NULL,
  `reconciled_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `till_sessions_shop_id_foreign` (`shop_id`),
  KEY `till_sessions_user_id_foreign` (`user_id`),
  KEY `till_sessions_closed_by_foreign` (`closed_by`),
  KEY `till_sessions_reconciled_by_foreign` (`reconciled_by`),
  CONSTRAINT `till_sessions_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `till_sessions_reconciled_by_foreign` FOREIGN KEY (`reconciled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `till_sessions_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `till_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `till_sessions`
--

LOCK TABLES `till_sessions` WRITE;
/*!40000 ALTER TABLE `till_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `till_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `scope` enum('branch','regional','all') NOT NULL DEFAULT 'branch',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_shop_id_foreign` (`shop_id`),
  CONSTRAINT `users_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Butcherhut Admin','08000000001','admin@butcherhut.ng',NULL,'$2y$12$Ns.cmTUHf7ocojpoobU46OIjIOtGHCRswMSImx6BV26a51ljC86pi','kGyCTEmCFppu3FRCo1vAFl7BUFKBWuQCgSkarcnUqggwRnCAgPQHsYg7E67D',1,'2026-05-24 00:16:07','all','2026-05-09 12:54:10','2026-05-24 00:16:07'),(2,NULL,'Site Administrator','08000000002','siteadmin@butcherhut.ng',NULL,'$2y$12$Zi6MhY/aI7sNDLf4OtlIZu7r1k.WIHQ3LnwQqZRcUUiZy7J6X.WNe',NULL,1,NULL,'all','2026-05-09 12:54:11','2026-05-09 12:54:11'),(3,1,'cashier','09090394000','cashier@butcherhut.ng',NULL,'$2y$12$esFBTa7yEPJqTBLNrrKuB.5eBp5pwaIiBKOjRn5rreJjtRKHjjwIu',NULL,1,NULL,'branch','2026-05-22 08:51:13','2026-05-22 08:51:13'),(4,1,'Blessing','09090394000','blessing@gmail.com',NULL,'$2y$12$hxCf.9tqs0TU50tELdJATu2Vo1PRf3yLNwGDME8yeCzT/91ZJ8hfG',NULL,1,'2026-05-22 08:55:13','branch','2026-05-22 08:52:39','2026-05-22 08:55:13'),(5,1,'Matthew','09090394023','matthew@gmail.com',NULL,'$2y$12$H7/tbtauw.4aA4DIoTyhseLa8oMPel.ayRv8tQdlPtWjk0tPr7ujG',NULL,1,NULL,'branch','2026-05-22 08:53:31','2026-05-22 08:53:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-27 23:37:57
