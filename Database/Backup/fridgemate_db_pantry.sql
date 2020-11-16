CREATE DATABASE  IF NOT EXISTS `fridgemate_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fridgemate_db`;
-- MySQL dump 10.13  Distrib 8.0.18, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fridgemate_db
-- ------------------------------------------------------
-- Server version	8.0.18

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

--
-- Table structure for table `pantry`
--

DROP TABLE IF EXISTS `pantry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pantry` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(45) DEFAULT NULL,
  `name2` varchar(45) DEFAULT NULL,
  `name3` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantry`
--

LOCK TABLES `pantry` WRITE;
/*!40000 ALTER TABLE `pantry` DISABLE KEYS */;
INSERT INTO `pantry` VALUES (1,'Tomato','Canned','Whole',1,0),(2,'Tomato','Romma','',1,0),(3,'Tofu','Firm','',1,0),(4,'Squash','Butternut','',1,0),(5,'Pasta','Fettuccine','',1,0),(6,'Onion','Yellow','',1,0),(7,'Sugar','White','',1,0),(8,'Cashew','Raw','',1,0),(9,'Oil','Olive','',1,0),(10,'Pepper Flakes','','',1,0),(11,'Sage','Fresh','',1,0),(12,'Black Pepper','','',1,0),(13,'Nutritional yeast','','',1,0),(14,'Veggie broth','','',0,0),(15,'Lemon','Juice','',1,0),(16,'Oil','','',1,0),(17,'Soy Sauce','','',1,0),(18,'Paprika','Smoked','',1,0),(19,'Garlic','Powder','',0,0),(20,'Maple Syrup','','',1,0),(21,'Miso paste','','',0,0),(22,'Garlic','Fresh','',1,0),(23,'Salt','','',1,0),(24,'Pasta','Farfalle','',1,0),(25,'Soy curls','','',0,0),(26,'Milk','Cashew','',1,3),(27,'Sun dried tomato ','','',1,0),(28,'Spinach','','',1,0),(29,'Sage','Dry','',1,0),(30,'Onion','Dry','',1,0),(31,'Thyme','Dry','',0,0),(32,'Poultry seasoning','','',0,4),(33,'Oregano','Dry','',0,0),(34,'Thyme','Fresh','',1,0),(35,'Basil','Fresh','',1,0),(36,'Water','','',1,0),(37,'Marjoram','Dry','',1,0),(38,'Rosemary','Dry','',1,0),(39,'Nutmeg','Ground','',1,0);
/*!40000 ALTER TABLE `pantry` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-15 18:43:29
