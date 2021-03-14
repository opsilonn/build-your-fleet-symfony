-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: vlvlnl1grfzh34vj.chr7pe7iynqr.eu-west-1.rds.amazonaws.com    Database: ifaiicw3h4pew9dg
-- ------------------------------------------------------
-- Server version	5.7.26-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `spaceship`
--

DROP TABLE IF EXISTS `spaceship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spaceship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `franchise` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_crew` int(11) DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `available` int(11) DEFAULT '0',
  `height` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `assets` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spaceship`
--

LOCK TABLES `spaceship` WRITE;
/*!40000 ALTER TABLE `spaceship` DISABLE KEYS */;
INSERT INTO `spaceship` VALUES (1,'X-Wing','Star Wars','Military','Individual Spaceship',1,3000,12,2,12,11,'Hyperdrive, Astromech Droid','This spaceship can destroy a whole Space station all by itself... twice.','img/Uploads/00001.jpg'),(2,'Constitution Class Cruiser','Star Trek','Scientific','Cruiser',400,2000000,1,NULL,NULL,NULL,'Hyperdrive, Warp device, High Accommodation Capacity','Most well-known iteration : USS Enterprise','img/Uploads/00002.jpg'),(3,'O\'Neil Cylinder','The High-Frontier : Human Colony in Space','Civil','Space Station',300000,999999999,1,NULL,NULL,NULL,'Terraforming, Colonization','Made an appearance in the movie \'Interstellar\', for instance','img/Uploads/00003.jpg'),(4,'Arwing','Star Fox','Military','Individual Spaceship',1,6000,4,6,28,21,'Hyperdrive, Bomb Launcher','Do a barrel roll !!','img/Uploads/00004.jpg'),(5,'Lambda-class T-4a shuttle','Star Wars','Company','Shuttle',20,15000,2,20,5,10,'Hyperdrive, Luxury interiors, Defensive weapons','Also called \'Imperial Shuttle\'','img/Uploads/00005.jpg'),(6,'International Space Station','Mankind','Scientific','Satellite',6,20000,1,20,73,109,'Observation devices','Currently the best we have (yet !!)','img/Uploads/00006.jpg'),(7,'SSV Normandy SR1','Mass Effect','Military','Frigate',50,600000,2,40,170,80,'Hyperdrive, Stealth System','A ship to save the universe','img/Uploads/00007.jpg'),(8,'SSV Normandy SR2','Mass Effect','Military','Frigate',50,600000,3,40,170,80,'Hyperdrive, Stealth System','A ship to save the universe... Again ?','img/Uploads/00008.jpg'),(9,'Hubble','Mankind','Scientific','Satellite',0,5000,1,4,13,4,'Observation devices, Top-notch pictures','Wanna have a look to the other side of the galaxy ?','img/Uploads/00009.jpg'),(10,'Death Star','Star Wars','Military','Space Station',1700000,999999999,2,120,160,160,'Hyperdrive, Superlaser, Tractor Beam','This isn\'t a moon ...','img/Uploads/00010.jpg'),(11,'EF76 Nebulon-B Escort Frigate','Star Wars','Civil','Frigate',850,450000,12,90,300,350,'Hyperdrive, Medical assets','A little help never hurts anybody','img/Uploads/00011.JPG'),(12,' Corellian YT-1300f Light Freighter','Star Wars','Company','Shuttle',2,45000,4,8,35,25,'Hyperdrive++, storage space, Autopilot','It\'s a ship that made the Kessel run in  less than 12 Parsecs !!','img/Uploads/00012.jpg'),(13,'S. S. Dolphin','Pikmin','Personal','Individual Spaceship ',1,10000,10,NULL,NULL,NULL,'Hyperdrive, Luxurious environment','A small ship, but a comfortable one to be sure','img/Uploads/00013.jpg'),(14,'Hocotate Ship','Pikmin','Company','Shuttle',2,20000,10,NULL,NULL,NULL,'Hyperdrive, Research Pod, High fret capacity','An old ship... at least it flies','img/Uploads/00014.jpg'),(15,'S. S. Drake','Pikmin','Scientific','Shuttle',3,30000,10,NULL,NULL,NULL,'Hyperdrive, Scanner, Good A.I.','Wanna save the world with some fruits ?','img/Uploads/00015.jpg'),(16,'Saturn V','Mankind','Scientific','Shuttle',3,10000,5,10,110,13,'High propulsion','One small step for Man ...','img/Uploads/00016.jpg'),(17,'Citadel','Mass Effect','Civil','Space Station',13200000,999999999,1,12800,44700,12800,'Transport Beam, Serves as a 2nd Earth','Everything you may need, and even more','img/Uploads/00017.jpg'),(18,'Romulan Mining Ship','Star Trek','Company','Space Station',1000,999999999,1,NULL,NULL,NULL,'Hyperdrive, Astral body\'s Mining tools, Red matter','Diggy diggy hole, diggy diggy hole ...','img/Uploads/00018.jpg'),(19,'Airspeeder Koro-2','Star Wars','Personal','Individual Spaceship',1,2000,45,2,7,2,'Resistant to extremes conditions','A good way to travel cities','img/Uploads/00019.jpg'),(20,'Hunter-Class Gunship','Metroid','Military','Shuttle',1,64000,4,NULL,NULL,NULL,'Hyperdrive, Missile Launcher, Save Station','A ship made for the best bounty hunters','img/Uploads/00020.jpg');
/*!40000 ALTER TABLE `spaceship` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-14  2:01:37
