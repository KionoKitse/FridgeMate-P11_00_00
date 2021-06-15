CREATE DATABASE  IF NOT EXISTS `fridgemate_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fridgemate_db`;
-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fridgemate_db
-- ------------------------------------------------------
-- Server version	8.0.21

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
  `GROUP_ID` int DEFAULT NULL,
  `ITEM_ID` int DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
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
  `RECIPE_ID` int DEFAULT NULL,
  `ITEM_ID` int DEFAULT NULL,
  `CATEGORY` int DEFAULT NULL,
  `QUANTITY` varchar(5) DEFAULT NULL,
  `UNIT` varchar(45) DEFAULT NULL,
  `STEP` int DEFAULT NULL,
  `PREP` tinyint DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (1,3,1,'7','OZ',5,1,1),(1,4,1,'2','CUPS',2,1,2),(1,5,1,'8','OZ',4,0,3),(1,6,2,'0.25','CUPS',1,1,4),(1,22,2,'2','CLOVES',1,1,5),(1,8,2,'0.33','CUPS',3,0,6),(1,9,3,'2','TSP',1,0,7),(1,10,3,'0.25','TSP',2,0,8),(1,11,3,'1','TBSP',2,0,9),(1,23,3,'0.5','TSP',3,0,10),(1,12,3,'0.25','TSP',3,0,11),(1,13,3,'2','TBSP',3,0,12),(1,14,3,'1.25','CUPS',3,0,13),(1,15,3,'2','TSP',3,0,14),(1,16,3,'2','TSP',5,0,15),(1,17,3,'1.5','TBSP',5,0,16),(1,18,3,'1.5','TSP',5,0,17),(1,19,3,'0.25','TSP',5,0,18),(1,7,3,'0.25','TSP',5,0,19),(1,21,4,'0.5','TSP',3,0,20),(2,24,1,'8','OZ',0,0,21),(2,25,1,'3','OZ',1,0,22),(2,14,2,'0.5','CUPS',1,0,23),(2,22,2,'4','CLOVES',3,1,24),(2,26,2,'1.5','CUPS',4,0,25),(2,27,2,'0.25','CUPS',5,0,26),(2,28,2,'4','OZ',5,0,27),(2,29,3,'0.25','TSP',1,0,28),(2,30,3,'0.25','TSP',1,0,29),(2,19,3,'0.25','TSP',1,0,30),(2,31,3,'0.25','TSP',1,0,31),(2,16,3,'2','TSP',2,0,32),(2,12,3,'0.25','TSP',3,1,33),(2,18,3,'0.5','TSP',3,1,34),(2,32,3,'0.5','TSP',3,1,35),(2,23,3,'0.5','TSP',4,0,36),(2,19,3,'0.5','TSP',4,0,37),(2,30,3,'0.5','TSP',4,0,38),(2,33,3,'0.5','TSP',4,0,39),(2,31,3,'0.5','TSP',4,0,40),(2,9,3,'1','TBSP',2,0,41),(2,13,3,'2','TBSP',5,0,42),(3,8,1,'0.5','CUPS',0,1,43),(3,36,1,'1.5','CUPS',0,1,44),(4,29,1,'1','TSP',0,1,45),(4,31,1,'0.75','TSP',0,1,46),(4,37,1,'0.5','TSP',0,1,47),(4,38,1,'0.25','TSP',0,1,48),(4,39,1,'0.25','TSP',0,1,49),(4,12,1,'0.25','TSP',0,1,50),(15,6,1,'1','Pc',1,1,189),(15,16,2,'*','*',1,0,190),(15,48,3,'1','Tb',1,0,191),(15,49,3,'1','Pc',1,0,192),(15,45,1,'2','Pc',2,0,193),(15,46,2,'1.5','ts',2,1,194),(15,50,3,'3','ts',2,1,195),(15,51,3,'1','ts',2,0,196),(15,23,3,'1','ts',2,0,197),(15,12,3,'0.5','ts',2,0,198),(15,53,3,'1','ts',2,0,199),(15,36,1,'3','C',3,0,200),(15,47,2,'2','Tb',3,0,201),(15,54,4,'*','*',3,0,202),(15,55,4,'*','*',3,0,203),(5,40,1,'2','P',1,0,240),(5,42,2,'2','TBSP',1,0,241),(5,41,1,'3','OZ',2,0,242),(5,28,2,'1','OZ',2,1,243),(5,19,3,'0.25','TSP',2,1,244),(5,33,3,'0.5','TSP',2,1,245),(5,43,4,'*','*',2,1,246),(5,42,4,'*','*',2,0,247),(5,32,4,'0.1','t',2,0,248);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pantry`
--

DROP TABLE IF EXISTS `pantry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pantry` (
  `ITEM_ID` int NOT NULL AUTO_INCREMENT,
  `NAME1` varchar(45) DEFAULT NULL,
  `NAME2` varchar(45) DEFAULT NULL,
  `NAME3` varchar(45) DEFAULT NULL,
  `STATUS` tinyint DEFAULT NULL,
  `RECIPE_ID` int DEFAULT NULL,
  `EXPIRES` int DEFAULT NULL,
  `PURCHASE` varchar(10) DEFAULT NULL,
  `CATEGORY` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ITEM_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantry`
--

LOCK TABLES `pantry` WRITE;
/*!40000 ALTER TABLE `pantry` DISABLE KEYS */;
INSERT INTO `pantry` VALUES (1,'Tomato','Canned','Whole',1,0,NULL,NULL,'Canned'),(2,'Tomato','Romma','',1,0,NULL,NULL,'Produce'),(3,'Tofu','Firm','',1,0,NULL,NULL,'Dairy'),(4,'Squash','Butternut','',1,4,NULL,'2021-06-08','Produce'),(5,'Pasta','Fettuccine','',1,0,NULL,NULL,'Boxed'),(6,'Onion','Yellow','',1,0,14,'2021-06-01','Produce'),(7,'Sugar','White','',1,0,NULL,NULL,'Baking'),(8,'Cashew','Raw','',1,0,NULL,NULL,'Snacks'),(9,'Oil','Olive','',1,0,NULL,NULL,'Baking'),(10,'Pepper Flakes','','',1,0,NULL,NULL,'Spices'),(11,'Sage','Fresh','',1,0,3,'2021-06-08','Herbs'),(12,'Black Pepper','','',1,0,NULL,'2021-06-08','Spices'),(13,'Nutritional yeast','','',1,0,NULL,NULL,'Specialty'),(14,'Veggie broth','','',0,0,NULL,'2021-06-08','Boxed'),(15,'Lemon','Juice','',1,0,NULL,NULL,'Produce'),(16,'Oil','','',1,0,NULL,NULL,'Baking'),(17,'Soy Sauce','','',1,0,NULL,NULL,'Specialty'),(18,'Paprika','Smoked','',1,0,NULL,NULL,'Spices'),(19,'Garlic','Powder','',0,0,NULL,NULL,'Spices'),(20,'Maple Syrup','','',1,0,NULL,NULL,'Canned'),(21,'Miso paste','','',0,0,NULL,NULL,'Specialty'),(22,'Garlic','Fresh','',1,0,NULL,NULL,'Herbs'),(23,'Salt','','',1,0,NULL,NULL,'Baking'),(24,'Pasta','Farfalle','',1,0,NULL,NULL,'Boxed'),(25,'Soy curls','','',0,0,NULL,NULL,'Specialty'),(26,'Milk','Cashew','',1,3,NULL,NULL,'Dairy'),(27,'Sun dried tomato','','',1,0,NULL,NULL,'Boxed'),(28,'Spinach','','',1,0,NULL,NULL,'Produce'),(29,'Sage','Dry','',0,0,NULL,NULL,'Spices'),(30,'Onion','Dry','',1,0,NULL,NULL,'Spices'),(31,'Thyme','Dry','',1,0,NULL,NULL,'Spices'),(32,'Poultry seasoning','','',0,4,NULL,NULL,'Spices'),(33,'Oregano','Dry','',0,0,NULL,NULL,'Spices'),(34,'Thyme','Fresh','',1,0,NULL,NULL,'Herbs'),(35,'Basil','Fresh','',1,0,NULL,'2021-06-08','Herbs'),(36,'Water','','',1,0,NULL,NULL,'None'),(37,'Marjoram','Dry','',1,0,NULL,NULL,'Spices'),(38,'Rosemary','Dry','',1,0,NULL,NULL,'Spices'),(39,'Nutmeg','Ground','',1,0,NULL,NULL,'Spices'),(40,'Bread','','',1,6,NULL,NULL,'Baking'),(41,'Cheese','Cheddar','',1,0,NULL,NULL,'Dairy'),(42,'Butter','','',1,0,NULL,NULL,'Dairy'),(43,'Parsley','Fresh','',1,0,NULL,NULL,'Herbs'),(44,'Lentils','Split','',0,0,NULL,NULL,'Grains'),(45,'Tomato','','',0,0,NULL,NULL,'Produce'),(46,'Ginger','','',0,0,NULL,NULL,'Herbs'),(47,'Ghee','','',0,0,NULL,NULL,'Specialty'),(48,'Cumin','Seeds','',0,0,NULL,NULL,'Spices'),(49,'Bay leaf','','',1,0,NULL,'2021-06-08','Spices'),(50,'Garlic','','',0,0,NULL,NULL,'Herbs'),(51,'Garam masala','','',0,0,NULL,NULL,'Spices'),(52,'Turmeric','','',0,0,NULL,NULL,'Spices'),(53,'Cayenne','','',0,0,NULL,NULL,'Spices'),(54,'Cilantro','','',0,0,NULL,NULL,'Herbs'),(55,'Cream','Heavy','',0,0,NULL,NULL,'Dairy');
/*!40000 ALTER TABLE `pantry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `RECIPE_ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) DEFAULT NULL,
  `PEOPLE` int DEFAULT NULL,
  `ACTIVE` decimal(5,2) DEFAULT NULL,
  `PASSIVE` decimal(5,2) DEFAULT NULL,
  `RATING` int DEFAULT NULL,
  `PERCENT` decimal(5,2) DEFAULT NULL,
  `IMAGE` varchar(150) DEFAULT NULL,
  `TOP1` varchar(45) DEFAULT NULL,
  `TOP2` varchar(45) DEFAULT NULL,
  `TOP3` varchar(45) DEFAULT NULL,
  `TOP4` varchar(45) DEFAULT NULL,
  `TOP5` varchar(45) DEFAULT NULL,
  `TOP6` varchar(45) DEFAULT NULL,
  `MENU` tinyint DEFAULT '0',
  `INGREDIANT` tinyint DEFAULT '0',
  PRIMARY KEY (`RECIPE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (1,'Butternut Squash Carbonara',2,1.15,0.00,4,97.00,'https://www.veganricha.com/wp-content/uploads/2020/11/butternut-squash-carbonara-10-150x150.jpg',NULL,NULL,NULL,NULL,NULL,NULL,1,0),(2,'Creamy Sun Dried Tomato Pasta with Garlic Soy',3,0.30,0.00,3,58.00,'images/search.svg',NULL,NULL,NULL,NULL,NULL,NULL,0,0),(3,'Cashew milk',0,0.00,0.00,5,100.00,'images/search.svg',NULL,NULL,NULL,NULL,NULL,NULL,0,1),(4,'Poultry seasoning',0,0.00,0.00,5,97.00,'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F6298346.jpg',NULL,NULL,NULL,NULL,NULL,NULL,0,1),(5,'Grilled Cheese',2,20.00,80.00,3,10.98,'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F641694.jpg','Bread','Cheese','Butter','Spinach',NULL,NULL,1,0),(15,'Instant Pot Dal Makhni Recipe',4,10.00,0.00,0,NULL,'images/search.svg',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `RECIPE_ID` int DEFAULT NULL,
  `TAG` varchar(45) DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Vegan',1),(1,'Main',2),(1,'Savory',3),(2,'Vegan',4),(2,'Main',5),(2,'Savory',6),(2,'Italian',7);
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

-- Dump completed on 2021-06-15 11:18:32
