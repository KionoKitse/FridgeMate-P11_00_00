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
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group` (
  `GROUP_ID` int(11) DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,1,1),(1,2,2),(2,19,3),(2,22,4),(3,16,5),(3,9,6),(4,24,7),(4,5,8),(5,29,9),(6,19,11);
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredient` (
  `RECIPE_ID` int(11) DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  `CATEGORY` int(11) DEFAULT NULL,
  `QUANTITY` varchar(5) DEFAULT NULL,
  `UNIT` varchar(45) DEFAULT NULL,
  `STEP` int(11) DEFAULT NULL,
  `PREP` tinyint(4) DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (1,3,1,'7','OZ',5,1,1),(1,4,1,'2','CUPS',2,1,2),(1,5,1,'8','OZ',4,0,3),(1,6,2,'0.25','CUPS',1,1,4),(1,22,2,'2','CLOVES',1,1,5),(1,8,2,'0.33','CUPS',3,0,6),(1,9,3,'2','TSP',1,0,7),(1,10,3,'0.25','TSP',2,0,8),(1,11,3,'1','TBSP',2,0,9),(1,23,3,'0.5','TSP',3,0,10),(1,12,3,'0.25','TSP',3,0,11),(1,13,3,'2','TBSP',3,0,12),(1,14,3,'1.25','CUPS',3,0,13),(1,15,3,'2','TSP',3,0,14),(1,16,3,'2','TSP',5,0,15),(1,17,3,'1.5','TBSP',5,0,16),(1,18,3,'1.5','TSP',5,0,17),(1,19,3,'0.25','TSP',5,0,18),(1,7,3,'0.25','TSP',5,0,19),(1,21,4,'0.5','TSP',3,0,20),(2,24,1,'8','OZ',0,0,21),(2,25,1,'3','OZ',1,0,22),(2,14,2,'0.5','CUPS',1,0,23),(2,22,2,'4','CLOVES',3,1,24),(2,26,2,'1.5','CUPS',4,0,25),(2,27,2,'0.25','CUPS',5,0,26),(2,28,2,'4','OZ',5,0,27),(2,29,3,'0.25','TSP',1,0,28),(2,30,3,'0.25','TSP',1,0,29),(2,19,3,'0.25','TSP',1,0,30),(2,31,3,'0.25','TSP',1,0,31),(2,16,3,'2','TSP',2,0,32),(2,12,3,'0.25','TSP',3,1,33),(2,18,3,'0.5','TSP',3,1,34),(2,32,3,'0.5','TSP',3,1,35),(2,23,3,'0.5','TSP',4,0,36),(2,19,3,'0.5','TSP',4,0,37),(2,30,3,'0.5','TSP',4,0,38),(2,33,3,'0.5','TSP',4,0,39),(2,31,3,'0.5','TSP',4,0,40),(2,9,3,'1','TBSP',2,0,41),(2,13,3,'2','TBSP',5,0,42),(3,8,1,'0.5','CUPS',0,1,43),(3,36,1,'1.5','CUPS',0,1,44),(4,29,1,'1','TSP',0,1,45),(4,31,1,'0.75','TSP',0,1,46),(4,37,1,'0.5','TSP',0,1,47),(4,38,1,'0.25','TSP',0,1,48),(4,39,1,'0.25','TSP',0,1,49),(4,12,1,'0.25','TSP',0,1,50),(5,40,1,'2','P',1,0,51),(5,41,1,'3','OZ',2,0,52),(5,28,2,'1','OZ',2,1,53),(5,42,2,'2','TBSP',1,0,54),(5,19,3,'0.25','TSP',2,1,55),(5,33,3,'0.5','TSP',2,1,56),(5,43,4,'*','*',2,1,57),(5,42,4,'*','*',2,0,58),(5,32,4,'0.1','t',2,0,59);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantry`
--

LOCK TABLES `pantry` WRITE;
/*!40000 ALTER TABLE `pantry` DISABLE KEYS */;
INSERT INTO `pantry` VALUES (1,'Tomato','Canned','Whole',1,0),(2,'Tomato','Romma',NULL,1,0),(3,'Tofu','Firm',NULL,1,0),(4,'Squash','Butternut',NULL,0,4),(5,'Pasta','Fettuccine',NULL,1,0),(6,'Onion','Yellow',NULL,1,0),(7,'Sugar','White',NULL,1,0),(8,'Cashew','Raw',NULL,1,0),(9,'Oil','Olive',NULL,1,0),(10,'Pepper Flakes',NULL,NULL,1,0),(11,'Sage','Fresh',NULL,1,0),(12,'Black Pepper',NULL,NULL,1,0),(13,'Nutritional yeast',NULL,NULL,1,0),(14,'Veggie broth',NULL,NULL,0,0),(15,'Lemon','Juice',NULL,1,0),(16,'Oil',NULL,NULL,1,0),(17,'Soy Sauce',NULL,NULL,1,0),(18,'Paprika','Smoked',NULL,1,0),(19,'Garlic','Powder',NULL,0,0),(20,'Maple Syrup',NULL,NULL,1,0),(21,'Miso paste',NULL,NULL,0,0),(22,'Garlic','Fresh',NULL,1,0),(23,'Salt',NULL,NULL,1,0),(24,'Pasta','Farfalle',NULL,1,0),(25,'Soy curls',NULL,NULL,0,0),(26,'Milk','Cashew',NULL,1,3),(27,'Sun dried tomato',NULL,NULL,1,0),(28,'Spinach',NULL,NULL,1,0),(29,'Sage','Dry',NULL,0,0),(30,'Onion','Dry',NULL,1,0),(31,'Thyme','Dry',NULL,1,0),(32,'Poultry seasoning',NULL,NULL,0,4),(33,'Oregano','Dry',NULL,0,0),(34,'Thyme','Fresh',NULL,1,0),(35,'Basil','Fresh',NULL,1,0),(36,'Water',NULL,NULL,1,0),(37,'Marjoram','Dry',NULL,1,0),(38,'Rosemary','Dry',NULL,1,0),(39,'Nutmeg','Ground',NULL,1,0),(40,'Bread',NULL,NULL,1,6),(41,'Cheese','Cheddar',NULL,1,0),(42,'Butter',NULL,NULL,1,0),(43,'Parsley','Fresh',NULL,1,0);
/*!40000 ALTER TABLE `pantry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `RECIPE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) DEFAULT NULL,
  `PEOPLE` int(11) DEFAULT NULL,
  `ACTIVE` decimal(5,2) DEFAULT NULL,
  `PASIVE` decimal(5,2) DEFAULT NULL,
  `RATING` int(11) DEFAULT NULL,
  `PERCENT` decimal(5,2) DEFAULT NULL,
  `INGREDIANT` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`RECIPE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (1,'Butternut Squash Carbonara',2,1.15,0.00,4,97.65,0),(2,'Creamy Sun Dried Tomato Pasta with Garlic Soy',3,0.30,0.00,3,58.00,0),(3,'Cashew milk',0,0.00,0.00,5,100.00,1),(4,'Poultry seasoning',0,0.00,0.00,5,97.00,1),(5,'Grilled Cheese',3,20.00,80.00,3,10.98,0);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `RECIPE_ID` int(11) DEFAULT NULL,
  `TAG` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Vegan'),(1,'Main'),(1,'Savory'),(2,'Vegan'),(2,'Main'),(2,'Savory'),(2,'Italian');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-18  6:41:04
